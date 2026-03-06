<?php require_once '../app/Views/layouts/admin/sidebar.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="text-dark font-weight-bold">Quản lý Đơn hàng</h3>
</div>

<div class="card shadow-sm border-0 mb-4 bg-white">
    <div class="card-body p-3">
        <form method="GET" action="/MY_WEB/public/admin/order" class="row align-items-center">
            <div class="col-md-4 mb-2 mb-md-0">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text bg-light border-right-0"><i class="fas fa-search text-muted"></i></span>
                    </div>
                    <input type="text" name="search" class="form-control border-left-0" placeholder="Tìm mã đơn, tên KH..." value="<?= $_GET['search'] ?? '' ?>">
                </div>
            </div>
            
            <div class="col-md-3 mb-2 mb-md-0">
                <select name="status" class="form-control custom-select" onchange="this.form.submit()">
                    <option value="">-- Tất cả trạng thái --</option>
                    <option value="pending" <?= (($_GET['status'] ?? '') == 'pending') ? 'selected' : '' ?>>Chờ xác nhận</option>
                    <option value="processing" <?= (($_GET['status'] ?? '') == 'processing') ? 'selected' : '' ?>>Đang xử lý</option>
                    <option value="shipping" <?= (($_GET['status'] ?? '') == 'shipping') ? 'selected' : '' ?>>Đang giao hàng</option>
                    <option value="completed" <?= (($_GET['status'] ?? '') == 'completed') ? 'selected' : '' ?>>Hoàn thành</option>
                    <option value="cancelled" <?= (($_GET['status'] ?? '') == 'cancelled') ? 'selected' : '' ?>>Đã hủy</option>
                </select>
            </div>

            <div class="col-md-3 mb-2 mb-md-0">
                <select name="payment_method" class="form-control custom-select" onchange="this.form.submit()">
                    <option value="">-- Hình thức thanh toán --</option>
                    <option value="cod" <?= (($_GET['payment_method'] ?? '') == 'cod') ? 'selected' : '' ?>>Thanh toán COD</option>
                    <option value="banking" <?= (($_GET['payment_method'] ?? '') == 'banking') ? 'selected' : '' ?>>Chuyển khoản</option>
                </select>
            </div>

            <div class="col-md-2 d-flex">
                <button type="submit" class="btn btn-primary flex-grow-1 mr-2 font-weight-bold">Lọc</button>
                <a href="/MY_WEB/public/admin/order" class="btn btn-outline-secondary" title="Xóa bộ lọc"><i class="fas fa-sync-alt"></i></a>
            </div>
        </form>
    </div>
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
                            <td class="align-middle text-right font-weight-bold">
                                <div class="text-danger"><?= number_format($order['total_cents']) ?> đ</div>
                                <?php if($order['payment_method'] == 'banking'): ?>
                                    <?php if($order['payment_status'] == 'paid'): ?>
                                        <span class="badge badge-success mt-1">Đã CK</span>
                                    <?php else: ?>
                                        <span class="badge badge-warning text-dark mt-1">Chờ CK</span>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="badge badge-info mt-1">COD</span>
                                <?php endif; ?>
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
                                <a href="/MY_WEB/public/admin/order/detail/<?= $order['id'] ?>" class="btn btn-sm btn-outline-primary shadow-sm rounded-pill font-weight-bold px-2 mb-1">
                                    <i class="fas fa-search mr-1"></i> Chi tiết
                                </a>
                                
                                <?php if($order['payment_method'] == 'banking' && $order['payment_status'] == 'unpaid'): ?>
                                    <button type="button" class="btn btn-sm btn-success shadow-sm rounded-pill font-weight-bold px-2" onclick="confirmPayment(<?= $order['id'] ?>, this)">
                                        <i class="fas fa-check-circle mr-1"></i> Đã nhận tiền
                                    </button>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="fas fa-box-open fa-3x mb-3 text-light"></i><br>
                                Không tìm thấy đơn hàng nào phù hợp với bộ lọc.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-white py-3">
        <small class="text-muted">Tổng số: <strong><?= count($orders) ?></strong> kết quả</small>
    </div>
</div>

<script>
    function confirmPayment(orderId, btnElement) {
        if(confirm('Xác nhận đã nhận đủ tiền cho đơn hàng này?')) {
            // Đổi giao diện nút thành trạng thái đang xử lý để chống click nhiều lần
            const originalHtml = btnElement.innerHTML;
            btnElement.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Đang xử lý...';
            btnElement.disabled = true;

            // Gọi ngầm xuống Controller
            fetch(`/MY_WEB/public/admin/order/confirmPayment/${orderId}`)
            .then(() => {
                // Ép trình duyệt tải lại trang (bỏ qua Cache) để hiển thị huy hiệu "Đã CK"
                window.location.reload();
            })
            .catch(err => {
                console.error(err);
                alert('Lỗi kết nối. Vui lòng thử lại!');
                btnElement.innerHTML = originalHtml;
                btnElement.disabled = false;
            });
        }
    }
</script>

<?php require_once '../app/Views/layouts/admin/footer.php'; ?>