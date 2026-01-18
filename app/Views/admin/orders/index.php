<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý Đơn hàng</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
</head>
<body>
    <?php require_once '../app/Views/layouts/admin_sidebar.php'; ?>

    <div class="container-fluid">
        <h2 class="mb-4">📦 Quản lý Đơn hàng</h2>
        
        <table class="table table-hover bg-white shadow-sm border rounded">
            <thead class="thead-light">
                <tr>
                    <th>Mã đơn</th>
                    <th>Khách hàng</th>
                    <th>Ngày đặt</th>
                    <th>Tổng tiền</th>
                    <th>Trạng thái</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                <tr>
                    <td><strong>#<?= $order['order_number'] ?></strong></td>
                    <td>
                        <?= $order['customer_name'] ?><br>
                        <small class="text-muted"><?= $order['email'] ?? 'Khách vãng lai/Không có email' ?></small>
                    </td>
                    <td><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></td>
                    <td class="text-danger font-weight-bold"><?= number_format($order['total_cents']) ?> đ</td>
                    <td>
                        <?php 
                            $badges = [
                                'pending' => 'badge-secondary',
                                'processing' => 'badge-primary',
                                'shipping' => 'badge-info',
                                'completed' => 'badge-success',
                                'cancelled' => 'badge-danger'
                            ];
                            $statusLabel = [
                                'pending' => 'Chờ xác nhận',
                                'processing' => 'Đang xử lý',
                                'shipping' => 'Đang giao',
                                'completed' => 'Hoàn thành',
                                'cancelled' => 'Đã hủy'
                            ];
                            $st = $order['status'] ?? 'pending';
                        ?>
                        <span class="badge <?= $badges[$st] ?? 'badge-secondary' ?>">
                            <?= $statusLabel[$st] ?? $st ?>
                        </span>
                    </td>
                    <td>
                        <a href="/MY_WEB/public/admin/order/detail/<?= $order['id'] ?>" class="btn btn-sm btn-outline-primary">🔍 Chi tiết</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <?php require_once '../app/Views/layouts/admin_footer.php'; ?>
</body>
</html>