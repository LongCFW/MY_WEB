<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý Danh mục</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
</head>
<body>
    <nav class="navbar navbar-dark bg-dark mb-4">
        <span class="navbar-brand">Admin Panel</span>
        <div>
            <a href="/MY_WEB/public/admin/dashboard" class="btn btn-secondary btn-sm">Dashboard</a>
            <a href="/MY_WEB/public/admin/auth/logout" class="btn btn-danger btn-sm">Logout</a>
        </div>
    </nav>

    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Danh sách Danh mục</h2>
            <a href="/MY_WEB/public/admin/category/create" class="btn btn-success">Thêm mới</a>
        </div>

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tên danh mục</th>
                    <th>Slug</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categories as $cate): ?>
                <tr>
                    <td><?= $cate['id'] ?></td>
                    <td><?= $cate['name'] ?></td>
                    <td><?= $cate['slug'] ?></td>
                    <td>
                        <a href="/MY_WEB/public/admin/category/edit/<?= $cate['id'] ?>" class="btn btn-warning btn-sm">Sửa</a>
                        <a href="/MY_WEB/public/admin/category/delete/<?= $cate['id'] ?>" 
                           onclick="return confirm('Bạn chắc chắn muốn xóa?')" 
                           class="btn btn-danger btn-sm">Xóa</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>