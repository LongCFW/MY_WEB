<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sửa Sản phẩm</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="card">
            <div class="card-header">
                <h3>Cập nhật sản phẩm: <?= $product['name'] ?></h3>
            </div>
            <div class="card-body">
                <form action="/MY_WEB/public/admin/product/update/<?= $product['id'] ?>" method="POST" enctype="multipart/form-data">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tên sản phẩm</label>
                                <input type="text" name="name" class="form-control" 
                                       value="<?= $product['name'] ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Danh mục</label>
                                <select name="category_id" class="form-control">
                                    <?php foreach ($categories as $cate): ?>
                                        <option value="<?= $cate['id'] ?>" 
                                            <?= ($cate['id'] == $product['category_id']) ? 'selected' : '' ?>>
                                            <?= $cate['name'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Giá bán (VNĐ)</label>
                                <input type="number" name="price" class="form-control" 
                                       value="<?= $product['price_cents'] ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Số lượng kho</label>
                                <input type="number" name="stock" class="form-control" 
                                       value="<?= $product['stock'] ?>" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Hình ảnh hiện tại</label><br>
                        <?php if (!empty($product['image_url'])): ?>
                            <img src="/MY_WEB/public/<?= $product['image_url'] ?>" width="100" class="mb-2">
                        <?php endif; ?>
                        
                        <input type="file" name="image" class="form-control-file">
                        <small class="text-muted">Chỉ chọn ảnh nếu muốn thay đổi ảnh mới.</small>
                    </div>

                    <div class="form-group">
                        <label>Mô tả chi tiết</label>
                        <textarea name="description" class="form-control" rows="5"><?= $product['description'] ?></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">Cập nhật sản phẩm</button>
                    <a href="/MY_WEB/public/admin/product" class="btn btn-secondary">Hủy</a>
                </form>
            </div>
        </div>
    </div>
</body>
</html>