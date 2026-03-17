<?php
// Tách dữ liệu thành 2 mảng riêng biệt cho 2 Tab
$activeCoupons = [];
$historyCoupons = []; // Đã dùng hoặc hết hạn/hết lượt

if (!empty($savedCoupons)) {
    foreach ($savedCoupons as $c) {
        $isExpired = strtotime($c['ends_at']) < time();
        $isUsedUp = (isset($c['usage_limit']) && $c['usage_limit'] > 0 && isset($c['used_count']) && $c['used_count'] >= $c['usage_limit']);
        $isUsedByUser = isset($c['is_used']) && $c['is_used'] == 1;

        if ($isUsedByUser || $isExpired || $isUsedUp) {
            $historyCoupons[] = $c;
        } else {
            $activeCoupons[] = $c;
        }
    }
}
?>

<div class="d-flex justify-content-between align-items-end mb-4 border-bottom pb-3">
    <h4 class="fw-bold text-success mb-0"><i class="fas fa-ticket-alt me-2"></i> Ví Voucher Của Tôi</h4>
    <?php if (!empty($historyCoupons)): ?>
        <button class="btn btn-sm btn-outline-danger rounded-pill px-3" onclick="cleanHistoryCoupons()">
            <i class="fas fa-trash-alt me-1"></i> Dọn dẹp mã hết hạn
        </button>
    <?php endif; ?>
</div>

<ul class="nav nav-pills mb-4 gap-2" id="voucher-tab" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active rounded-pill px-4 fw-bold" id="active-tab" data-bs-toggle="pill" data-bs-target="#active" type="button" role="tab">
            Mã đang có (<?= count($activeCoupons) ?>)
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link rounded-pill px-4 fw-bold text-muted" id="history-tab" data-bs-toggle="pill" data-bs-target="#history" type="button" role="tab">
            Lịch sử mã (<?= count($historyCoupons) ?>)
        </button>
    </li>
</ul>

<div class="tab-content" id="voucher-tabContent">
    <div class="tab-pane fade show active" id="active" role="tabpanel">
        <div class="voucher-scroll-container">
            <div class="row g-4 mb-2">
                <?php if (!empty($activeCoupons)): ?>
                    <?php foreach ($activeCoupons as $c): ?>
                        <div class="col-md-6 mb-3" id="saved-coupon-<?= $c['user_coupon_id'] ?>"> 
                            <div class="voucher-ticket d-flex h-100 border rounded-3 overflow-hidden bg-white shadow-sm position-relative transition-hover">
                                
                                <div class="btn-delete-voucher" onclick="deleteSavedCoupon(<?= $c['user_coupon_id'] ?>)" title="Xóa voucher này">
                                    <i class="fas fa-times"></i>
                                </div>

                                <div class="text-white p-3 d-flex flex-column align-items-center justify-content-center text-center" style="width: 100px; background: linear-gradient(135deg, #43a047 0%, #1de9b6 100%);">
                                    <span class="fs-5 fw-bold mb-1">
                                        <?= $c['type'] == 'percent' ? $c['value'] . '%' : number_format($c['value']/1000) . 'K' ?>
                                    </span>
                                    <span class="small border border-white px-1 rounded" style="font-size: 0.7rem;">GIẢM</span>
                                </div>
                                
                                <div class="p-3 flex-grow-1 d-flex flex-column">
                                    <h6 class="fw-bold text-dark mb-1"><?= $c['code'] ?></h6>
                                    <p class="text-muted small mb-2 flex-grow-1">Đơn tối thiểu <?= number_format($c['min_order_cents']) ?>đ</p>
                                    
                                    <div class="d-flex justify-content-between align-items-center mt-auto border-top pt-2">
                                        <small class="text-danger" style="font-size: 0.75rem;">
                                            <i class="far fa-clock me-1"></i>HSD: <?= date('d/m/Y', strtotime($c['ends_at'])) ?>
                                        </small>
                                        <button class="btn btn-sm btn-outline-success rounded-pill fw-bold py-1 px-3 copy-btn" onclick="copyCouponCode('<?= $c['code'] ?>', this)">Copy Mã</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12 text-center py-5">
                        <div class="d-inline-flex align-items-center justify-content-center bg-light rounded-circle mb-3" style="width: 80px; height: 80px;">
                            <i class="fas fa-ticket-alt fs-2 text-muted opacity-50"></i>
                        </div>
                        <h6 class="fw-bold text-dark mb-2">Ví voucher của bạn đang trống</h6>
                        <p class="text-muted small mb-4">Bạn chưa lưu mã giảm giá nào. Hãy săn ưu đãi ngay để mua sắm tiết kiệm hơn!</p>
                        <a href="/MY_WEB/public/offer" class="btn btn-success rounded-pill px-4 py-2 shadow-sm"><i class="fas fa-search me-2"></i> Khám phá Ưu đãi</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="tab-pane fade" id="history" role="tabpanel">
        <div class="voucher-scroll-container">
            <div class="row g-4 mb-2">
                <?php if (!empty($historyCoupons)): ?>
                    <?php foreach ($historyCoupons as $c): ?>
                        <?php 
                            $isExpired = strtotime($c['ends_at']) < time();
                            $isUsedUp = (isset($c['usage_limit']) && $c['usage_limit'] > 0 && isset($c['used_count']) && $c['used_count'] >= $c['usage_limit']);
                            $isUsedByUser = isset($c['is_used']) && $c['is_used'] == 1;

                            $statusText = '';
                            if ($isUsedByUser) $statusText = 'Đã sử dụng';
                            elseif ($isExpired) $statusText = 'Đã hết hạn';
                            elseif ($isUsedUp) $statusText = 'Đã hết lượt';
                        ?>
                        <div class="col-md-6 mb-3" id="saved-coupon-<?= $c['user_coupon_id'] ?>"> 
                            <div class="voucher-ticket d-flex h-100 border rounded-3 overflow-hidden bg-white shadow-sm position-relative voucher-disabled">
                                
                                <div class="btn-delete-voucher" onclick="deleteSavedCoupon(<?= $c['user_coupon_id'] ?>)" title="Xóa voucher này">
                                    <i class="fas fa-times"></i>
                                </div>

                                <div class="text-white p-3 d-flex flex-column align-items-center justify-content-center text-center" style="width: 100px; background: #9e9e9e;">
                                    <span class="fs-5 fw-bold mb-1">
                                        <?= $c['type'] == 'percent' ? $c['value'] . '%' : number_format($c['value']/1000) . 'K' ?>
                                    </span>
                                    <span class="small border border-white px-1 rounded" style="font-size: 0.7rem;">GIẢM</span>
                                </div>
                                
                                <div class="p-3 flex-grow-1 d-flex flex-column">
                                    <h6 class="fw-bold text-dark mb-1 text-decoration-line-through"><?= $c['code'] ?></h6>
                                    <p class="text-muted small mb-2 flex-grow-1">Đơn tối thiểu <?= number_format($c['min_order_cents']) ?>đ</p>
                                    
                                    <div class="d-flex justify-content-between align-items-center mt-auto border-top pt-2">
                                        <small class="text-muted" style="font-size: 0.75rem;">
                                            <i class="far fa-clock me-1"></i>HSD: <?= date('d/m/Y', strtotime($c['ends_at'])) ?>
                                        </small>
                                        <button class="btn btn-sm btn-secondary rounded-pill py-1 px-3" disabled style="font-size: 0.8rem;">
                                            <?= $statusText ?>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12 text-center py-5">
                        <div class="d-inline-flex align-items-center justify-content-center bg-light rounded-circle mb-3" style="width: 80px; height: 80px;">
                            <i class="fas fa-history fs-2 text-muted opacity-50"></i>
                        </div>
                        <h6 class="fw-bold text-dark mb-2">Lịch sử voucher trống</h6>
                        <p class="text-muted small">Những voucher bạn đã sử dụng hoặc hết hạn sẽ nằm ở đây.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<style>
    /* CSS Khung cuộn */
    .voucher-scroll-container { max-height: 480px; overflow-y: auto; overflow-x: hidden; padding-right: 8px; }
    .voucher-scroll-container::-webkit-scrollbar { width: 6px; }
    .voucher-scroll-container::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 10px; }
    .voucher-scroll-container::-webkit-scrollbar-thumb { background: #c1c1c1; border-radius: 10px; }
    
    /* CSS Nút Tab */
    .nav-pills .nav-link { background: #f8f9fa; color: #555; border: 1px solid #eee; }
    .nav-pills .nav-link.active { background: #198754; color: #fff; border-color: #198754; }

    /* Hiệu ứng voucher bị vô hiệu hóa */
    .voucher-disabled { opacity: 0.75; filter: grayscale(80%); }
    .transition-hover { transition: transform 0.2s ease, box-shadow 0.2s ease; }
    .transition-hover:not(.voucher-disabled):hover { transform: translateY(-3px); box-shadow: 0 5px 15px rgba(0,0,0,0.1) !important; }

    /* Nút xóa voucher */
    .btn-delete-voucher {
        position: absolute; top: 6px; right: 6px; width: 24px; height: 24px;
        background: rgba(0, 0, 0, 0.05); border-radius: 50%; display: flex;
        align-items: center; justify-content: center; color: #999; cursor: pointer; z-index: 10; transition: all 0.2s;
    }
    .voucher-disabled .btn-delete-voucher { pointer-events: auto; }
    .btn-delete-voucher:hover { background: #dc3545; color: white; }
</style>

<script>
function copyCouponCode(code, btnElement) {
    navigator.clipboard.writeText(code).then(() => {
        const originalText = btnElement.innerHTML;
        btnElement.innerHTML = '<i class="fas fa-check me-1"></i> Đã copy';
        btnElement.classList.remove('btn-outline-success');
        btnElement.classList.add('btn-success', 'text-white');
        setTimeout(() => {
            btnElement.innerHTML = originalText;
            btnElement.classList.remove('btn-success', 'text-white');
            btnElement.classList.add('btn-outline-success');
        }, 2000);
    }).catch(err => { alert('Không thể copy mã. Bạn vui lòng tự bôi đen mã: ' + code); });
}

// Gọi API Xóa Từng mã (Đã Fix POST JSON)
function deleteSavedCoupon(id) {
    if(confirm('Xóa voucher này khỏi ví?')) {
        fetch(`/MY_WEB/public/account/removeSavedCoupon`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id: id })
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                location.reload(); // Xóa xong tự động F5 để chia lại số lượng 2 Tab
            } else {
                alert('Có lỗi xảy ra, không thể xóa voucher!');
            }
        });
    }
}

// Gọi API Dọn dẹp toàn bộ mã hết hạn (Tab Lịch sử)
function cleanHistoryCoupons() {
    if(confirm('Bạn có chắc chắn muốn dọn sạch tất cả voucher đã hết hạn và đã sử dụng không?')) {
        fetch(`/MY_WEB/public/account/cleanCoupons`, {
            method: 'POST'
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                location.reload(); 
            } else {
                alert('Có lỗi xảy ra, vui lòng thử lại!');
            }
        });
    }
}
</script>