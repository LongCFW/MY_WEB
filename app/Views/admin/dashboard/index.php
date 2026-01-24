<?php require_once '../app/Views/layouts/admin_sidebar.php'; ?>

<h3 class="mb-4 text-dark font-weight-bold">Tổng quan hệ thống</h3>

<div class="row mb-4">
    <div class="col-md-4">
        <div class="card card-box bg-success text-white h-100">
            <div class="card-body position-relative">
                <h5 class="text-uppercase font-weight-normal mb-3">Doanh thu</h5>
                <h2 class="font-weight-bold mb-0"><?= number_format($stats['revenue']) ?> đ</h2>
                <i class="fas fa-money-bill-wave stat-icon"></i>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-box bg-primary text-white h-100">
            <div class="card-body position-relative">
                <h5 class="text-uppercase font-weight-normal mb-3">Tổng đơn hàng</h5>
                <h2 class="font-weight-bold mb-0"><?= $stats['total_orders'] ?></h2>
                <i class="fas fa-shopping-bag stat-icon"></i>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-box bg-warning text-white h-100">
            <div class="card-body position-relative">
                <h5 class="text-uppercase font-weight-normal mb-3">Sản phẩm</h5>
                <h2 class="font-weight-bold mb-0"><?= $stats['total_products'] ?></h2>
                <i class="fas fa-box stat-icon"></i>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white border-0 py-3">
        <h5 class="mb-0 font-weight-bold text-secondary"><i class="fas fa-clock mr-2"></i> Đơn hàng mới nhất</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="thead-light">
                    <tr>
                        <th>Mã đơn</th>
                        <th>Khách hàng</th>
                        <th>Tổng tiền</th>
                        <th>Trạng thái</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($stats['recent_orders'] as $order): ?>
                    <tr>
                        <td class="font-weight-bold">#<?= $order['order_number'] ?></td>
                        <td><?= $order['user_name'] ?></td>
                        <td class="text-danger font-weight-bold"><?= number_format($order['total_cents']) ?> đ</td>
                        <td>
                            <?php 
                                $badges = ['pending'=>'secondary', 'processing'=>'primary', 'completed'=>'success', 'cancelled'=>'danger'];
                                $badgeClass = $badges[$order['status']] ?? 'light';
                            ?>
                            <span class="badge badge-<?= $badgeClass ?> px-2 py-1"><?= ucfirst($order['status']) ?></span>
                        </td>
                        <td>
                            <a href="/MY_WEB/public/admin/order/detail/<?= $order['id'] ?>" class="btn btn-sm btn-outline-primary rounded-pill px-3">Chi tiết</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once '../app/Views/layouts/admin_footer.php'; ?>