<?php
namespace App\Models;
use App\Core\Model;

class ShippingAddress extends Model {
    protected $table = 'shipping_addresses';

    public function getByUserId($userId) {
        $sql = "SELECT * FROM {$this->table} WHERE user_id = ? ORDER BY is_default DESC, created_at DESC";
        return $this->db->fetchAll($sql, [$userId]);
    }

    public function setAsDefault($id, $userId) {
        $this->resetDefault($userId);
        $sqlSet = "UPDATE {$this->table} SET is_default = 1 WHERE id = ? AND user_id = ?";
        return $this->db->query($sqlSet, [$id, $userId]);
    }

    // QUAN TRỌNG: Hàm này phải có
    public function resetDefault($userId) {
        $sql = "UPDATE {$this->table} SET is_default = 0 WHERE user_id = ?";
        return $this->db->query($sql, [$userId]);
    }

    public function hasAddress($userId) {
        $sql = "SELECT count(*) as total FROM {$this->table} WHERE user_id = ?";
        $rs = $this->db->fetch($sql, [$userId]);
        return $rs['total'] > 0;
    }
}