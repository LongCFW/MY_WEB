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

        // Nhận ID category từ URL
        $category_input = $_GET['category'] ?? [];
        if (!is_array($category_input) && $category_input !== '') {
            $category_input = [$category_input];
        }

        // TÌM KIẾM DANH MỤC CHA
        // Nếu User click vào danh mục Cha, ta sẽ gom luôn cả các danh mục Con của nó vào bộ lọc
        $final_category_ids = [];
        if (!empty($category_input)) {
            foreach ($category_input as $cat_id) {
                // Hàm này bạn đã viết sẵn ở Category Model: Trả về mảng chứa [ID_Cha, ID_Con1, ID_Con2...]
                $treeIds = $categoryModel->getCategoryTreeIds($cat_id);
                $final_category_ids = array_merge($final_category_ids, $treeIds);
            }
            // Loại bỏ các ID trùng lặp (nếu có)
            $final_category_ids = array_unique($final_category_ids);
        }

        $filters = [
            'keyword'      => $_GET['keyword'] ?? '',
            'sort'         => $_GET['sort'] ?? 'default',
            'category_ids' => $final_category_ids, // Sử dụng mảng ID đã được gom
            'price_ranges' => $_GET['price'] ?? [],    
            'brands'       => $_GET['brand'] ?? [],
            'ratings' => $_GET['rating'] ?? []
        ];

        // 2. Lấy dữ liệu sản phẩm
        $products = $productModel->getFilteredProducts($filters, $limit, $offset);
        $totalProducts = $productModel->countFilteredProducts($filters);
        $totalPages = ceil($totalProducts / $limit);

        $categories = $categoryModel->all();
        $brands = $productModel->getDistinctBrands();        

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

        // --- LOGIC REVIEW & RATING ---
        $reviewModel = $this->model('Review');
        $reviews = $reviewModel->getReviewsByProduct($id);
        $ratingInfo = $reviewModel->getAverageRating($id);
        
        // Kiểm tra xem User hiện tại có được phép đánh giá không
        $eligibleOrderId = false;
        if(isset($_SESSION['user_id'])) {
            $eligibleOrderId = $reviewModel->getEligibleOrderId($_SESSION['user_id'], $id);
        }

        // 4. Truyền data xuống View
        $data = [
            'product' => $product,
            'relatedProducts' => $relatedProducts,
            'likedIds' => $likedIds,
            'reviews' => $reviews,              // Truyền danh sách bình luận
            'ratingInfo' => $ratingInfo,        // Truyền thông tin điểm số
            'eligibleOrderId' => $eligibleOrderId // Truyền ID đơn hàng để duyệt
        ];

        $this->view('client/products/detail', $data);
    }    

    // API Xử lý người dùng gửi Đánh giá
    public function submitReview() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['user_logged_in'])) {
            $productId = $_POST['product_id'];
            $orderId = $_POST['order_id'];
            $rating = $_POST['rating'] ?? 5;
            $comment = trim($_POST['comment'] ?? '');

            $reviewModel = $this->model('Review');
            
            // Re-check bảo mật: Tránh trường hợp hack HTML sửa order_id
            $checkEligibility = $reviewModel->getEligibleOrderId($_SESSION['user_id'], $productId);
            
            if ($checkEligibility == $orderId) {
                $reviewModel->create([
                    'user_id' => $_SESSION['user_id'],
                    'product_id' => $productId,
                    'order_id' => $orderId,
                    'rating' => $rating,
                    'comment' => htmlspecialchars($comment),
                    'is_approved' => 1 // Mặc định tự động duyệt (hoặc set 0 nếu muốn Admin duyệt trước)
                ]);
            }
            
            header("Location: /MY_WEB/public/product/detail/" . $productId . "#reviews");
            exit;
        }
    }
}