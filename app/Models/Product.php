<?php
namespace App\Models;

use App\Core\Model;

class Product extends Model {
    protected $table = 'products';

    // Lấy danh sách sản phẩm kèm Giá (từ Variant) và Ảnh (từ Image)
    public function getAllProducts() {
        // SỬ DỤNG HÀM MIN() ĐỂ FIX LỖI ONLY_FULL_GROUP_BY
        // MIN(v.price_cents): Lấy giá thấp nhất trong các biến thể
        // MIN(i.image_url): Lấy ảnh đầu tiên tìm thấy
        
        $sql = "SELECT p.*, c.name as category_name, 
                       MIN(v.price_cents) as price_cents, 
                       MIN(i.image_url) as image_url
                FROM products p
                LEFT JOIN categories c ON p.category_id = c.id
                LEFT JOIN product_variants v ON p.id = v.product_id 
                LEFT JOIN product_images i ON p.id = i.product_id
                GROUP BY p.id 
                ORDER BY p.id DESC";
        
        return $this->db->fetchAll($sql);
    }

    // Hàm lấy chi tiết 1 sản phẩm kèm giá và ảnh để sửa
    public function getProductDetail($id) {
        $sql = "SELECT p.*, 
                       v.price_cents, v.stock, 
                       i.image_url
                FROM products p
                LEFT JOIN product_variants v ON p.id = v.product_id
                LEFT JOIN product_images i ON p.id = i.product_id
                WHERE p.id = ?";
        
        // Dùng fetch (lấy 1 dòng) thay vì fetchAll
        return $this->db->fetch($sql, [$id]);
    }
}