<?php
namespace App\Core;

use PDO;
use PDOException;

class Database {
    private static $instance = null;
    private $connection;

    private function __construct() {
        // Load config
        $config = require __DIR__ . '/../../config/database.php';
        
        $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}";
        
        try {
            $this->connection = new PDO($dsn, $config['user'], $config['pass']);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("DB Connection Error: " . $e->getMessage());
        }
    }

    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new Database();
        }
        return self::$instance;
    }


    // Hàm lấy kết nối gốc nếu cần
    public function getConnection() {
        return $this->connection;
    }

    // Hàm thực thi SQL an toàn (Prepared Statement)
    // Cách dùng: $db->query("SELECT * FROM users WHERE id = ?", [$id]);
    public function query($sql, $params = []) {
        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            die("Lỗi truy vấn SQL: " . $e->getMessage());
        }
    }

    // Lấy 1 dòng dữ liệu
    public function fetch($sql, $params = []) {
        return $this->query($sql, $params)->fetch();
    }

    // Lấy nhiều dòng dữ liệu
    public function fetchAll($sql, $params = []) {
        return $this->query($sql, $params)->fetchAll();
    }
    
    // Lấy ID vừa insert
    public function lastInsertId() {
        return $this->connection->lastInsertId();
    }
    
    // Đếm số dòng bị ảnh hưởng (DELETE, UPDATE)
    public function rowCount($sql, $params = []) {
        return $this->query($sql, $params)->rowCount();
    }
}