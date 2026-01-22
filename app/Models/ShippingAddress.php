<?php
namespace App\Models;
use App\Core\Model;

class ShippingAddress extends Model {
    protected $table = 'shipping_addresses';

    // Lấy danh sách địa chỉ của user
    public function getByUserId($userId) {
        $sql = "SELECT * FROM {$this->table} WHERE user_id = ? ORDER BY is_default DESC, created_at DESC";
        return $this->db->fetchAll($sql, [$userId]);
    }

    // Hàm hỗ trợ: Bỏ trạng thái mặc định của tất cả địa chỉ cũ của user này
    // Dùng khi user muốn đặt một địa chỉ mới làm mặc định
    public function resetDefault($userId) {
        $sql = "UPDATE {$this->table} SET is_default = 0 WHERE user_id = ?";
        return $this->db->query($sql, [$userId]);
    }
}