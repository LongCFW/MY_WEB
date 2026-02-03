<?php use App\Utils\Pagination; ?>
<h4 class="fw-bold text-primary mb-4 border-bottom pb-3">
    <i class="fas fa-box-open me-2"></i> Lịch sử đơn hàng
</h4>

<?php if (empty($orders)): ?>
    <div class="text-center py-5">
        <img src="https://cdn-icons-png.flaticon.com/512/4076/4076432.png" width="80" class="opacity-50 mb-3">
        <p class="text-muted">Bạn chưa có đơn hàng nào.</p>
        <a href="/MY_WEB/public/product" class="btn btn-outline-success rounded-pill px-4">Mua sắm ngay</a>
    </div>
<?php else: ?>
    <div class="table-responsive">
        <table class="table table-hover align-middle border rounded-3 overflow-hidden">
            <thead class="bg-light">
                <tr>
                    <th class="py-3 ps-3">Mã đơn</th>
                    <th>Ngày đặt</th>
                    <th>Tổng tiền</th>
                    <th>Trạng thái</th>
                    <th class="text-end pe-3">Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $od): ?>
                <tr>
                    <td class="ps-3 fw-bold text-success">#<?= $od['order_number'] ?></td>
                    <td><?= date('d-m-Y', strtotime($od['created_at'])) ?></td>
                    <td class="fw-bold"><?= number_format($od['total_cents']) ?> đ</td>
                    <td>
                        <?php 
                            $sttClass = match($od['status']) {
                                'pending' => 'bg-warning text-dark',
                                'shipping' => 'bg-primary text-white',
                                'completed' => 'bg-success text-white',
                                'cancelled' => 'bg-danger text-white',
                                default => 'bg-secondary text-white'
                            };
                            $sttLabel = match($od['status']) {
                                'pending' => 'Chờ xử lý',
                                'shipping' => 'Đang vận chuyển',
                                'completed' => 'Giao thành công',
                                'cancelled' => 'Đã hủy',
                                default => $od['status']
                            };
                        ?>
                        <span class="badge rounded-pill <?= $sttClass ?> px-3 py-2 fw-normal">
                            <?= $sttLabel ?>
                        </span>
                    </td>
                    <td class="text-end pe-3">
                        <button class="btn btn-outline-primary btn-sm rounded-pill px-3" onclick="viewOrder(<?= $od['id'] ?>)">
                            <i class="fas fa-eye me-1"></i> Chi tiết
                        </button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>
<div class="py-3">
    <?php echo Pagination::render($pageNum, $totalPages, 'p'); ?>
</div>