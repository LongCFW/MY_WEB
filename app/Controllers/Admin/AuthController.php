<?php
namespace App\Controllers\Admin;

use App\Core\Controller;

class AuthController extends Controller {

    //  Nếu người dùng chỉ gõ /admin/auth thì tự động vào login
    public function index() {
        $this->login();
    }

    
    // 1. Hiển thị form đăng nhập
    public function login() {
        // Nếu đã login rồi thì đá về Dashboard
        if (isset($_SESSION['admin_logged_in'])) {
            header('Location: /MY_WEB/public/admin/dashboard');
            exit();
        }
        $this->view('admin/auth/login');
    }

    // 2. Xử lý dữ liệu khi bấm nút "Đăng nhập"
    public function handleLogin() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            $userModel = $this->model('User');
            $user = $userModel->findByEmail($email);

            if ($user) {
                if (password_verify($password, $user['password_hash'])) {
                    $allowedRoles = [1, 2, 3];
                    if (in_array($user['role_id'], $allowedRoles)) {
                        
                        // 1. Lưu session cho Admin (Để truy cập Dashboard)
                        $_SESSION['admin_logged_in'] = true;
                        $_SESSION['admin_id'] = $user['id'];
                        $_SESSION['admin_name'] = $user['name'];
                        $_SESSION['admin_role'] = $user['role_id'];

                        // 2. ĐỒNG BỘ SESSION CHO CLIENT (Để ra trang chủ vẫn nhận diện được)
                        $_SESSION['user_logged_in'] = true;
                        $_SESSION['user_id'] = $user['id'];
                        $_SESSION['user_name'] = $user['name'];
                        $_SESSION['user_email'] = $user['email'];
                        $_SESSION['user_role'] = $user['role_id'];
                        $_SESSION['user_avatar'] = $user['avatar_url'] ?? '';

                        header('Location: /MY_WEB/public/admin/dashboard');
                        exit();
                    } else {
                        $error = "Bạn không có quyền truy cập Admin!";
                    }
                } else {
                    $error = "Mật khẩu không đúng!";
                }
            } else {
                $error = "Email không tồn tại!";
            }

            $this->view('admin/auth/login', ['error' => $error]);
        }
    }

    // 3. Đăng xuất
    public function logout() {
        // Xóa toàn bộ Session (Cả Admin lẫn Client) để tránh kẹt trạng thái
        session_unset();
        session_destroy();
        header('Location: /MY_WEB/public/admin/auth/login');
        exit();
    }
}