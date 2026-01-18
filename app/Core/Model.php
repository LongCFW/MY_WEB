<?php
namespace App\Core;

use App\Core\Database;

abstract class Model {
    protected $table; // Tên bảng (ví dụ: users)
    protected $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    // Lấy tất cả dữ liệu
    public function all() {
        $sql = "SELECT * FROM {$this->table}";
        return $this->db->fetchAll($sql);
    }

    // Tìm theo ID
    public function find($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = ?";
        return $this->db->fetch($sql, [$id]);
    }

    // Xóa theo ID
    public function delete($id) {
        $sql = "DELETE FROM {$this->table} WHERE id = ?";
        return $this->db->query($sql, [$id]);
    }
    
    // Chúng ta sẽ thêm hàm create, update sau
}