<?php require_once '../app/Views/layouts/admin/sidebar.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="text-dark font-weight-bold">Quản lý Danh mục</h3>
    <a href="/MY_WEB/public/admin/category/create" class="btn btn-success shadow-sm rounded-pill px-4">
        <i class="fas fa-plus mr-1"></i> Thêm mới
    </a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-scroll-wrapper">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th class="text-center" width="50">ID</th>
                        <th class="text-center" width="80">Hình ảnh</th>
                        <th>Tên danh mục</th>
                        <th>Slug</th>
                        <th class="text-center" width="150">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($categories as $cate): ?>
                    <tr>
                        <td class="text-center align-middle font-weight-bold text-muted"><?= $cate['id'] ?></td>
                        <td class="text-center align-middle">
                            <?php if (!empty($cate['image_url'])): ?>
                                <img src="/MY_WEB/public/<?= $cate['image_url'] ?>" style="width: 40px; height: 40px; object-fit: cover; border-radius: 6px;">
                            <?php else: ?>
                                <span class="badge badge-light border">No Img</span>
                            <?php endif; ?>
                        </td>
                        <td class="align-middle font-weight-bold"><?= $cate['name'] ?></td>
                        <td class="align-middle text-muted"><?= $cate['slug'] ?></td>
                        <td class="text-center align-middle">
                            <a href="/MY_WEB/public/admin/category/edit/<?= $cate['id'] ?>" class="btn btn-warning btn-sm action-btn text-white shadow-sm">
                                <i class="fas fa-pen"></i>
                            </a>
                            <a href="/MY_WEB/public/admin/category/delete/<?= $cate['id'] ?>" 
                               onclick="return confirm('Xóa danh mục này?')" 
                               class="btn btn-danger btn-sm action-btn shadow-sm ml-1">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once '../app/Views/layouts/admin/footer.php'; ?>