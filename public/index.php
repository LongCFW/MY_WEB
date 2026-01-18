<?php
// public/index.php

// 1. Nạp Composer Autoload
require_once __DIR__ . '/../vendor/autoload.php';

// 2. Nạp biến môi trường từ file .env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

echo "<h1>Kiểm tra hệ thống Ecostore</h1>";

// 3. Test kết nối Database & Model User
try {
    // Khởi tạo Model User
    $userModel = new \App\Models\User();
    
    // Thử lấy danh sách user (Hiện tại bảng users đang rỗng nên nó sẽ trả về mảng rỗng)
    $users = $userModel->all();
    
    echo "<p style='color:green; font-weight:bold;'>✅ KẾT NỐI DATABASE THÀNH CÔNG!</p>";
    echo "<p>Đang kết nối tới database: <strong>" . $_ENV['DB_DATABASE'] . "</strong></p>";
    
    echo "<h3>Dữ liệu bảng users:</h3>";
    echo "<pre>";
    print_r($users); // Sẽ in ra Array() nếu chưa có dữ liệu
    echo "</pre>";

} catch (Exception $e) {
    echo "<p style='color:red; font-weight:bold;'>❌ LỖI KẾT NỐI:</p>";
    echo $e->getMessage();
}