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
        // Kiểm tra nếu session đã được start chưa (đề phòng)
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $isAdminRequest = (strpos($_SERVER['REQUEST_URI'], '/admin/') !== false);

        if ($isAdminRequest) {
            // Logic check quyền Admin (nếu cần thiết thì đặt ở đây hoặc AdminController)
            return; // Thoát ngay, không check status của user thường
        }

        // Chỉ kiểm tra khi user đã đăng nhập
        if (isset($_SESSION['user_logged_in']) && isset($_SESSION['user_id'])) {
            
            // Đề phòng trường hợp Admin login đè lên session user (nếu code login chưa tách biệt)
            // Nếu session này là của Admin (role_id = 1) thì cũng bỏ qua
            if (isset($_SESSION['user_role']) && $_SESSION['user_role'] == 1) {
                return;
            }

            // Gọi Model User thủ công vì trong Core không dùng được $this->model() ngay
            // Cách fix: Tự khởi tạo class Model
            require_once '../app/Models/User.php'; // Đảm bảo đường dẫn đúng
            $userModel = new \App\Models\User();
            
            $status = $userModel->getUserStatus($_SESSION['user_id']);

            // Nếu status = 0 (Bị khóa) hoặc null (Tài khoản bị xóa)
            if ($status === 0 || $status === '0') {
                // Xóa session
                session_unset();
                session_destroy();
                
                // Báo lỗi và đá về trang login
                echo "<script>
                        alert('Tài khoản của bạn đã bị khóa bởi quản trị viên!');
                        window.location.href = '/MY_WEB/public/auth/login';
                      </script>";
                exit();
            }
        }
    }
}