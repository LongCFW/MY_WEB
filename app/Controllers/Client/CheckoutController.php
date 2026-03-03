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

    // 2. Xử lý Đặt hàng (POST)
    // 2. Xử lý Đặt hàng (POST)
    public function process() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (!isset($_SESSION['user_logged_in'])) {
                header('Location: /MY_WEB/public/auth/login');
                exit();
            }

            $userId = $_SESSION['user_id'];
            $addressId = $_POST['address_id'] ?? null;
            $paymentMethod = $_POST['payment_method'] ?? 'cod';

            // Validate Address
            if (!$addressId) {
                echo "<script>alert('Vui lòng chọn địa chỉ giao hàng!'); window.history.back();</script>";
                return;
            }
            
            // LẤY LẠI GIỎ HÀNG TỪ DB ĐỂ TÍNH TIỀN 
            $cartModel = $this->model('CartItem');
            $allCartItems = $cartModel->getCartDetails($userId);
            
            // Ở đây lấy tạm toàn bộ giỏ hàng DB để xử lý cho chạy được đã:
            $cartToCheckout = $allCartItems; 
                    
            if (empty($cartToCheckout)) {
                echo "<script>alert('Giỏ hàng trống!'); window.location.href='/MY_WEB/public/cart';</script>";
                exit();
            }

            // --- [CHỐT CHẶN BẢO MẬT] KIỂM TRA TRẠNG THÁI BIẾN THỂ TRƯỚC KHI TRỪ KHO ---
            // Tránh trường hợp khách giữ đồ trong giỏ từ lâu, nay admin đã xóa (is_active = 0)
            $variantModel = $this->model('ProductVariant');
            foreach ($cartToCheckout as $item) {
                // Sử dụng Model để gọi dữ liệu (Chuẩn MVC)
                $variantInfo = $variantModel->getVariantInfo($item['variant_id']);

                if (!$variantInfo || $variantInfo['is_active'] == 0) {
                    echo "<script>alert('Sản phẩm \"{$item['name']}\" đã ngừng kinh doanh. Vui lòng xóa khỏi giỏ hàng.'); window.location.href='/MY_WEB/public/cart';</script>";
                    exit();
                }

                if ($variantInfo['stock'] < $item['quantity']) {
                    echo "<script>alert('Sản phẩm \"{$item['name']}\" chỉ còn {$variantInfo['stock']} kiện. Vui lòng cập nhật lại giỏ hàng.'); window.location.href='/MY_WEB/public/cart';</script>";
                    exit();
                }
            }
            // --- KẾT THÚC CHỐT CHẶN ---

            // BƯỚC QUAN TRỌNG: TRỪ KHO 
            // Gọi transaction trừ kho
            $stockResult = $variantModel->deductStockForOrder($cartToCheckout);

            if ($stockResult['status'] === false) {
                // Nếu thất bại (hết hàng phút chót do có người mua trùng lúc) -> Báo lỗi & về giỏ hàng
                echo "<script>alert('" . $stockResult['message'] . "'); window.location.href='/MY_WEB/public/cart';</script>";
                exit();
            }

            // Tính toán tiền lại (Backend Calculation)
            $subtotal = 0;
            foreach ($cartToCheckout as $item) {
                $subtotal += $item['price'] * $item['quantity'];
            }
            
            $shippingFee = 30000;
            $tax = 0;
            $totalCents = $subtotal + $shippingFee + $tax;
            
            $orderModel = $this->model('Order');
            $orderData = [
                'user_id' => $userId,
                'order_number' => 'ORD-' . strtoupper(uniqid()),
                'status' => 'pending',
                'subtotal_cents' => $subtotal,
                'shipping_fee_cents' => $shippingFee,
                'tax_cents' => $tax,
                'total_cents' => $totalCents,
                'shipping_address_id' => $addressId,
                'billing_address_id' => $addressId,            
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
                    'variant_id' => $item['variant_id'], // Có variant_id từ DB
                    'product_snapshot' => $snapshot, 
                    'quantity' => $item['quantity'],
                    'unit_price_cents' => $item['price'],
                    'total_price_cents' => $item['price'] * $item['quantity']
                ];
                $orderItemModel->create($itemData);
            }

            $historyModel = $this->model('OrderStatusHistory');
            $historyModel->addHistory($orderId, 'pending', $userId, 'Đơn hàng mới được tạo');

            // XÓA GIỎ HÀNG TRONG DB 
            // Vì CartController dùng DB, nên phải xóa trong DB, unset Session ko có tác dụng
            $cartModel->deleteMulti(array_column($cartToCheckout, 'id'));
            
            header("Location: /MY_WEB/public/checkout/success?order_id=$orderId");
            exit;
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