<h4 class="fw-bold text-success mb-4 border-bottom pb-3"><i class="fas fa-ticket-alt me-2"></i> Ví Voucher Của Tôi</h4>

<div class="voucher-scroll-container">
    <div class="row g-4 mb-2">
        <?php if (!empty($savedCoupons)): ?>
            <?php foreach ($savedCoupons as $c): ?>
                <?php 
                    // KIỂM TRA TRẠNG THÁI VOUCHER
                    $isExpired = strtotime($c['ends_at']) < time();
                    // Giả định bạn có trường usage_limit và used_count để kiểm tra. Hãy điều chỉnh tên biến cho khớp với DB của bạn nhé!
                    $isUsedUp = (isset($c['usage_limit']) && $c['usage_limit'] > 0 && isset($c['used_count']) && $c['used_count'] >= $c['usage_limit']);
                    
                    $isDisabled = $isExpired || $isUsedUp;
                    $statusText = '';
                    if ($isExpired) $statusText = 'Đã hết hạn';
                    elseif ($isUsedUp) $statusText = 'Đã hết lượt';
                ?>
                <div class="col-md-6 mb-3" id="saved-coupon-<?= $c['id'] ?>"> <div class="voucher-ticket d-flex h-100 border rounded-3 overflow-hidden bg-white shadow-sm position-relative transition-hover <?= $isDisabled ? 'voucher-disabled' : '' ?>">
                        
                        <div class="btn-delete-voucher" onclick="deleteSavedCoupon(<?= $c['id'] ?>)" title="Xóa voucher này">
                            <i class="fas fa-times"></i>
                        </div>

                        <div class="text-white p-3 d-flex flex-column align-items-center justify-content-center text-center" 
                             style="width: 100px; <?= $isDisabled ? 'background: #9e9e9e;' : 'background: linear-gradient(135deg, #43a047 0%, #1de9b6 100%);' ?>">
                            <span class="fs-5 fw-bold mb-1">
                                <?= $c['type'] == 'percent' ? $c['value'] . '%' : number_format($c['value']/1000) . 'K' ?>
                            </span>
                            <span class="small border border-white px-1 rounded" style="font-size: 0.7rem;">GIẢM</span>
                        </div>
                        
                        <div class="p-3 flex-grow-1 d-flex flex-column">
                            <div class="d-flex justify-content-between align-items-start mb-1">
                                <h6 class="fw-bold text-dark mb-0 <?= $isDisabled ? 'text-decoration-line-through' : '' ?>"><?= $c['code'] ?></h6>
                            </div>
                            <p class="text-muted small mb-2 flex-grow-1">Đơn tối thiểu <?= number_format($c['min_order_cents']) ?>đ</p>
                            
                            <div class="d-flex justify-content-between align-items-center mt-auto border-top pt-2">
                                <small class="<?= $isDisabled ? 'text-muted' : 'text-danger' ?>" style="font-size: 0.75rem;">
                                    <i class="far fa-clock me-1"></i>HSD: <?= date('d/m/Y', strtotime($c['ends_at'])) ?>
                                </small>
                                
                                <?php if ($isDisabled): ?>
                                    <button class="btn btn-sm btn-secondary rounded-pill py-1 px-3" disabled style="font-size: 0.8rem;">
                                        <?= $statusText ?>
                                    </button>
                                <?php else: ?>
                                    <button class="btn btn-sm btn-outline-success rounded-pill fw-bold py-1 px-3 copy-btn" onclick="copyCouponCode('<?= $c['code'] ?>', this)">
                                        Copy Mã
                                    </button>
                                <?php endif; ?>
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

<style>
    /* [MỚI] CSS Khung cuộn thông minh (sẽ tự hiện thanh cuộn nếu quá chiều cao) */
    .voucher-scroll-container {
        max-height: 480px; /* Chiều cao này tương đương khoảng 3-4 hàng (6-8 voucher). Bạn có thể chỉnh lại số này cho vừa mắt */
        overflow-y: auto;
        overflow-x: hidden;
        padding-right: 8px; /* Tránh để thanh cuộn đè vào voucher */
    }

    /* Tùy chỉnh thanh cuộn cho đẹp (WebKit) */
    .voucher-scroll-container::-webkit-scrollbar { width: 6px; }
    .voucher-scroll-container::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 10px; }
    .voucher-scroll-container::-webkit-scrollbar-thumb { background: #c1c1c1; border-radius: 10px; }
    .voucher-scroll-container::-webkit-scrollbar-thumb:hover { background: #a8a8a8; }

    /* Hiệu ứng voucher bị vô hiệu hóa (hết hạn/hết lượt) */
    .voucher-disabled {
        opacity: 0.75; /* Làm mờ nhẹ */
        filter: grayscale(80%); /* Biến thành màu xám */
    }
    .voucher-disabled .copy-btn { pointer-events: none; } /* Không cho click khi đã làm mờ */

    /* CSS cho thẻ voucher */
    .transition-hover {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .transition-hover:not(.voucher-disabled):hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1) !important;
    }

    /* Nút xóa voucher */
    .btn-delete-voucher {
        position: absolute;
        top: 6px;
        right: 6px;
        width: 24px;
        height: 24px;
        background: rgba(0, 0, 0, 0.05);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #999;
        cursor: pointer;
        z-index: 10;
        transition: all 0.2s;
    }
    /* Luôn cho phép bấm xóa kể cả khi thẻ voucher bị mờ */
    .voucher-disabled .btn-delete-voucher {
        pointer-events: auto; 
    }
    .btn-delete-voucher:hover {
        background: #dc3545;
        color: white;
    }
</style>

<script>
// Hàm xử lý copy mã mượt mà
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
    }).catch(err => {
        alert('Không thể copy mã. Bạn vui lòng tự bôi đen mã: ' + code);
    });
}

// Hàm xử lý xóa voucher đã lưu
function deleteSavedCoupon(id) {
    if(confirm('Bạn có chắc chắn muốn xóa voucher này khỏi ví không?')) {
        // [QUAN TRỌNG]: Gửi request AJAX xuống backend để xóa thực sự trong DB
        // Bạn cần thay đổi '/MY_WEB/public/api/remove_coupon' thành route thực tế của bạn
        fetch(`/MY_WEB/public/account/remove-saved-coupon?id=${id}`, {
            method: 'POST', // hoặc GET tùy thiết kế hệ thống của bạn
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                // Xóa phần tử HTML để tạo hiệu ứng biến mất không cần load lại trang
                const voucherCard = document.getElementById('saved-coupon-' + id);
                voucherCard.style.transition = "opacity 0.3s";
                voucherCard.style.opacity = 0;
                setTimeout(() => voucherCard.remove(), 300);
            } else {
                alert('Có lỗi xảy ra, không thể xóa voucher!');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            // Dòng dưới đây dùng để TEST UI khi bạn chưa có API backend. Bạn hãy xóa dòng này khi đưa vào code chạy thật nhé!
            document.getElementById('saved-coupon-' + id).remove(); 
        });
    }
}
</script>