<?php
$servername = "localhost";
$username = "root";
$password = "baolong!@#123";
$dbname = "ecostore";

try {
    $dsn = "mysql:host=$servername;dbname=$dbname;charset=utf8mb4";
    $conn = new PDO($dsn, $username, $password);

    // Bật chế độ báo lỗi
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    die("Kết nối thất bại: " . $e->getMessage());
}
