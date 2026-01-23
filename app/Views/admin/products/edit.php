<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sửa Sản phẩm</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
</head>
<body>
    <?php require_once '../app/Views/layouts/admin_sidebar.php'; ?>

    <div class="container-fluid">
        <div class="card shadow-sm">
            <div class="card-header bg-warning">
                <h5 class="mb-0">Cập nhật sản phẩm: <?= $product['name'] ?></h5>
            </div>
            <div class="card-body">
                <form action="/MY_WEB/public/admin/product/update/<?= $product['id'] ?>" method="POST" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label>Tên sản phẩm <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" value="<?= $product['name'] ?>" required>
                            </div>

                            <div class="form-group">
                                <label>Thương hiệu (Brand)</label>
                                <input type="text" name="brand" class="form-control" placeholder="VD: Vinamilk, TH True Milk...">
                            </div>
                            
                            <div class="form-group">
                                <label>Mô tả chi tiết</label>
                                <textarea name="description" class="form-control" rows="5"><?= $product['description'] ?></textarea>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Danh mục <span class="text-danger">*</span></label>
                                <select name="category_id" class="form-control">
                                    <?php foreach ($categories as $cate): ?>
                                        <option value="<?= $cate['id'] ?>" <?= ($cate['id'] == $product['category_id']) ? 'selected' : '' ?>>
                                            <?= $cate['name'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Giá bán (VNĐ) <span class="text-danger">*</span></label>
                                <input type="number" name="price" class="form-control" value="<?= $product['price_cents'] ?>" required min="0">
                            </div>

                            <div class="form-group">
                                <label>Số lượng kho <span class="text-danger">*</span></label>
                                <input type="number" name="stock" class="form-control" value="<?= $product['stock'] ?>" required min="0">
                            </div>

                            <div class="form-group">
                                <label>Hình ảnh</label>
                                <?php if (!empty($product['image_url'])): ?>
                                    <div class="mb-2">
                                        <img src="/MY_WEB/public/<?= $product['image_url'] ?>" width="100" class="img-thumbnail">
                                    </div>
                                <?php endif; ?>
                                <input type="file" name="image" class="form-control-file border p-1">
                                <small class="text-muted">Bỏ qua nếu không muốn thay đổi ảnh.</small>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <button type="submit" class="btn btn-primary px-4">Cập nhật sản phẩm</button>
                    <a href="/MY_WEB/public/admin/product" class="btn btn-secondary">Hủy bỏ</a>
                </form>
            </div>
        </div>
    </div>

    <?php require_once '../app/Views/layouts/admin_footer.php'; ?>
</body>
</html>