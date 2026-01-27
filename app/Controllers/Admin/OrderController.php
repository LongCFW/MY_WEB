<?php
namespace App\Controllers\Admin;
use App\Core\Controller;

class OrderController extends Controller {
    
    public function index() {
        $this->checkAuth();
        if (!isset($_SESSION['admin_logged_in'])) header('Location: /MY_WEB/public/admin/auth/login');
        $this->checkAdmin();
        $orderModel = $this->model('Order');
        $orders = $orderModel->getAllOrders();
        $this->view('admin/orders/index', ['orders' => $orders]);
    }

    public function detail($id) {
        $this->checkAuth();
        $this->checkAdmin();
        $orderModel = $this->model('Order');
        
        $order = $orderModel->getOrderDetail($id);
        if (!$order) {
            header('Location: /MY_WEB/public/admin/order');
            exit();
        }

        $items = $orderModel->getOrderItems($id);
        
        // --- FIX LỖI HIỂN THỊ: Parse JSON Snapshot ---
        foreach ($items as &$item) {
            $snapshot = json_decode($item['product_snapshot'] ?? '', true);
            
            // Ưu tiên lấy từ snapshot, nếu không có thì fallback sang dữ liệu live (nếu query có join)
            $item['display_name'] = $snapshot['name'] ?? $item['product_name'] ?? 'Sản phẩm không xác định';
            
            $img = $snapshot['image'] ?? $item['live_image_url'] ?? '';
            $item['display_image'] = !empty($img) ? "/MY_WEB/public/" . $img : "https://placehold.co/50";
            
            $item['display_sku'] = $item['product_sku'] ?? 'N/A';
        }
        // ----------------------------------------------

        // Lấy lịch sử trạng thái
        $historyModel = $this->model('OrderStatusHistory');
        $history = $historyModel->getHistoryByOrderId($id);

        $this->view('admin/orders/detail', [
            'order' => $order, 
            'items' => $items,
            'history' => $history // Truyền history sang view
        ]);
    }

    public function update_status($id) {
        $this->checkAuth();
        $this->checkAdmin();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $status = $_POST['status'];
            $note = $_POST['note'] ?? ''; // Lấy ghi chú
            
            $validStatuses = ['pending', 'processing', 'shipping', 'completed', 'cancelled'];
            
            if (in_array($status, $validStatuses)) {
                $orderModel = $this->model('Order');
                $orderModel->updateStatus($id, $status);

                // --- FIX LỖI CẬP NHẬT: Ghi log lịch sử ---
                $historyModel = $this->model('OrderStatusHistory');
                
                // Lấy ID admin từ session (đảm bảo session key đúng)
                $adminId = $_SESSION['admin_id'] ?? $_SESSION['user_id'] ?? null; 
                
                $historyModel->addHistory($id, $status, $adminId, $note);
            }
            
            header("Location: /MY_WEB/public/admin/order/detail/$id");
            exit;
        }
    }

    // Hàm phụ trợ check login (Sửa lỗi Undefined array key)
    private function checkAdmin() {
        // Trường hợp 1: Đã đăng nhập bằng session Admin riêng (ưu tiên nhất)
        if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
            return; // Cho phép đi tiếp
        }

        // Trường hợp 2: Đã đăng nhập bằng User nhưng có Role là Admin (ID = 1)
        // SỬA LỖI: Thêm isset($_SESSION['role_id']) để tránh lỗi khi key không tồn tại
        if (isset($_SESSION['user_logged_in']) && isset($_SESSION['role_id']) && $_SESSION['role_id'] == 1) {
            return; // Cho phép đi tiếp
        }

        // Nếu không thỏa mãn cả 2 trường hợp trên -> Chuyển hướng về login
        header('Location: /MY_WEB/public/admin/auth/login');
        exit();
    }

    private function checkAuth() {
    // Chỉ cần kiểm tra đã đăng nhập là được (vì Staff cũng được vào)
    if (!isset($_SESSION['admin_logged_in'])) {
        header('Location: /MY_WEB/public/admin/auth/login');
        exit();
    }
}
}