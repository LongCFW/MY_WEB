<?php
namespace App\Models;
use App\Core\Model;

class Order extends Model {
    protected $table = 'orders';

    public function countOrders() {
        $sql = "SELECT COUNT(*) as total FROM {$this->table}";
        return $this->db->fetch($sql)['total'];
    }

    public function getTotalRevenue() {
        // Tính tổng tiền những đơn đã thanh toán hoặc hoàn thành (Tùy logic, ở đây tính hết)
        $sql = "SELECT SUM(total_cents) as total FROM {$this->table} WHERE payment_status = 'paid'";
        $result = $this->db->fetch($sql);
        return $result['total'] ?? 0;
    }

    public function getRecentOrders($limit = 5) {
        $sql = "SELECT o.*, u.name as user_name 
                FROM orders o 
                LEFT JOIN users u ON o.user_id = u.id 
                ORDER BY o.created_at DESC LIMIT $limit";
        return $this->db->fetchAll($sql);
    }

    public function getAllOrders() {
        $sql = "SELECT o.*, u.name as customer_name 
                FROM orders o 
                LEFT JOIN users u ON o.user_id = u.id 
                ORDER BY o.created_at DESC";
        return $this->db->fetchAll($sql);
    }

    public function getOrderDetail($id) {
        // Lấy thông tin đơn + địa chỉ ship
        $sql = "SELECT o.*, u.name as customer_name, u.email,
                       sa.full_name as ship_name, sa.phone as ship_phone, sa.address_line, sa.city
                FROM orders o
                LEFT JOIN users u ON o.user_id = u.id
                LEFT JOIN shipping_addresses sa ON o.shipping_address_id = sa.id
                WHERE o.id = ?";
        return $this->db->fetch($sql, [$id]);
    }

    public function getOrderItems($orderId) {
        $sql = "SELECT oi.*, p.name as product_name, p.sku as product_sku, 
                       MIN(img.image_url) as image_url
                FROM order_items oi
                LEFT JOIN product_variants pv ON oi.variant_id = pv.id
                LEFT JOIN products p ON pv.product_id = p.id
                LEFT JOIN product_images img ON p.id = img.product_id
                WHERE oi.order_id = ?
                GROUP BY oi.id";
        return $this->db->fetchAll($sql, [$orderId]);
    }

    public function updateStatus($id, $status) {
        $sql = "UPDATE {$this->table} SET status = ? WHERE id = ?";
        return $this->db->query($sql, [$status, $id]);
    }
}