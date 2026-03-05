<?php require_once '../app/Views/layouts/admin/sidebar.php'; ?>

<style>
    /* Thêm CSS cho tính năng cuộn mượt mà */
    .table-scroll-wrapper {
        max-height: 500px;
        overflow-y: auto;
    }

    /* Đóng băng dòng tiêu đề khi cuộn xuống */
    .table-scroll-wrapper thead th {
        position: sticky;
        top: 0;
        background-color: #f8f9fa;
        z-index: 1;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
    }
</style>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="text-dark font-weight-bold">Quản lý Đánh giá</h3>
    <button type="button" class="btn btn-warning font-weight-bold shadow-sm" data-toggle="modal" data-target="#seedingModal">
        <i class="fas fa-magic mr-1"></i> Thêm Đánh Giá Mồi (Seeding)
    </button>
</div>

<div class="card shadow-sm border-0 mb-4 bg-white">
    <div class="card-body p-3">
        <form method="GET" action="/MY_WEB/public/admin/review" class="row align-items-center">
            <div class="col-md-4 mb-2 mb-md-0">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text bg-light border-right-0"><i class="fas fa-search text-muted"></i></span>
                    </div>
                    <input type="text" name="search" class="form-control border-left-0" placeholder="Tên khách hàng, Nội dung..." value="<?= $_GET['search'] ?? '' ?>">
                </div>
            </div>

            <div class="col-md-3 mb-2 mb-md-0">
                <select name="type" class="form-control custom-select" onchange="this.form.submit()">
                    <option value="">-- Loại đánh giá --</option>
                    <option value="real" <?= (($_GET['type'] ?? '') == 'real') ? 'selected' : '' ?>>Khách hàng thật</option>
                    <option value="seeding" <?= (($_GET['type'] ?? '') == 'seeding') ? 'selected' : '' ?>>Seeding (Tài khoản ảo)</option>
                </select>
            </div>

            <div class="col-md-3 mb-2 mb-md-0">
                <select name="rating" class="form-control custom-select text-warning font-weight-bold" onchange="this.form.submit()">
                    <option value="" class="text-dark">-- Số sao --</option>
                    <option value="5" <?= (($_GET['rating'] ?? '') == '5') ? 'selected' : '' ?>>⭐⭐⭐⭐⭐ (5 Sao)</option>
                    <option value="4" <?= (($_GET['rating'] ?? '') == '4') ? 'selected' : '' ?>>⭐⭐⭐⭐ (4 Sao)</option>
                    <option value="3" <?= (($_GET['rating'] ?? '') == '3') ? 'selected' : '' ?>>⭐⭐⭐ (3 Sao)</option>
                    <option value="2" <?= (($_GET['rating'] ?? '') == '2') ? 'selected' : '' ?>>⭐⭐ (2 Sao)</option>
                    <option value="1" <?= (($_GET['rating'] ?? '') == '1') ? 'selected' : '' ?>>⭐ (1 Sao)</option>
                </select>
            </div>

            <div class="col-md-2 d-flex">
                <button type="submit" class="btn btn-primary flex-grow-1 mr-2 font-weight-bold">Lọc</button>
                <a href="/MY_WEB/public/admin/review" class="btn btn-outline-secondary" title="Xóa bộ lọc"><i class="fas fa-sync-alt"></i></a>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-scroll-wrapper">
            <table class="table table-hover mb-0">
                <thead class="thead-light">
                    <tr>
                        <th class="text-center" width="50">ID</th>
                        <th>Sản phẩm</th>
                        <th>Người đánh giá</th>
                        <th class="text-center">Số sao</th>
                        <th width="35%">Nội dung</th>
                        <th>Thời gian</th>
                        <th class="text-center">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($reviews)): ?>
                        <?php foreach ($reviews as $rv): ?>
                            <tr>
                                <td class="align-middle text-center text-muted"><?= $rv['id'] ?></td>
                                <td class="align-middle">
                                    <div class="d-flex align-items-center">
                                        <?php $img = !empty($rv['product_image']) ? '/MY_WEB/public/' . $rv['product_image'] : 'https://placehold.co/50'; ?>
                                        <img src="<?= $img ?>" class="rounded border mr-2 object-fit-cover" width="40" height="40">
                                        <div class="text-truncate" style="max-width: 200px; font-weight: 500;">
                                            <a href="/MY_WEB/public/product/detail/<?= $rv['product_id'] ?>" target="_blank" class="text-dark">
                                                <?= htmlspecialchars($rv['product_name']) ?>
                                            </a>
                                        </div>
                                    </div>
                                </td>
                                <td class="align-middle">
                                    <div class="font-weight-bold text-dark"><?= htmlspecialchars($rv['user_name']) ?></div>
                                    <?php if (empty($rv['order_id'])): ?>
                                        <span class="badge badge-warning"><i class="fas fa-robot mr-1"></i> Seeding</span>
                                    <?php else: ?>
                                        <span class="badge badge-success"><i class="fas fa-check-circle mr-1"></i> Đã mua</span>
                                    <?php endif; ?>
                                </td>
                                <td class="align-middle text-center text-warning" style="font-size: 0.9rem;">
                                    <?php for ($i = 1; $i <= 5; $i++) echo $i <= $rv['rating'] ? '<i class="fas fa-star"></i>' : '<i class="far fa-star"></i>'; ?>
                                </td>
                                <td class="align-middle">
                                    <p class="mb-0 text-secondary" style="font-size: 0.9rem; display: -webkit-box; -webkit-line-clamp: 2; line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                        <?= htmlspecialchars($rv['comment']) ?>
                                    </p>
                                </td>
                                <td class="align-middle text-muted small">
                                    <?= date('d/m/Y H:i', strtotime($rv['created_at'])) ?>
                                </td>
                                <td class="align-middle text-center">
                                    <a href="/MY_WEB/public/admin/review/delete/<?= $rv['id'] ?>" class="btn btn-sm btn-outline-danger rounded-circle" onclick="return confirm('Bạn có chắc chắn muốn xóa đánh giá này?');" title="Xóa">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">Chưa có đánh giá nào phù hợp.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-white py-3">
        <small class="text-muted">Tổng số: <strong><?= is_array($reviews) ? count($reviews) : 0 ?></strong> đánh giá</small>
    </div>
</div>

<div class="modal fade" id="seedingModal" tabindex="-1" aria-labelledby="seedingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-warning text-dark border-0">
                <h5 class="modal-title font-weight-bold" id="seedingModalLabel"><i class="fas fa-magic mr-2"></i>Tạo Đánh Giá Mồi (Seeding)</h5>
                <button type="button" class="close text-dark" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="/MY_WEB/public/admin/review/store_seeding" method="POST">
                <div class="modal-body p-4">

                    <div class="form-group mb-3">
                        <label class="font-weight-bold">Chọn Sản Phẩm Cần Seeding:</label>
                        <select name="product_id" class="form-control custom-select" required>
                            <option value="">-- Chọn sản phẩm --</option>
                            <?php if (!empty($products)): foreach ($products as $p): ?>
                                    <option value="<?= $p['id'] ?>">SKU: <?= $p['sku'] ?> - <?= htmlspecialchars($p['name']) ?></option>
                            <?php endforeach;
                            endif; ?>
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label class="font-weight-bold">Chọn Tài Khoản Ảo (Đóng vai khách):</label>
                        <select name="user_id" class="form-control custom-select" required>
                            <option value="">-- Chọn tài khoản --</option>
                            <?php if (!empty($seedingUsers)): foreach ($seedingUsers as $u): ?>
                                    <option value="<?= $u['id'] ?>"><?= htmlspecialchars($u['name']) ?></option>
                            <?php endforeach;
                            endif; ?>
                        </select>
                        <small class="form-text text-muted">Danh sách này lấy từ các tài khoản đang hoạt động.</small>
                    </div>

                    <div class="form-group mb-3">
                        <label class="font-weight-bold">Đánh giá Sao:</label>
                        <select name="rating" class="form-control custom-select text-warning font-weight-bold" required>
                            <option value="5">⭐⭐⭐⭐⭐ (5 Sao)</option>
                            <option value="4">⭐⭐⭐⭐ (4 Sao)</option>
                            <option value="3">⭐⭐⭐ (3 Sao)</option>
                            <option value="2">⭐⭐ (2 Sao)</option>
                            <option value="1">⭐ (1 Sao)</option>
                        </select>
                    </div>

                    <div class="form-group mb-0">
                        <label class="font-weight-bold">Nội dung khen ngợi:</label>
                        <textarea name="comment" class="form-control" rows="4" placeholder="VD: Sản phẩm rất tươi ngon, giao hàng siêu nhanh, sẽ ủng hộ shop dài dài..." required></textarea>
                    </div>

                </div>
                <div class="modal-footer border-0 bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-warning font-weight-bold shadow-sm">Thêm Đánh Giá</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once '../app/Views/layouts/admin/footer.php'; ?>