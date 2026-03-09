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
        // --- MIDDLEWARE CHO ADMIN ---
        if ($isAdminRequest) {
            // Bỏ qua check auth nếu đang ở trang đăng nhập/đăng xuất Admin
            if (strpos($requestUri, '/admin/auth') === false) {
                // 1. Kiểm tra đã đăng nhập chưa
                if (!isset($_SESSION['admin_logged_in'])) {
                    header('Location: /MY_WEB/public/admin/auth/login');
                    exit();
                }

                // 2. Lấy Role của user hiện tại
                $role = $_SESSION['admin_role'] ?? 0;

                // 3. Phân quyền chi tiết (RBAC - Role Based Access Control)
                // Cấu trúc: 'chuỗi_url_cần_chặn' => [danh_sách_role_được_phép_vào]
                $permissions = [
                    '/admin/dashboard' => [1, 2, 3], // Ai cũng được vào
                    '/admin/order'     => [1, 2, 3], // Ai cũng được vào
                    '/admin/review'    => [1, 2, 3], // Ai cũng được vào
                    '/admin/category'  => [1, 2],    // Chỉ Admin, Manager
                    '/admin/product'   => [1, 2],    // Chỉ Admin, Manager
                    '/admin/coupon'    => [1, 2],    // Chỉ Admin, Manager
                    '/admin/user'      => [1],       // CHỈ ADMIN ĐƯỢC VÀO
                ];

                $hasPermission = false;

                // Kiểm tra xem URL hiện tại người dùng đang truy cập thuộc nhóm nào
                foreach ($permissions as $path => $allowedRolesArr) {
                    if (strpos($requestUri, $path) !== false) {
                        // Nếu Role của user nằm trong mảng cho phép của URL này -> Hợp lệ
                        if (in_array($role, $allowedRolesArr)) {
                            $hasPermission = true;
                        }
                        break; // Tìm thấy path khớp rồi thì thoát vòng lặp check
                    }
                }

                // 4. Xử lý khi truy cập trái phép
                // Mặc định nếu URL không nằm trong mảng $permissions (ví dụ URL rác) thì sẽ check xem có phải role 1,2,3 ko
                // Nếu nằm trong mảng mà $hasPermission = false tức là bị cấm
                if (isset($path) && strpos($requestUri, $path) !== false && !$hasPermission) {
                    echo "<script>
                            alert('Tài khoản của bạn không có quyền truy cập trang này!');
                            window.location.href = '/MY_WEB/public/admin/dashboard';
                          </script>";
                    exit();
                }

                // Kiểm tra sơ cua xem có phải là 1 trong 3 role không (phòng trường hợp session bị hack)
                if (!in_array($role, [1, 2, 3])) {
                    echo "<script>
                            alert('Tài khoản không hợp lệ!');
                            window.location.href = '/MY_WEB/public/admin/auth/logout';
                          </script>";
                    exit();
                }
            }
            return; // Nếu là Admin hợp lệ và có quyền, thoát constructor để chạy code
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