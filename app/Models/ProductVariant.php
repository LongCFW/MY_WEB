<?php
namespace App\Models;
use App\Core\Model;

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
}