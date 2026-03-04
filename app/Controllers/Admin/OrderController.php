<?php
namespace App\Controllers\Admin;
use App\Core\Controller;

class OrderController extends Controller {
    
    public function index() {
        if (!isset($_SESSION['admin_logged_in'])) header('Location: /MY_WEB/public/admin/auth/login');
        $this->checkAdmin();
        $orderModel = $this->model('Order');
        
        $filters = [
            'search' => trim($_GET['search'] ?? ''),
            'status' => trim($_GET['status'] ?? ''),
            'payment_method' => trim($_GET['payment_method'] ?? '')
        ];

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
        
        foreach ($items as &$item) {
            $snapshot = json_decode($item['product_snapshot'] ?? '', true);
            $item['display_name'] = $snapshot['name'] ?? $item['product_name'] ?? 'Sản phẩm không xác định';
            $img = $snapshot['image'] ?? $item['live_image_url'] ?? '';
            $item['display_image'] = !empty($img) ? "/MY_WEB/public/" . $img : "https://placehold.co/50";
            $item['display_sku'] = $item['product_sku'] ?? 'N/A';
        }

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
                
                // Lấy thông tin đơn hàng trước để biết user_id và mã đơn
                $order = $orderModel->getOrderDetail($id);

                // Cập nhật DB
                $orderModel->updateStatus($id, $status);

                // Ghi log lịch sử
                $historyModel = $this->model('OrderStatusHistory');
                $adminId = $_SESSION['admin_id'] ?? $_SESSION['user_id'] ?? null; 
                $historyModel->addHistory($id, $status, $adminId, $note);

                // --- [MỚI] BẮN THÔNG BÁO TRẠNG THÁI CHO CLIENT ---
                if ($order && isset($order['user_id'])) {
                    $notificationModel = $this->model('Notification');
                    $orderNum = $order['order_number'];

                    if ($status == 'completed') {
                        $title = "Đơn hàng giao thành công!";
                        $message = "Đơn hàng #{$orderNum} đã được giao đến bạn. Đừng quên để lại đánh giá cho sản phẩm nhé!";
                        $notificationModel->send($order['user_id'], 'order_completed', $title, $message, ['order_id' => $id]);
                        
                    } elseif ($status == 'shipping') {
                        $title = "Đơn hàng đang trên đường giao!";
                        $message = "Đơn hàng #{$orderNum} đã được bàn giao cho đơn vị vận chuyển.";
                        $notificationModel->send($order['user_id'], 'system', $title, $message, ['order_id' => $id]);
                        
                    } elseif ($status == 'cancelled') {
                        $title = "Đơn hàng đã hủy";
                        $message = "Đơn hàng #{$orderNum} đã bị hủy. Lý do: " . ($note ?: 'Không xác định');
                        $notificationModel->send($order['user_id'], 'system', $title, $message, ['order_id' => $id]);
                    }
                }
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

    public function confirmPayment($id) {
        $this->checkAdmin();
        
        $orderModel = $this->model('Order');
        $orderModel->updatePaymentStatus($id, 'paid');
        $orderModel->updateStatus($id, 'processing');

        $historyModel = $this->model('OrderStatusHistory');
        $adminId = $_SESSION['admin_id'] ?? $_SESSION['user_id'] ?? null; 
        $historyModel->addHistory($id, 'processing', $adminId, 'Admin đã xác nhận nhận được tiền chuyển khoản VietQR.');

        $order = $orderModel->getOrderDetail($id);
        $items = $orderModel->getOrderItems($id);
        $userEmail = $order['email'] ?? null; 

        // Gửi mail xác nhận
        if ($userEmail) {
            \App\Core\MailHelper::sendOrderConfirmation($userEmail, $order, $items, true);
        }

        // --- [MỚI] BẮN THÔNG BÁO NHẬN TIỀN CHO USER ---
        if ($order && isset($order['user_id'])) {
            $notificationModel = $this->model('Notification');
            $title = "Thanh toán thành công!";
            $message = "Chúng tôi đã nhận được khoản thanh toán cho đơn hàng #{$order['order_number']}. Đơn hàng của bạn đang được xử lý.";
            $notificationModel->send($order['user_id'], 'system', $title, $message, ['order_id' => $id]);
        }

        echo "<script>alert('Đã xác nhận nhận tiền và gửi Email thành công!'); window.history.back();</script>";
    }
}