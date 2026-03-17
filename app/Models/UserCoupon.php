<?php
namespace App\Models;
use App\Core\Model;

class UserCoupon extends Model {
    protected $table = 'user_coupons';

    public function checkSaved($userId, $couponId) {
        $sql = "SELECT id FROM {$this->table} WHERE user_id = ? AND coupon_id = ?";
        return $this->db->fetch($sql, [$userId, $couponId]);
    }

    // --- SỬA: LẤY TOÀN BỘ VOUCHER ĐỂ CHIA TAB ---
    public function getSavedCoupons($userId) {
        $sql = "SELECT uc.id as user_coupon_id, uc.coupon_id, uc.is_used, 
                       c.code, c.type, c.value, c.min_order_cents, c.ends_at, c.usage_limit, c.used_count
                FROM {$this->table} uc 
                JOIN coupons c ON uc.coupon_id = c.id 
                WHERE uc.user_id = ?
                ORDER BY c.ends_at ASC"; // Sắp xếp theo ngày hết hạn
        return $this->db->fetchAll($sql, [$userId]);
    }

    public function getSavedCouponIds($userId) {
        $sql = "SELECT coupon_id FROM {$this->table} WHERE user_id = ?";
        $result = $this->db->fetchAll($sql, [$userId]);
        return array_column($result, 'coupon_id');
    }

    // Xóa TỪNG MÃ giảm giá khỏi ví của khách hàng
    public function removeCouponFromWallet($userId, $userCouponId) {
        // [QUAN TRỌNG] Phải dùng user_coupon_id (ID của bảng user_coupons)
        $sql = "DELETE FROM {$this->table} WHERE user_id = ? AND id = ?";
        return $this->db->query($sql, [$userId, $userCouponId]);
    }

    // --- MỚI: Xóa NHANH CÁC MÃ ĐÃ HẾT HẠN / ĐÃ DÙNG ---
    public function cleanExpiredCoupons($userId) {
        $sql = "DELETE uc FROM {$this->table} uc
                JOIN coupons c ON uc.coupon_id = c.id
                WHERE uc.user_id = ? AND (uc.is_used = 1 OR c.ends_at < NOW() OR (c.usage_limit > 0 AND c.used_count >= c.usage_limit))";
        return $this->db->query($sql, [$userId]);
    }
}