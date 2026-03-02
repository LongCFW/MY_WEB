<?php require_once '../app/Views/layouts/admin/sidebar.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="text-dark font-weight-bold">Quản lý Sản phẩm</h3>
    <a href="/MY_WEB/public/admin/product/create" class="btn btn-success shadow-sm rounded-pill px-4">
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
                        <th style="min-width: 200px;">Tên sản phẩm</th>
                        <th>Thương hiệu</th>
                        <th>Danh mục</th>
                        <th class="text-right">Giá bán</th>
                        <th class="text-center">Kho</th>
                        <th class="text-center" width="120">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($products)): foreach ($products as $p): ?>
                    <tr>
                        <td class="text-center align-middle font-weight-bold text-muted"><?= $p['id'] ?></td>
                        <td class="text-center align-middle">
                            <?php $imgUrl = !empty($p['image_url']) ? "/MY_WEB/public/" . $p['image_url'] : "https://placehold.co/50"; ?>
                            <img src="<?= $imgUrl ?>" style="width: 45px; height: 45px; object-fit: cover; border-radius: 4px; border: 1px solid #eee;">
                        </td>
                        
                        <td class="align-middle">
                            <div class="font-weight-bold text-dark mb-1"><?= htmlspecialchars($p['name']) ?></div>
                            <small class="text-muted"><i class="fas fa-barcode mr-1"></i><?= $p['sku'] ?? 'N/A' ?></small>
                            
                            <?php if(isset($p['variant_count']) && $p['variant_count'] > 1): ?>
                                <div class="mt-1">
                                    <span class="badge badge-info"><i class="fas fa-layer-group mr-1"></i> <?= $p['variant_count'] ?> phân loại</span>
                                    <small class="text-muted font-italic ml-1">(<?= htmlspecialchars($p['variant_names'] ?? '') ?>)</small>
                                </div>
                            <?php else: ?>
                                <div class="mt-1">
                                    <span class="badge badge-secondary"><i class="fas fa-box mr-1"></i> Tiêu chuẩn</span>
                                </div>
                            <?php endif; ?>
                        </td>

                        <td class="align-middle">
                            <?php if(!empty($p['brand'])): ?>
                                <span class="badge badge-info font-weight-normal px-2"><?= htmlspecialchars($p['brand']) ?></span>
                            <?php else: ?> - <?php endif; ?>
                        </td>

                        <td class="align-middle">
                            <span class="badge badge-light border text-dark"><?= htmlspecialchars($p['category_name'] ?? 'N/A') ?></span>
                        </td>

                        <td class="align-middle text-right text-success font-weight-bold">
                            <?php if(isset($p['min_price']) && isset($p['max_price']) && $p['min_price'] != $p['max_price']): ?>
                                <?= number_format($p['min_price']) ?> đ - <?= number_format($p['max_price']) ?> đ
                            <?php else: ?>
                                <?= number_format($p['min_price'] ?? ($p['price_cents'] ?? 0)) ?> đ
                            <?php endif; ?>
                        </td>

                        <td class="align-middle text-center">
                            <?php $stock = $p['total_stock'] ?? 0; ?>
                            <?php if ($stock > 10): ?>
                                <span class="badge badge-success px-2"><?= $stock ?></span>
                            <?php elseif ($stock > 0): ?>
                                <span class="badge badge-warning px-2 text-white"><?= $stock ?></span>
                            <?php else: ?>
                                <span class="badge badge-danger px-2">Hết hàng</span>
                            <?php endif; ?>
                        </td>

                        <td class="text-center align-middle">
                            <a href="/MY_WEB/public/admin/product/edit/<?= $p['id'] ?>" class="btn btn-primary btn-sm action-btn shadow-sm" title="Sửa">
                                <i class="fas fa-pen fa-xs"></i>
                            </a>
                            <a href="/MY_WEB/public/admin/product/delete/<?= $p['id'] ?>" class="btn btn-danger btn-sm action-btn shadow-sm ml-1 btn-delete" title="Xóa" onclick="return confirm('Bạn có chắc chắn muốn xóa?')"> 
                                <i class="fas fa-trash fa-xs"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; else: ?>
                    <tr><td colspan="8" class="text-center py-5 text-muted">Chưa có sản phẩm nào.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-white py-3">
        <small class="text-muted">Tổng số: <strong><?= count($products) ?></strong> sản phẩm</small>
    </div>
</div>

<?php require_once '../app/Views/layouts/admin/footer.php'; ?>