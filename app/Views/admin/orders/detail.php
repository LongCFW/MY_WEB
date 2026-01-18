<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Chi tiết Đơn hàng</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
</head>
<body>
    <?php require_once '../app/Views/layouts/admin_sidebar.php'; ?>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Chi tiết đơn: <?= $order['order_number'] ?></h2>
        <a href="/MY_WEB/public/admin/order" class="btn btn-secondary">Quay lại</a>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-info text-white">Thông tin khách hàng</div>
                <div class="card-body">
                    <p><strong>Họ tên:</strong> <?= $order['customer_name'] ?></p>
                    <p><strong>Email:</strong> <?= $order['email'] ?></p>
                    <hr>
                    <h6>Địa chỉ giao hàng:</h6>
                    <p>
                        <?= $order['ship_name'] ?> (<?= $order['ship_phone'] ?>)<br>
                        <?= $order['address_line'] ?>, <?= $order['city'] ?>
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-warning">Cập nhật trạng thái</div>
                <div class="card-body">
                    <form action="/MY_WEB/public/admin/order/update_status/<?= $order['id'] ?>" method="POST">
                        <div class="form-group">
                            <label>Trạng thái hiện tại:</label>
                            <select name="status" class="form-control">
                                <?php 
                                    $statuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];
                                    foreach ($statuses as $st): 
                                ?>
                                    <option value="<?= $st ?>" <?= ($order['status'] == $st) ? 'selected' : '' ?>>
                                        <?= ucfirst($st) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Cập nhật</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <h4 class="mt-4">Sản phẩm đã đặt</h4>
    <table class="table table-bordered bg-white">
        <thead>
            <tr>
                <th>Sản phẩm</th>
                <th>Hình ảnh</th>
                <th>Giá</th>
                <th>Số lượng</th>
                <th>Thành tiền</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $item): ?>
            <tr>
                <td><?= $item['product_name'] ?></td>
                <td><img src="/MY_WEB/public/<?= $item['image_url'] ?>" width="50"></td>
                <td><?= number_format($item['unit_price_cents']) ?> đ</td>
                <td><?= $item['quantity'] ?></td>
                <td><?= number_format($item['total_price_cents']) ?> đ</td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <?php require_once '../app/Views/layouts/admin_footer.php'; ?>