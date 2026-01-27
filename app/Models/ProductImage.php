<?php
namespace App\Models;
use App\Core\Model;

class ProductImage extends Model {
    protected $table = 'product_images';

    public function updateByProductId($productId, $imageUrl) {
        $sql = "UPDATE {$this->table} 
                SET image_url = :image_url 
                WHERE product_id = :product_id";
        
        return $this->db->query($sql, [
            'image_url' => $imageUrl, 
            'product_id' => $productId
        ]);
    }
}