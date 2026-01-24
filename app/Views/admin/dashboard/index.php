<?php require_once '../app/Views/layouts/admin_sidebar.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="text-dark font-weight-bold">Tổng quan hệ thống</h3>
    <span class="text-muted">Cập nhật: <?= date('d/m/Y H:i') ?></span>
</div>

<div class="row mb-4 g-3">
    <div class="col-xl-3 col-md-6">
        <div class="card card-box bg-success text-white h-100">
            <div class="card-body position-relative">
                <h6 class="text-uppercase font-weight-bold mb-3 opacity-75">Doanh thu thực tế</h6>
                <h2 class="font-weight-bold mb-0"><?= number_format($stats['revenue']) ?> đ</h2>
                <small class="text-white-50 mt-2 d-block">(Đơn đã hoàn thành)</small>
                <i class="fas fa-money-bill-wave stat-icon" style="font-size: 3rem; opacity: 0.2; position: absolute; right: 20px; top: 50%; transform: translateY(-50%);"></i>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card card-box bg-primary text-white h-100">
            <div class="card-body position-relative">
                <h6 class="text-uppercase font-weight-bold mb-3 opacity-75">Tổng đơn hàng</h6>
                <h2 class="font-weight-bold mb-0"><?= $stats['total_orders'] ?></h2>
                <small class="text-white-50 mt-2 d-block">Đơn hàng toàn hệ thống</small>
                <i class="fas fa-shopping-bag stat-icon" style="font-size: 3rem; opacity: 0.2; position: absolute; right: 20px; top: 50%; transform: translateY(-50%);"></i>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card card-box bg-info text-white h-100">
            <div class="card-body position-relative">
                <h6 class="text-uppercase font-weight-bold mb-3 opacity-75">Khách hàng</h6>
                <h2 class="font-weight-bold mb-0"><?= $stats['total_users'] ?></h2>
                <small class="text-white-50 mt-2 d-block">Tài khoản đã đăng ký</small>
                <i class="fas fa-users stat-icon" style="font-size: 3rem; opacity: 0.2; position: absolute; right: 20px; top: 50%; transform: translateY(-50%);"></i>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card card-box bg-warning text-white h-100">
            <div class="card-body position-relative">
                <h6 class="text-uppercase font-weight-bold mb-3 opacity-75">Sản phẩm</h6>
                <h2 class="font-weight-bold mb-0"><?= $stats['total_products'] ?></h2>
                <small class="text-white-50 mt-2 d-block">Đang kinh doanh</small>
                <i class="fas fa-box stat-icon" style="font-size: 3rem; opacity: 0.2; position: absolute; right: 20px; top: 50%; transform: translateY(-50%);"></i>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 font-weight-bold text-secondary"><i class="fas fa-clock mr-2"></i> Đơn hàng mới nhất</h5>
        <a href="/MY_WEB/public/admin/order" class="btn btn-sm btn-outline-secondary rounded-pill">Xem tất cả</a>
    </div>
    <div class="card-body p-0">
        <div class="table-scroll-wrapper" style="max-height: 400px;">
            <table class="table table-hover mb-0">
                <thead class="thead-light">
                    <tr>
                        <th>Mã đơn</th>
                        <th>Khách hàng</th>
                        <th class="text-right">Tổng tiền</th>
                        <th class="text-center">Trạng thái</th>
                        <th class="text-center">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($stats['recent_orders'])): ?>
                        <?php foreach ($stats['recent_orders'] as $order): ?>
                        <tr>
                            <td class="font-weight-bold text-primary">#<?= $order['order_number'] ?></td>
                            <td>
                                <div class="font-weight-bold"><?= $order['user_name'] ?></div>
                                <small class="text-muted"><?= date('d/m H:i', strtotime($order['created_at'])) ?></small>
                            </td>
                            <td class="text-right text-danger font-weight-bold"><?= number_format($order['total_cents']) ?> đ</td>
                            <td class="text-center">
                                <?php 
                                    $badges = ['pending'=>'secondary', 'processing'=>'primary', 'shipping'=>'info', 'completed'=>'success', 'cancelled'=>'danger'];
                                    $statusLabel = [
                                        'pending' => 'Chờ xử lý', 'processing' => 'Đang xử lý', 'shipping' => 'Đang giao', 'completed' => 'Hoàn thành', 'cancelled' => 'Đã hủy'
                                    ];
                                    $badgeClass = $badges[$order['status']] ?? 'light';
                                ?>
                                <span class="badge badge-<?= $badgeClass ?> px-3 py-2 rounded-pill"><?= $statusLabel[$order['status']] ?? ucfirst($order['status']) ?></span>
                            </td>
                            <td class="text-center">
                                <a href="/MY_WEB/public/admin/order/detail/<?= $order['id'] ?>" class="btn btn-sm btn-outline-primary rounded-circle shadow-sm" style="width: 32px; height: 32px; padding: 0; line-height: 30px;">
                                    <i class="fas fa-arrow-right"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="5" class="text-center py-4 text-muted">Chưa có đơn hàng nào.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once '../app/Views/layouts/admin_footer.php'; ?>