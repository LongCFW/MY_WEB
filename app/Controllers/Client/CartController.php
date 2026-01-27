<?php

namespace App\Controllers\Client;

use App\Core\Controller;

class CartController extends Controller
{
    // 1. Hiển thị giỏ hàng
    public function index()
    {
        if (isset($_SESSION['user_logged_in'])) {
            $userId = $_SESSION['user_id'];
            $cartModel = $this->model('CartItem');
            $cart = $cartModel->getCartDetails($userId);
        } else {
            // Logic Session
            $cartSession = $_SESSION['cart'] ?? [];
            $cart = [];
            $productModel = $this->model('Product');

            foreach ($cartSession as $pid => $item) {
                $prod = $productModel->getProductDetail($item['id']);
                $isActive = ($prod && isset($prod['is_active'])) ? $prod['is_active'] : 0;

                $cart[] = [
                    'id' => $pid,
                    'product_id' => $item['id'],
                    'name' => $item['name'],
                    'price' => $item['price'],
                    'image' => $item['image'],
                    'quantity' => $item['quantity'],
                    'is_active' => $isActive,
                    'variant_id' => $pid
                ];
            }
        }

        $this->view('client/cart/index', ['cart' => $cart]);
    }

    // 2. Thêm vào giỏ (ĐÃ FIX LỖI DB ACCESS)
    public function add()
    {
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

            $message = 'Đã thêm sản phẩm vào giỏ hàng';
            $status = 'success';
            $cartCount = 0;
            // Khởi tạo Model Variant để check stock
            $variantModel = $this->model('ProductVariant');

            if (isset($_SESSION['user_logged_in'])) {
                $userId = $_SESSION['user_id'];
                $cartModel = $this->model('CartItem');

                // Gọi hàm Model thay vì gọi trực tiếp DB
                $variantId = $cartModel->getVariantIdByProduct($productId);

                if ($variantId) {

                    // Check stock thực tế
                    $currentStock = $variantModel->getStock($variantId);
                    // Gọi hàm tìm item
                    $existingItem = $cartModel->findCartItem($userId, $variantId);
                    $currentQtyInCart = $existingItem ? $existingItem['quantity'] : 0;

                    if ($currentStock <= 0) {
                        $this->returnJson(['status' => 'error', 'message' => 'Sản phẩm đã hết hàng!'], $contentType);
                    }
                    if (($currentQtyInCart + $qty) > $currentStock) {
                        $this->returnJson(['status' => 'error', 'message' => "Không đủ hàng! Kho chỉ còn $currentStock sản phẩm."], $contentType);
                    }

                    if ($existingItem) {
                        $newQty = $existingItem['quantity'] + $qty;
                        // [FIX] Gọi hàm update
                        $cartModel->updateQuantity($existingItem['id'], $newQty);
                        $message = 'Đã cập nhật số lượng trong giỏ hàng';
                    } else {
                        // [FIX] Gọi hàm add
                        $cartModel->addItem($userId, $variantId, $qty);
                    }
                }
                $cartCount = $cartModel->countCartItems($userId);
            } else {
                // Logic Session 
                $productModel = $this->model('Product');
                $product = $productModel->getProductDetail($productId);

                if ($product) {

                    $currentStock = $product['stock'];
                    $currentQtyInCart = isset($_SESSION['cart'][$productId]) ? $_SESSION['cart'][$productId]['quantity'] : 0;
                    
                    if ($currentStock <= 0) {
                        $this->returnJson(['status' => 'error', 'message' => 'Sản phẩm đã hết hàng!'], $contentType);
                    }
                    if (($currentQtyInCart + $qty) > $currentStock) {
                        $this->returnJson(['status' => 'error', 'message' => "Không đủ hàng! Kho chỉ còn $currentStock sản phẩm."], $contentType);
                    }

                    if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
                    if (isset($_SESSION['cart'][$productId])) {
                        $_SESSION['cart'][$productId]['quantity'] += $qty;
                        $message = 'Đã cập nhật số lượng trong giỏ hàng';
                    } else {
                        $_SESSION['cart'][$productId] = [
                            'id' => $product['id'],
                            'name' => $product['name'],
                            'price' => $product['price_cents'],
                            'image' => $product['images'][0] ?? null,
                            'quantity' => $qty,
                            'max_stock' => $product['stock']
                        ];
                    }
                }
                $cartCount = count($_SESSION['cart']);
            }
            $this->returnJson(['status' => $status, 'message' => $message, 'cart_count' => $cartCount], $contentType);

            if ($contentType === "application/json") {
                ob_clean();
                echo json_encode(['status' => $status, 'message' => $message, 'cart_count' => $cartCount]);
                exit;
            }
            header('Location: ' . $_SERVER['HTTP_REFERER']);


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
