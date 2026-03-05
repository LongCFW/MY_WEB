<?php
namespace App\Models;
use App\Core\Model;

class Review extends Model {
    protected $table = 'reviews';

    // 1. Lấy danh sách đánh giá của 1 sản phẩm (chỉ lấy bài đã duyệt)
    public function getReviewsByProduct($productId) {
        $sql = "SELECT r.*, u.name as user_name, u.avatar_url 
                FROM {$this->table} r 
                JOIN users u ON r.user_id = u.id 
                WHERE r.product_id = ? AND r.is_approved = 1 
                ORDER BY r.created_at DESC";
        return $this->db->fetchAll($sql, [$productId]);
    }

    // 2. Tính số sao trung bình và tổng số đánh giá
    public function getAverageRating($productId) {
        $sql = "SELECT ROUND(AVG(rating), 1) as avg_rating, COUNT(*) as total_reviews 
                FROM {$this->table} 
                WHERE product_id = ? AND is_approved = 1";
        $result = $this->db->fetch($sql, [$productId]);
        return [
            'avg_rating' => $result['avg_rating'] ?: 0,
            'total_reviews' => $result['total_reviews'] ?: 0
        ];
    }

    // 3. [QUAN TRỌNG] Kiểm tra xem user có đơn hàng Hoàn thành nào chứa sản phẩm này chưa
    // Đồng thời đảm bảo họ CHƯA từng đánh giá cho chính đơn hàng đó
    public function getEligibleOrderId($userId, $productId) {
        $sql = "SELECT o.id 
                FROM orders o
                JOIN order_items oi ON o.id = oi.order_id
                JOIN product_variants pv ON oi.variant_id = pv.id
                WHERE o.user_id = ? 
                  AND o.status = 'completed' 
                  AND pv.product_id = ?
                  AND NOT EXISTS (
                      SELECT 1 FROM {$this->table} r 
                      WHERE r.order_id = o.id AND r.product_id = ? AND r.user_id = ?
                  )
                LIMIT 1";
        $result = $this->db->fetch($sql, [$userId, $productId, $productId, $userId]);
        return $result ? $result['id'] : false; // Trả về ID đơn hàng nếu hợp lệ, ngược lại trả về false
    }

    // --- Lấy danh sách cho trang Admin ---
    public function getAllReviewsForAdmin() {
        $sql = "SELECT r.*, 
                       u.name as user_name, 
                       p.name as product_name,
                       (SELECT image_url FROM product_images WHERE product_id = p.id ORDER BY id ASC LIMIT 1) as product_image
                FROM {$this->table} r 
                JOIN users u ON r.user_id = u.id 
                JOIN products p ON r.product_id = p.id
                ORDER BY r.created_at DESC";
        return $this->db->fetchAll($sql);
    }
}