<?php require_once '../app/Views/layouts/admin_sidebar.php'; ?>

<div class="card shadow-sm border-0 rounded-lg">
    <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
        <h4 class="mb-0 font-weight-bold text-success"><i class="fas fa-plus-circle mr-2"></i> Thêm sản phẩm mới</h4>
    </div>
    <div class="card-body p-4">
        <form action="/MY_WEB/public/admin/product/store" method="POST" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-8">
                    <div class="form-group">
                        <label class="font-weight-bold">Tên sản phẩm <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label class="font-weight-bold">Thương hiệu</label>
                            <input type="text" name="brand" class="form-control" placeholder="VD: Vinamilk...">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Mô tả chi tiết</label>
                        <textarea name="description" class="form-control" rows="5"></textarea>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="font-weight-bold">Danh mục <span class="text-danger">*</span></label>
                        <select name="category_id" class="form-control">
                            <?php foreach ($categories as $cate): ?>
                                <option value="<?= $cate['id'] ?>"><?= $cate['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Giá bán (VNĐ) <span class="text-danger">*</span></label>
                        <input type="number" name="price" class="form-control" required min="0">
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Tồn kho <span class="text-danger">*</span></label>
                        <input type="number" name="stock" class="form-control" value="100" required min="0">
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Hình ảnh</label>
                        <input type="file" name="image" class="form-control-file border p-2 rounded w-100">
                    </div>
                </div>
            </div>
            <hr>
            <div class="d-flex justify-content-end">
                <a href="/MY_WEB/public/admin/product" class="btn btn-light mr-2 rounded-pill px-4">Hủy bỏ</a>
                <button type="submit" class="btn btn-success rounded-pill px-4 shadow-sm font-weight-bold">Lưu sản phẩm</button>
            </div>
        </form>
    </div>
</div>

<?php require_once '../app/Views/layouts/admin_footer.php'; ?>