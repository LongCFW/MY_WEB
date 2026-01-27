<?php
namespace App\Models;
use App\Core\Model;

class Wishlist extends Model {
    protected $table = 'wishlists';

    // Lấy danh sách sản phẩm yêu thích (Kèm thông tin Product + Variant + Image)
    public function getWishlistItems($userId, $limit = 5, $offset = 0) {
        $sql = "SELECT w.id as wishlist_id, w.created_at as liked_at, 
                       pv.id as variant_id, pv.price_cents as variant_price,
                       p.id as product_id, p.name as product_name,
                       (SELECT image_url FROM product_images WHERE product_id = p.id LIMIT 1) as image_url
                FROM {$this->table} w
                JOIN product_variants pv ON w.variant_id = pv.id
                JOIN products p ON pv.product_id = p.id
                WHERE w.user_id = ?
                ORDER BY w.created_at DESC
                LIMIT $limit OFFSET $offset"; 
        return $this->db->fetchAll($sql, [$userId]);
    }

    // Đếm tổng số lượng (Để tính Pagination)
    public function countWishlistItems($userId) {
        $sql = "SELECT count(*) as total FROM {$this->table} WHERE user_id = ?";
        $result = $this->db->fetch($sql, [$userId]);
        return $result['total'] ?? 0;
    }

    // Lấy danh sách Variant ID user đã like (Để check active trái tim)
    public function getUserLikedVariantIds($userId) {
        $sql = "SELECT variant_id FROM {$this->table} WHERE user_id = ?";
        $rows = $this->db->fetchAll($sql, [$userId]);
        return array_column($rows, 'variant_id');
    }

    // Lấy Product ID từ Variant ID (Hỗ trợ check active ở trang danh sách)
    public function getUserLikedProductIds($userId) {
        $sql = "SELECT DISTINCT pv.product_id 
                FROM {$this->table} w 
                JOIN product_variants pv ON w.variant_id = pv.id 
                WHERE w.user_id = ?";
        $rows = $this->db->fetchAll($sql, [$userId]);
        return array_column($rows, 'product_id');
    }

    // Toggle: Thêm/Xóa dựa trên variant_id
    public function toggle($userId, $variantId) {
        // 1. Check tồn tại
        $sqlCheck = "SELECT id FROM {$this->table} WHERE user_id = ? AND variant_id = ?";
        $exist = $this->db->fetch($sqlCheck, [$userId, $variantId]);

        if ($exist) {
            // Xóa
            $sqlDel = "DELETE FROM {$this->table} WHERE user_id = ? AND variant_id = ?";
            $this->db->query($sqlDel, [$userId, $variantId]);
            return 'removed';
        } else {
            // Thêm
            $sqlAdd = "INSERT INTO {$this->table} (user_id, variant_id) VALUES (?, ?)";
            $this->db->query($sqlAdd, [$userId, $variantId]);
            return 'added';
        }
    }

    public function getFirstVariantIdByProductId($productId) {
        // Lấy variant đầu tiên tìm thấy của sản phẩm
        $sql = "SELECT id FROM product_variants WHERE product_id = ? LIMIT 1";
        $result = $this->db->fetch($sql, [$productId]);
        return $result ? $result['id'] : null;
    }
    public function getUserWishlistProductIds($userId) {
        // Join sang bảng variants để lấy product_id cha
        $sql = "SELECT DISTINCT pv.product_id 
                FROM wishlists w
                JOIN product_variants pv ON w.variant_id = pv.id
                WHERE w.user_id = ?";
        
        $result = $this->db->fetchAll($sql, [$userId]);
        
        // Trả về mảng 1 chiều chứa các ID: ví dụ [1, 5, 10]
        return array_column($result, 'product_id');
    }
}