<?php require_once '../app/Views/layouts/admin/sidebar.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="text-dark font-weight-bold">Quản lý Mã giảm giá</h3>
    <a href="/MY_WEB/public/admin/coupon/create" class="btn btn-success shadow-sm">
        <i class="fas fa-plus-circle mr-1"></i> Thêm mã mới
    </a>
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
                                // Logic kiểm tra trạng thái (Còn hạn / Hết hạn)
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
                                    <?= $coupon['code'] ?> <br>
                                    <span class="badge badge-<?= $statusClass ?>"><?= $statusText ?></span>
                                </td>
                                <td class="align-middle">
                                    <?php if($coupon['type'] == 'percent'): ?>
                                        Giảm <?= $coupon['value'] ?>%
                                    <?php else: ?>
                                        Giảm <?= number_format($coupon['value']) ?>đ
                                    <?php endif; ?>
                                </td>
                                <td class="align-middle text-muted small">
                                    Đơn tối thiểu: <br><strong class="text-dark"><?= number_format($coupon['min_order_cents']) ?>đ</strong>
                                </td>
                                <td class="align-middle">
                                    <?= $coupon['used_count'] ?> / <?= $coupon['usage_limit'] ?: 'Vô hạn' ?>
                                </td>
                                <td class="align-middle small">
                                    Từ: <?= date('d/m/Y H:i', strtotime($coupon['starts_at'])) ?> <br>
                                    Đến: <?= date('d/m/Y H:i', strtotime($coupon['ends_at'])) ?>
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
                        <tr><td colspan="6" class="text-center py-4">Chưa có mã giảm giá nào.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once '../app/Views/layouts/admin/footer.php'; ?>