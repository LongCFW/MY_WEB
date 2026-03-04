<?php require_once '../app/Views/layouts/client/header.php'; ?>

<div class="bg-light min-vh-100 pb-5">
    <div class="bg-success py-5 mb-5 text-center text-white position-relative overflow-hidden" 
         style="background-image: linear-gradient(135deg, #0f3d28 0%, #2e7d32 100%)">
        <div class="container position-relative z-1">
            <h1 class="fw-bold display-5 mb-3">Kho Voucher & Ưu Đãi</h1>
            <p class="lead opacity-75 mb-4">Săn mã giảm giá hàng ngày để mua sắm tiết kiệm hơn</p>
        </div>
    </div>

    <div class="container">
        <div class="bg-white p-4 rounded-4 shadow-sm border">
            <ul class="nav nav-tabs justify-content-center mb-4 border-bottom-0" id="offerTabs">
                <li class="nav-item"><button class="nav-link active fw-bold text-success" data-bs-toggle="tab" data-bs-target="#all">Tất cả</button></li>
                <li class="nav-item"><button class="nav-link fw-bold text-muted" data-bs-toggle="tab" data-bs-target="#shipping">Vận chuyển</button></li>
            </ul>

            <div class="tab-content" id="offerTabsContent">
                <div class="tab-pane fade show active" id="all">
                    <div class="row g-4">
                        <?php if (!empty($vouchers)): ?>
                            <?php foreach ($vouchers as $v): ?>
                            <div class="col-md-6 col-lg-4">
                                <div class="voucher-ticket d-flex h-100 border rounded-3 overflow-hidden bg-white shadow-sm position-relative">
                                    <div class="voucher-left bg-success text-white p-3 d-flex flex-column align-items-center justify-content-center" style="width: 100px; background: linear-gradient(135deg, #43a047 0%, #1de9b6 100%);">
                                        <i class="fas fa-ticket-alt fs-3 mb-1"></i>
                                        <span class="small fw-bold border border-white px-1 rounded"><?= strtoupper($v['type'] == 'percent' ? 'Giảm %' : 'Giảm Tiền') ?></span>
                                    </div>
                                    <div class="p-3 flex-grow-1 d-flex flex-column">
                                        <div class="d-flex justify-content-between align-items-start mb-1">
                                            <h5 class="fw-bold text-dark mb-0"><?= $v['code'] ?></h5>
                                            <span class="badge bg-light text-dark border">Số lượng có hạn</span>
                                        </div>
                                        <p class="fw-bold text-success mb-1 small"><?= $v['desc'] ?></p>
                                        <p class="text-muted small mb-3 flex-grow-1" style="font-size: 0.75rem;">
                                            Đơn tối thiểu: <?= number_format($v['min']) ?>đ <br> HSD: <?= $v['expiry'] ?>
                                        </p>
                                        
                                        <?php if ($v['is_saved']): ?>
                                            <button class="btn btn-secondary btn-sm rounded-pill w-100 fw-bold" disabled>
                                                <i class="fas fa-check-circle me-1"></i> Đã Lưu
                                            </button>
                                        <?php else: ?>
                                            <button class="btn btn-outline-success btn-sm rounded-pill w-100 fw-bold btn-save-coupon" data-id="<?= $v['id'] ?>">
                                                <i class="fas fa-download me-1"></i> Lưu Mã
                                            </button>
                                        <?php endif; ?>

                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="col-12 text-center py-5">
                                <h5 class="text-muted">Hiện tại chưa có mã giảm giá nào.</h5>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.querySelectorAll('.btn-save-coupon').forEach(button => {
    button.addEventListener('click', function() {
        const couponId = this.getAttribute('data-id');
        const btn = this;
        
        // Hiện hiệu ứng loading trên nút
        const originalHtml = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Đang lưu...';
        btn.disabled = true;

        const formData = new FormData();
        formData.append('coupon_id', couponId);

        // Gọi router của OfferController (lưu ý chữ 'offer' viết thường theo chuẩn router của bạn)
        fetch('/MY_WEB/public/offer/saveCoupon', {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                // Đổi giao diện nút thành "Đã Lưu"
                btn.className = 'btn btn-secondary btn-sm rounded-pill w-100 fw-bold';
                btn.innerHTML = '<i class="fas fa-check-circle me-1"></i> Đã Lưu';
                alert(data.message); 
            } else {
                // Lỗi (ví dụ chưa đăng nhập)
                btn.innerHTML = originalHtml;
                btn.disabled = false;
                alert(data.message);
                if(data.message.includes('đăng nhập')) {
                    window.location.href = '/MY_WEB/public/auth/login';
                }
            }
        })
        .catch(err => {
            btn.innerHTML = originalHtml;
            btn.disabled = false;
            console.error(err);
            alert('Có lỗi xảy ra, vui lòng thử lại.');
        });
    });
});
</script>

<?php require_once '../app/Views/layouts/client/footer.php'; ?>