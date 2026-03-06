<?php
namespace App\Controllers\Admin;
use App\Core\Controller;

class CouponController extends Controller {

    public function index() {
        $this->checkAdmin();
        $couponModel = $this->model('Coupon');
        
        // --- BẮT CÁC THAM SỐ LỌC TỪ URL PARAMS ---
        $filters = [
            'search' => trim($_GET['search'] ?? ''),
            'type'   => $_GET['type'] ?? '',
            'status' => $_GET['status'] ?? ''
        ];

        // Lấy danh sách mã giảm giá dựa trên bộ lọc
        $coupons = $couponModel->getAllCoupons($filters); 
        
        $this->view('admin/coupons/index', ['coupons' => $coupons]);
    }

    public function create() {
        $this->checkAdmin();
        $this->view('admin/coupons/create');
    }

    public function store() {
        $this->checkAdmin();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $couponModel = $this->model('Coupon');
            
            // Chuyển đổi giá trị tiền (nếu là fixed) và đơn hàng tối thiểu thành cents
            $value = $_POST['value'];
            if ($_POST['type'] == 'fixed') {
                $value = $value * 100;
            }
            
            $min_order_cents = $_POST['min_order_cents'] ? $_POST['min_order_cents'] : 0;

            $data = [
                'code' => strtoupper($_POST['code']),
                'type' => $_POST['type'],
                'value' => $value,
                'min_order_cents' => $min_order_cents,
                'usage_limit' => $_POST['usage_limit'] ?: null,
                'starts_at' => $_POST['starts_at'],
                'ends_at' => $_POST['ends_at'],
                'created_at' => date('Y-m-d H:i:s')
            ];

            // Kiểm tra trùng mã
            $existing = $couponModel->findByCode($data['code']);
            if ($existing) {
                echo "<script>alert('Mã giảm giá này đã tồn tại!'); window.history.back();</script>";
                return;
            }

            if ($couponModel->create($data)) {
                
                // --- [MỚI] BẮN THÔNG BÁO CHO TẤT CẢ KHÁCH HÀNG ---
                $notificationModel = $this->model('Notification');
                
                $title = "🎁 Mã giảm giá mới: " . $data['code'];
                $valText = ($data['type'] == 'percent') ? $data['value'] . '%' : number_format($data['value']) . 'đ';
                $minOrder = number_format($data['min_order_cents']) . 'đ';
                $message = "Shop vừa tung mã {$data['code']} giảm ngay {$valText} cho đơn từ {$minOrder}. Nhanh tay lưu mã kẻo lỡ!";
                
                // Type 'new_coupon' sẽ hiện icon Voucher màu vàng ở trang Client
                $notificationModel->sendToAllCustomers('new_coupon', $title, $message, ['coupon_code' => $data['code']]);
                // -------------------------------------------------

                echo "<script>alert('Thêm mã giảm giá thành công!'); window.location.href='/MY_WEB/public/admin/coupon';</script>";
            } else {
                echo "<script>alert('Có lỗi xảy ra!'); window.history.back();</script>";
            }
        }
    }

    public function edit($id) {
        $this->checkAdmin();
        $couponModel = $this->model('Coupon');
        $coupon = $couponModel->getById($id);

        if (!$coupon) {
            header('Location: /MY_WEB/public/admin/coupon');
            exit;
        }

        $this->view('admin/coupons/edit', ['coupon' => $coupon]);
    }

    public function update($id) {
        $this->checkAdmin();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $couponModel = $this->model('Coupon');
            
            $value = $_POST['value'];
            if ($_POST['type'] == 'fixed') {
                $value = $value * 100; 
            }

            $data = [
                'code' => strtoupper($_POST['code']),
                'type' => $_POST['type'],
                'value' => $value,
                'min_order_cents' => $_POST['min_order_cents'] ?: 0,
                'usage_limit' => $_POST['usage_limit'] ?: null,
                'starts_at' => $_POST['starts_at'],
                'ends_at' => $_POST['ends_at']
            ];

            // Kiểm tra trùng mã (loại trừ mã hiện tại)
            $existing = $couponModel->findByCode($data['code'], $id);
            if ($existing) {
                echo "<script>alert('Mã giảm giá này đã tồn tại ở một mục khác!'); window.history.back();</script>";
                return;
            }

            if ($couponModel->update($id, $data)) {
                echo "<script>alert('Cập nhật mã giảm giá thành công!'); window.location.href='/MY_WEB/public/admin/coupon';</script>";
            } else {
                echo "<script>alert('Có lỗi xảy ra!'); window.history.back();</script>";
            }
        }
    }

    public function delete($id) {
        $this->checkAdmin();
        $couponModel = $this->model('Coupon');
        
        // Kiểm tra xem mã đã được sử dụng chưa        
        $orderCouponModel = $this->model('OrderCoupon');
        $isUsed = $orderCouponModel->isCouponUsed($id);
        
        if ($isUsed) {
             echo "<script>alert('Không thể xóa! Mã giảm giá này đã được sử dụng trong đơn hàng.'); window.location.href='/MY_WEB/public/admin/coupon';</script>";
             return;
        }

        if ($couponModel->delete($id)) {
            echo "<script>alert('Đã xóa mã giảm giá!'); window.location.href='/MY_WEB/public/admin/coupon';</script>";
        } else {
            echo "<script>alert('Có lỗi xảy ra!'); window.location.href='/MY_WEB/public/admin/coupon';</script>";
        }
    }

    private function checkAdmin() {
        if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) return;
        if (isset($_SESSION['user_logged_in']) && isset($_SESSION['role_id']) && $_SESSION['role_id'] == 1) return;
        header('Location: /MY_WEB/public/admin/auth/login');
        exit();
    }
}