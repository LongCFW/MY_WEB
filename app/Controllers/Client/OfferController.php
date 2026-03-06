<?php
namespace App\Controllers\Client;
use App\Core\Controller;

class OfferController extends Controller {

    // Hiển thị trang Kho Voucher
    // Hiển thị trang Kho Voucher
    public function index() {
        $couponModel = $this->model('Coupon');
        // Gọi hàm qua Model thay vì chọc trực tiếp vào db
        $activeCoupons = $couponModel->getActiveCoupons();

        // Nếu khách đã đăng nhập, lấy danh sách ID các mã họ đã lưu
        $savedCouponIds = [];
        if (isset($_SESSION['user_logged_in'])) {
            $userCouponModel = $this->model('UserCoupon');
            // Gọi hàm qua Model
            $savedCouponIds = $userCouponModel->getSavedCouponIds($_SESSION['user_id']);
        }

        // Map lại dữ liệu để khớp với vòng lặp HTML
        $vouchers = [];
        foreach ($activeCoupons as $c) {
            $isSaved = in_array($c['id'], $savedCouponIds);
            
            // Xử lý mô tả
            $desc = $c['type'] == 'percent' ? "Giảm {$c['value']}%" : "Giảm " . number_format($c['value']) . "đ";

            $vouchers[] = [
                'id' => $c['id'],
                'code' => $c['code'],
                'type' => $c['type'],
                'desc' => $desc,
                'min' => $c['min_order_cents'],
                'expiry' => date('d/m/Y H:i', strtotime($c['ends_at'])),
                'is_saved' => $isSaved 
            ];
        }

        $this->view('client/offers/index', ['vouchers' => $vouchers]);
    }

    // API Xử lý Lưu mã qua AJAX
    public function saveCoupon() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            header('Content-Type: application/json');

            if (!isset($_SESSION['user_logged_in'])) {
                echo json_encode(['status' => 'error', 'message' => 'Vui lòng đăng nhập để lưu mã!']);
                return;
            }

            $couponId = $_POST['coupon_id'] ?? 0;
            $userId = $_SESSION['user_id'];

            $userCouponModel = $this->model('UserCoupon');
            
            // Kiểm tra xem đã lưu chưa
            if ($userCouponModel->checkSaved($userId, $couponId)) {
                echo json_encode(['status' => 'error', 'message' => 'Bạn đã lưu mã này rồi!']);
                return;
            }

            // Lưu vào ví
            if ($userCouponModel->create(['user_id' => $userId, 'coupon_id' => $couponId])) {
                echo json_encode(['status' => 'success', 'message' => 'Lưu mã thành công!']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Có lỗi xảy ra, vui lòng thử lại.']);
            }
        }
    }
}