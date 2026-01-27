<?php
namespace App\Models;
use App\Core\Model;

class Category extends Model {
    protected $table = 'categories';
    public function countActiveProducts($categoryId) {
        $sql = "SELECT COUNT(*) as total FROM products WHERE category_id = ? AND is_active = 1";
        $result = $this->db->fetch($sql, [$categoryId]);
        return $result['total'] ?? 0;
    }
}