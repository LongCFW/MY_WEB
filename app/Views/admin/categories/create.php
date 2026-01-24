<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm Danh mục</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
</head>
<body>
    <?php require_once '../app/Views/layouts/admin_sidebar.php'; ?>

    <div class="container-fluid">
        <div class="card shadow-sm">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">Thêm danh mục mới</h5>
            </div>
            <div class="card-body">
                <form action="/MY_WEB/public/admin/category/store" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label>Tên danh mục <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" required placeholder="Ví dụ: Điện thoại...">
                    </div>
                    <div class="form-group">
                        <label>Slug <span class="text-danger">*</span></label>
                        <input type="text" name="slug" class="form-control" required placeholder="dien-thoai">
                    </div>
                    
                    <div class="form-group">
                        <label>Hình ảnh danh mục</label>
                        <input type="file" name="image" class="form-control-file border p-1">
                    </div>

                    <div class="form-group">
                        <label>Mô tả</label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>
                    <hr>
                    <button type="submit" class="btn btn-success">Lưu lại</button>
                    <a href="/MY_WEB/public/admin/category" class="btn btn-secondary">Hủy bỏ</a>
                </form>
            </div>
        </div>
    </div>

    <?php require_once '../app/Views/layouts/admin_footer.php'; ?>
</body>
</html>