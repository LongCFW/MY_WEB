<?php
namespace App\Models;
use App\Core\Model;

class Category extends Model {
    protected $table = 'categories';

    // --- HÀM LẤY DANH MỤC CÓ TÌM KIẾM ---
    public function getAllCategories($filters = []) {
        $sql = "SELECT * FROM {$this->table} WHERE is_active = 1";
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

    // Đếm tổng số danh mục
    public function countAll() {
        $sql = "SELECT COUNT(*) as total FROM {$this->table}";
        $result = $this->db->fetch($sql);
        return $result['total'] ?? 0;
    }

    public function delete($id) {
        // Lấy danh sách sản phẩm thuộc danh mục
        $sqlGetProducts = "SELECT id FROM products WHERE category_id = ?";
        $products = $this->db->fetchAll($sqlGetProducts, [$id]);
        
        if (!empty($products)) {
            $productIds = implode(',', array_column($products, 'id'));
            
            // Lấy danh sách biến thể của các sản phẩm đó
            $sqlGetVariants = "SELECT id FROM product_variants WHERE product_id IN ($productIds)";
            $variants = $this->db->fetchAll($sqlGetVariants);

            if (!empty($variants)) {
                $variantIds = implode(',', array_column($variants, 'id'));

                // --- KIỂM TRA PHỦ ĐẦU TỪ DANH MỤC ---
                $checkOrderSql = "SELECT COUNT(*) as total FROM order_items WHERE variant_id IN ($variantIds)";
                $checkOrder = $this->db->fetch($checkOrderSql);

                if ($checkOrder['total'] > 0) {
                    echo "<script>
                        alert('⛔ Có sản phẩm trong danh mục này đã phát sinh đơn hàng, không thể xóa!'); 
                        window.history.back();
                    </script>";
                    exit();
                }

                // Dọn rác giỏ hàng
                $this->db->query("DELETE FROM cart_items WHERE variant_id IN ($variantIds)");
            }
            
            // Xóa cứng SP con
            $this->db->query("DELETE FROM product_images WHERE product_id IN ($productIds)");
            $this->db->query("DELETE FROM product_variants WHERE product_id IN ($productIds)");
            $this->db->query("DELETE FROM products WHERE id IN ($productIds)");
        }

        // Xóa cứng Danh mục
        $this->db->query("DELETE FROM {$this->table} WHERE id = ?", [$id]);
        return true;
    }
}