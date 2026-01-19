<?php
namespace App\Controllers\Client;

use App\Core\Controller;

class HomeController extends Controller {
    public function index() {
        $productModel = $this->model('Product');
        
        // Lấy 8 sản phẩm mới nhất
        $products = $productModel->getAllProducts();
        $products = $products ? array_slice($products, 0, 8) : [];
        
        // Lấy sản phẩm "Rau củ" (Giả sử)
        // Nếu muốn query riêng thì viết thêm hàm trong Model
        
        $data = [
            'products' => $products,
            // Bạn có thể truyền thêm 'blogs', 'categories' tĩnh ở đây nếu chưa có bảng DB
        ];

        $this->view('client/home/index', $data);
    }
}