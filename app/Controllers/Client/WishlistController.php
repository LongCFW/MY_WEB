<?php
namespace App\Controllers\Client;
use App\Core\Controller;

class WishlistController extends Controller {

    public function toggle() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            header('Content-Type: application/json');

            if (!isset($_SESSION['user_logged_in'])) {
                echo json_encode(['status' => 'error', 'message' => 'Vui lòng đăng nhập để lưu yêu thích!']);
                exit;
            }

            $input = json_decode(file_get_contents('php://input'), true);
            $productId = $input['product_id'] ?? null;
            $variantId = $input['variant_id'] ?? null;

            $model = $this->model('Wishlist');
            $targetVariantId = $variantId;

            // Nếu không có variant_id (tức là bấm tim ở trang danh sách/home),
            // ta phải tự tìm variant mặc định của sản phẩm đó.
            if (!$targetVariantId && $productId) {                
                // Thay vì: $db = new \App\Core\Database(); (SAI)
                // Dùng hàm của Model:
                $targetVariantId = $model->getFirstVariantIdByProductId($productId);
                // -----------------------
            }

            if ($targetVariantId) {
                $action = $model->toggle($_SESSION['user_id'], $targetVariantId);
                $msg = ($action == 'added') ? 'Đã thêm vào yêu thích ❤️' : 'Đã xóa khỏi yêu thích 💔';
                echo json_encode(['status' => 'success', 'action' => $action, 'message' => $msg]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Sản phẩm không hợp lệ hoặc hết hàng']);
            }
        }
    }
}