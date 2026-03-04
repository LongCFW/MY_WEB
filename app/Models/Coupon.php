<?php
namespace App\Models;
use App\Core\Model;

class Coupon extends Model {
    protected $table = 'coupons';

    // Lấy tất cả
    public function getAll($orderBy = 'created_at DESC') {
        $sql = "SELECT * FROM {$this->table} ORDER BY $orderBy";
        return $this->db->fetchAll($sql);
    }

    // Hàm kiểm tra trùng mã Code (Dùng cho cả lúc Thêm mới và Cập nhật)
    public function findByCode($code, $excludeId = null) {
        $sql = "SELECT id FROM {$this->table} WHERE code = ?";
        $params = [$code];

        // Nếu truyền vào ID để loại trừ (lúc Update)
        if ($excludeId) {
            $sql .= " AND id != ?";
            $params[] = $excludeId;
        }

        return $this->db->fetch($sql, $params);
    }

    // Lấy thông tin mã giảm giá theo ID
    public function getById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = ?";
        return $this->db->fetch($sql, [$id]);
    }

    // Lấy các mã giảm giá đang trong thời gian áp dụng
    public function getActiveCoupons() {
        $sql = "SELECT * FROM {$this->table} WHERE starts_at <= NOW() AND ends_at >= NOW()";
        return $this->db->fetchAll($sql);
    }

    // Lấy TOÀN BỘ thông tin của Coupon dựa vào mã code
    public function getCouponByCode($code) {
        $sql = "SELECT * FROM {$this->table} WHERE code = ?";
        return $this->db->fetch($sql, [$code]);
    }

    // Tăng số lượt sử dụng của mã giảm giá lên 1
    public function incrementUsage($couponId)
    {
        // LƯU Ý: Vẫn phải kiểm tra xem cột lưu số lần dùng trong database có đúng tên là 'used_count' không nhé
        $sql = "UPDATE {$this->table} SET used_count = used_count + 1 WHERE id = ?";
        
        // Sử dụng hàm execute (hoặc query) của class Database thay vì prepare
        // Tham số truyền vào dùng dấu ? giống như ở hàm findByCode của bạn
        return $this->db->query($sql, [$couponId]);
    }

    // HÀM LẤY DANH SÁCH MÃ GIẢM GIÁ (CÓ BỘ LỌC)
    public function getAllCoupons($filters = []) {
        $sql = "SELECT * FROM {$this->table} WHERE 1=1";
        $params = [];

        // 1. Tìm kiếm theo mã Code
        if (!empty($filters['search'])) {
            $sql .= " AND code LIKE ?";
            $params[] = "%" . $filters['search'] . "%";
        }

        // 2. Lọc theo Loại giảm giá
        if (!empty($filters['type'])) {
            $sql .= " AND type = ?";
            $params[] = $filters['type'];
        }

        // 3. Lọc theo Trạng thái (Dựa vào thời gian starts_at và ends_at)
        if (!empty($filters['status'])) {
            if ($filters['status'] == 'active') {
                // Đang chạy
                $sql .= " AND starts_at <= NOW() AND ends_at >= NOW()";
            } elseif ($filters['status'] == 'upcoming') {
                // Sắp tới
                $sql .= " AND starts_at > NOW()";
            } elseif ($filters['status'] == 'expired') {
                // Đã hết hạn
                $sql .= " AND ends_at < NOW()";
            }
        }

        $sql .= " ORDER BY created_at DESC";
        return $this->db->fetchAll($sql, $params);
    }
}