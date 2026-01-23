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

            // Gọi Model User để tìm email
            $userModel = $this->model('User');
            $user = $userModel->findByEmail($email);

            if ($user) {
                // Kiểm tra mật khẩu (Dùng password_verify cho mã hóa)
                // Lưu ý: Lúc nãy ta insert hash mẫu, nên ở đây verify sẽ đúng
                if (password_verify($password, $user['password_hash'])) {
                    $allowedRoles = [1, 2, 3];
                    // Kiểm tra quyền (Role = 1 là Admin)
                    if (in_array($user['role_id'], $allowedRoles)) {
                        // Lưu session
                        $_SESSION['admin_logged_in'] = true;
                        $_SESSION['admin_id'] = $user['id'];
                        $_SESSION['admin_name'] = $user['name'];
                        $_SESSION['admin_role'] = $user['role_id'];

                        // Chuyển hướng vào Dashboard
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

            // Nếu lỗi, load lại view login kèm thông báo
            $this->view('admin/auth/login', ['error' => $error]);
        }
    }

    // 3. Đăng xuất
    public function logout() {
        unset($_SESSION['admin_logged_in']);
        unset($_SESSION['admin_id']);
        unset($_SESSION['admin_name']);
        unset($_SESSION['admin_role']);
        header('Location: /MY_WEB/public/admin/auth/login');
    }
}