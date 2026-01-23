<?php
namespace App\Controllers\Client;

use App\Core\Controller;

class AccountController extends Controller {

    public function index() {
        if (!isset($_SESSION['user_logged_in'])) {
            header('Location: /MY_WEB/public/auth/login');
            exit();
        }

        $userId = $_SESSION['user_id'];
        $userModel = $this->model('User');
        $user = $userModel->find($userId);

        if (!$user) {
            unset($_SESSION['user_logged_in']);
            header('Location: /MY_WEB/public/auth/login');
            exit();
        }

        $currentPage = $_GET['page'] ?? 'info';
        
        // Cấu hình chung cho phân trang (có thể đổi limit tùy ý mỗi mục)
        $limit = 5; 
        $pageNum = isset($_GET['p']) ? (int)$_GET['p'] : 1;
        if ($pageNum < 1) $pageNum = 1;
        $offset = ($pageNum - 1) * $limit;

        $data = [
            'user' => $user,
            'current_page' => $currentPage,
            'page_title' => 'Tài khoản của tôi',            
            'pageNum' => $pageNum, 
            'totalPages' => 0 
        ];

        // 1. QUẢN LÝ ĐƠN HÀNG
        if ($currentPage == 'orders') {
            $orderModel = $this->model('Order');
            
            // Lấy dữ liệu phân trang
            $data['orders'] = $orderModel->getOrdersByUserId($userId, $limit, $offset);
            $totalItems = $orderModel->countOrdersByUserId($userId);
            
            // Tính toán
            $data['totalPages'] = ceil($totalItems / $limit);
        }

        // 2. SỔ ĐỊA CHỈ
        if ($currentPage == 'address') {
            $addrModel = $this->model('ShippingAddress');
            
            $data['addresses'] = $addrModel->getByUserId($userId, $limit, $offset);
            $totalItems = $addrModel->countByUserId($userId);
            
            $data['totalPages'] = ceil($totalItems / $limit);
        }

        // 3. SẢN PHẨM YÊU THÍCH (Đã làm trước đó)
        if ($currentPage == 'wishlist') {
            $wishlistModel = $this->model('Wishlist');
            
            $data['wishlistItems'] = $wishlistModel->getWishlistItems($userId, $limit, $offset);
            $totalItems = $wishlistModel->countWishlistItems($userId);
            
            $data['totalPages'] = ceil($totalItems / $limit);
        }

        $this->view('client/account/profile', $data);
    }
}