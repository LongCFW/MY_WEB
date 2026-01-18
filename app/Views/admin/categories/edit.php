<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sửa Danh mục</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="card">
            <div class="card-header">
                <h3>Cập nhật danh mục: <?= $category['name'] ?></h3>
            </div>
            <div class="card-body">
                <form action="/MY_WEB/public/admin/category/update/<?= $category['id'] ?>" method="POST">
                    
                    <div class="form-group">
                        <label>Tên danh mục</label>
                        <input type="text" name="name" class="form-control" 
                               value="<?= $category['name'] ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Slug (Đường dẫn tĩnh)</label>
                        <input type="text" name="slug" class="form-control" 
                               value="<?= $category['slug'] ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Mô tả</label>
                        <textarea name="description" class="form-control"><?= $category['description'] ?></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                    <a href="/MY_WEB/public/admin/category" class="btn btn-secondary">Hủy</a>
                </form>
            </div>
        </div>
    </div>
</body>
</html>