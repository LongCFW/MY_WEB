<?php
namespace App\Controllers\Client;

use App\Core\Controller;

class CheckoutController extends Controller {

    // 1. Hiển thị trang thanh toán
    public function index() {
        // Bắt buộc đăng nhập
        if (!isset($_SESSION['user_logged_in'])) {
            $_SESSION['redirect_url'] = '/MY_WEB/public/checkout'; // Lưu url để redirect lại sau khi login
            header('Location: /MY_WEB/public/auth/login');
            exit();
        }

        // Kiểm tra giỏ hàng
        $cart = $_SESSION['cart'] ?? [];
        if (empty($cart)) {
            header('Location: /MY_WEB/public/cart');
            exit();
        }

        // Lấy danh sách địa chỉ của User
        $addressModel = $this->model('ShippingAddress');
        $addresses = $addressModel->getByUserId($_SESSION['user_id']);

        // Tính toán tiền
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }
        $shippingFee = 30000; // Cố định hoặc tính toán logic khác
        $total = $subtotal + $shippingFee;

        $this->view('client/checkout/index', [
            'cart' => $cart,
            'addresses' => $addresses,
            'subtotal' => $subtotal,
            'shipping_fee' => $shippingFee,
            'total' => $total
        ]);
    }

    // 2. Xử lý Đặt hàng (POST)
    public function process() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (!isset($_SESSION['user_logged_in']) || empty($_SESSION['cart'])) {
                header('Location: /MY_WEB/public/');
                exit();
            }

            $userId = $_SESSION['user_id'];
            $addressId = $_POST['address_id'] ?? null;
            $paymentMethod = $_POST['payment_method'] ?? 'cod';
            
            // Tính lại tổng tiền (Backend validation)
            $cart = $_SESSION['cart'];
            $totalCents = 0;
            foreach ($cart as $item) {
                $totalCents += $item['price'] * $item['quantity'];
            }
            $shippingFee = 30000;
            $totalCents += $shippingFee;

            // === BƯỚC 1: LƯU ORDER ===
            $orderModel = $this->model('Order');
            $orderData = [
                'user_id' => $userId,
                'order_number' => 'ORD-' . time() . rand(100, 999),
                'status' => 'pending',
                'total_cents' => $totalCents,
                'shipping_address_id' => $addressId, // Lưu ID địa chỉ đã chọn
                'payment_method' => $paymentMethod,
                'payment_status' => 'unpaid',
                'created_at' => date('Y-m-d H:i:s')
            ];
            
            $orderId = $orderModel->create($orderData);

            // === BƯỚC 2: LƯU ORDER ITEMS ===
            $orderItemModel = $this->model('OrderItem');
            foreach ($cart as $item) {
                $itemData = [
                    'order_id' => $orderId,
                    'variant_id' => null, // Nếu bạn có variant thì điền vào, tạm thời null
                    'product_id' => $item['id'], // Lưu product_id để dễ truy vấn sau này (cần sửa bảng order_items thêm cột này nếu chưa có)
                    'product_name' => $item['name'], // Lưu cứng tên phòng khi đổi tên
                    'unit_price_cents' => $item['price'],
                    'quantity' => $item['quantity'],
                    'total_price_cents' => $item['price'] * $item['quantity']
                ];
                // Lưu ý: Model create nhận array key => value khớp với cột database
                // Bạn cần đảm bảo bảng order_items có các cột này
                $orderItemModel->create($itemData);
            }

            // === BƯỚC 3: XÓA GIỎ HÀNG & REDIRECT ===
            unset($_SESSION['cart']);
            
            // Chuyển hướng đến trang thành công
            header("Location: /MY_WEB/public/checkout/success?order_id=$orderId");
        }
    }

    // 3. Trang thông báo thành công
    public function success() {
        $orderId = $_GET['order_id'] ?? 0;
        // Có thể gọi model lấy thông tin order để hiện lời cảm ơn
        $this->view('client/checkout/success', ['order_id' => $orderId]);
    }
}