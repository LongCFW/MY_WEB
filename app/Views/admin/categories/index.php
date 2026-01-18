<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý Danh mục</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
</head>
<body>
    <?php require_once '../app/Views/layouts/admin_sidebar.php'; ?>

    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Danh sách Danh mục</h2>
            <a href="/MY_WEB/public/admin/category/create" class="btn btn-success">Thêm mới</a>
        </div>

        <table class="table table-bordered table-striped bg-white shadow-sm">
            <thead>
                <tr>
                    <th style="width: 50px;">ID</th>
                    <th>Tên danh mục</th>
                    <th>Slug</th>
                    <th style="width: 150px;">Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categories as $cate): ?>
                <tr>
                    <td><?= $cate['id'] ?></td>
                    <td><strong><?= $cate['name'] ?></strong></td>
                    <td><?= $cate['slug'] ?></td>
                    <td>
                        <a href="/MY_WEB/public/admin/category/edit/<?= $cate['id'] ?>" class="btn btn-warning btn-sm">Sửa</a>
                        <a href="/MY_WEB/public/admin/category/delete/<?= $cate['id'] ?>" 
                           onclick="return confirm('Bạn chắc chắn muốn xóa danh mục này?')" 
                           class="btn btn-danger btn-sm">Xóa</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <?php require_once '../app/Views/layouts/admin_footer.php'; ?>
</body>
</html>