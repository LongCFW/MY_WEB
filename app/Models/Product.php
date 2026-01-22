<?php
namespace App\Models;

use App\Core\Model;

class Product extends Model {
    protected $table = 'products';

    // --- 1. HÀM DÀNH CHO ADMIN (KHÔI PHỤC LẠI) ---
    public function getAllProducts() {
        // Fix lỗi ONLY_FULL_GROUP_BY bằng cách dùng MIN()
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

    // --- HÀM DÀNH CHO CLIENT (FILTER + PAGINATION) ---
    public function getFilteredProducts($filters = [], $limit = 12, $offset = 0) {
        // Xây dựng câu truy vấn cơ bản
        $sql = "SELECT p.*, c.name as category_name, 
                       MIN(v.price_cents) as price_cents, 
                       MIN(i.image_url) as image_url
                FROM products p
                LEFT JOIN categories c ON p.category_id = c.id
                LEFT JOIN product_variants v ON p.id = v.product_id 
                LEFT JOIN product_images i ON p.id = i.product_id
                WHERE p.is_active = 1";

        $params = [];
        $sql .= $this->buildFilterConditions($filters, $params);
        
        $sql .= " GROUP BY p.id";

        // Sắp xếp
        if (!empty($filters['sort'])) {
            switch ($filters['sort']) {
                case 'price-asc': $sql .= " ORDER BY price_cents ASC"; break;
                case 'price-desc': $sql .= " ORDER BY price_cents DESC"; break;
                case 'name-asc': $sql .= " ORDER BY p.name ASC"; break;
                case 'name-desc': $sql .= " ORDER BY p.name DESC"; break;
                default: $sql .= " ORDER BY p.id DESC"; break;
            }
        } else {
            $sql .= " ORDER BY p.id DESC";
        }

        // Phân trang
        $sql .= " LIMIT $limit OFFSET $offset";

        return $this->db->fetchAll($sql, $params);
    }

    // --- HÀM ĐẾM TỔNG SỐ SẢN PHẨM (ĐỂ TÍNH SỐ TRANG) ---
    public function countFilteredProducts($filters = []) {
        $sql = "SELECT COUNT(DISTINCT p.id) as total
                FROM products p
                LEFT JOIN product_variants v ON p.id = v.product_id 
                WHERE p.is_active = 1";

        $params = [];
        $sql .= $this->buildFilterConditions($filters, $params);

        $result = $this->db->fetch($sql, $params);
        return $result['total'] ?? 0;
    }

    // --- HÀM PHỤ TRỢ XÂY DỰNG WHERE CLAUSE ---
    private function buildFilterConditions($filters, &$params) {
        $sql = "";
        
        // Danh mục
        if (!empty($filters['category_ids'])) {
            $placeholders = implode(',', array_fill(0, count($filters['category_ids']), '?'));
            $sql .= " AND p.category_id IN ($placeholders)";
            $params = array_merge($params, $filters['category_ids']);
        }

        // Giá
        if (!empty($filters['price_ranges'])) {
            $sql .= " AND ("; 
            $priceConditions = [];
            foreach ($filters['price_ranges'] as $range) {
                $r = explode('-', $range);
                if (count($r) == 2) {
                    $priceConditions[] = "(v.price_cents >= ? AND v.price_cents <= ?)";
                    $params[] = $r[0];
                    $params[] = $r[1];
                } elseif (strpos($range, '+') !== false) {
                    $val = (int)$range;
                    $priceConditions[] = "(v.price_cents >= ?)";
                    $params[] = $val;
                }
            }
            $sql .= implode(' OR ', $priceConditions);
            $sql .= ")";
        }

        // Từ khóa
        if (!empty($filters['keyword'])) {
            $sql .= " AND p.name LIKE ?";
            $params[] = "%" . $filters['keyword'] . "%";
        }

        // Thương hiệu
        if (!empty($filters['brands'])) {
            $placeholders = implode(',', array_fill(0, count($filters['brands']), '?'));
            $sql .= " AND p.brand IN ($placeholders)"; // Giả sử bảng products có cột brand
            $params = array_merge($params, $filters['brands']);
        }

        return $sql;
    }

    // Lấy thương hiệu (Mock hoặc lấy thật)
    public function getDistinctBrands() {
        // Tạm thời trả về dữ liệu mẫu để test giao diện
        return [
            ['brand' => 'Vinamilk'],
            ['brand' => 'TH True Milk'],
            ['brand' => 'Organic Life'],
            ['brand' => 'Eco Farm']
        ];
    }

    public function getProductDetail($id) {
        // Truy vấn lấy thông tin cơ bản + danh mục
        $sql = "SELECT p.*, c.name as category_name 
                FROM products p
                LEFT JOIN categories c ON p.category_id = c.id
                WHERE p.id = ?";
        
        $product = $this->db->fetch($sql, [$id]);

        if (!$product) {
            return false;
        }

        // Lấy thêm danh sách ảnh (Gallery)
        $sqlImages = "SELECT image_url FROM product_images WHERE product_id = ?";
        $images = $this->db->fetchAll($sqlImages, [$id]);
        $product['images'] = array_column($images, 'image_url'); // Chuyển về mảng 1 chiều url

        // Lấy thông tin biến thể (Giá, SKU, Tồn kho) - Lấy cái đầu tiên làm mặc định hoặc list tất cả
        // Giả sử ta lấy biến thể đầu tiên để hiển thị giá chính
        $sqlVariant = "SELECT price_cents, sku, stock FROM product_variants WHERE product_id = ? LIMIT 1";
        $variant = $this->db->fetch($sqlVariant, [$id]);
        
        if ($variant) {
            $product['price_cents'] = $variant['price_cents'];
            $product['sku'] = $variant['sku'];
            $product['stock'] = $variant['stock'];
        } else {
            // Fallback nếu không có variant
             $product['price_cents'] = 0;
             $product['sku'] = 'N/A';
             $product['stock'] = 0;
        }

        return $product;
    }

    public function getRelatedProducts($categoryId, $excludeId, $limit = 4) {
        $sql = "SELECT p.*, c.name as category_name, 
                       MIN(v.price_cents) as price_cents, 
                       MIN(i.image_url) as image_url
                FROM products p
                LEFT JOIN categories c ON p.category_id = c.id
                LEFT JOIN product_variants v ON p.id = v.product_id 
                LEFT JOIN product_images i ON p.id = i.product_id
                WHERE p.category_id = ? AND p.id != ? AND p.is_active = 1
                GROUP BY p.id
                LIMIT $limit";
        
        return $this->db->fetchAll($sql, [$categoryId, $excludeId]);
    }
}