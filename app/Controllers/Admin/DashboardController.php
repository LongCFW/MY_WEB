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
        $categoryModel = $this->model('Category'); // Thêm model Category
        $couponModel = $this->model('Coupon');     // Thêm model Coupon

        // Lấy dữ liệu thống kê
        $revenue = $orderModel->getRealRevenue(); 
        
        $stats = [
            'revenue' => $revenue, 
            'total_orders' => $orderModel->countAllOrders(),        
            'total_products' => $productModel->countAll(),            
            
            // --- [CẬP NHẬT] PHÂN TÁCH SỐ LIỆU NGƯỜI DÙNG ---
            'total_real_users' => $userModel->countRealCustomers(), // Chỉ đếm role = 4
            'total_seeding_users' => $userModel->countSeedingUsers(), // Chỉ đếm role = 5
            'total_staff' => $userModel->countStaffAndAdmin(), // Admin & Staff
            
            // --- [CẬP NHẬT] THÊM THỐNG KÊ MỚI ---
            'total_categories' => $categoryModel->countAll(),
            'total_coupons' => $couponModel->countAll(),

            'recent_orders' => $orderModel->getRecentOrders(5)
        ];

        $this->view('admin/dashboard/index', ['stats' => $stats]);
    }
}