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
        $this->view('admin/orders/detail', ['order' => $order, 'items' => $items]);
    }

    public function update_status($id) {
        $this->checkAdmin();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $status = $_POST['status'];
            // Validate status hợp lệ
            $validStatuses = ['pending', 'processing', 'shipping', 'completed', 'cancelled'];
            
            if (in_array($status, $validStatuses)) {
                $orderModel = $this->model('Order');
                $orderModel->updateStatus($id, $status);
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