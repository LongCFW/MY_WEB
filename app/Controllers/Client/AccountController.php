<?php

namespace App\Controllers\Client;

use App\Core\Controller;

class AccountController extends Controller
{

    public function index()
    {
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

        // 4. VÍ VOUCHER
        if ($currentPage == 'voucher') {
            $userCouponModel = $this->model('UserCoupon');
            $data['savedCoupons'] = $userCouponModel->getSavedCoupons($userId);
            // Có thể thêm phân trang sau này nếu muốn
        }

        $this->view('client/account/profile', $data);
    }


    public function update()
    {
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

            if (
                isset($_FILES['avatar']) &&
                $_FILES['avatar']['error'] === UPLOAD_ERR_OK &&
                is_uploaded_file($_FILES['avatar']['tmp_name'])
            ) {                
                $allowedExt  = ['jpg', 'jpeg', 'png', 'gif'];
                $allowedMime = ['image/jpeg', 'image/png', 'image/gif'];
                $maxSize     = 2 * 1024 * 1024; // 2MB (avatar)

                $tmpPath  = $_FILES['avatar']['tmp_name'];
                $fileSize = $_FILES['avatar']['size'];
                $ext      = strtolower(pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION));
               
                if (!in_array($ext, $allowedExt, true)) {
                    throw new \Exception('Định dạng ảnh không hợp lệ');
                }

                $finfo = new \finfo(FILEINFO_MIME_TYPE);
                $mime  = $finfo->file($tmpPath);

                if (!in_array($mime, $allowedMime, true)) {
                    throw new \Exception('File upload không phải ảnh hợp lệ');
                }

                if ($fileSize > $maxSize) {
                    throw new \Exception('Dung lượng ảnh tối đa 2MB');
                }

                $newFilename = sprintf(
                    'avatar_%d_%s.%s',
                    $userId,
                    time(),
                    $ext
                );

                $uploadDir = __DIR__ . '/../../../public/assets/uploads/avatars/';

                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }

                $destPath = $uploadDir . $newFilename;

                if (!move_uploaded_file($tmpPath, $destPath)) {
                    throw new \Exception('Upload ảnh thất bại');
                }

                $avatarPath = 'assets/uploads/avatars/' . $newFilename;
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
