<?php
namespace App\Controllers\Admin;
use App\Core\Controller;

class OrderController extends Controller {
    
    public function index() {
        if (!isset($_SESSION['admin_logged_in'])) header('Location: /MY_WEB/public/admin/auth/login');
        $this->checkAdmin();
        $orderModel = $this->model('Order');
        
        // --- BẮT CÁC THAM SỐ TỪ URL (URL PARAMS) ---
        $filters = [
            'search' => trim($_GET['search'] ?? ''),
            'status' => trim($_GET['status'] ?? ''),
            'payment_method' => trim($_GET['payment_method'] ?? '')
        ];

        // Truyền mảng filter vào hàm để lấy dữ liệu
        $orders = $orderModel->getAllOrders($filters);
        
        $this->view('admin/orders/index', ['orders' => $orders]);
    }

    public function detail($id) {
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
            
            // Ưu tiên lấy từ snapshot, nếu không có thì fallback sang dữ liệu live
            $item['display_name'] = $snapshot['name'] ?? $item['product_name'] ?? 'Sản phẩm không xác định';
            
            $img = $snapshot['image'] ?? $item['live_image_url'] ?? '';
            $item['display_image'] = !empty($img) ? "/MY_WEB/public/" . $img : "https://placehold.co/50";
            
            $item['display_sku'] = $item['product_sku'] ?? 'N/A';
        }

        // Lấy lịch sử trạng thái
        $historyModel = $this->model('OrderStatusHistory');
        $history = $historyModel->getHistoryByOrderId($id);

        $this->view('admin/orders/detail', [
            'order' => $order, 
            'items' => $items,
            'history' => $history 
        ]);
    }

    public function update_status($id) {
        $this->checkAdmin();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $status = $_POST['status'];
            $note = $_POST['note'] ?? ''; 
            
            $validStatuses = ['pending', 'processing', 'shipping', 'completed', 'cancelled'];
            
            if (in_array($status, $validStatuses)) {
                $orderModel = $this->model('Order');
                $orderModel->updateStatus($id, $status);

                // Ghi log lịch sử
                $historyModel = $this->model('OrderStatusHistory');
                $adminId = $_SESSION['admin_id'] ?? $_SESSION['user_id'] ?? null; 
                
                $historyModel->addHistory($id, $status, $adminId, $note);
            }
            
            header("Location: /MY_WEB/public/admin/order/detail/$id");
            exit;
        }
    }

    private function checkAdmin() {
        if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
            return;
        }

        if (isset($_SESSION['user_logged_in']) && isset($_SESSION['role_id']) && $_SESSION['role_id'] == 1) {
            return; 
        }

        header('Location: /MY_WEB/public/admin/auth/login');
        exit();
    }

    // HÀM XÁC NHẬN ĐÃ NHẬN TIỀN VIETQR
    public function confirmPayment($id) {
        $this->checkAdmin();
        
        $orderModel = $this->model('Order');
        $orderModel->updatePaymentStatus($id, 'paid');
        $orderModel->updateStatus($id, 'pending');

        $historyModel = $this->model('OrderStatusHistory');
        $adminId = $_SESSION['admin_id'] ?? $_SESSION['user_id'] ?? null; 
        $historyModel->addHistory($id, 'processing', $adminId, 'Admin đã xác nhận nhận được tiền chuyển khoản VietQR.');

        echo "<script>alert('Đã xác nhận nhận tiền thành công!'); window.history.back();</script>";
    }
}