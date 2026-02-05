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

    public function getCategoryTreeIds($parentId) {
    // Lấy ID của chính nó
    $ids = [$parentId];
    // Lấy ID của các con
    $sql = "SELECT id FROM categories WHERE parent_id = ?";
    $children = $this->db->fetchAll($sql, [$parentId]);
    foreach ($children as $child) {
        $ids[] = $child['id'];
    }
    return $ids; // Trả về mảng [3, 6, 7] (Cha + Các con)
}
}