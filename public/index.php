<?php
date_default_timezone_set('Asia/Ho_Chi_Minh');
session_start(); // Bắt đầu session

// 1. Nạp thư viện Composer (Dotenv, v.v...)
require_once __DIR__ . '/../vendor/autoload.php';

// 2. Load biến môi trường .env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// 3. Nạp file init.php (SỬA DÒNG NÀY)
// Kiểm tra file có tồn tại không trước khi require để tránh lỗi Fatal
$initFile = __DIR__ . '/../app/init.php';

// 4. Khởi chạy Router
use App\Core\App; // Import namespace
$app = new App();