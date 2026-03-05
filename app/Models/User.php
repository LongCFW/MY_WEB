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

    // check email
    public function checkPhoneExists($phone, $excludeId = null) {
        $sql = "SELECT id FROM {$this->table} WHERE phone = ?";
        $params = [$phone];
        
        if ($excludeId) {
            $sql .= " AND id != ?";
            $params[] = $excludeId;
        }
        
        return $this->db->fetch($sql, $params);
    }

    // Hàm check email tồn tại (trừ user hiện tại đang sửa)
    public function checkEmailExists($email, $excludeId = null) {
        $sql = "SELECT id FROM {$this->table} WHERE email = ?";
        $params = [$email];
        
        if ($excludeId) {
            $sql .= " AND id != ?";
            $params[] = $excludeId;
        }
        
        return $this->db->fetch($sql, $params);
    }

    public function updateProfile($id, $name, $phone, $avatarPath = null) {
        if ($avatarPath) {
            // đổi ảnh
            $sql = "UPDATE {$this->table} SET name = ?, phone = ?, avatar_url = ? WHERE id = ?";
            return $this->db->query($sql, [$name, $phone, $avatarPath, $id]);
        } else {
            // Nếu không đổi ảnh
            $sql = "UPDATE {$this->table} SET name = ?, phone = ? WHERE id = ?";
            return $this->db->query($sql, [$name, $phone, $id]);
        }
    }

    // Tìm user theo Email VÀ Phone (Dùng cho quên mật khẩu)
    public function findByEmailAndPhone($email, $phone) {
        $sql = "SELECT * FROM {$this->table} WHERE email = ? AND phone = ?";
        return $this->db->fetch($sql, [$email, $phone]);
    }

    // Cập nhật mật khẩu mới
    public function updatePassword($id, $newPasswordHash) {
        $sql = "UPDATE {$this->table} SET password_hash = ? WHERE id = ?";
        return $this->db->query($sql, [$newPasswordHash, $id]);
    }

    // Đếm số lượng Khách hàng THẬT (role_id = 4)
    public function countRealCustomers() {
        $sql = "SELECT COUNT(*) as total FROM {$this->table} WHERE role_id = 4";
        $result = $this->db->fetch($sql);
        return $result['total'] ?? 0;
    }

    // Đếm số lượng Tài khoản ẢO / SEEDING (role_id = 5)
    public function countSeedingUsers() {
        $sql = "SELECT COUNT(*) as total FROM {$this->table} WHERE role_id = 5";
        $result = $this->db->fetch($sql);
        return $result['total'] ?? 0;
    }

    // Đếm số lượng Quản trị & Nhân viên
    public function countStaffAndAdmin() {
        $sql = "SELECT COUNT(*) as total FROM {$this->table} WHERE role_id IN (1, 2, 3)";
        $result = $this->db->fetch($sql);
        return $result['total'] ?? 0;
    }

    public function getUserStatus($userId) {
        $sql = "SELECT status FROM {$this->table} WHERE id = ?";
        $result = $this->db->fetch($sql, [$userId]);
        return $result ? $result['status'] : null;
    }

    // Xác thực Email bằng Token
    public function verifyEmailByToken($token) {
        $sql = "UPDATE {$this->table} SET email_verified = 1, verification_token = NULL WHERE verification_token = ?";
        return $this->db->query($sql, [$token]);
    }

    // (Bên trong class User, thêm 2 hàm này)

    // Tạo user và trả về ID vừa tạo
    public function createAndReturnId($data) {
        if ($this->create($data)) {
            return $this->db->lastInsertId(); // Yêu cầu Database.php có hàm này, nếu không thì lấy theo email
        }
        return false;
    }

    // Tìm user bằng ID
    public function findById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = ?";
        return $this->db->fetch($sql, [$id]);
    }

    // Lưu OTP Quên mật khẩu và thời gian hết hạn (15 phút)
    public function saveResetToken($userId, $token, $expiry) {
        $sql = "UPDATE {$this->table} SET reset_token = ?, reset_token_expire = ? WHERE id = ?";
        return $this->db->query($sql, [$token, $expiry, $userId]);
    }

    // Kiểm tra OTP Quên mật khẩu (phải đúng mã và chưa hết hạn)
    public function checkResetToken($userId, $token) {
        $sql = "SELECT id FROM {$this->table} WHERE id = ? AND reset_token = ? AND reset_token_expire > NOW()";
        return $this->db->fetch($sql, [$userId, $token]);
    }

    // Xóa OTP sau khi đã đổi mật khẩu thành công
    public function clearResetToken($userId) {
        $sql = "UPDATE {$this->table} SET reset_token = NULL, reset_token_expire = NULL WHERE id = ?";
        return $this->db->query($sql, [$userId]);
    }

    // Cập nhật Google ID cho tài khoản đã tồn tại
    public function updateGoogleId($id, $googleId, $avatarUrl = null) {
        $sql = "UPDATE {$this->table} SET google_id = ?";
        $params = [$googleId];

        // Nếu Google có trả về ảnh đại diện mà user chưa có ảnh, thì cập nhật luôn
        if ($avatarUrl) {
            $sql .= ", avatar_url = ?";
            $params[] = $avatarUrl;
        }

        $sql .= " WHERE id = ?";
        $params[] = $id;

        return $this->db->query($sql, $params);
    }
    
    // Kiểm tra xem user có đơn hàng nào không
    public function hasOrders($userId) {
        $sql = "SELECT id FROM orders WHERE user_id = ? LIMIT 1";
        $result = $this->db->fetch($sql, [$userId]);
        return $result ? true : false;
    }

    // HÀM LẤY DANH SÁCH USER CHO ADMIN (CÓ BỘ LỌC)
    public function getAllUsers($filters = []) {
        $sql = "SELECT * FROM {$this->table} WHERE 1=1";
        $params = [];

        // 1. Tìm kiếm theo Tên, Email hoặc Số điện thoại
        if (!empty($filters['search'])) {
            $sql .= " AND (name LIKE ? OR email LIKE ? OR phone LIKE ?)";
            $params[] = "%" . $filters['search'] . "%";
            $params[] = "%" . $filters['search'] . "%";
            $params[] = "%" . $filters['search'] . "%";
        }

        // 2. Lọc theo Vai trò
        if (!empty($filters['role_id'])) {
            $sql .= " AND role_id = ?";
            $params[] = $filters['role_id'];
        }

        // 3. Lọc theo Trạng thái
        if (isset($filters['status']) && $filters['status'] !== '') {
            $sql .= " AND status = ?";
            $params[] = $filters['status'];
        }

        $sql .= " ORDER BY id DESC";

        return $this->db->fetchAll($sql, $params);
    }

    // --- HÀM LẤY TÀI KHOẢN ẢO ĐỂ SEEDING ĐÁNH GIÁ ---
    // (Bạn có thể quy ước trong CSDL những User nào có role_id = 5 là tài khoản Seeding)
    public function getSeedingUsers() {
        // Tạm thời lấy các user khách hàng để làm mồi (bạn có thể đổi WHERE role_id = 5 sau này)
        $sql = "SELECT id, name, avatar_url FROM {$this->table} WHERE role_id = 5 AND status = 1";
        return $this->db->fetchAll($sql);
    }
}