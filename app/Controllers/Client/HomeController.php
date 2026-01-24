<?php
namespace App\Controllers\Client;

use App\Core\Controller;

class HomeController extends Controller {
    public function index() {
        // 1. Khởi tạo Models
        $productModel = $this->model('Product');
        $categoryModel = $this->model('Category');
        
        // 2. Lấy 8 sản phẩm mới nhất từ DB (Hiển thị ở Flash Sale)
        $products = $productModel->getFilteredProducts([], 8, 0);
        
        // 3. Lấy danh sách danh mục thật từ DB
        $categories = $categoryModel->all();

        // 4. Xử lý dữ liệu hiển thị cho Danh mục 
        $demoImages = [
            'https://images.unsplash.com/photo-1597362925123-77861d3fbac7?auto=format&fit=crop&w=300&q=80',
            'https://images.unsplash.com/photo-1610832958506-aa56368176cf?auto=format&fit=crop&w=300&q=80',
            'https://images.unsplash.com/photo-1608755717536-dd95d356333a?auto=format&fit=crop&w=300&q=80',
            'https://images.unsplash.com/photo-1600271886742-f049cd451bba?auto=format&fit=crop&w=300&q=80',
            'https://images.unsplash.com/photo-1596040033229-a9821ebd058d?auto=format&fit=crop&w=300&q=80',
            'https://images.unsplash.com/photo-1536591375315-196008b6eb71?auto=format&fit=crop&w=300&q=80',
        ];

        foreach ($categories as $index => &$cat) {
            // A. Gán ảnh random
            $cat['img'] = $demoImages[$index % count($demoImages)];
            
            // B. Đếm số lượng sản phẩm THẬT (GỌI HÀM MỚI TRONG MODEL)
            // [FIX LỖI TẠI ĐÂY]
            $count = $categoryModel->countActiveProducts($cat['id']);
            
            $cat['count'] = $count . ' SP';
        }

        $wishlistProductIds = [];
        if (isset($_SESSION['user_logged_in'])) {
            $wishlistModel = $this->model('Wishlist');
            $wishlistProductIds = $wishlistModel->getUserWishlistProductIds($_SESSION['user_id']);
        }

        $data = [
            'products' => $products,
            'categories' => $categories,
            'wishlistProductIds' => $wishlistProductIds // Truyền sang view
        ];

        $this->view('client/home/index', $data);
    }
}