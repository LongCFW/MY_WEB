<?php
namespace App\Controllers\Admin;
use App\Core\Controller;

class OrderController extends Controller {
    
    public function index() {
        if (!isset($_SESSION['admin_logged_in'])) header('Location: /MY_WEB/public/admin/auth/login');
        
        $orderModel = $this->model('Order');
        $orders = $orderModel->getAllOrders();
        $this->view('admin/orders/index', ['orders' => $orders]);
    }

    public function detail($id) {
        $orderModel = $this->model('Order');
        $order = $orderModel->getOrderDetail($id);
        $items = $orderModel->getOrderItems($id);

        if (!$order) header('Location: /MY_WEB/public/admin/order');

        $this->view('admin/orders/detail', ['order' => $order, 'items' => $items]);
    }

    public function update_status($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $status = $_POST['status'];
            $orderModel = $this->model('Order');
            $orderModel->updateStatus($id, $status);
            header("Location: /MY_WEB/public/admin/order/detail/$id");
        }
    }
}