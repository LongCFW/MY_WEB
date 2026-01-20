<?php
namespace App\Controllers\Client;

use App\Core\Controller;

class AuthController extends Controller {
    
    // 1. Trang Đăng nhập
    public function login() {
        if (isset($_SESSION['user_logged_in'])) {
            header('Location: /MY_WEB/public/');
            exit();
        }
        $this->view('auth/login');
    }

    // 2. Xử lý Đăng nhập
    public function handleLogin() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];

            $userModel = $this->model('User');
            $user = $userModel->findByEmail($email);

            if ($user && password_verify($password, $user['password_hash'])) {
                // Kiểm tra xem có phải khách hàng không (hoặc admin cũng cho login kiểu user)
                $_SESSION['user_logged_in'] = true;
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_avatar'] = $user['avatar_url']; // Lưu avatar để hiện ở Header

                header('Location: /MY_WEB/public/');
            } else {
                // Truyền lỗi về view
                $this->view('client/auth/login', ['error' => 'Email hoặc mật khẩu không đúng']);
            }
        }
    }

    // 3. Trang Đăng ký
    public function register() {
        $this->view('client/auth/register');
    }

    // 4. Xử lý Đăng ký
    public function handleRegister() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $phone = $_POST['phone'];

            $userModel = $this->model('User');
            
            // Check email tồn tại
            if ($userModel->findByEmail($email)) {
                $this->view('auth/register', ['error' => 'Email đã tồn tại']);
                return;
            }

            $data = [
                'name' => $name,
                'email' => $email,
                'password_hash' => password_hash($password, PASSWORD_DEFAULT),
                'phone' => $phone,
                'role_id' => 2, // Mặc định là Customer
                'status' => 1
            ];

            $userModel->create($data);
            header('Location: /MY_WEB/public/auth/login');
        }
    }

    // 5. Đăng xuất
    public function logout() {
        unset($_SESSION['user_logged_in']);
        unset($_SESSION['user_id']);
        unset($_SESSION['user_name']);
        unset($_SESSION['user_email']);
        unset($_SESSION['user_avatar']);
        header('Location: /MY_WEB/public/auth/login');
    }
}