<?php require_once '../app/Views/layouts/admin/sidebar.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="text-dark font-weight-bold">Quản lý Mã giảm giá</h3>
    <a href="/MY_WEB/public/admin/coupon/create" class="btn btn-success shadow-sm">
        <i class="fas fa-plus-circle mr-1"></i> Thêm mã mới
    </a>
</div>

<div class="card shadow-sm border-0 mb-4 bg-white">
    <div class="card-body p-3">
        <form method="GET" action="/MY_WEB/public/admin/coupon" class="row align-items-center">
            
            <div class="col-md-5 mb-2 mb-md-0">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text bg-light border-right-0"><i class="fas fa-search text-muted"></i></span>
                    </div>
                    <input type="text" name="search" class="form-control border-left-0" placeholder="Nhập mã giảm giá..." value="<?= $_GET['search'] ?? '' ?>" style="text-transform: uppercase;">
                </div>
            </div>
            
            <div class="col-md-3 mb-2 mb-md-0">
                <select name="type" class="form-control custom-select" onchange="this.form.submit()">
                    <option value="">-- Loại giảm giá --</option>
                    <option value="percent" <?= (($_GET['type'] ?? '') == 'percent') ? 'selected' : '' ?>>Giảm theo phần trăm (%)</option>
                    <option value="fixed" <?= (($_GET['type'] ?? '') == 'fixed') ? 'selected' : '' ?>>Giảm tiền mặt (VNĐ)</option>
                </select>
            </div>

            <div class="col-md-2 mb-2 mb-md-0">
                <select name="status" class="form-control custom-select" onchange="this.form.submit()">
                    <option value="">-- Trạng thái --</option>
                    <option value="active" <?= (($_GET['status'] ?? '') == 'active') ? 'selected' : '' ?>>Đang chạy</option>
                    <option value="upcoming" <?= (($_GET['status'] ?? '') == 'upcoming') ? 'selected' : '' ?>>Sắp diễn ra</option>
                    <option value="expired" <?= (($_GET['status'] ?? '') == 'expired') ? 'selected' : '' ?>>Đã hết hạn</option>
                </select>
            </div>

            <div class="col-md-2 d-flex">
                <button type="submit" class="btn btn-primary flex-grow-1 mr-2 font-weight-bold">Lọc</button>
                <a href="/MY_WEB/public/admin/coupon" class="btn btn-outline-secondary" title="Xóa bộ lọc"><i class="fas fa-sync-alt"></i></a>
            </div>
        </form>
    </div>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="thead-light">
                    <tr>
                        <th>Mã (Code)</th>
                        <th>Loại - Giá trị</th>
                        <th>Điều kiện</th>
                        <th>Đã dùng / Giới hạn</th>
                        <th>Thời gian áp dụng</th>
                        <th class="text-center">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($coupons)): ?>
                        <?php foreach($coupons as $coupon): ?>
                            <?php 
                                // Logic kiểm tra trạng thái hiển thị
                                $now = time();
                                $start = strtotime($coupon['starts_at']);
                                $end = strtotime($coupon['ends_at']);
                                $statusClass = 'success';
                                $statusText = 'Đang chạy';
                                
                                if ($now < $start) {
                                    $statusClass = 'warning'; $statusText = 'Sắp tới';
                                } elseif ($now > $end) {
                                    $statusClass = 'danger'; $statusText = 'Hết hạn';
                                }
                            ?>
                            <tr>
                                <td class="align-middle font-weight-bold text-primary">
                                    <span class="d-block mb-1 fs-5"><?= $coupon['code'] ?></span>
                                    <span class="badge badge-<?= $statusClass ?>"><?= $statusText ?></span>
                                </td>
                                <td class="align-middle">
                                    <?php if($coupon['type'] == 'percent'): ?>
                                        <span class="text-success font-weight-bold">Giảm <?= $coupon['value'] ?>%</span>
                                    <?php else: ?>
                                        <span class="text-success font-weight-bold">Giảm <?= number_format($coupon['value']) ?>đ</span>
                                    <?php endif; ?>
                                </td>
                                <td class="align-middle text-muted small">
                                    Đơn tối thiểu: <br><strong class="text-dark"><?= number_format($coupon['min_order_cents']) ?>đ</strong>
                                </td>
                                <td class="align-middle">
                                    <?php
                                        $limit = $coupon['usage_limit'] ?: 'Vô hạn';
                                        $used = $coupon['used_count'];
                                        $color = ($limit !== 'Vô hạn' && $used >= $limit) ? 'text-danger font-weight-bold' : 'text-dark';
                                    ?>
                                    <span class="<?= $color ?>"><?= $used ?></span> / <?= $limit ?>
                                </td>
                                <td class="align-middle small">
                                    <div class="mb-1"><i class="fas fa-play-circle text-success mr-1"></i><?= date('d/m/Y H:i', strtotime($coupon['starts_at'])) ?></div>
                                    <div><i class="fas fa-stop-circle text-danger mr-1"></i><?= date('d/m/Y H:i', strtotime($coupon['ends_at'])) ?></div>
                                </td>
                                <td class="align-middle text-center">
                                    <a href="/MY_WEB/public/admin/coupon/edit/<?= $coupon['id'] ?>" class="btn btn-sm btn-outline-info rounded-circle mr-1" title="Sửa">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="/MY_WEB/public/admin/coupon/delete/<?= $coupon['id'] ?>" class="btn btn-sm btn-outline-danger rounded-circle" onclick="return confirm('Bạn có chắc chắn muốn xóa mã này?');" title="Xóa">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="fas fa-ticket-alt fa-3x mb-3 text-light"></i><br>
                                Không tìm thấy mã giảm giá nào phù hợp.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once '../app/Views/layouts/admin/footer.php'; ?>