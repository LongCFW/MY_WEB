<?php
namespace App\Models;
use App\Core\Model;

class OrderCoupon extends Model {
    protected $table = 'order_coupons';

    // Kiểm tra xem coupon đã từng được áp dụng cho đơn hàng nào chưa
    public function isCouponUsed($couponId) {
        $sql = "SELECT id FROM {$this->table} WHERE coupon_id = ? LIMIT 1";
        return $this->db->fetch($sql, [$couponId]);
    }
}