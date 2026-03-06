<?php

namespace App\Controllers\Client;

use App\Core\Controller;

class CartController extends Controller
{
    // 1. Hiển thị giỏ hàng
    public function index()
    {
        // Bắt buộc đăng nhập mới xem được giỏ hàng
        if (!isset($_SESSION['user_logged_in'])) {
            header('Location: /MY_WEB/public/auth/login');
            exit();
        }

        // Khi đã qua bước trên, chắc chắn là User đã đăng nhập -> Chỉ lấy từ DB
        $userId = $_SESSION['user_id'];
        $cartModel = $this->model('CartItem');
        $cart = $cartModel->getCartDetails($userId);

        $this->view('client/cart/index', ['cart' => $cart]);
    }

    // 2. Thêm vào giỏ
    public function add()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';
            
            // Lấy dữ liệu đầu vào
            if ($contentType === "application/json") {
                $input = json_decode(file_get_contents('php://input'), true);
            } else {
                $input = $_POST;
            }

            $qty = (int)($input['quantity'] ?? 1);

            // KIỂM TRA ĐĂNG NHẬP
            if (!isset($_SESSION['user_logged_in'])) {
                if ($contentType === "application/json") {
                    ob_clean();
                    echo json_encode(['status' => 'login_required', 'message' => 'Vui lòng đăng nhập để mua hàng!']);
                    exit;
                } else {
                    header('Location: /MY_WEB/public/auth/login');
                    exit;
                }
            }

            $userId = $_SESSION['user_id'];
            $cartModel = $this->model('CartItem');
            $variantModel = $this->model('ProductVariant');

            // --- [LOGIC MỚI LÕI]: Xác định chính xác Variant ID ---
            $variantId = $input['variant_id'] ?? null;
            
            // Nếu Client gửi product_id (thêm từ nút dấu + ngoài danh sách) -> tự lấy Variant đầu tiên
            if (!$variantId && isset($input['product_id'])) {
                $variantId = $cartModel->getVariantIdByProduct($input['product_id']);
            }

            if ($variantId) {
                // KIỂM TRA TỒN KHO
                $currentStock = $variantModel->getStock($variantId);
                $existingItem = $cartModel->findCartItem($userId, $variantId);
                $currentQtyInCart = $existingItem ? $existingItem['quantity'] : 0;

                if ($currentStock <= 0) {
                    $this->returnJson(['status' => 'error', 'message' => 'Sản phẩm đã hết hàng!'], $contentType);
                }
                
                if (($currentQtyInCart + $qty) > $currentStock) {
                    $this->returnJson(['status' => 'error', 'message' => "Không đủ hàng! Kho chỉ còn $currentStock sản phẩm."], $contentType);
                }

                // CẬP NHẬT HOẶC THÊM MỚI
                if ($existingItem) {
                    $newQty = $existingItem['quantity'] + $qty;
                    $cartModel->updateQuantity($existingItem['id'], $newQty);
                    $message = 'Đã cập nhật số lượng trong giỏ hàng';
                } else {
                    $cartModel->addItem($userId, $variantId, $qty);
                    $message = 'Đã thêm sản phẩm vào giỏ hàng';
                }
            } else {
                 $this->returnJson(['status' => 'error', 'message' => 'Sản phẩm không hợp lệ!'], $contentType);
            }

            $cartCount = $cartModel->countCartItems($userId);
            $this->returnJson(['status' => 'success', 'message' => $message, 'cart_count' => $cartCount], $contentType);
        }
    }

    // Helper function để return nhanh
    private function returnJson($data, $contentType) {
        if ($contentType === "application/json") {
            ob_clean();
            echo json_encode($data);
            exit;
        }
        // Fallback cho form submit thường (nếu có)
        echo "<script>alert('".$data['message']."'); window.history.back();</script>";
        exit;
    }

    // 3. Cập nhật số lượng (ĐÃ FIX LỖI DB ACCESS)
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $input = json_decode(file_get_contents('php://input'), true);
            $id = $input['id'] ?? null;
            $qty = $input['quantity'] ?? 1;
            if ($qty < 1) $qty = 1;
            $itemTotal = 0;

            if (isset($_SESSION['user_logged_in'])) {
                $cartModel = $this->model('CartItem');

                // Gọi hàm update của Model
                $cartModel->updateQuantity($id, $qty);

                // [FIX] Gọi hàm lấy giá của Model
                $res = $cartModel->getItemPrice($id);
                if ($res) $itemTotal = $res['price_cents'] * $qty;
            } else {
                if (isset($_SESSION['cart'][$id])) {
                    $_SESSION['cart'][$id]['quantity'] = $qty;
                    $itemTotal = $_SESSION['cart'][$id]['price'] * $qty;
                }
            }
            echo json_encode(['status' => 'success', 'new_quantity' => $qty, 'item_total' => $itemTotal]);
        }
    }

    // 4. Xóa (ĐÃ FIX)
    public function remove($id)
    {
        if (isset($_SESSION['user_logged_in'])) {
            $cartModel = $this->model('CartItem');
            // [FIX] Gọi hàm delete của Model
            $cartModel->deleteItem($id);
        } else {
            if (isset($_SESSION['cart'][$id])) unset($_SESSION['cart'][$id]);
        }
        header('Location: /MY_WEB/public/cart');
    }

    // 5. Xóa nhiều (ĐÃ FIX)
    public function removeMulti()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $input = json_decode(file_get_contents('php://input'), true);
            $ids = $input['ids'] ?? [];

            if (!empty($ids)) {
                if (isset($_SESSION['user_logged_in'])) {
                    $cartModel = $this->model('CartItem');
                    // [FIX] Gọi hàm deleteMulti của Model
                    $cartModel->deleteMulti($ids);
                } else {
                    foreach ($ids as $id) unset($_SESSION['cart'][$id]);
                }
            }
            header('Content-Type: application/json');
            echo json_encode(['status' => 'success', 'message' => 'Đã xóa các sản phẩm đã chọn']);
            exit;
        }
    }
}
