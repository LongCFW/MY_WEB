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

        // --- LOGIC MỚI: Xác định trang hiện tại dựa vào GET param ---
        $currentPage = $_GET['page'] ?? 'info'; // Mặc định là 'info'

        $data = [
            'user' => $user,
            'current_page' => $currentPage,
            'page_title' => 'Tài khoản của tôi'
        ];

        // Nếu đang ở trang đơn hàng, lấy thêm dữ liệu đơn hàng
        if ($currentPage == 'orders') {
            $orderModel = $this->model('Order');
            $data['orders'] = $orderModel->getOrdersByUserId($userId);
        }

        $this->view('client/account/profile', $data);
    }
}