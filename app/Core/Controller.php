<?php
namespace App\Core;

class Controller {
    // Hàm gọi Model
    public function model($model) {
        $class = "App\\Models\\" . $model;
        return new $class(); // Trả về instance của Model
    }

    // Hàm gọi View
    public function view($view, $data = []) {
        // Tự động giải nén mảng data thành biến
        // Ví dụ: ['name' => 'Long'] => biến $name = 'Long'
        extract($data);
        
        $viewFile = "../app/Views/" . $view . ".php";
        if (file_exists($viewFile)) {
            require_once $viewFile;
        } else {
            die("View does not exist: " . $view);
        }
    }

    public function __construct() {
        // Kiểm tra nếu session đã được start chưa
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $requestUri = $_SERVER['REQUEST_URI'];
        $isAdminRequest = (strpos($requestUri, '/admin/') !== false);

        // --- MIDDLEWARE CHO ADMIN ---
        if ($isAdminRequest) {
            // Bỏ qua check auth nếu đang ở trang đăng nhập/đăng xuất Admin
            if (strpos($requestUri, '/admin/auth') === false) {
                // 1. Kiểm tra đã đăng nhập chưa
                if (!isset($_SESSION['admin_logged_in'])) {
                    header('Location: /MY_WEB/public/admin/auth/login');
                    exit();
                }
                // 2. Kiểm tra Role có hợp lệ không (1: Admin, 2: Manager, 3: Staff)
                $allowedRoles = [1, 2, 3];
                if (!isset($_SESSION['admin_role']) || !in_array($_SESSION['admin_role'], $allowedRoles)) {
                    echo "<script>
                            alert('Tài khoản của bạn không có quyền truy cập khu vực này!');
                            window.location.href = '/MY_WEB/public/';
                          </script>";
                    exit();
                }
            }
            return; // Nếu là Admin hợp lệ, thoát constructor để chạy code Admin
        }

        // --- MIDDLEWARE CHO KHÁCH HÀNG (CLIENT) ---
        if (isset($_SESSION['user_logged_in']) && isset($_SESSION['user_id'])) {
            // Đề phòng admin login đè session client
            if (isset($_SESSION['user_role']) && $_SESSION['user_role'] == 1) {
                return;
            }

            require_once '../app/Models/User.php'; 
            $userModel = new \App\Models\User();
            $status = $userModel->getUserStatus($_SESSION['user_id']);

            // Nếu status = 0 (Bị khóa)
            if ($status === 0 || $status === '0') {
                unset($_SESSION['user_logged_in']);
                unset($_SESSION['user_id']);
                echo "<script>
                        alert('Tài khoản của bạn đã bị khóa bởi quản trị viên!');
                        window.location.href = '/MY_WEB/public/auth/login';
                      </script>";
                exit();
            }
        }
    }
}