<?php require_once '../app/Views/layouts/admin/sidebar.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="text-dark font-weight-bold">Quản lý Đơn hàng</h3>
    </div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        
        <div class="table-scroll-wrapper">
            <table class="table table-hover mb-0">
                <thead class="thead-light">
                    <tr>
                        <th class="col-fixed">Mã đơn</th>
                        <th>Khách hàng</th>
                        <th class="col-fixed">Ngày đặt</th>
                        <th class="col-fixed text-right">Tổng tiền</th>
                        <th class="col-fixed text-center">Trạng thái</th>
                        <th class="col-fixed text-center">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($orders)): ?>
                        <?php foreach ($orders as $order): ?>
                        <tr>
                            <td class="align-middle font-weight-bold text-primary">#<?= $order['order_number'] ?></td>
                            <td class="align-middle">
                                <div class="font-weight-bold text-dark"><?= $order['customer_name'] ?></div>
                                </td>
                            <td class="align-middle text-muted small">
                                <?= date('d/m/Y H:i', strtotime($order['created_at'])) ?>
                            </td>
                            <td class="align-middle text-right text-danger font-weight-bold">
                                <?= number_format($order['total_cents']) ?> đ
                            </td>
                            <td class="align-middle text-center">
                                <?php 
                                    $badges = [
                                        'pending' => 'secondary',
                                        'processing' => 'primary',
                                        'shipping' => 'info',
                                        'completed' => 'success',
                                        'cancelled' => 'danger'
                                    ];
                                    $labels = [
                                        'pending' => 'Chờ xác nhận',
                                        'processing' => 'Đang xử lý',
                                        'shipping' => 'Đang giao',
                                        'completed' => 'Hoàn thành',
                                        'cancelled' => 'Đã hủy'
                                    ];
                                    $st = $order['status'] ?? 'pending';
                                ?>
                                <span class="badge badge-<?= $badges[$st] ?? 'secondary' ?> px-2 py-1">
                                    <?= $labels[$st] ?? ucfirst($st) ?>
                                </span>
                            </td>
                            <td class="align-middle text-center">
                                <a href="/MY_WEB/public/admin/order/detail/<?= $order['id'] ?>" class="btn btn-sm btn-outline-primary shadow-sm rounded-pill font-weight-bold px-3">
                                    <i class="fas fa-search mr-1"></i> Chi tiết
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">Chưa có đơn hàng nào.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        </div>
    <div class="card-footer bg-white py-3">
        <small class="text-muted">Tổng số: <strong><?= count($orders) ?></strong> đơn hàng</small>
    </div>
</div>

<?php require_once '../app/Views/layouts/admin/footer.php'; ?>