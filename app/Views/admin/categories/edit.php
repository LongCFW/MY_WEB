<?php require_once '../app/Views/layouts/admin/sidebar.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm border-0 rounded-lg">
            <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
                <h4 class="mb-0 font-weight-bold text-warning"><i class="fas fa-edit mr-2"></i> Cập nhật danh mục</h4>
            </div>
            <div class="card-body p-4">
                <form action="/MY_WEB/public/admin/category/update/<?= $category['id'] ?>" method="POST" enctype="multipart/form-data">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label class="font-weight-bold">Tên danh mục <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" value="<?= $category['name'] ?>" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="font-weight-bold">Slug (URL)</label>
                            <input type="text" name="slug" class="form-control" value="<?= $category['slug'] ?>" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Hình ảnh</label>
                        <input type="file" name="image" class="form-control-file border p-1 rounded mb-2">
                        <?php if (!empty($category['image_url'])): ?>
                            <img src="/MY_WEB/public/<?= $category['image_url'] ?>" style="height: 80px; border-radius: 6px; border: 1px solid #ddd;">
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Mô tả</label>
                        <textarea name="description" class="form-control" rows="3"><?= $category['description'] ?></textarea>
                    </div>
                    <div class="mt-4 d-flex justify-content-end">
                        <a href="/MY_WEB/public/admin/category" class="btn btn-light mr-2 rounded-pill px-4">Quay lại</a>
                        <button type="submit" class="btn btn-warning text-white rounded-pill px-4 shadow-sm font-weight-bold">
                            <i class="fas fa-save mr-1"></i> Cập nhật
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once '../app/Views/layouts/admin/footer.php'; ?>