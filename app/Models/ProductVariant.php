<?php
namespace App\Models;
use App\Core\Model;
use PDOException; // catch lỗi DB

class ProductVariant extends Model {
    protected $table = 'product_variants';

    // Cập nhật giá và kho theo ID sản phẩm cha
    public function updateByProductId($productId, $data) {
        $sql = "UPDATE {$this->table} 
                SET price_cents = :price_cents, stock = :stock 
                WHERE product_id = :product_id";
        
        $data['product_id'] = $productId;
        return $this->db->query($sql, $data);
    }

    // [ADD] Lấy tồn kho hiện tại của 1 variant
    public function getStock($variantId) {
        $sql = "SELECT stock FROM {$this->table} WHERE id = ?";
        $res = $this->db->fetch($sql, [$variantId]);
        return $res ? (int)$res['stock'] : 0;
    }

    // [ADD] Trừ kho an toàn (Transaction + Lock)
    public function deductStockForOrder($orderItems) {
        try {
            // 1. Bắt đầu Transaction
            $this->db->beginTransaction();

            foreach ($orderItems as $item) {
                // Lấy variant_id. Trong cart_items là 'variant_id', trong mảng truyền vào cần đảm bảo có key này
                $variantId = $item['variant_id']; 
                $qtyNeeded = $item['quantity'];

                // 2. Lock dòng này lại để đọc (FOR UPDATE) tránh Race Condition
                $sqlCheck = "SELECT stock FROM {$this->table} WHERE id = ? FOR UPDATE";
                $currentStock = $this->db->fetchColumn($sqlCheck, [$variantId]);

                if ($currentStock === false) {
                    $this->db->rollBack();
                    return ['status' => false, 'message' => 'Sản phẩm không tồn tại hoặc đã bị xóa.'];
                }

                if ($currentStock < $qtyNeeded) {
                    $this->db->rollBack();
                    return [
                        'status' => false, 
                        'message' => "Sản phẩm " . ($item['name'] ?? 'trong giỏ') . " không đủ hàng. Chỉ còn: $currentStock"
                    ];
                }

                // 3. Trừ kho
                $newStock = $currentStock - $qtyNeeded;
                $sqlUpdate = "UPDATE {$this->table} SET stock = ? WHERE id = ?";
                $this->db->query($sqlUpdate, [$newStock, $variantId]);
            }

            // 4. Commit thay đổi
            $this->db->commit();
            return ['status' => true];

        } catch (PDOException $e) {
            $this->db->rollBack();
            return ['status' => false, 'message' => 'Lỗi hệ thống: ' . $e->getMessage()];
        }
    }

    // Lấy tất cả biến thể theo ID sản phẩm (Dùng cho hàm Edit)
    public function getVariantsByProductId($productId) {
        $sql = "SELECT * FROM {$this->table} WHERE product_id = ? AND is_active = 1";
        return $this->db->fetchAll($sql, [$productId]);
    }

    // Xóa tất cả biến thể theo ID sản phẩm (Dùng cho hàm Update)
    public function deleteByProductId($productId) {
        $sql = "DELETE FROM {$this->table} WHERE product_id = ?";
        return $this->db->query($sql, [$productId]);
    }

    // Kiểm tra trạng thái và tồn kho của biến thể trước khi thanh toán
    public function getVariantInfo($variantId) {
        $sql = "SELECT is_active, stock FROM {$this->table} WHERE id = ?";
        return $this->db->fetch($sql, [$variantId]);
    }
}