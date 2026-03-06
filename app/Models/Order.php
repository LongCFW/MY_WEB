<?php
namespace App\Models;
use App\Core\Model;

class Order extends Model {
    protected $table = 'orders';

    // Lấy danh sách đơn hàng của User (Cho trang Profile)
    // Cập nhật: Thêm $limit và $offset (Mặc định 1000 để không ảnh hưởng code cũ nếu gọi thiếu)
    public function getOrdersByUserId($userId, $limit = 1000, $offset = 0) {
        $sql = "SELECT * FROM {$this->table} WHERE user_id = ? ORDER BY created_at DESC LIMIT $limit OFFSET $offset";
        return $this->db->fetchAll($sql, [$userId]);
    }

    //  Đếm tổng đơn hàng của user
    public function countOrdersByUserId($userId) {
        $sql = "SELECT count(*) as total FROM {$this->table} WHERE user_id = ?";
        $result = $this->db->fetch($sql, [$userId]);
        return $result['total'] ?? 0;
    }

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

    public function getAllOrders($filters = []) {
        // Query cơ bản (Dùng WHERE 1=1 để dễ dàng nối thêm các điều kiện AND ở dưới)
        $sql = "SELECT o.*, u.name as customer_name 
                FROM orders o 
                LEFT JOIN users u ON o.user_id = u.id 
                WHERE 1=1";
        
        $params = [];

        // 1. Lọc theo trạng thái đơn hàng
        if (!empty($filters['status'])) {
            $sql .= " AND o.status = ?";
            $params[] = $filters['status'];
        }

        // 2. Lọc theo phương thức thanh toán
        if (!empty($filters['payment_method'])) {
            $sql .= " AND o.payment_method = ?";
            $params[] = $filters['payment_method'];
        }

        // 3. Tìm kiếm theo Mã đơn hàng hoặc Tên khách hàng
        if (!empty($filters['search'])) {
            $sql .= " AND (o.order_number LIKE ? OR u.name LIKE ?)";
            // Thêm dấu % vào 2 bên để tìm kiếm chuỗi chứa từ khóa
            $params[] = "%" . $filters['search'] . "%"; 
            $params[] = "%" . $filters['search'] . "%";
        }

        // Sắp xếp đơn mới nhất lên đầu
        $sql .= " ORDER BY o.created_at DESC";

        // Sử dụng hàm fetchAll từ class Database của bạn
        return $this->db->fetchAll($sql, $params);
    }

    // Lấy chi tiết đơn hàng (Kèm thông tin User & Address)
    public function getOrderDetail($id) {
        $sql = "SELECT o.*, u.name as customer_name, u.email,
                       sa.full_name as ship_name, sa.phone as ship_phone, 
                       sa.address_line, sa.city, sa.province, sa.country
                FROM orders o
                LEFT JOIN users u ON o.user_id = u.id
                LEFT JOIN shipping_addresses sa ON o.shipping_address_id = sa.id
                WHERE o.id = ?";
        return $this->db->fetch($sql, [$id]);
    }

    public function getOrderItems($orderId) {
    // Kết hợp: Lấy dữ liệu item (chứa snapshot) VÀ Join thêm để lấy thông tin hiện tại (SKU, Category...)
    // Lưu ý: Trong CheckoutController tôi đã lưu 'product_id' vào order_items, nên ta JOIN thẳng vào products
    
    $sql = "SELECT oi.*, 
                   p.sku as product_sku,           -- Lấy SKU từ bảng sản phẩm hiện tại
                   p.id as live_product_id,        -- ID để tạo link bấm vào xem sp
                   MIN(img.image_url) as live_image_url -- Ảnh hiện tại (fallback nếu snapshot lỗi)
            FROM order_items oi
            -- Left Join để nếu sản phẩm gốc bị xóa thì đơn hàng vẫn hiện (chỉ mất SKU/Link)
            LEFT JOIN products p ON oi.product_id = p.id
            LEFT JOIN product_images img ON p.id = img.product_id
            WHERE oi.order_id = ?
            GROUP BY oi.id";

    return $this->db->fetchAll($sql, [$orderId]);
}

    public function updateStatus($id, $status) {
        $sql = "UPDATE {$this->table} SET status = ?, updated_at = NOW() WHERE id = ?";
        return $this->db->query($sql, [$status, $id]);
    }

    // Tính tổng doanh thu của các đơn hàng "Thành công"
    public function getRealRevenue() {
        // Chỉ tính tổng tiền của các đơn có status là 'completed'
        // Nếu bạn muốn tính cả đơn 'shipping' thì sửa thành: WHERE status IN ('completed', 'shipping')
        $sql = "SELECT SUM(total_cents) as total FROM {$this->table} WHERE status = 'completed'";
        $result = $this->db->fetch($sql);
        return $result['total'] ?? 0;
    }

    // Đếm tổng số đơn hàng
    public function countAllOrders() {
        $sql = "SELECT COUNT(*) as total FROM {$this->table}";
        $result = $this->db->fetch($sql);
        return $result['total'] ?? 0;
    }
    
     // Lấy trạng thái thanh toán của đơn hàng
    public function getPaymentStatus($orderId) {
        $sql = "SELECT payment_status FROM {$this->table} WHERE id = ?";
        $result = $this->db->fetch($sql, [$orderId]);
        return $result ? $result['payment_status'] : null;
    }
    
    // Cập nhật trạng thái thanh toán
    public function updatePaymentStatus($id, $status) {
        $sql = "UPDATE {$this->table} SET payment_status = ?, updated_at = NOW() WHERE id = ?";
        return $this->db->query($sql, [$status, $id]);
    }
}