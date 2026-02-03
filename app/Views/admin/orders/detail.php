<?php require_once '../app/Views/layouts/admin/sidebar.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="font-weight-bold text-dark">
        Đơn hàng #<?= $order['order_number'] ?>
    </h3>
    <a href="/MY_WEB/public/admin/order" class="btn btn-light rounded-pill border px-4 shadow-sm">
        <i class="fas fa-arrow-left mr-1"></i> Quay lại
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white font-weight-bold py-3">Danh sách sản phẩm</div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="border-top-0">Sản phẩm</th>
                            <th class="border-top-0 text-center">Đơn giá</th>
                            <th class="border-top-0 text-center">SL</th>
                            <th class="border-top-0 text-right">Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $item): ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="<?= $item['display_image'] ?>" width="50" height="50" class="mr-3 rounded shadow-sm object-fit-cover">
                                        <div>
                                            <div class="font-weight-bold text-dark"><?= htmlspecialchars($item['display_name']) ?></div>
                                            <small class="text-muted">SKU: <?= $item['display_sku'] ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td class="align-middle text-center"><?= number_format($item['unit_price_cents']) ?> đ</td>
                                <td class="align-middle text-center">x<?= $item['quantity'] ?></td>
                                <td class="align-middle text-right font-weight-bold"><?= number_format($item['total_price_cents']) ?> đ</td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot class="bg-light">
                        <tr>
                            <td colspan="3" class="text-right font-weight-bold text-uppercase">Tổng thanh toán:</td>
                            <td class="text-right text-danger font-weight-bold h5 mb-0">
                                <?= number_format($order['total_cents']) ?> đ
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm border-0 mb-4 border-top-primary" style="border-top: 4px solid #007bff !important;">
            <div class="card-header bg-white font-weight-bold">Cập nhật trạng thái</div>
            <div class="card-body">
                <form action="/MY_WEB/public/admin/order/update_status/<?= $order['id'] ?>" method="POST">
                    <div class="form-group">
                        <select name="status" class="form-control custom-select">
                            <option value="pending" <?= $order['status'] == 'pending' ? 'selected' : '' ?>>Chờ xác nhận</option>
                            <option value="processing" <?= $order['status'] == 'processing' ? 'selected' : '' ?>>Đang xử lý</option>
                            <option value="shipping" <?= $order['status'] == 'shipping' ? 'selected' : '' ?>>Đang giao hàng</option>
                            <option value="completed" <?= $order['status'] == 'completed' ? 'selected' : '' ?>>Hoàn thành</option>
                            <option value="cancelled" <?= $order['status'] == 'cancelled' ? 'selected' : '' ?>>Đã hủy</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <textarea name="note" class="form-control" rows="3" placeholder="Ghi chú thêm..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block rounded-pill font-weight-bold">Cập nhật</button>
                </form>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-header bg-white font-weight-bold">Lịch sử thay đổi</div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush" style="max-height: 300px; overflow-y: auto;">
                    <?php if (!empty($history)): foreach ($history as $his): ?>
                        <li class="list-group-item">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="badge badge-light border text-dark"><?= ucfirst($his['status']) ?></span>
                                <small class="text-muted" style="font-size: 0.75rem;"><?= date('H:i d/m', strtotime($his['created_at'])) ?></small>
                            </div>
                            <?php if ($his['note']): ?>
                                <div class="small text-secondary font-italic mb-1">"<?= htmlspecialchars($his['note']) ?>"</div>
                            <?php endif; ?>
                            <div class="small text-info"><i class="fas fa-user-edit mr-1"></i> <?= $his['changer_name'] ?? 'System' ?></div>
                        </li>
                    <?php endforeach; else: ?>
                        <li class="list-group-item text-center text-muted">Chưa có lịch sử</li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php require_once '../app/Views/layouts/admin/footer.php'; ?>