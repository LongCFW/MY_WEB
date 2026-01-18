<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
</head>
<body>
    <?php require_once '../app/Views/layouts/admin_sidebar.php'; ?>

    <h2 class="mb-4">Tổng quan hệ thống</h2>
    
    <div class="row">
        <div class="col-md-4">
            <div class="card text-white bg-success mb-3">
                <div class="card-header">Doanh thu</div>
                <div class="card-body">
                    <h3 class="card-title"><?= number_format($stats['revenue']) ?> đ</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-primary mb-3">
                <div class="card-header">Tổng đơn hàng</div>
                <div class="card-body">
                    <h3 class="card-title"><?= $stats['total_orders'] ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-warning mb-3">
                <div class="card-header">Sản phẩm</div>
                <div class="card-body">
                    <h3 class="card-title"><?= $stats['total_products'] ?></h3>
                </div>
            </div>
        </div>
    </div>

    <h4 class="mt-4">Đơn hàng mới nhất</h4>
    <table class="table table-bordered bg-white">
        <thead>
            <tr>
                <th>Mã đơn</th>
                <th>Khách hàng</th>
                <th>Tổng tiền</th>
                <th>Trạng thái</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($stats['recent_orders'] as $order): ?>
            <tr>
                <td><?= $order['order_number'] ?></td>
                <td><?= $order['user_name'] ?></td>
                <td><?= number_format($order['total_cents']) ?> đ</td>
                <td><span class="badge badge-info"><?= $order['status'] ?></span></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <?php require_once '../app/Views/layouts/admin_footer.php'; ?>