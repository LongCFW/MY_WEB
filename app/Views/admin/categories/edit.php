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
                <form action="/MY_WEB/public/admin/category/update/<?= $category['id'] ?>" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label>Tên danh mục <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" value="<?= $category['name'] ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Slug <span class="text-danger">*</span></label>
                        <input type="text" name="slug" class="form-control" value="<?= $category['slug'] ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Hình ảnh</label>
                        <input type="file" name="image" class="form-control-file border p-1 mb-2">
                        <?php if (!empty($category['image_url'])): ?>
                            <div class="mt-2">
                                <p class="small text-muted mb-1">Ảnh hiện tại:</p>
                                <img src="/MY_WEB/public/<?= $category['image_url'] ?>" alt="Category Image" style="height: 100px; border-radius: 5px; border: 1px solid #ddd;">
                            </div>
                        <?php endif; ?>
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