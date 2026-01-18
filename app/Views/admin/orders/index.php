<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý Đơn hàng</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
</head>
<body>
    <?php require_once '../app/Views/layouts/admin_sidebar.php'; ?>

    <h2>Danh sách Đơn hàng</h2>
    <table class="table table-striped mt-3 bg-white">
        <thead>
            <tr>
                <th>ID</th>
                <th>Mã đơn</th>
                <th>Khách hàng</th>
                <th>Tổng tiền</th>
                <th>Ngày đặt</th>
                <th>Trạng thái</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orders as $order): ?>
            <tr>
                <td><?= $order['id'] ?></td>
                <td><?= $order['order_number'] ?></td>
                <td><?= $order['customer_name'] ?></td>
                <td><?= number_format($order['total_cents']) ?> đ</td>
                <td><?= $order['created_at'] ?></td>
                <td>
                    <span class="badge badge-primary"><?= $order['status'] ?></span>
                </td>
                <td>
                    <a href="/MY_WEB/public/admin/order/detail/<?= $order['id'] ?>" class="btn btn-info btn-sm">Chi tiết</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <?php require_once '../app/Views/layouts/admin_footer.php'; ?>