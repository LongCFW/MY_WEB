<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý Sản phẩm</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
</head>
<body>
    <?php require_once '../app/Views/layouts/admin_sidebar.php'; ?>

    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Danh sách Sản phẩm</h2>
            <a href="/MY_WEB/public/admin/product/create" class="btn btn-success">Thêm Sản phẩm</a>
        </div>

        <table class="table table-bordered table-hover bg-white shadow-sm">
            <thead class="thead-light">
                <tr>
                    <th>ID</th>
                    <th>Hình ảnh</th>
                    <th>Tên sản phẩm</th>
                    <th>Danh mục</th>
                    <th>Giá</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $p): ?>
                <tr>
                    <td><?= $p['id'] ?></td>
                    <td>
                        <?php if (!empty($p['image_url'])): ?>
                            <img src="/MY_WEB/public/<?= $p['image_url'] ?>" width="50" height="50" style="object-fit: cover; border-radius: 4px; border: 1px solid #ddd;">
                        <?php else: ?>
                            <span class="text-muted small">No image</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <strong><?= $p['name'] ?></strong><br>
                        <small class="text-muted">SKU: <?= $p['sku'] ?></small>
                    </td>
                    <td>
                        <span class="badge badge-info"><?= $p['category_name'] ?? 'N/A' ?></span>
                    </td>
                    <td>
                        <span class="text-success font-weight-bold">
                            <?= number_format($p['price_cents'] ?? 0) ?> đ
                        </span>
                    </td>
                    <td>
                        <a href="/MY_WEB/public/admin/product/edit/<?= $p['id'] ?>" class="btn btn-warning btn-sm">Sửa</a>
                        <a href="/MY_WEB/public/admin/product/delete/<?= $p['id'] ?>" 
                           onclick="return confirm('Xóa sản phẩm này?')" 
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