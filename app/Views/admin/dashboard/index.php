<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
</head>
<body>
    <nav class="navbar navbar-dark bg-dark">
        <span class="navbar-brand mb-0 h1">Admin Panel</span>
        <a href="/MY_WEB/public/admin/auth/logout" class="btn btn-danger btn-sm">Đăng xuất</a>
    </nav>

    <div class="container mt-4">
        <div class="jumbotron">
            <h1 class="display-4">Xin chào, <?= $_SESSION['admin_name'] ?? 'Admin' ?>!</h1>
            <p class="lead">Bạn đã đăng nhập thành công vào hệ thống quản trị.</p>
            <hr class="my-4">
            <p>Đây là nơi chúng ta sẽ xây dựng các chức năng quản lý sản phẩm, đơn hàng...</p>
            <a class="btn btn-primary btn-lg" href="#" role="button">Xem danh sách sản phẩm</a>
        </div>
    </div>
</body>
</html>