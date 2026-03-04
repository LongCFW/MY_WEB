<?php require_once '../app/Views/layouts/admin/sidebar.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="text-dark font-weight-bold">Quản lý Danh mục</h3>
    <a href="/MY_WEB/public/admin/category/create" class="btn btn-success shadow-sm rounded-pill px-4">
        <i class="fas fa-plus mr-1"></i> Thêm mới
    </a>
</div>

<div class="card shadow-sm border-0 mb-4 bg-white">
    <div class="card-body p-3">
        <form method="GET" action="/MY_WEB/public/admin/category" class="row align-items-center">
            <div class="col-md-6 mb-2 mb-md-0">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text bg-light border-right-0"><i class="fas fa-search text-muted"></i></span>
                    </div>
                    <input type="text" name="search" class="form-control border-left-0" placeholder="Tìm tên hoặc slug danh mục..." value="<?= $_GET['search'] ?? '' ?>">
                </div>
            </div>
            <div class="col-md-2 d-flex">
                <button type="submit" class="btn btn-primary flex-grow-1 mr-2 font-weight-bold">Tìm kiếm</button>
                <a href="/MY_WEB/public/admin/category" class="btn btn-outline-secondary" title="Xóa bộ lọc"><i class="fas fa-sync-alt"></i></a>
            </div>
        </form>
    </div>
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
                    <?php if(empty($categories)): ?>
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">Không tìm thấy danh mục nào.</td>
                        </tr>
                    <?php else: ?>
                        
                        <?php 
                        // KIỂM TRA: Nếu đang tìm kiếm thì in ra danh sách phẳng
                        if (!empty($_GET['search'])): 
                            foreach ($categories as $cat): 
                        ?>
                            <tr>
                                <td class="align-middle text-center fw-bold text-muted"><?= $cat['id'] ?></td>
                                <td class="align-middle text-center">
                                    <?php $img = !empty($cat['image_url']) ? "/MY_WEB/public/" . $cat['image_url'] : "https://placehold.co/50"; ?>
                                    <img src="<?= $img ?>" width="50" height="50" class="rounded object-fit-cover border">
                                </td>
                                <td class="align-middle">
                                    <span class="fw-bold text-dark"><?= $cat['name'] ?></span>
                                    <?php if(empty($cat['parent_id'])): ?>
                                        <span class="badge bg-primary ms-2 small">Danh mục gốc</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary ms-2 small">Danh mục con</span>
                                    <?php endif; ?>
                                </td>
                                <td class="align-middle text-muted"><?= $cat['slug'] ?></td>
                                <td class="align-middle text-center">
                                    <a href="/MY_WEB/public/admin/category/edit/<?= $cat['id'] ?>" class="btn btn-sm btn-warning rounded-circle"><i class="fas fa-edit text-white"></i></a>
                                    <a href="/MY_WEB/public/admin/category/delete/<?= $cat['id'] ?>" class="btn btn-sm btn-danger rounded-circle" onclick="return confirm('Bạn có chắc chắn muốn xóa?')"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                        <?php 
                            endforeach;
                        
                        // KIỂM TRA: Nếu không tìm kiếm thì in ra dạng CÂY (Cha - Con) như cũ
                        else: 
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
                                    <td class="align-middle text-center fw-bold"><?= $parent['id'] ?></td>
                                    <td class="align-middle text-center">
                                        <?php $img = !empty($parent['image_url']) ? "/MY_WEB/public/" . $parent['image_url'] : "https://placehold.co/50"; ?>
                                        <img src="<?= $img ?>" width="50" height="50" class="rounded object-fit-cover border">
                                    </td>
                                    <td class="align-middle">
                                        <span class="fw-bold text-success"><?= $parent['name'] ?></span>
                                        <span class="badge bg-primary ms-2 small">Danh mục gốc</span>
                                    </td>
                                    <td class="align-middle text-muted"><?= $parent['slug'] ?></td>
                                    <td class="align-middle text-center">
                                        <a href="/MY_WEB/public/admin/category/edit/<?= $parent['id'] ?>" class="btn btn-sm btn-warning rounded-circle"><i class="fas fa-edit text-white"></i></a>
                                        <a href="/MY_WEB/public/admin/category/delete/<?= $parent['id'] ?>" class="btn btn-sm btn-danger rounded-circle" onclick="return confirm('Xóa danh mục này sẽ xóa luôn các danh mục con. Tiếp tục?')"><i class="fas fa-trash"></i></a>
                                    </td>
                                </tr>

                                <?php if (!empty($parent['children'])): ?>
                                    <?php foreach ($parent['children'] as $child): ?>
                                    <tr>
                                        <td class="align-middle text-center text-muted"><?= $child['id'] ?></td>
                                        <td class="align-middle text-center">
                                            <?php $cImg = !empty($child['image_url']) ? "/MY_WEB/public/" . $child['image_url'] : "https://placehold.co/50"; ?>
                                            <img src="<?= $cImg ?>" width="50" height="50" class="rounded object-fit-cover opacity-75">
                                        </td>
                                        <td class="align-middle ps-4"> 
                                            <i class="fas fa-level-up-alt fa-rotate-90 text-muted me-2"></i> <span class="text-dark"><?= $child['name'] ?></span>
                                            <span class="badge bg-secondary ms-2" style="font-size: 0.7em;">Danh mục con</span>
                                        </td>
                                        <td class="align-middle text-muted small"><?= $child['slug'] ?></td>
                                        <td class="align-middle text-center">
                                            <a href="/MY_WEB/public/admin/category/edit/<?= $child['id'] ?>" class="btn btn-sm btn-warning rounded-circle"><i class="fas fa-edit text-white"></i></a>
                                            <a href="/MY_WEB/public/admin/category/delete/<?= $child['id'] ?>" class="btn btn-sm btn-danger rounded-circle" onclick="return confirm('Xóa danh mục con này?')"><i class="fas fa-trash"></i></a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>

                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once '../app/Views/layouts/admin/footer.php'; ?>