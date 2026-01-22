<?php
namespace App\Controllers\Client;

use App\Core\Controller;

class CartController extends Controller {
    
    // 1. Hiển thị giỏ hàng
    public function index() {
        $cart = $_SESSION['cart'] ?? [];
        // Không cần tính total ở đây nữa vì JS sẽ tính dựa trên checkbox
        $this->view('client/cart/index', ['cart' => $cart]);
    }

    // 2. Thêm vào giỏ (Dùng chung cho cả Form POST và AJAX)
    public function add() {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';
        
        if ($contentType === "application/json") {
            $input = json_decode(file_get_contents('php://input'), true);
            $productId = $input['product_id'];
            $qty = (int)$input['quantity'];
        } else {
            $productId = $_POST['product_id'];
            $qty = (int)$_POST['quantity'];
        }

        $productModel = $this->model('Product');
        $product = $productModel->getProductDetail($productId);

        if ($product) {
            if (!isset($_SESSION['cart'])) {
                $_SESSION['cart'] = [];
            }

            // Biến xác định thông báo
            $message = '';
            $status = 'success';

            if (isset($_SESSION['cart'][$productId])) {
                // Trường hợp 1: Sản phẩm đã có -> Cập nhật số lượng
                $_SESSION['cart'][$productId]['quantity'] += $qty;
                $message = 'Đã cập nhật số lượng sản phẩm trong giỏ hàng';
            } else {
                // Trường hợp 2: Sản phẩm mới -> Thêm mới
                $_SESSION['cart'][$productId] = [
                    'id' => $product['id'],
                    'name' => $product['name'],
                    'price' => $product['price_cents'],
                    'image' => $product['images'][0] ?? null,
                    'quantity' => $qty,
                    'max_stock' => $product['stock']
                ];
                $message = 'Đã thêm sản phẩm vào giỏ hàng';
            }
            
            $cartCount = count($_SESSION['cart']);

            if ($contentType === "application/json") {
                // Xóa bộ đệm đầu ra để đảm bảo JSON sạch
                ob_clean();
                echo json_encode([
                    'status' => $status, 
                    'message' => $message, 
                    'cart_count' => $cartCount
                ]);
                exit;
            }
        }

        // Fallback
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    }
}

    // 3. API Cập nhật số lượng (Dành cho trang Cart)
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $input = json_decode(file_get_contents('php://input'), true);
            $id = $input['id'] ?? null;
            $qty = $input['quantity'] ?? 1;

            if ($id && isset($_SESSION['cart'][$id])) {
                // Validate số lượng không âm
                if ($qty < 1) $qty = 1;
                // Validate tồn kho (nếu cần)
                if (isset($_SESSION['cart'][$id]['max_stock']) && $qty > $_SESSION['cart'][$id]['max_stock']) {
                    $qty = $_SESSION['cart'][$id]['max_stock'];
                }

                $_SESSION['cart'][$id]['quantity'] = $qty;
                
                // Tính thành tiền của item này
                $itemTotal = $_SESSION['cart'][$id]['price'] * $qty;

                echo json_encode([
                    'status' => 'success',
                    'new_quantity' => $qty,
                    'item_total' => $itemTotal
                ]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Sản phẩm không tồn tại']);
            }
        }
    }

    // 4. Xóa sản phẩm
    public function remove($id) {
        if (isset($_SESSION['cart'][$id])) {
            unset($_SESSION['cart'][$id]);
        }
        header('Location: /MY_WEB/public/cart');
    }
}