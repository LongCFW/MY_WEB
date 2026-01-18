<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sửa Danh mục</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
</head>
<body>
    <?php require_once '../app/Views/layouts/admin_sidebar.php'; ?>

    <div class="container-fluid">
        <div class="card shadow-sm">
            <div class="card-header bg-warning">
                <h5 class="mb-0">Cập nhật danh mục: <?= $category['name'] ?></h5>
            </div>
            <div class="card-body">
                <form action="/MY_WEB/public/admin/category/update/<?= $category['id'] ?>" method="POST">
                    <div class="form-group">
                        <label>Tên danh mục <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" value="<?= $category['name'] ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Slug (Đường dẫn tĩnh) <span class="text-danger">*</span></label>
                        <input type="text" name="slug" class="form-control" value="<?= $category['slug'] ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Mô tả</label>
                        <textarea name="description" class="form-control" rows="3"><?= $category['description'] ?></textarea>
                    </div>

                    <hr>
                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                    <a href="/MY_WEB/public/admin/category" class="btn btn-secondary">Hủy bỏ</a>
                </form>
            </div>
        </div>
    </div>

    <?php require_once '../app/Views/layouts/admin_footer.php'; ?>
</body>
</html>