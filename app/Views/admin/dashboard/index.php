<?php require_once '../app/Views/layouts/admin/sidebar.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="text-dark font-weight-bold">Tổng quan hệ thống</h3>
    <span class="text-muted">Cập nhật: <?= date('d/m/Y H:i') ?></span>
</div>

<div class="row mb-4 g-3">
    <div class="col-xl-3 col-md-6">
        <div class="card card-box bg-success text-white h-100 shadow-sm border-0">
            <div class="card-body position-relative">
                <h6 class="text-uppercase font-weight-bold mb-3 opacity-75">Doanh thu thực tế</h6>
                <h2 class="font-weight-bold mb-0"><?= number_format($stats['revenue']) ?> đ</h2>
                <small class="text-white-50 mt-2 d-block">(Đơn đã hoàn thành)</small>
                <i class="fas fa-money-bill-wave stat-icon" style="font-size: 3.5rem; opacity: 0.15; position: absolute; right: 20px; top: 50%; transform: translateY(-50%);"></i>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card card-box bg-primary text-white h-100 shadow-sm border-0">
            <div class="card-body position-relative">
                <h6 class="text-uppercase font-weight-bold mb-3 opacity-75">Tổng đơn hàng</h6>
                <h2 class="font-weight-bold mb-0"><?= $stats['total_orders'] ?></h2>
                <small class="text-white-50 mt-2 d-block">Đơn hàng toàn hệ thống</small>
                <i class="fas fa-shopping-bag stat-icon" style="font-size: 3.5rem; opacity: 0.15; position: absolute; right: 20px; top: 50%; transform: translateY(-50%);"></i>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card card-box bg-info text-white h-100 shadow-sm border-0">
            <div class="card-body position-relative">
                <h6 class="text-uppercase font-weight-bold mb-3 opacity-75">Khách hàng thật</h6>
                <h2 class="font-weight-bold mb-0"><?= $stats['total_real_users'] ?></h2>
                <small class="text-white-50 mt-2 d-block">Role ID = 4</small>
                <i class="fas fa-users stat-icon" style="font-size: 3.5rem; opacity: 0.15; position: absolute; right: 20px; top: 50%; transform: translateY(-50%);"></i>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card card-box bg-warning text-white h-100 shadow-sm border-0">
            <div class="card-body position-relative">
                <h6 class="text-uppercase font-weight-bold mb-3 opacity-75">Sản phẩm</h6>
                <h2 class="font-weight-bold mb-0"><?= $stats['total_products'] ?></h2>
                <small class="text-white-50 mt-2 d-block">Đang kinh doanh</small>
                <i class="fas fa-box stat-icon" style="font-size: 3.5rem; opacity: 0.15; position: absolute; right: 20px; top: 50%; transform: translateY(-50%);"></i>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4 g-3">
    <div class="col-xl-3 col-md-6">
        <div class="card border-left-primary shadow-sm h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Tài khoản Seeding (Ảo)</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $stats['total_seeding_users'] ?></div>
                    </div>
                    <div class="col-auto"><i class="fas fa-robot fa-2x text-gray-300"></i></div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card border-left-danger shadow-sm h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Admin & Nhân viên</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $stats['total_staff'] ?></div>
                    </div>
                    <div class="col-auto"><i class="fas fa-user-shield fa-2x text-gray-300"></i></div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card border-left-success shadow-sm h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Danh mục sản phẩm</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $stats['total_categories'] ?></div>
                    </div>
                    <div class="col-auto"><i class="fas fa-tags fa-2x text-gray-300"></i></div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card border-left-warning shadow-sm h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Voucher khuyến mãi</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $stats['total_coupons'] ?></div>
                    </div>
                    <div class="col-auto"><i class="fas fa-ticket-alt fa-2x text-gray-300"></i></div>
                </div>
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
        <div class="table-scroll-wrapper" style="max-height: 400px; overflow-y: auto;">
            <table class="table table-hover mb-0">
                <thead class="thead-light">
                    <tr>
                        <th class="pl-4">Mã đơn</th>
                        <th>Khách hàng</th>
                        <th class="text-right">Tổng tiền</th>
                        <th class="text-center">Trạng thái</th>
                        <th class="text-center pr-4">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($stats['recent_orders'])): ?>
                        <?php foreach ($stats['recent_orders'] as $order): ?>
                        <tr>
                            <td class="font-weight-bold text-primary pl-4 align-middle">#<?= $order['order_number'] ?></td>
                            <td class="align-middle">
                                <div class="font-weight-bold text-dark"><?= $order['user_name'] ?></div>
                                <small class="text-muted"><i class="far fa-clock mr-1"></i><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></small>
                            </td>
                            <td class="text-right text-danger font-weight-bold align-middle"><?= number_format($order['total_cents']) ?> đ</td>
                            <td class="text-center align-middle">
                                <?php 
                                    $badges = ['pending'=>'secondary', 'processing'=>'primary', 'shipping'=>'info', 'completed'=>'success', 'cancelled'=>'danger'];
                                    $statusLabel = [
                                        'pending' => 'Chờ xử lý', 'processing' => 'Đang xử lý', 'shipping' => 'Đang giao', 'completed' => 'Hoàn thành', 'cancelled' => 'Đã hủy'
                                    ];
                                    $badgeClass = $badges[$order['status']] ?? 'light';
                                ?>
                                <span class="badge badge-<?= $badgeClass ?> px-3 py-2 rounded-pill"><?= $statusLabel[$order['status']] ?? ucfirst($order['status']) ?></span>
                            </td>
                            <td class="text-center pr-4 align-middle">
                                <a href="/MY_WEB/public/admin/order/detail/<?= $order['id'] ?>" class="btn btn-sm btn-light text-primary rounded-circle shadow-sm" style="width: 35px; height: 35px; padding: 0; line-height: 35px;">
                                    <i class="fas fa-arrow-right"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="5" class="text-center py-5 text-muted"><i class="fas fa-box-open fa-2x mb-3 opacity-50"></i><br>Chưa có đơn hàng nào.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once '../app/Views/layouts/admin/footer.php'; ?>