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
    <?php 
    // 1. Phân loại danh mục thành dạng Cây (Cha -> Con) ngay tại View
    $catTree = [];
    foreach ($categories as $c) {
        if (empty($c['parent_id'])) {
            $catTree[$c['id']] = $c;
            $catTree[$c['id']]['children'] = [];
        }
    }
    foreach ($categories as $c) {
        if (!empty($c['parent_id']) && isset($catTree[$c['parent_id']])) {
            $catTree[$c['parent_id']]['children'][] = $c;
        }
    }
    ?>

    <?php foreach ($catTree as $parent): ?>
        <tr class="bg-light">
            <td class="align-middle fw-bold"><?= $parent['id'] ?></td>
            <td class="align-middle">
                <?php $img = !empty($parent['image_url']) ? "/MY_WEB/public/" . $parent['image_url'] : "https://placehold.co/50"; ?>
                <img src="<?= $img ?>" width="50" height="50" class="rounded object-fit-cover border">
            </td>
            <td class="align-middle">
                <span class="fw-bold text-success"><?= $parent['name'] ?></span>
                <span class="badge bg-primary ms-2 small">Danh mục gốc</span>
            </td>
            <td class="align-middle text-muted"><?= $parent['slug'] ?></td>
            <td class="align-middle">
                <a href="/MY_WEB/public/admin/category/edit/<?= $parent['id'] ?>" class="btn btn-sm btn-warning rounded-circle"><i class="fas fa-edit text-white"></i></a>
                <a href="/MY_WEB/public/admin/category/delete/<?= $parent['id'] ?>" class="btn btn-sm btn-danger rounded-circle" onclick="return confirm('Xóa danh mục này sẽ xóa luôn các danh mục con. Tiếp tục?')"><i class="fas fa-trash"></i></a>
            </td>
        </tr>

        <?php if (!empty($parent['children'])): ?>
            <?php foreach ($parent['children'] as $child): ?>
            <tr>
                <td class="align-middle text-muted"><?= $child['id'] ?></td>
                <td class="align-middle">
                    <?php $cImg = !empty($child['image_url']) ? "/MY_WEB/public/" . $child['image_url'] : "https://placehold.co/50"; ?>
                    <img src="<?= $cImg ?>" width="50" height="50" class="rounded object-fit-cover opacity-75">
                </td>
                <td class="align-middle ps-4"> <i class="fas fa-level-up-alt fa-rotate-90 text-muted me-2"></i> <span class="text-dark"><?= $child['name'] ?></span>
                    <span class="badge bg-secondary ms-2" style="font-size: 0.7em;">Danh mục con</span>
                </td>
                <td class="align-middle text-muted small"><?= $child['slug'] ?></td>
                <td class="align-middle">
                    <a href="/MY_WEB/public/admin/category/edit/<?= $child['id'] ?>" class="btn btn-sm btn-warning rounded-circle"><i class="fas fa-edit text-white"></i></a>
                    <a href="/MY_WEB/public/admin/category/delete/<?= $child['id'] ?>" class="btn btn-sm btn-danger rounded-circle" onclick="return confirm('Xóa danh mục con này?')"><i class="fas fa-trash"></i></a>
                </td>
            </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    <?php endforeach; ?>
</tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once '../app/Views/layouts/admin/footer.php'; ?>