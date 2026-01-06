<?php
namespace App\Core; // Đặt namespace cho class này, giúp tránh trùng tên với các class khác

use PDO;           // Dùng class PDO (PHP Data Objects) để kết nối database
use PDOException;  // Dùng để bắt lỗi khi kết nối database thất bại

class Database {
    private static $instance = null; // Biến static lưu instance của Database (Singleton)
    private $connection;             // Biến lưu PDO connection

    // Constructor private để ngăn việc tạo object ngoài class (phục vụ Singleton pattern)
    private function __construct() {
        // Lấy cấu hình database từ file config
        $config = require __DIR__ . '/../../config/database.php';
        
        // Tạo DSN (Data Source Name) cho PDO
        $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}";
        
        try {
            // Tạo kết nối PDO
            $this->connection = new PDO($dsn, $config['user'], $config['pass']);
            
            // Thiết lập PDO để ném exception khi có lỗi
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Thiết lập fetch mode mặc định là associative array
            $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Nếu kết nối thất bại, dừng chương trình và hiển thị lỗi
            die("DB Connection Error: " . $e->getMessage());
        }
    }

    // Singleton Pattern: lấy instance Database duy nhất
    public static function getInstance() {
        if (!self::$instance) {        // Nếu chưa có instance nào
            self::$instance = new Database(); // Tạo mới
        }
        return self::$instance->connection; // Trả về connection PDO
    }
}
