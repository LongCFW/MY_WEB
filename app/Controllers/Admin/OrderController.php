<?php
namespace App\Controllers\Admin;
use App\Core\Controller;

class OrderController extends Controller {
    
    public function index() {
        if (!isset($_SESSION['admin_logged_in'])) header('Location: /MY_WEB/public/admin/auth/login');
        $this->checkAdmin();
        $orderModel = $this->model('Order');
        $orders = $orderModel->getAllOrders();
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
        $historyModel = $this->model('OrderStatusHistory');
        $history = $historyModel->getHistoryByOrderId($id);
        $this->view('admin/orders/detail', ['order' => $order, 'items' => $items, 'history' => $history]);
    }

    public function update_status($id) {
        $this->checkAdmin();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $status = $_POST['status'];
            $note = $_POST['note'] ?? ''; // Thêm input note bên View nếu muốn
            
            $validStatuses = ['pending', 'processing', 'shipping', 'completed', 'cancelled'];
            
            if (in_array($status, $validStatuses)) {
                $orderModel = $this->model('Order');
                $orderModel->updateStatus($id, $status);

                // Ghi log lịch sử
                $historyModel = $this->model('OrderStatusHistory');
                $adminId = $_SESSION['admin_id'];
                $historyModel->addHistory($id, $status, $adminId, $note);
            }
            
            header("Location: /MY_WEB/public/admin/order/detail/$id");
        }
    }

    // Hàm phụ trợ check login (nếu chưa có trong BaseController)
    private function checkAdmin() {
        if (!isset($_SESSION['admin_logged_in'])) {
            header('Location: /MY_WEB/public/admin/auth/login');
            exit();
        }
    }
}