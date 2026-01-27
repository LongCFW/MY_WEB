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
    
    // Hàm Thêm dữ liệu 
    public function create($data) {
        // $data = ['name' => 'A', 'slug' => 'b']
        $keys = implode(", ", array_keys($data));
        $values = ":" . implode(", :", array_keys($data));
        
        $sql = "INSERT INTO {$this->table} ($keys) VALUES ($values)";
        $this->db->query($sql, $data);
        return $this->db->lastInsertId();
    }

    // Hàm Cập nhật dữ liệu 
    public function update($id, $data) {
        $sets = "";
        foreach ($data as $key => $value) {
            $sets .= "$key = :$key, ";
        }
        $sets = rtrim($sets, ", "); // Xóa dấu phẩy thừa
        
        $sql = "UPDATE {$this->table} SET $sets WHERE id = :id";
        
        // Thêm id vào mảng data để bind param
        $data['id'] = $id;
        return $this->db->query($sql, $data);
    }
}