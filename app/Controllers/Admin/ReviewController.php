<?php
namespace App\Controllers\Admin;
use App\Core\Controller;

class ReviewController extends Controller {

    public function index() {
        $this->checkAdmin();
        $reviewModel = $this->model('Review');
        
        // Lấy tất cả đánh giá (Cả thật và ảo)
        $reviews = $reviewModel->getAllReviewsForAdmin();
        
        // Lấy dữ liệu mồi cho Form Seeding
        $userModel = $this->model('User');
        $productModel = $this->model('Product');
        
        $seedingUsers = $userModel->getSeedingUsers();
        $products = $productModel->getSimpleProductList();

        $this->view('admin/reviews/index', [
            'reviews' => $reviews,
            'seedingUsers' => $seedingUsers,
            'products' => $products
        ]);
    }

    // Xử lý tạo đánh giá mồi (Seeding)
    public function store_seeding() {
        $this->checkAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $productId = $_POST['product_id'];
            $userId = $_POST['user_id'];
            $rating = $_POST['rating'];
            $comment = trim($_POST['comment']);

            // Lưu ý: Đánh giá mồi thì order_id luôn luôn bằng NULL
            $data = [
                'user_id' => $userId,
                'product_id' => $productId,
                'order_id' => NULL, // Cố tình để NULL
                'rating' => $rating,
                'comment' => htmlspecialchars($comment),
                'is_approved' => 1, // Tự động duyệt
                'created_at' => date('Y-m-d H:i:s')
            ];

            $reviewModel = $this->model('Review');
            
            // Xóa validation Unique nếu User này đã từng đánh giá mồi sp này trước đó (Tùy chọn)
            try {
                $reviewModel->create($data);
                echo "<script>alert('Tạo đánh giá thành công!'); window.location.href='/MY_WEB/public/admin/review';</script>";
            } catch (\Exception $e) {
                echo "<script>alert('Lỗi: Khách này có thể đã đánh giá sản phẩm này rồi!'); window.history.back();</script>";
            }
        }
    }

    public function delete($id) {
        $this->checkAdmin();
        $reviewModel = $this->model('Review');
        $reviewModel->delete($id);
        
        echo "<script>alert('Đã xóa đánh giá!'); window.location.href='/MY_WEB/public/admin/review';</script>";
    }

    private function checkAdmin() {
        if (!isset($_SESSION['admin_logged_in']) && !isset($_SESSION['user_logged_in'])) {
            header('Location: /MY_WEB/public/admin/auth/login');
            exit();
        }
        $role = $_SESSION['admin_role'] ?? $_SESSION['user_role'] ?? 0;
        if (!in_array($role, [1, 2])) {
            echo "<script>alert('Không có quyền!'); window.location.href='/MY_WEB/public/';</script>";
            exit();
        }
    }
}