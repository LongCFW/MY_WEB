<?php
namespace App\Models;

use App\Core\Model;

class Notification extends Model {
    protected $table = 'notifications';

    // 1. Lấy danh sách thông báo của user (Có phân trang)
    public function getUserNotifications($userId, $limit = 10, $offset = 0) {
        $sql = "SELECT * FROM {$this->table} 
                WHERE user_id = ? 
                ORDER BY created_at DESC 
                LIMIT $limit OFFSET $offset";
        // Hàm fetchAll phải trả về array
        $result = $this->db->fetchAll($sql, [$userId]);
        return is_array($result) ? $result : []; 
    }

    // 2. Đếm tổng số thông báo (Để phân trang)
    public function countUserNotifications($userId) {
        $sql = "SELECT COUNT(*) as total FROM {$this->table} WHERE user_id = ?";
        $result = $this->db->fetch($sql, [$userId]);
        return $result['total'] ?? 0;
    }

    // 3. Đếm số thông báo CHƯA ĐỌC (Hiện lên Header)
    public function countUnread($userId) {
        $sql = "SELECT COUNT(*) as total FROM {$this->table} WHERE user_id = ? AND is_read = 0";
        $result = $this->db->fetch($sql, [$userId]);
        return (int)($result['total'] ?? 0);
    }

    // 4. Đánh dấu danh sách ID là đã đọc
    public function markAsRead($idsArray) {
        if (empty($idsArray)) return false;
        
        // Tạo chuỗi ?,?,? tương ứng với số lượng ID
        $placeholders = implode(',', array_fill(0, count($idsArray), '?'));
        $sql = "UPDATE {$this->table} SET is_read = 1 WHERE id IN ($placeholders)";
        
        return $this->db->query($sql, $idsArray);
    }

    // 5. Xóa thông báo (Kiểm tra đúng chủ sở hữu)
    public function deleteNotification($id, $userId) {
        $sql = "DELETE FROM {$this->table} WHERE id = ? AND user_id = ?";
        return $this->db->query($sql, [$id, $userId]);
    }

    // 6. [QUAN TRỌNG] Hàm dùng để gửi thông báo từ Controller khác
    public function send($userId, $type, $title, $message, $metadata = []) {
        $metaJson = !empty($metadata) ? json_encode($metadata) : null;
        
        $sql = "INSERT INTO {$this->table} (user_id, type, title, message, is_read, metadata, created_at) 
                VALUES (?, ?, ?, ?, 0, ?, NOW())";
        
        return $this->db->query($sql, [$userId, $type, $title, $message, $metaJson]);
    }

    public function sendToAllCustomers($type, $title, $message, $metadata = []) {
        $metaJson = !empty($metadata) ? json_encode($metadata) : null;
        
        // Lấy tất cả user có role_id = 4 (Khách hàng) và status = 1 (Đang hoạt động)
        $sql = "INSERT INTO {$this->table} (user_id, type, title, message, is_read, metadata, created_at) 
                SELECT id, ?, ?, ?, 0, ?, NOW() 
                FROM users 
                WHERE role_id = 4 AND status = 1";
        
        return $this->db->query($sql, [$type, $title, $message, $metaJson]);
    }

    // Đánh dấu tất cả thông báo của 1 user thành Đã đọc
    public function markAllAsReadByUserId($userId) {
        $sql = "UPDATE {$this->table} SET is_read = 1 WHERE user_id = ?";
        return $this->db->query($sql, [$userId]);
    }

    // Xóa toàn bộ thông báo của 1 user
    public function deleteAllByUserId($userId) {
        $sql = "DELETE FROM {$this->table} WHERE user_id = ?";
        return $this->db->query($sql, [$userId]);
    }
}