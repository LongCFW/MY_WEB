<?php
namespace App\Controllers\Client;

use App\Core\Controller;

class OrderController extends Controller {
    
    // API lấy chi tiết đơn hàng (Trả về JSON)
    public function get_detail($id) {
        if (!isset($_SESSION['user_logged_in'])) {
            echo json_encode(['error' => 'Unauthorized']);
            return;
        }

        $orderModel = $this->model('Order');
        $order = $orderModel->getOrderDetail($id);

        // Bảo mật: Chỉ xem được đơn của chính mình
        if (!$order || $order['user_id'] != $_SESSION['user_id']) {
            echo json_encode(['error' => 'Not found or Access denied']);
            return;
        }

        $items = $orderModel->getOrderItems($id);
        
        // Parse product_snapshot JSON
        foreach ($items as &$item) {
            $snapshot = json_decode($item['product_snapshot'], true);
            $item['product_name'] = $snapshot['name'] ?? 'Sản phẩm';
            $item['product_image'] = $snapshot['image'] ?? '';
        }

        echo json_encode([
            'order' => $order,
            'items' => $items
        ]);
    }
}