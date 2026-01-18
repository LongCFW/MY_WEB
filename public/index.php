<?php
session_start(); // Bắt đầu session cho toàn bộ web

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// Khởi chạy Router
$app = new App\Core\App();