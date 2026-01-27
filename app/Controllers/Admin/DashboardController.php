<?php
namespace App\Controllers\Admin;

use App\Core\Controller;

class DashboardController extends Controller {
    
    public function index() {
        // Kiểm tra đăng nhập
        if (!isset($_SESSION['admin_logged_in'])) {
            header('Location: /MY_WEB/public/admin/auth/login');
            exit();
        }

        // Load các Models
        $orderModel = $this->model('Order');
        $productModel = $this->model('Product');
        $userModel = $this->model('User');

        // Lấy dữ liệu thống kê
        $revenue = $orderModel->getRealRevenue(); 
        
        $stats = [
            'revenue' => $revenue, 
            'total_orders' => $orderModel->countAllOrders(),        
            'total_products' => $productModel->countAll(),             
            'total_users' => $userModel->countCustomers(),
            'recent_orders' => $orderModel->getRecentOrders(5)
        ];

        $this->view('admin/dashboard/index', ['stats' => $stats]);
    }
}