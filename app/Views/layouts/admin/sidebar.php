<?php
// Helper check active menu
function isActive($path) {
    return (strpos($_SERVER['REQUEST_URI'], $path) !== false) ? 'active' : '';
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoStore Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="/MY_WEB/public/assets/css/admin.css">
</head>
<body>

<div id="wrapper">
    <div id="sidebar-wrapper">
        <div class="sidebar-heading">
            <i class="fas fa-leaf mr-2"></i> ECO ADMIN
        </div>
        <div class="list-group list-group-flush mt-3">
            <a href="/MY_WEB/public/admin/dashboard" class="list-group-item list-group-item-action <?= isActive('dashboard') ?>">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
            <a href="/MY_WEB/public/admin/category" class="list-group-item list-group-item-action <?= isActive('category') ?>">
                <i class="fas fa-tags"></i> Danh mục
            </a>
            <a href="/MY_WEB/public/admin/product" class="list-group-item list-group-item-action <?= isActive('product') ?>">
                <i class="fas fa-box-open"></i> Sản phẩm
            </a>
            <a href="/MY_WEB/public/admin/order" class="list-group-item list-group-item-action <?= isActive('order') ?>">
                <i class="fas fa-shopping-cart"></i> Đơn hàng
            </a>
            <a href="/MY_WEB/public/admin/user" class="list-group-item list-group-item-action <?= isActive('user') ?>">
                <i class="fas fa-users"></i> Khách hàng
            </a>
            
            <div class="mt-5 border-top border-secondary pt-3">
                <a href="/MY_WEB/public/" target="_blank" class="list-group-item list-group-item-action">
                    <i class="fas fa-globe"></i> Trang chủ
                </a>
                <a href="/MY_WEB/public/admin/auth/logout" class="list-group-item list-group-item-action text-danger">
                    <i class="fas fa-sign-out-alt"></i> Đăng xuất
                </a>
            </div>
        </div>
    </div>

    <div id="page-content-wrapper">
        <nav class="navbar navbar-admin">
            <button class="btn btn-light shadow-sm" id="menu-toggle"><i class="fas fa-bars"></i></button>
            <div class="dropdown">
                <a class="text-dark font-weight-bold dropdown-toggle text-decoration-none" href="#" role="button" data-toggle="dropdown">
                    <img src="https://ui-avatars.com/api/?name=<?= $_SESSION['admin_name'] ?? 'Admin' ?>&background=random" class="rounded-circle mr-2" width="35">
                    Xin chào, <?= $_SESSION['admin_name'] ?? 'Admin' ?>
                </a>
                <div class="dropdown-menu dropdown-menu-right shadow border-0 mt-2">
                    <a class="dropdown-item text-danger" href="/MY_WEB/public/admin/auth/logout"><i class="fas fa-sign-out-alt mr-2"></i> Đăng xuất</a>
                </div>
            </div>
        </nav>

        <div class="main-content container-fluid">