<?php
namespace App\Controllers\Client;

use App\Core\Controller;

class ProductController extends Controller {
    
    public function index() {
        $productModel = $this->model('Product');
        $categoryModel = $this->model('Category');

        // 1. Nhận tham số
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 8; 
        $offset = ($page - 1) * $limit;

        // Xử lý an toàn cho category (vì header menu gửi string ?category=1, còn checkbox gửi mảng ?category[]=1)
        $category_input = $_GET['category'] ?? [];
        if (!is_array($category_input) && $category_input !== '') {
            $category_input = [$category_input];
        }

        $filters = [
            'keyword'      => $_GET['keyword'] ?? '',
            'sort'         => $_GET['sort'] ?? 'default',
            'category_ids' => $category_input, 
            'price_ranges' => $_GET['price'] ?? [],    
            'brands'       => $_GET['brand'] ?? [],
            'types'        => $_GET['type'] ?? [] // MỚI: Lọc theo loại/trọng lượng
        ];

        // 2. Lấy dữ liệu sản phẩm
        $products = $productModel->getFilteredProducts($filters, $limit, $offset);
        $totalProducts = $productModel->countFilteredProducts($filters);
        $totalPages = ceil($totalProducts / $limit);

        $categories = $categoryModel->all();
        $brands = $productModel->getDistinctBrands();
        $types = $productModel->getDistinctTypes(); // Lấy danh sách số gam

        // --- 3. LOGIC WISHLIST ---
        $wishlistModel = $this->model('Wishlist');
        $likedIds = [];                          
        if(isset($_SESSION['user_id'])) {        
            $likedIds = $wishlistModel->getUserLikedProductIds($_SESSION['user_id']);
        }

        // 4. Truyền View
        $data = [
            'products' => $products,
            'categories' => $categories,
            'brands' => $brands,
            'types' => $types, // Truyền types ra View
            'filters' => $filters,
            'likedIds' => $likedIds, 
            'pagination' => [
                'current_page' => $page,
                'total_pages' => $totalPages,
                'total_items' => $totalProducts
            ]
        ];

        $this->view('client/products/index', $data);
    }

    public function detail($id) {
        $productModel = $this->model('Product');
        
        // 1. Lấy chi tiết sản phẩm
        $product = $productModel->getProductDetail($id);

        if (!$product) {
            header('Location: /MY_WEB/public/product'); 
            exit;
        }

        // 2. Lấy sản phẩm liên quan
        $relatedProducts = [];
        if (!empty($product['category_id'])) {
            $relatedProducts = $productModel->getRelatedProducts($product['category_id'], $id);
        }

        // --- 3. LOGIC WISHLIST  ---
        // Để hiển thị tim đỏ cho sản phẩm chính và các sản phẩm liên quan
        $wishlistModel = $this->model('Wishlist');
        $likedIds = [];                           
        if(isset($_SESSION['user_id'])) {         
            $likedIds = $wishlistModel->getUserLikedProductIds($_SESSION['user_id']);
        }
        // -------------------------------

        // 4. Truyền data xuống View
        $data = [
            'product' => $product,
            'relatedProducts' => $relatedProducts,
            'likedIds' => $likedIds 
        ];

        $this->view('client/products/detail', $data);
    }    
}