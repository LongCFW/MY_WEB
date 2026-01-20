<?php
namespace App\Controllers\Client;

use App\Core\Controller;

class ProductController extends Controller {
    
    public function index() {
        $productModel = $this->model('Product');
        $categoryModel = $this->model('Category');

        // 1. Nhận tham số
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 8; // Số sản phẩm mỗi trang (Giống React itemsPerPage = 8)
        $offset = ($page - 1) * $limit;

        $filters = [
            'keyword'      => $_GET['keyword'] ?? '',
            'sort'         => $_GET['sort'] ?? 'default',
            'category_ids' => $_GET['category'] ?? [], 
            'price_ranges' => $_GET['price'] ?? [],    
            'brands'       => $_GET['brand'] ?? []     
        ];

        // 2. Lấy dữ liệu
        $products = $productModel->getFilteredProducts($filters, $limit, $offset);
        $totalProducts = $productModel->countFilteredProducts($filters);
        $totalPages = ceil($totalProducts / $limit);

        $categories = $categoryModel->all();
        $brands = $productModel->getDistinctBrands();

        // 3. Truyền View
        $data = [
            'products' => $products,
            'categories' => $categories,
            'brands' => $brands,
            'filters' => $filters,
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

        // Nếu không tìm thấy sản phẩm -> Redirect hoặc lỗi 404
        if (!$product) {
            // Có thể redirect về trang danh sách hoặc hiện trang 404
            header('Location: /MY_WEB/public/product'); 
            exit;
        }

        // 2. Lấy sản phẩm liên quan (Dựa vào category_id của sản phẩm vừa lấy)
        $relatedProducts = [];
        if (!empty($product['category_id'])) {
            $relatedProducts = $productModel->getRelatedProducts($product['category_id'], $id);
        }

        // 3. Truyền data xuống View
        $data = [
            'product' => $product,
            'relatedProducts' => $relatedProducts
        ];

        $this->view('client/products/detail', $data);
    }    
}