<?php
namespace App\Controllers\Client;

use App\Core\Controller;

class CheckoutController extends Controller {

    // 1. Hiển thị trang thanh toán
    public function index() {
        // Bắt buộc đăng nhập
        if (!isset($_SESSION['user_logged_in'])) {
            $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI']; // Lưu url để redirect lại đúng chỗ
            header('Location: /MY_WEB/public/auth/login');
            exit();
        }

        // Lấy danh sách ID sản phẩm được chọn từ URL (do JS gửi lên: ?ids=1,2,5)
        $selectedIdsParam = $_GET['ids'] ?? '';
        $selectedIds = !empty($selectedIdsParam) ? explode(',', $selectedIdsParam) : [];

        $userId = $_SESSION['user_id'];
        $cart = [];

        // --- [FIX QUAN TRỌNG] LẤY GIỎ HÀNG TỪ DATABASE ---
        $cartModel = $this->model('CartItem');
        $allCartItems = $cartModel->getCartDetails($userId);

        // Lọc ra những sản phẩm khách đã tick chọn
        // Nếu không có ?ids (ví dụ gõ trực tiếp url), thì lấy toàn bộ (hoặc chặn tùy logic)
        foreach ($allCartItems as $item) {
            // Nếu danh sách ID chọn không rỗng -> Chỉ lấy item có ID nằm trong danh sách
            // Lưu ý: $item['id'] ở đây là ID của dòng trong bảng cart_items
            if (empty($selectedIds) || in_array($item['id'], $selectedIds)) {
                $cart[] = $item;
            }
        }

        // Nếu lọc xong mà vẫn rỗng (Do hack URL hoặc lỗi) -> Đá về giỏ hàng
        if (empty($cart)) {
            echo "<script>alert('Vui lòng chọn sản phẩm để thanh toán!'); window.location.href='/MY_WEB/public/cart';</script>";
            exit();
        }

        // Lấy danh sách địa chỉ của User
        $addressModel = $this->model('ShippingAddress');
        $addresses = $addressModel->getByUserId($userId);

        // Tính toán tiền
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }
        $shippingFee = 30000; 
        $total = $subtotal + $shippingFee;

        $this->view('client/checkout/index', [
            'cart' => $cart,          // Truyền danh sách đã lọc sang View
            'addresses' => $addresses,
            'subtotal' => $subtotal,
            'shipping_fee' => $shippingFee,
            'total' => $total
        ]);
    }
    
    // 2. Xử lý Đặt hàng (POST) - Cập nhật cho AJAX và QR
    public function process() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            header('Content-Type: application/json'); // Luôn trả về JSON
            
            if (!isset($_SESSION['user_logged_in'])) {
                echo json_encode(['status' => 'error', 'message' => 'Vui lòng đăng nhập', 'redirect' => '/MY_WEB/public/auth/login']);
                exit();
            }

            $userId = $_SESSION['user_id'];
            $addressId = $_POST['address_id'] ?? null;
            $paymentMethod = $_POST['payment_method'] ?? 'cod';

            // Validate Address
            if (!$addressId) {
                echo json_encode(['status' => 'error', 'message' => 'Vui lòng chọn địa chỉ giao hàng!']);
                return;
            }
            
            $cartModel = $this->model('CartItem');
            $allCartItems = $cartModel->getCartDetails($userId);
            
            // Ở đây lấy tạm toàn bộ giỏ hàng DB để xử lý cho chạy được đã:
            $cartToCheckout = $allCartItems; 
                    
            if (empty($cartToCheckout)) {
                echo json_encode(['status' => 'error', 'message' => 'Giỏ hàng trống!', 'redirect' => '/MY_WEB/public/cart']);
                exit();
            }

            $variantModel = $this->model('ProductVariant');
            foreach ($cartToCheckout as $item) {
                $variantInfo = $variantModel->getVariantInfo($item['variant_id']);

                if (!$variantInfo || $variantInfo['is_active'] == 0) {
                    echo json_encode(['status' => 'error', 'message' => 'Sản phẩm "' . $item['name'] . '" đã ngừng kinh doanh. Vui lòng cập nhật giỏ hàng.']);
                    exit();
                }

                if ($variantInfo['stock'] < $item['quantity']) {
                    echo json_encode(['status' => 'error', 'message' => 'Sản phẩm "' . $item['name'] . '" chỉ còn ' . $variantInfo['stock'] . ' kiện.']);
                    exit();
                }
            }

            // Trừ kho
            $stockResult = $variantModel->deductStockForOrder($cartToCheckout);

            if ($stockResult['status'] === false) {
                 echo json_encode(['status' => 'error', 'message' => $stockResult['message']]);
                 exit();
            }

            // Tính toán tiền
            $subtotal = 0;
            foreach ($cartToCheckout as $item) {
                $subtotal += $item['price'] * $item['quantity'];
            }
            
            $shippingFee = 30000;
            $tax = 0;
            $totalCents = $subtotal + $shippingFee + $tax;
            
            // Cập nhật mảng data: thêm payment_method
            $orderModel = $this->model('Order');
            $orderNumber = 'ORD-' . strtoupper(uniqid());
            $orderData = [
                'user_id' => $userId,
                'order_number' => $orderNumber,
                'status' => 'pending',
                'subtotal_cents' => $subtotal,
                'shipping_fee_cents' => $shippingFee,
                'tax_cents' => $tax,
                'total_cents' => $totalCents,
                'shipping_address_id' => $addressId,
                'billing_address_id' => $addressId,            
                'payment_method' => $paymentMethod,
                'payment_status' => 'unpaid',
                'placed_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s')
            ];
            $orderId = $orderModel->create($orderData);

            $orderItemModel = $this->model('OrderItem');
            foreach ($cartToCheckout as $item) {
                $snapshot = json_encode([
                    'name' => $item['name'],
                    'image' => $item['image'] ?? '',
                ]);
                $itemData = [
                    'order_id' => $orderId,
                    'variant_id' => $item['variant_id'],
                    'product_snapshot' => $snapshot, 
                    'quantity' => $item['quantity'],
                    'unit_price_cents' => $item['price'],
                    'total_price_cents' => $item['price'] * $item['quantity']
                ];
                $orderItemModel->create($itemData);
            }

            $historyModel = $this->model('OrderStatusHistory');
            $historyModel->addHistory($orderId, 'pending', $userId, 'Đơn hàng mới được tạo');

            $cartModel->deleteMulti(array_column($cartToCheckout, 'id'));
            
            // Xử lý luồng dựa trên phương thức thanh toán
            if ($paymentMethod === 'banking') {
                // Tạo link VietQR
                // Format: https://img.vietqr.io/image/{BANK_ID}-{ACCOUNT_NO}-{TEMPLATE}.png?amount={AMOUNT}&addInfo={CONTENT}&accountName={ACCOUNT_NAME}
                $bankId = 'mbbank'; // ID ngân hàng (vd: mbbank, vcb, vietinbank)
                $accountNo = '3512345635'; // Số tài khoản
                $accountName = 'LE NGUYEN BAO LONG'; // Tên tài khoản không dấu
                $amount = $totalCents;
                $addInfo = $orderNumber; // Nội dung chuyển khoản là mã đơn hàng

                $qrUrl = "https://img.vietqr.io/image/{$bankId}-{$accountNo}-compact2.png?amount={$amount}&addInfo={$addInfo}&accountName=" . urlencode($accountName);

                echo json_encode([
                    'status' => 'success', 
                    'action' => 'show_qr', 
                    'order_id' => $orderId, 
                    'qr_url' => $qrUrl,
                    'amount' => number_format($totalCents) . 'đ',
                    'order_number' => $orderNumber
                ]);
            } else {
                // COD hoặc khác -> Chuyển hướng thẳng
                echo json_encode([
                    'status' => 'success', 
                    'action' => 'redirect', 
                    'redirect' => "/MY_WEB/public/checkout/success?order_id=$orderId"
                ]);
            }
            exit;
        }
    }

    // API Kiểm tra trạng thái thanh toán (Dùng cho AJAX Polling)
    public function checkPaymentStatus($orderId) {
        if (!isset($_SESSION['user_logged_in'])) {
            echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
            return;
        }
        
        $orderModel = $this->model('Order');
        $paymentStatus = $orderModel->getPaymentStatus($orderId);
        
        if ($paymentStatus === 'paid') {
            echo json_encode(['status' => 'paid']);
        } else {
            echo json_encode(['status' => 'unpaid']);
        }
    }

    // 3. Trang thông báo thành công
    public function success() {
        // Kiểm tra đăng nhập
        if (!isset($_SESSION['user_logged_in'])) {
            header('Location: /MY_WEB/public/');
            exit();
        }

        $orderId = $_GET['order_id'] ?? 0;
        $orderModel = $this->model('Order');
        $order = $orderModel->getOrderDetail($orderId);

        // Bảo mật: Chỉ cho phép xem đơn hàng của chính mình
        if (!$order || $order['user_id'] != $_SESSION['user_id']) {
            // Redirect về trang chủ hoặc hiện lỗi nếu cố tình xem đơn người khác
            header('Location: /MY_WEB/public/');
            exit();
        }

        // Lấy danh sách sản phẩm
        $items = $orderModel->getOrderItems($orderId);
        
        // Xử lý logic hiển thị ảnh/tên từ Snapshot JSON (nếu có)
        foreach ($items as &$item) {
            $snapshot = json_decode($item['product_snapshot'] ?? '', true);
            $item['display_name'] = $snapshot['name'] ?? $item['product_name']; // Ưu tiên snapshot
            
            // Xử lý ảnh
            $img = $snapshot['image'] ?? $item['live_image_url'];
            $item['display_image'] = !empty($img) ? "/MY_WEB/public/" . $img : "https://placehold.co/60";
        }

        // Tính ngày dự kiến giao hàng (Ví dụ: +3 ngày kể từ ngày đặt)
        $placedDate = strtotime($order['created_at']);
        $expectedDate = date('d/m/Y', strtotime('+3 days', $placedDate));

        $this->view('client/checkout/success', [
            'order' => $order,
            'items' => $items,
            'expectedDate' => $expectedDate
        ]);
    }
}