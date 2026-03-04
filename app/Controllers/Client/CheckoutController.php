<?php

namespace App\Controllers\Client;

use App\Core\Controller;

class CheckoutController extends Controller
{

    // 1. Hiển thị trang thanh toán
    public function index()
    {
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

    public function process()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            header('Content-Type: application/json');

            if (!isset($_SESSION['user_logged_in'])) {
                echo json_encode(['status' => 'error', 'message' => 'Vui lòng đăng nhập', 'redirect' => '/MY_WEB/public/auth/login']);
                exit();
            }

            $userId = $_SESSION['user_id'];
            $addressId = $_POST['address_id'] ?? null;
            $paymentMethod = $_POST['payment_method'] ?? 'cod';

            if (!$addressId) {
                echo json_encode(['status' => 'error', 'message' => 'Vui lòng chọn địa chỉ giao hàng!']);
                return;
            }

            $cartModel = $this->model('CartItem');
            $allCartItems = $cartModel->getCartDetails($userId);
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

            $stockResult = $variantModel->deductStockForOrder($cartToCheckout);
            if ($stockResult['status'] === false) {
                echo json_encode(['status' => 'error', 'message' => $stockResult['message']]);
                exit();
            }

            $subtotal = 0;
            foreach ($cartToCheckout as $item) {
                $subtotal += $item['price'] * $item['quantity'];
            }

            $shippingFee = 30000;
            $tax = 0;
            $discountAmount = 0;
            $appliedCouponId = null;
            
            if (isset($_SESSION['applied_coupon'])) {
                $discountAmount = $_SESSION['applied_coupon']['discount_amount'];
                $appliedCouponId = $_SESSION['applied_coupon']['id'];
            }

            $totalCents = $subtotal + $shippingFee + $tax - $discountAmount;
            if ($totalCents < 0) $totalCents = 0;

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
            $mailItems = []; 

            foreach ($cartToCheckout as $item) {
                $snapshot = json_encode([
                    'name' => $item['name'],
                    'image' => $item['image'] ?? '',
                ]);
                $itemTotal = $item['price'] * $item['quantity'];
                
                $itemData = [
                    'order_id' => $orderId,
                    'variant_id' => $item['variant_id'],
                    'product_snapshot' => $snapshot,
                    'quantity' => $item['quantity'],
                    'unit_price_cents' => $item['price'],
                    'total_price_cents' => $itemTotal
                ];
                $orderItemModel->create($itemData);
                
                $mailItems[] = [
                    'display_name' => $item['name'],
                    'quantity' => $item['quantity'],
                    'total_price_cents' => $itemTotal
                ];
            }

            if ($appliedCouponId) {
                $orderCouponModel = $this->model('OrderCoupon');
                $orderCouponModel->create([
                    'order_id' => $orderId,
                    'coupon_id' => $appliedCouponId,
                    'applied_amount_cents' => $discountAmount
                ]);

                $couponModel = $this->model('Coupon');
                $couponModel->incrementUsage($appliedCouponId);
                unset($_SESSION['applied_coupon']);
            }

            $historyModel = $this->model('OrderStatusHistory');
            $historyModel->addHistory($orderId, 'pending', $userId, 'Đơn hàng mới được tạo');

            $cartModel->deleteMulti(array_column($cartToCheckout, 'id'));

            // --- XỬ LÝ LUỒNG TRẢ VỀ & GỬI MAIL ---
            if ($paymentMethod === 'banking') {
                $bankId = 'mbbank';
                $accountNo = '3512345635';
                $accountName = 'LE NGUYEN BAO LONG';
                $amount = $totalCents;
                $addInfo = $orderNumber;

                $qrUrl = "https://img.vietqr.io/image/{$bankId}-{$accountNo}-compact2.png?amount={$amount}&addInfo={$addInfo}&accountName=" . urlencode($accountName);

                echo json_encode([
                    'status' => 'success',
                    'action' => 'show_qr',
                    'order_id' => $orderId,
                    'qr_url' => $qrUrl,
                    'amount' => number_format($totalCents) . 'đ',
                    'order_number' => $orderNumber
                ]);
                exit; 
            } else {
                // TRƯỜNG HỢP COD: GỬI MAIL VÀ REDIRECT
                $userModel = $this->model('User');
                $user = $userModel->findById($userId);
                $userEmail = $user['email'] ?? null;
                
                if ($userEmail) {
                    // Lấy đầy đủ thông tin đơn hàng (bao gồm cả địa chỉ đã JOIN) để truyền vào Mail
                    $fullOrderDetails = $orderModel->getOrderDetail($orderId);
                    if($fullOrderDetails) {
                        \App\Core\MailHelper::sendOrderConfirmation($userEmail, $fullOrderDetails, $mailItems, false);
                    }
                }

                // --- [MỚI] BẮN THÔNG BÁO TẠO ĐƠN THÀNH CÔNG (COD) ---
                $notificationModel = $this->model('Notification');
                $title = "Đặt hàng thành công!";
                $message = "Đơn hàng #{$orderNumber} đã được đặt thành công bằng hình thức COD. Cảm ơn bạn đã mua sắm!";
                $notificationModel->send($userId, 'system', $title, $message, ['order_id' => $orderId]);                

                echo json_encode([
                    'status' => 'success',
                    'action' => 'redirect',
                    'redirect' => "/MY_WEB/public/checkout/success?order_id=$orderId"
                ]);
                exit;
            }
        }
    }

    // API Kiểm tra trạng thái thanh toán (Dùng cho AJAX Polling)
    public function checkPaymentStatus($orderId = null)
    {
        header('Content-Type: application/json');

        if (!isset($_SESSION['user_logged_in'])) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Unauthorized'
            ]);
            exit;
        }

        if (!$orderId) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Missing order id'
            ]);
            exit;
        }

        $orderModel = $this->model('Order');
        $paymentStatus = $orderModel->getPaymentStatus($orderId);

        if ($paymentStatus === 'paid') {
            echo json_encode(['status' => 'paid']);
        } else {
            echo json_encode(['status' => 'unpaid']);
        }

        exit;
    }

    // 3. Trang thông báo thành công
    public function success()
    {
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

    public function applyCoupon()
    {
        // Tắt hiển thị lỗi HTML để không làm hỏng chuỗi JSON
        ini_set('display_errors', 0);
        header('Content-Type: application/json');

        if (!isset($_SESSION['user_logged_in'])) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Vui lòng đăng nhập để sử dụng mã'
            ]);
            exit;
        }

        $code = $_POST['code'] ?? '';
        $subtotal = isset($_POST['subtotal']) ? (float)$_POST['subtotal'] : 0;

        if (empty($code)) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Vui lòng nhập mã giảm giá'
            ]);
            exit;
        }

        $couponModel = $this->model('Coupon');

        // [ĐÃ SỬA CHUẨN MVC]: Gọi hàm qua Model thay vì dùng $db
        $coupon = $couponModel->getCouponByCode($code);

        if (!$coupon) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Mã giảm giá không tồn tại'
            ]);
            exit;
        }

        // --- CÁC BƯỚC KIỂM TRA ĐIỀU KIỆN MÃ ---

        // 1. Kiểm tra ngày tháng
        $now = time();
        if (strtotime($coupon['starts_at']) > $now) {
            echo json_encode(['status' => 'error', 'message' => 'Mã giảm giá chưa đến thời gian sử dụng']);
            exit;
        }
        if (strtotime($coupon['ends_at']) < $now) {
            echo json_encode(['status' => 'error', 'message' => 'Mã giảm giá đã hết hạn']);
            exit;
        }

        // 2. Kiểm tra điều kiện đơn hàng tối thiểu
        if ($coupon['min_order_cents'] > 0 && $subtotal < $coupon['min_order_cents']) {
            echo json_encode(['status' => 'error', 'message' => 'Đơn hàng tối thiểu phải từ ' . number_format($coupon['min_order_cents'], 0, ',', '.') . 'đ']);
            exit;
        }

        // 3. Kiểm tra số lượt sử dụng
        if (!empty($coupon['usage_limit']) && $coupon['used_count'] >= $coupon['usage_limit']) {
            echo json_encode(['status' => 'error', 'message' => 'Mã giảm giá này đã hết số lượt sử dụng']);
            exit;
        }

        // --- TÍNH TOÁN SỐ TIỀN GIẢM ---
        $type  = $coupon['type'];
        $value = (float)$coupon['value'];
        $discount = 0;

        if ($type === 'percent') {
            $discount = $subtotal * ($value / 100);
        } else {
            $discount = $value;
        }

        // Không cho giảm vượt quá subtotal (Tổng tiền không được âm)
        if ($discount > $subtotal) {
            $discount = $subtotal;
        }

        // Lưu vào Session để lát nữa hàm process() lấy ra trừ tiền
        $_SESSION['applied_coupon'] = [
            'id' => $coupon['id'],
            'discount_amount' => $discount,
            'code' => $coupon['code']
        ];

        // Trả kết quả thành công về cho JS hiển thị
        echo json_encode([
            'status' => 'success',
            'message' => 'Áp dụng mã thành công!',
            'discount_amount' => $discount,
            'discount_formatted' => number_format($discount, 0, ',', '.') . ' đ'
        ]);

        exit;
    }
}
