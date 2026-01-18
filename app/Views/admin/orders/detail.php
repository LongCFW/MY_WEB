<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Chi tiết Đơn hàng #<?= $order['order_number'] ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
</head>

<body>
    <?php require_once '../app/Views/layouts/admin_sidebar.php'; ?>

    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3>🧾 Chi tiết đơn hàng: #<?= $order['order_number'] ?></h3>
            <a href="/MY_WEB/public/admin/order" class="btn btn-secondary">Quay lại</a>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-white font-weight-bold">Sản phẩm trong đơn</div>
                    <div class="card-body p-0">
                        <table class="table table-borderless mb-0">
                            <thead>
                                <tr class="border-bottom">
                                    <th>Sản phẩm</th>
                                    <th>Đơn giá</th>
                                    <th>SL</th>
                                    <th class="text-right">Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($items as $item): ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <?php if ($item['image_url']): ?>
                                                    <img src="/MY_WEB/public/<?= $item['image_url'] ?>" width="50" class="mr-3 rounded border">
                                                <?php endif; ?>
                                                <div>
                                                    <strong><?= $item['product_name'] ?></strong><br>
                                                    <small class="text-muted">SKU: <?= $item['product_sku'] ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td><?= number_format($item['unit_price_cents'] ?? 0) ?> đ</td>
                                        <td>x<?= $item['quantity'] ?? 1 ?></td>
                                        <td class="text-right font-weight-bold"><?= number_format($item['total_price_cents'] ?? 0) ?> đ</td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot class="border-top bg-light">
                                <tr>
                                    <td colspan="3" class="text-right font-weight-bold">Tổng thanh toán:</td>
                                    <td class="text-right text-danger font-weight-bold" style="font-size: 1.2em;">
                                        <?= number_format($order['total_cents']) ?> đ
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card mb-3 shadow-sm border-primary">
                    <div class="card-header bg-primary text-white">Cập nhật trạng thái</div>
                    <div class="card-body">
                        <form action="/MY_WEB/public/admin/order/update_status/<?= $order['id'] ?>" method="POST">
                            <div class="form-group">
                                <label>Trạng thái hiện tại:</label>
                                <select name="status" class="form-control">
                                    <option value="pending" <?= $order['status'] == 'pending' ? 'selected' : '' ?>>Chờ xác nhận</option>
                                    <option value="processing" <?= $order['status'] == 'processing' ? 'selected' : '' ?>>Đang xử lý</option>
                                    <option value="shipping" <?= $order['status'] == 'shipping' ? 'selected' : '' ?>>Đang giao hàng</option>
                                    <option value="completed" <?= $order['status'] == 'completed' ? 'selected' : '' ?>>Hoàn thành</option>
                                    <option value="cancelled" <?= $order['status'] == 'cancelled' ? 'selected' : '' ?>>Đã hủy</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block">Cập nhật ngay</button>
                        </form>
                    </div>
                </div>

                <div class="card shadow-sm">
                    <div class="card-header bg-white font-weight-bold">Thông tin giao hàng</div>
                    <div class="card-body">
                        <p><strong>Người nhận:</strong> <?= $order['ship_name'] ?></p>
                        <p><strong>SĐT:</strong> <?= $order['ship_phone'] ?></p>
                        <p><strong>Địa chỉ:</strong><br>
                            <?= $order['address_line'] ?><br>
                            <?= $order['city'] ?>, <?= $order['province'] ?? '' ?>
                        </p>
                        <hr>
                        <p><strong>Khách hàng (TK):</strong> <?= $order['customer_name'] ?></p>
                        <p><strong>Email:</strong> <?= $order['email'] ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php require_once '../app/Views/layouts/admin_footer.php'; ?>
</body>

</html>