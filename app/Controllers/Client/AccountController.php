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


    public function update() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            // 1. Kiểm tra đăng nhập
            if (!isset($_SESSION['user_logged_in'])) {
                header('Location: /MY_WEB/public/auth/login');
                exit;
            }

            $userId = $_SESSION['user_id'];
            $name = $_POST['fullname'] ?? '';
            $phone = $_POST['phone'] ?? '';
            
            // 2. Xử lý Upload Ảnh (Nếu có)
            $avatarPath = null;
            
            if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] == 0) {
                $allowed = ['jpg', 'jpeg', 'png', 'gif'];
                $filename = $_FILES['avatar']['name'];
                $filetype = $_FILES['avatar']['type'];
                $filesize = $_FILES['avatar']['size'];
                
                // Lấy đuôi file
                $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                
                if (in_array($ext, $allowed)) {
                    // Tạo tên file mới để tránh trùng: avatar_USERID_TIMESTAMP.ext
                    $newFilename = "avatar_" . $userId . "_" . time() . "." . $ext;
                    
                    // Đường dẫn lưu file (ví dụ: public/uploads/avatars/)
                    $uploadDir = 'assests/uploads/avatars';
                    
                    // Tạo thư mục nếu chưa có
                    if (!file_exists($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }

                    $destPath = $uploadDir . $newFilename;

                    if (move_uploaded_file($_FILES['avatar']['tmp_name'], $destPath)) {
                        $avatarPath = $destPath; // Lưu đường dẫn tương đối vào DB
                    }
                }
            }

            // 3. Gọi Model cập nhật
            $userModel = $this->model('User');
            $userModel->updateProfile($userId, $name, $phone, $avatarPath);

            // 4. Cập nhật lại Session để hiển thị ngay lập tức (Header,...)
            $_SESSION['user_name'] = $name; // Cập nhật tên hiển thị
            if ($avatarPath) {
                $_SESSION['user_avatar'] = $avatarPath; // Cập nhật ảnh nếu có
            }

            // 5. Redirect về trang Info với thông báo thành công
            header('Location: /MY_WEB/public/account?page=info&status=success');
            exit;
        }
    }
}