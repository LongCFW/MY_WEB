<?php
namespace App\Models;
use App\Core\Model;

class Category extends Model {
    protected $table = 'categories';

    // --- HÀM LẤY DANH MỤC CÓ TÌM KIẾM ---
    public function getAllCategories($filters = []) {
        $sql = "SELECT * FROM {$this->table} WHERE 1=1";
        $params = [];

        // Nếu có tìm kiếm theo tên hoặc slug
        if (!empty($filters['search'])) {
            $sql .= " AND (name LIKE ? OR slug LIKE ?)";
            $params[] = "%" . $filters['search'] . "%";
            $params[] = "%" . $filters['search'] . "%";
        }

        // Sắp xếp ID để danh mục cha thường xuất hiện trước
        $sql .= " ORDER BY parent_id ASC, id ASC";

        return $this->db->fetchAll($sql, $params);
    }

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