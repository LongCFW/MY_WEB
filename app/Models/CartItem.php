<?php
namespace App\Models;
use App\Core\Model;

class CartItem extends Model {
    protected $table = 'cart_items';

    // 1. Lấy chi tiết giỏ hàng
    public function getCartDetails($userId) {
        $sql = "SELECT c.id, c.quantity, c.variant_id,
                       p.id as product_id, p.name, p.slug, p.is_active, 
                       pv.price_cents as price,
                       (SELECT image_url FROM product_images WHERE product_id = p.id LIMIT 1) as image
                FROM {$this->table} c
                JOIN product_variants pv ON c.variant_id = pv.id
                JOIN products p ON pv.product_id = p.id
                WHERE c.user_id = ?
                ORDER BY c.id DESC"; 
        return $this->db->fetchAll($sql, [$userId]);
    }
    
    // 2. Đếm số lượng
    public function countCartItems($userId) {
        $sql = "SELECT count(*) as total FROM {$this->table} WHERE user_id = ?";
        $res = $this->db->fetch($sql, [$userId]);
        return $res['total'] ?? 0;
    }

    // --- CÁC HÀM MỚI ĐỂ FIX LỖI ---

    // Tìm Variant ID từ Product ID
    public function getVariantIdByProduct($productId) {
        $sql = "SELECT id FROM product_variants WHERE product_id = ? LIMIT 1";
        $res = $this->db->fetch($sql, [$productId]);
        return $res ? $res['id'] : null;
    }

    // Kiểm tra sản phẩm đã có trong giỏ chưa
    public function findCartItem($userId, $variantId) {
        $sql = "SELECT id, quantity FROM {$this->table} WHERE user_id = ? AND variant_id = ?";
        return $this->db->fetch($sql, [$userId, $variantId]);
    }

    // Cập nhật số lượng
    public function updateQuantity($id, $qty) {
        $sql = "UPDATE {$this->table} SET quantity = ? WHERE id = ?";
        return $this->db->query($sql, [$qty, $id]);
    }

    // Thêm mới vào giỏ
    public function addItem($userId, $variantId, $qty) {
        $sql = "INSERT INTO {$this->table} (user_id, variant_id, quantity) VALUES (?, ?, ?)";
        return $this->db->query($sql, [$userId, $variantId, $qty]);
    }

    // Lấy giá để tính lại tổng tiền (cho AJAX)
    public function getItemPrice($cartId) {
        $sql = "SELECT pv.price_cents FROM {$this->table} c 
                JOIN product_variants pv ON c.variant_id = pv.id 
                WHERE c.id = ?";
        return $this->db->fetch($sql, [$cartId]);
    }

    // Xóa item
    public function deleteItem($id) {
        $sql = "DELETE FROM {$this->table} WHERE id = ?";
        return $this->db->query($sql, [$id]);
    }

    // Xóa nhiều
    public function deleteMulti($ids) {
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $sql = "DELETE FROM {$this->table} WHERE id IN ($placeholders)";
        return $this->db->query($sql, $ids);
    }
}