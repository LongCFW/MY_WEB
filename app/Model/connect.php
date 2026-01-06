<?php
$servername = "localhost"; // Tên server (mặc định: localhost)
$username = "root"; // Username MySQL (mặc định: root)
$password = "baolong!@#123"; // Password MySQL (mặc định: trống)
$dbname = "ecostore"; // Tên database
// Tạo kết nối
$conn = new mysqli($servername, $username, $password, $dbname);
// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
// Set charset UTF-8
$conn->set_charset("utf8mb4");
