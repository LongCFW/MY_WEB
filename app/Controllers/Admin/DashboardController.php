<?php
namespace App\Controllers\Admin;
use App\Core\Controller;

class DashboardController extends Controller {
    public function index() {
        if (!isset($_SESSION['admin_logged_in'])) header('Location: /MY_WEB/public/admin/auth/login');

        // Gọi các Model để lấy số liệu thống kê
        $orderModel = $this->model('Order');
        $productModel = $this->model('Product'); // Bạn dùng hàm countAll có sẵn hoặc all() rồi đếm
        $userModel = $this->model('User');

        // Vì Model gốc chưa có hàm countAll(), ta có thể dùng query trực tiếp hoặc thêm vào Model gốc
        // Ở đây thêm hàm count() vào Core/Model hoặc dùng query raw cho nhanh
        $stats = [
            'total_orders' => $orderModel->countOrders(),
            'revenue' => $orderModel->getTotalRevenue(),
            'total_products' => count($productModel->all()), // Cách nhanh, tối ưu thì viết query COUNT
            'recent_orders' => $orderModel->getRecentOrders()
        ];

        $this->view('admin/dashboard/index', ['stats' => $stats]);
    }
}