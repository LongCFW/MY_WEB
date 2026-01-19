<?php
namespace App\Controllers\Client;

use App\Core\Controller;

class CartController extends Controller {
    
    // Hiển thị giỏ hàng
    public function index() {
        $cart = $_SESSION['cart'] ?? [];
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        
        $this->view('client/cart/index', ['cart' => $cart, 'total' => $total]);
    }

    // Thêm vào giỏ
    public function add() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $productId = $_POST['product_id'];
            $qty = (int)$_POST['quantity'];

            $productModel = $this->model('Product');
            $product = $productModel->getProductDetail($productId);

            if ($product) {
                // Nếu chưa có giỏ thì tạo mới
                if (!isset($_SESSION['cart'])) {
                    $_SESSION['cart'] = [];
                }

                // Nếu sản phẩm đã có -> Tăng số lượng
                if (isset($_SESSION['cart'][$productId])) {
                    $_SESSION['cart'][$productId]['quantity'] += $qty;
                } else {
                    // Thêm mới
                    $_SESSION['cart'][$productId] = [
                        'id' => $product['id'],
                        'name' => $product['name'],
                        'price' => $product['price_cents'],
                        'image' => $product['image_url'],
                        'quantity' => $qty
                    ];
                }
            }
            
            // Quay lại trang trước đó
            header('Location: ' . $_SERVER['HTTP_REFERER']);
        }
    }

    // Xóa khỏi giỏ
    public function remove($id) {
        if (isset($_SESSION['cart'][$id])) {
            unset($_SESSION['cart'][$id]);
        }
        header('Location: /MY_WEB/public/cart');
    }
    
    // Cập nhật số lượng (Dùng cho AJAX hoặc Form update)
    // Tạm thời làm đơn giản là xóa đi add lại hoặc logic sau
}