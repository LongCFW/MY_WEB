<?php
namespace App\Models;
use App\Core\Model;

class UserCoupon extends Model {
    protected $table = 'user_coupons';

    // Kiểm tra khách đã lưu mã này chưa
    public function checkSaved($userId, $couponId) {
        $sql = "SELECT id FROM {$this->table} WHERE user_id = ? AND coupon_id = ?";
        return $this->db->fetch($sql, [$userId, $couponId]);
    }

    // Lấy danh sách mã khách đã lưu (Để hiển thị ở trang Account)
    public function getSavedCoupons($userId) {
        $sql = "SELECT uc.*, c.code, c.type, c.value, c.min_order_cents, c.ends_at 
                FROM {$this->table} uc 
                JOIN coupons c ON uc.coupon_id = c.id 
                WHERE uc.user_id = ? AND uc.is_used = 0 AND c.ends_at >= NOW()";
        return $this->db->fetchAll($sql, [$userId]);
    }

    // Lấy mảng ID các mã mà user đã lưu
    public function getSavedCouponIds($userId) {
        $sql = "SELECT coupon_id FROM {$this->table} WHERE user_id = ?";
        $result = $this->db->fetchAll($sql, [$userId]);
        // Chuyển mảng đa chiều thành mảng 1 chiều chứa các coupon_id
        return array_column($result, 'coupon_id');
    }

    // Xóa mã giảm giá khỏi ví của khách hàng
    public function removeCouponFromWallet($userId, $couponId) {
        $sql = "DELETE FROM {$this->table} WHERE user_id = ? AND coupon_id = ?";
        return $this->db->query($sql, [$userId, $couponId]);
    }
}