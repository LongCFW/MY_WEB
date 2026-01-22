<?php
namespace App\Controllers\Client;

use App\Core\Controller;

class AccountController extends Controller {

    public function index() {
        // 1. Kiểm tra đăng nhập (Yêu cầu kỹ thuật)
        if (!isset($_SESSION['user_logged_in'])) {
            header('Location: /MY_WEB/public/auth/login');
            exit();
        }

        // 2. Lấy ID từ session
        $userId = $_SESSION['user_id'];

        // 3. Gọi Model để lấy dữ liệu thật
        $userModel = $this->model('User');
        $user = $userModel->find($userId);

        // Nếu không tìm thấy user (trường hợp hiếm, ví dụ bị xóa tay khỏi DB)
        if (!$user) {
            unset($_SESSION['user_logged_in']);
            header('Location: /MY_WEB/public/auth/login');
            exit();
        }

        // 4. Truyền dữ liệu sang View
        $data = [
            'user' => $user,
            'page_title' => 'Thông tin tài khoản'
        ];

        $this->view('client/account/profile', $data);
    }
}