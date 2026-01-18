<?php
namespace App\Models;

use App\Core\Model;

class User extends Model {
    // Khai báo tên bảng trùng khớp với Database của bạn
    protected $table = 'users';

    // Hàm riêng: Tìm user theo email (dùng cho đăng nhập)
    public function findByEmail($email) {
        $sql = "SELECT * FROM {$this->table} WHERE email = ?";
        return $this->db->fetch($sql, [$email]);
    }
}