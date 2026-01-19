<?php
namespace App\Controllers\Client;

use App\Core\Controller;

class ProductController extends Controller {
    
    // Trang danh sách sản phẩm
    public function index() {
        $productModel = $this->model('Product');
        $products = $productModel->getAllProducts();
        $this->view('client/products/index', ['products' => $products]);
    }

    // Trang chi tiết
    public function detail($id) {
        $productModel = $this->model('Product');
        $product = $productModel->getProductDetail($id);

        if (!$product) {
            header('Location: /MY_WEB/public/product');
            exit();
        }

        // Có thể lấy thêm sản phẩm liên quan ở đây
        
        $this->view('client/products/detail', ['product' => $product]);
    }
}