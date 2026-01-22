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
            $paymentMethod = $_POST['payment_method'] ?? 'cod'; // Mặc định COD

            // Validate Address
            if (!$addressId) {
                // Xử lý lỗi nếu chưa chọn địa chỉ (đơn giản là redirect lại)
                echo "<script>alert('Vui lòng chọn địa chỉ giao hàng!'); window.history.back();</script>";
                return;
            }
            
            // 1. Tính toán tiền (Backend Calculation)
            $cart = $_SESSION['cart'];
            $subtotal = 0;
            foreach ($cart as $item) {
                $subtotal += $item['price'] * $item['quantity'];
            }
            
            $shippingFee = 30000;
            $tax = 0; // Tạm thời 0
            $totalCents = $subtotal + $shippingFee + $tax;

            // 2. Insert ORDER
            $orderModel = $this->model('Order');
            $orderData = [
                'user_id' => $userId,
                'order_number' => 'ORD-' . strtoupper(uniqid()), // Tạo mã đơn unique
                'status' => 'pending', // Trạng thái mặc định
                'subtotal_cents' => $subtotal,
                'shipping_fee_cents' => $shippingFee,
                'tax_cents' => $tax,
                'total_cents' => $totalCents,
                'shipping_address_id' => $addressId,
                'billing_address_id' => $addressId, // Tạm thời giống shipping
                'payment_status' => 'unpaid',
                'placed_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s')
            ];
            
            $orderId = $orderModel->create($orderData);

            // 3. Insert ORDER ITEMS
            $orderItemModel = $this->model('OrderItem');
            foreach ($cart as $item) {
                // Tạo snapshot JSON (lưu tên, ảnh, sku tại thời điểm mua)
                $snapshot = json_encode([
                    'name' => $item['name'],
                    'image' => $item['image'] ?? '',
                    // 'sku' => ... nếu trong cart có lưu sku
                ]);

                $itemData = [
                    'order_id' => $orderId,
                    'variant_id' => null, // Nếu có variant system thì điền ID vào đây
                    'product_snapshot' => $snapshot, 
                    'quantity' => $item['quantity'],
                    'unit_price_cents' => $item['price'],
                    'total_price_cents' => $item['price'] * $item['quantity']
                ];
                $orderItemModel->create($itemData);
            }

            // 4. Insert ORDER STATUS HISTORY (Log khởi tạo)
            $historyModel = $this->model('OrderStatusHistory');
            $historyModel->addHistory($orderId, 'pending', $userId, 'Đơn hàng mới được tạo');

            // 5. Insert PAYMENT (Nếu cần track COD)
            if ($paymentMethod === 'cod') {
                $paymentModel = $this->model('Payment');
                $paymentModel->create([
                    'order_id' => $orderId,
                    'payment_method' => 'cod',
                    'amount_cents' => $totalCents,
                    'status' => 'pending',
                    'created_at' => date('Y-m-d H:i:s')
                ]);
            }

            // TODO: Xử lý Coupon (Insert vào order_coupons nếu có logic coupon)

            // 6. Hoàn tất: Xóa giỏ hàng & Redirect
            unset($_SESSION['cart']);
            
            // Redirect về Profile tab Orders kèm query param để hiện Toast
            header("Location: /MY_WEB/public/account?tab=orders&order_success=1");
            exit;
        }
    }

    // 3. Trang thông báo thành công
    public function success() {
        $orderId = $_GET['order_id'] ?? 0;
        // Có thể gọi model lấy thông tin order để hiện lời cảm ơn
        $this->view('client/checkout/success', ['order_id' => $orderId]);
    }
}