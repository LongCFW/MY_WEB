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
        $this->view('auth/register');
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

            // check sđt
            if ($userModel->checkPhoneExists($phone)) {
                $this->view('auth/register', ['error' => 'Số điện thoại này đã được sử dụng!']);
                return;
            }


            $data = [
                'name' => $name,
                'email' => $email,
                'password_hash' => password_hash($password, PASSWORD_DEFAULT),
                'phone' => $phone,
                'role_id' => 4, 
                'status' => 1
            ];

            if ($userModel->create($data)) {
                // Đăng ký thành công -> Chuyển sang login
                echo "<script>alert('Đăng ký thành công! Vui lòng đăng nhập.'); window.location.href='/MY_WEB/public/auth/login';</script>";
            } else {
                $this->view('auth/register', ['error' => 'Có lỗi xảy ra, vui lòng thử lại sau.']);
            }
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

    // ... bên trong class AuthController

    // 6. Trang Quên mật khẩu
    public function forgotPassword() {
        $this->view('auth/forgot_password');
    }

    // 7. Xử lý gửi yêu cầu reset (Kiểm tra Email & SĐT)
    public function handleForgotPassword() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = $_POST['email'];
            $phone = $_POST['phone'];

            $userModel = $this->model('User');
            // Tìm user có cả email và phone khớp
            $user = $userModel->findByEmailAndPhone($email, $phone);

            if ($user) {
                // Tìm thấy -> Chuyển sang trang đặt lại mật khẩu kèm ID (hoặc token bảo mật hơn)
                // Để đơn giản, ta dùng session tạm để lưu ID user đang reset
                $_SESSION['reset_user_id'] = $user['id'];
                header('Location: /MY_WEB/public/auth/resetPassword');
            } else {
                $this->view('auth/forgot_password', ['error' => 'Thông tin Email và Số điện thoại không khớp với bất kỳ tài khoản nào.']);
            }
        }
    }

    // 8. Trang Đặt lại mật khẩu
    public function resetPassword() {
        // Phải có session reset_user_id mới được vào
        if (!isset($_SESSION['reset_user_id'])) {
            header('Location: /MY_WEB/public/auth/login');
            exit;
        }
        $this->view('auth/reset_password');
    }

    // 9. Xử lý cập nhật mật khẩu mới
    public function handleResetPassword() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (!isset($_SESSION['reset_user_id'])) {
                header('Location: /MY_WEB/public/auth/login');
                exit;
            }

            $password = $_POST['password'];
            $confirmPassword = $_POST['confirm_password'];

            if ($password !== $confirmPassword) {
                $this->view('auth/reset_password', ['error' => 'Mật khẩu xác nhận không khớp']);
                return;
            }

            $userId = $_SESSION['reset_user_id'];
            $userModel = $this->model('User');
            
            // Hash mật khẩu mới
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            
            if ($userModel->updatePassword($userId, $hashedPassword)) {
                // Xóa session tạm
                unset($_SESSION['reset_user_id']);
                echo "<script>alert('Đổi mật khẩu thành công! Vui lòng đăng nhập lại.'); window.location.href='/MY_WEB/public/auth/login';</script>";
            } else {
                $this->view('auth/reset_password', ['error' => 'Có lỗi xảy ra, vui lòng thử lại.']);
            }
        }
    }
}