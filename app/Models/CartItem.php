<?php
namespace App\Models;
use App\Core\Model;

class CartItem extends Model {
    protected $table = 'cart_items';

    // 1. Lấy chi tiết giỏ hàng
    public function getCartDetails($userId) {
        $sql = "SELECT c.id, c.quantity, c.variant_id,
                       p.id as product_id, p.name, p.slug, 
                       IF(p.is_active = 1 AND pv.is_active = 1, 1, 0) as is_active, 
                       pv.price_cents as price, 
                       pv.stock, /* <--- [THÊM CỘT NÀY] Lấy số lượng tồn kho thực tế */
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

    // Tìm Variant ID từ Product ID (Thông minh: Bỏ qua biến thể rác, ưu tiên lấy loại còn hàng)
    public function getVariantIdByProduct($productId) {
        // Bước 1: Tìm biến thể ĐANG HOẠT ĐỘNG, CÒN HÀNG, ưu tiên giá rẻ nhất
        $sql = "SELECT id FROM product_variants 
                WHERE product_id = ? AND is_active = 1 AND stock > 0 
                ORDER BY price_cents ASC LIMIT 1";
        $res = $this->db->fetch($sql, [$productId]);
        
        // Bước 2: Nếu tất cả đều hết hàng, thì lấy tạm 1 cái đang hoạt động để báo lỗi "Hết hàng"
        if (!$res) {
            $sqlFallback = "SELECT id FROM product_variants 
                            WHERE product_id = ? AND is_active = 1 LIMIT 1";
            $res = $this->db->fetch($sqlFallback, [$productId]);
        }
        
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