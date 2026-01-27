<?php
namespace App\Controllers\Client;

use App\Core\Controller;

class HomeController extends Controller {
    public function index() {
        // 1. Khởi tạo Models
        $productModel = $this->model('Product');
        $categoryModel = $this->model('Category');
        $wishlistModel = $this->model('Wishlist');
        
        // 2. Lấy 8 sản phẩm mới nhất từ DB (Hiển thị ở Flash Sale)
        $products = $productModel->getFilteredProducts([], 8, 0);
        
        // 3. Lấy danh sách danh mục thật từ DB
        $categories = $categoryModel->all();

        // 4. Xử lý dữ liệu hiển thị cho Danh mục (Lấy ảnh thật)
        foreach ($categories as &$cat) {
            // A. Xử lý hình ảnh
            if (!empty($cat['image_url'])) {
                // Nếu có ảnh trong DB -> Dùng ảnh thật
                $cat['img'] = '/MY_WEB/public/' . $cat['image_url'];
            } else {
                // Nếu chưa có ảnh -> Dùng ảnh Placeholder mặc định
                // Dùng hàm urlencode để tránh lỗi ký tự đặc biệt trong tên
                $cat['img'] = 'https://placehold.co/300x300?text=' . urlencode($cat['name']);
            }
            
            // B. Đếm số lượng sản phẩm THẬT (Gọi hàm trong Model Category)
            $count = $categoryModel->countActiveProducts($cat['id']);
            $cat['count'] = $count . ' SP';
        }

        // 5. Lấy danh sách sản phẩm user đã thích (để tô đỏ trái tim)
        $wishlistProductIds = [];
        if (isset($_SESSION['user_logged_in'])) {
            $wishlistProductIds = $wishlistModel->getUserWishlistProductIds($_SESSION['user_id']);
        }

        $data = [
            'products' => $products,
            'categories' => $categories,
            'wishlistProductIds' => $wishlistProductIds
        ];

        $this->view('client/home/index', $data);
    }
}