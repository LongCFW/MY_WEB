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

        // chỉ dùng tạm, nên ưu tiên pagination nếu là thương mại điện tử lớn hoặc tăng limit lớn hơn
        $data = [
            'user' => $user,
            'current_page' => $currentPage,
            'page_title' => 'Tài khoản của tôi',            
        ];

        // 1. QUẢN LÝ ĐƠN HÀNG (Lấy 50 đơn gần nhất)
        if ($currentPage == 'orders') {
            $orderModel = $this->model('Order');
            $data['orders'] = $orderModel->getOrdersByUserId($userId, 50, 0); 
        }

        // 2. SỔ ĐỊA CHỈ (Lấy tất cả)
        if ($currentPage == 'address') {
            $addrModel = $this->model('ShippingAddress');
            $data['addresses'] = $addrModel->getByUserId($userId, 50, 0); 
        }

        // 3. SẢN PHẨM YÊU THÍCH (Lấy 50 món)
        if ($currentPage == 'wishlist') {
            $wishlistModel = $this->model('Wishlist');
            $data['wishlistItems'] = $wishlistModel->getWishlistItems($userId, 50, 0);
        }

        // 4. VÍ VOUCHER
        if ($currentPage == 'voucher') {
            $userCouponModel = $this->model('UserCoupon');
            $data['savedCoupons'] = $userCouponModel->getSavedCoupons($userId);
        }

        // --- 5. THÔNG BÁO (NOTIFICATIONS) ---
        if ($currentPage == 'notification') {
            $notificationModel = $this->model('Notification');
            $data['notifications'] = $notificationModel->getUserNotifications($userId, 50, 0);                        
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

    // Hàm xóa thông báo (Dùng cho fetch API ở View notifications.php)
    public function deleteNotif($notifId) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['user_logged_in'])) {
            $notificationModel = $this->model('Notification');
            // Cần truyền user_id vào để đảm bảo khách không xóa trộm thông báo của người khác
            $success = $notificationModel->deleteNotification($notifId, $_SESSION['user_id']);
            echo json_encode(['success' => $success]);
            exit;
        }
    }

    // API Đánh dấu đã đọc TẤT CẢ thông báo
    public function markAllNotifRead() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['user_logged_in'])) {
            $notificationModel = $this->model('Notification');
            $success = $notificationModel->markAllAsReadByUserId($_SESSION['user_id']);
            echo json_encode(['success' => $success]);
            exit;
        }
    }

    // API Xóa TẤT CẢ thông báo
    public function deleteAllNotifs() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['user_logged_in'])) {
            $notificationModel = $this->model('Notification');
            $success = $notificationModel->deleteAllByUserId($_SESSION['user_id']);
            echo json_encode(['success' => $success]);
            exit;
        }
    }

    // --- [MỚI] API Đánh dấu 1 thông báo là đã đọc khi User click vào ---
    public function readNotif($notifId) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['user_logged_in'])) {
            $notificationModel = $this->model('Notification');
            // Truyền array chứa 1 ID vào hàm markAsRead đã viết sẵn ở Model
            $success = $notificationModel->markAsRead([$notifId]); 
            echo json_encode(['success' => $success]);
            exit;
        }
    }

    // --- API Xóa Voucher đã lưu khỏi Ví của khách ---
    public function removeSavedCoupon() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['user_logged_in'])) {
            $couponId = $_GET['id'] ?? 0;
            $userId = $_SESSION['user_id'];

            if ($couponId > 0) {
                $userCouponModel = $this->model('UserCoupon');
                $success = $userCouponModel->removeCouponFromWallet($userId, $couponId);
                
                echo json_encode(['success' => $success]);
                exit;
            }
        }
        echo json_encode(['success' => false, 'message' => 'Lỗi tham số hoặc chưa đăng nhập']);
        exit;
    }
}
