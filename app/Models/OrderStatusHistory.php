<?php
namespace App\Models;
use App\Core\Model;

class OrderStatusHistory extends Model {
    protected $table = 'order_status_history';

    public function addHistory($orderId, $status, $userId = null, $note = '') {
        $data = [
            'order_id' => $orderId,
            'status' => $status,
            'changed_by' => $userId, // NULL nếu là hệ thống tự động, hoặc ID admin
            'note' => $note,
            'created_at' => date('Y-m-d H:i:s')
        ];
        return $this->create($data);
    }
    
    public function getHistoryByOrderId($orderId) {
        $sql = "SELECT h.*, u.name as changer_name 
                FROM {$this->table} h 
                LEFT JOIN users u ON h.changed_by = u.id 
                WHERE order_id = ? 
                ORDER BY created_at DESC";
        return $this->db->fetchAll($sql, [$orderId]);
    }
}