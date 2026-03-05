<?php
namespace App\Models;

use App\Core\Model;

class Product extends Model {
    protected $table = 'products';

    // --- 1. HÀM DÀNH CHO ADMIN (KHÔI PHỤC LẠI + THÊM BỘ LỌC) ---
    public function getAllProducts($filters = []) {
        $sql = "SELECT p.*, c.name as category_name, parent_c.name as parent_category_name,
                       MIN(v.price_cents) as min_price, 
                       MAX(v.price_cents) as max_price,
                       COALESCE(SUM(v.stock), 0) as total_stock,
                       MIN(i.image_url) as image_url,
                       COUNT(v.id) as variant_count,
                       GROUP_CONCAT(v.name SEPARATOR ', ') as variant_names
                FROM products p
                LEFT JOIN categories c ON p.category_id = c.id
                LEFT JOIN categories parent_c ON c.parent_id = parent_c.id 
                LEFT JOIN product_variants v ON p.id = v.product_id AND v.is_active = 1
                LEFT JOIN product_images i ON p.id = i.product_id
                WHERE 1=1"; // Điều kiện gốc để dễ dàng nối các WHERE phía dưới

        $params = [];

        // --- BẮT ĐẦU XỬ LÝ LỌC ---
        
        // 1. Tìm kiếm theo tên hoặc SKU
        if (!empty($filters['search'])) {
            $sql .= " AND (p.name LIKE ? OR p.sku LIKE ?)";
            $params[] = "%" . $filters['search'] . "%";
            $params[] = "%" . $filters['search'] . "%";
        }

        // 2. Lọc theo Danh mục
        if (!empty($filters['category_id'])) {
            // Lọc cả danh mục cha và các danh mục con của nó
            $categoryModel = new \App\Models\Category();
            $catIds = $categoryModel->getCategoryTreeIds($filters['category_id']);
            $placeholders = implode(',', array_fill(0, count($catIds), '?'));
            $sql .= " AND p.category_id IN ($placeholders)";
            $params = array_merge($params, $catIds);
        }

        // --- KẾT THÚC LỌC ---

        $sql .= " GROUP BY p.id";

        // 3. Lọc theo Tình trạng Kho (Phải dùng HAVING vì total_stock là kết quả của hàm SUM)
        if (!empty($filters['stock_status'])) {
            if ($filters['stock_status'] == 'in_stock') {
                $sql .= " HAVING total_stock > 0";
            } elseif ($filters['stock_status'] == 'out_of_stock') {
                $sql .= " HAVING total_stock <= 0";
            }
        }

        $sql .= " ORDER BY p.id DESC";
        
        return $this->db->fetchAll($sql, $params);
    }

    // --- HÀM DÀNH CHO CLIENT (FILTER + PAGINATION) ---
    public function getFilteredProducts($filters = [], $limit = 12, $offset = 0) {
        // Xây dựng câu truy vấn cơ bản
        // [CẬP NHẬT]: Thêm MAX(v.price_cents) as max_price để Client biết SP có nhiều mức giá
        $sql = "SELECT p.*, c.name as category_name, 
                       MIN(v.price_cents) as price_cents, 
                       MAX(v.price_cents) as max_price,
                       MIN(i.image_url) as image_url,
                       COALESCE(SUM(v.stock), 0) as total_stock
                FROM products p
                LEFT JOIN categories c ON p.category_id = c.id
                LEFT JOIN product_variants v ON p.id = v.product_id AND v.is_active = 1
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

        // Loại / Trọng lượng (từ bảng product_variants)
        if (!empty($filters['types'])) {
            $placeholders = implode(',', array_fill(0, count($filters['types']), '?'));
            $sql .= " AND v.name IN ($placeholders)";
            $params = array_merge($params, $filters['types']);
        }

        return $sql;
    }

    // Lấy thương hiệu (Mock hoặc lấy thật)
    public function getDistinctBrands() {
        $sql = "SELECT TRIM(brand) as brand FROM {$this->table} 
                WHERE brand IS NOT NULL AND TRIM(brand) != '' AND is_active = 1 
                GROUP BY TRIM(brand)
                ORDER BY brand ASC";
        
        return $this->db->fetchAll($sql);
    }

    public function getProductDetail($id) {
        $sql = "SELECT p.*, c.name as category_name 
                FROM products p
                LEFT JOIN categories c ON p.category_id = c.id
                WHERE p.id = ?";
        
        $product = $this->db->fetch($sql, [$id]);

        if (!$product) return false;

        $sqlImages = "SELECT image_url FROM product_images WHERE product_id = ?";
        $images = $this->db->fetchAll($sqlImages, [$id]);
        $product['images'] = array_column($images, 'image_url');

        // [MỚI] Lấy TẤT CẢ biến thể đang hoạt động của sản phẩm này
        $sqlVariants = "SELECT id, name, price_cents, sku, stock FROM product_variants WHERE product_id = ? AND is_active = 1";
        $variants = $this->db->fetchAll($sqlVariants, [$id]);
        $product['variants'] = $variants;
        
        // Gán mặc định là biến thể đầu tiên để load lần đầu
        if (!empty($variants)) {
            $product['price_cents'] = $variants[0]['price_cents'];
            $product['sku'] = $variants[0]['sku'];
            $product['stock'] = $variants[0]['stock'];
            $product['default_variant_id'] = $variants[0]['id'];
        } else {
             $product['price_cents'] = 0;
             $product['sku'] = 'N/A';
             $product['stock'] = 0;
             $product['default_variant_id'] = 0;
        }

        return $product;
    }

    public function getRelatedProducts($categoryId, $excludeId, $limit = 4) {
        $sql = "SELECT p.*, c.name as category_name, 
                       MIN(v.price_cents) as price_cents, 
                       MIN(i.image_url) as image_url,
                       COALESCE(SUM(v.stock), 0) as total_stock
                FROM products p
                LEFT JOIN categories c ON p.category_id = c.id
                LEFT JOIN product_variants v ON p.id = v.product_id 
                LEFT JOIN product_images i ON p.id = i.product_id
                WHERE p.category_id = ? AND p.id != ? AND p.is_active = 1
                GROUP BY p.id
                LIMIT $limit";
        
        return $this->db->fetchAll($sql, [$categoryId, $excludeId]);
    }

    public function delete($id) {
        // Bước 1: Lấy danh sách Variant ID của sản phẩm này
        // (Vì Wishlist và CartItem liên kết qua variant_id)
        $sqlGetVariants = "SELECT id FROM product_variants WHERE product_id = ?";
        $variants = $this->db->fetchAll($sqlGetVariants, [$id]);
        
        // Tạo mảng chứa các ID biến thể
        $variantIds = array_column($variants, 'id');

        if (!empty($variantIds)) {
            $idsString = implode(',', array_map('intval', $variantIds));

            // DỌN SẠCH GIỎ HÀNG VÀ WISHLIST TRƯỚC KHI XÓA
            $this->db->query("DELETE FROM cart_items WHERE variant_id IN ($idsString)");
            
            try {
                $this->db->query("DELETE FROM wishlists WHERE variant_id IN ($idsString)");
            } catch (\Exception $e) {}

            $this->db->query("DELETE FROM product_variants WHERE product_id = ?", [$id]);
        }

        // Bước 5: Xóa Hình ảnh sản phẩm (product_images)
        $this->db->query("DELETE FROM product_images WHERE product_id = ?", [$id]);

        // Bước 6: Cuối cùng mới xóa SẢN PHẨM chính
        $sql = "DELETE FROM {$this->table} WHERE id = ?";
        return $this->db->query($sql, [$id]);
    }

    // Hàm đếm tổng số sản phẩm
    public function countAll() {
        $sql = "SELECT COUNT(*) as total FROM {$this->table}";
        $result = $this->db->fetch($sql);
        return $result['total'] ?? 0;
    }

    // Lấy danh sách Loại (quy cách/trọng lượng)
    public function getDistinctTypes() {
        $sql = "SELECT DISTINCT name as type FROM product_variants 
                WHERE name IS NOT NULL AND name != '' AND is_active = 1 
                ORDER BY type ASC";
        return $this->db->fetchAll($sql);
    }

    // --- [MỚI] HÀM LẤY DANH SÁCH SẢN PHẨM RÚT GỌN (CHO FORM SEEDING ĐÁNH GIÁ) ---
    public function getSimpleProductList() {
        $sql = "SELECT id, name, sku FROM {$this->table} WHERE is_active = 1 ORDER BY id DESC";
        return $this->db->fetchAll($sql);
    }
}