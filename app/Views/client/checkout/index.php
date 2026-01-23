<?php require_once '../app/Views/client/layouts/header.php'; ?>
<link rel="stylesheet" href="/MY_WEB/public/assets/css/checkout.css">

<?php 
    $userName = $_SESSION['user_name'] ?? 'Khách hàng';
    $userEmail = $_SESSION['user_email'] ?? '';
    
    // Logic tìm địa chỉ hiển thị (Mặc định)
    $selectedAddress = null;
    if (!empty($addresses)) {
        foreach($addresses as $addr) {
            if ($addr['is_default']) $selectedAddress = $addr;
        }
        if (!$selectedAddress) $selectedAddress = $addresses[0];
    }
?>

<div class="bg-light min-vh-100 pb-5">
    <div class="container py-4">
        <a href="/MY_WEB/public/cart" class="text-decoration-none text-muted mb-4 d-inline-block">
            <i class="fas fa-arrow-left me-1"></i> Quay lại giỏ hàng
        </a>

        <div class="step-indicator">
            <div class="step completed"><div class="step-num"><i class="fas fa-check"></i></div> Giỏ hàng</div>
            <div class="line"></div>
            <div class="step active"><div class="step-num">2</div> Thanh toán</div>
            <div class="line"></div>
            <div class="step"><div class="step-num">3</div> Hoàn tất</div>
        </div>

        <form action="/MY_WEB/public/checkout/process" method="POST" id="checkoutForm">
            <div class="row">
                
                <div class="col-lg-8">
                    
                    <div class="card border-0 shadow-sm rounded-4 mb-3">
                        <div class="card-header bg-white py-3">
                            <h6 class="fw-bold m-0 text-success"><i class="fas fa-user-circle me-2"></i> Thông tin khách hàng</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <small class="text-muted">Họ và tên</small>
                                    <div class="fw-bold"><?= $userName ?></div>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <small class="text-muted">Email</small>
                                    <div class="fw-bold"><?= $userEmail ?></div>
                                </div>
                                <div class="col-12 mt-2">
                                    <small class="text-muted fst-italic text-small" style="font-size: 0.85rem;">* Thông tin này được lấy từ tài khoản của bạn.</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm rounded-4 mb-4">
                        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                            <h6 class="fw-bold m-0 text-success"><i class="fas fa-map-marker-alt me-2"></i> Địa chỉ nhận hàng</h6>
                            <button type="button" class="btn btn-outline-success btn-sm rounded-pill" data-bs-toggle="modal" data-bs-target="#addressModal">
                                Thay đổi / Thêm mới
                            </button>
                        </div>
                        <div class="card-body">
                            <?php if ($selectedAddress): ?>
                                <input type="hidden" name="address_id" value="<?= $selectedAddress['id'] ?>">
                                <div class="d-flex align-items-start">
                                    <div class="me-3 mt-1"><i class="fas fa-home text-muted fs-4"></i></div>
                                    <div>
                                        <div class="fw-bold">
                                            <?= $selectedAddress['address_line'] ?>
                                            <?php if($selectedAddress['is_default']): ?>
                                                <span class="badge bg-success ms-2" style="font-size: 0.7em">Mặc định</span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="text-muted small">
                                            <?= $selectedAddress['city'] ?>, <?= $selectedAddress['province'] ?>, <?= $selectedAddress['country'] ?>
                                        </div>
                                        <div class="text-muted small mt-1">Người nhận: <strong><?= $selectedAddress['full_name'] ?></strong> - <?= $selectedAddress['phone'] ?></div>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="text-center py-3 text-muted">
                                    Chưa có địa chỉ. <br>
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#addressModal" class="fw-bold text-success">Thêm ngay</a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm rounded-4 mb-4">
                        <div class="card-header bg-white py-3">
                            <h6 class="fw-bold m-0 text-success"><i class="fas fa-wallet me-2"></i> Phương thức thanh toán</h6>
                        </div>
                        <div class="card-body">
                            <label class="selection-card d-flex align-items-center mb-2 cursor-pointer p-3 border rounded">
                                <input type="radio" name="payment_method" value="cod" checked class="form-check-input me-3" style="width: 1.2em; height: 1.2em;">
                                <i class="fas fa-money-bill-wave text-success fs-3 me-3"></i>
                                <div>
                                    <h6 class="mb-0 fw-bold">Thanh toán khi nhận hàng (COD)</h6>
                                    <small class="text-muted">Bạn chỉ phải thanh toán khi nhận được hàng</small>
                                </div>
                            </label>

                            <label class="selection-card d-flex align-items-center mb-2 cursor-pointer p-3 border rounded">
                                <input type="radio" name="payment_method" value="banking" class="form-check-input me-3" style="width: 1.2em; height: 1.2em;">
                                <i class="fas fa-university text-primary fs-3 me-3"></i>
                                <div>
                                    <h6 class="mb-0 fw-bold">Chuyển khoản ngân hàng</h6>
                                    <small class="text-muted">Quét mã QR hoặc chuyển khoản 24/7</small>
                                </div>
                            </label>

                            <label class="selection-card d-flex align-items-center cursor-pointer p-3 border rounded">
                                <input type="radio" name="payment_method" value="ewallet" class="form-check-input me-3" style="width: 1.2em; height: 1.2em;">
                                <i class="fas fa-qrcode text-danger fs-3 me-3"></i>
                                <div>
                                    <h6 class="mb-0 fw-bold">Ví điện tử</h6>
                                    <small class="text-muted">Momo, ZaloPay, ShopeePay</small>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card border-0 checkout-summary-card p-3 shadow-sm rounded-4 sticky-top" style="top: 100px;">
                        <h5 class="fw-bold mb-3">Đơn hàng (<?= count($cart) ?>)</h5>
                        
                        <div class="checkout-items mb-3 pe-2" style="max-height: 350px; overflow-y: auto;">
                            <?php foreach($cart as $item): ?>
                            <div class="d-flex mb-3 position-relative pb-3 border-bottom border-light">
                                <div class="position-relative me-3">
                                    <?php $img = !empty($item['image']) ? "/MY_WEB/public/" . $item['image'] : "https://placehold.co/60"; ?>
                                    <img src="<?= $img ?>" width="60" height="60" class="rounded border" style="object-fit: cover;">
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-secondary border border-light">
                                        <?= $item['quantity'] ?>
                                    </span>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1 small text-truncate fw-bold" style="max-width: 180px;"><?= $item['name'] ?></h6>
                                    <div class="text-muted small" style="font-size: 0.8rem;">Phân loại: Mặc định</div>
                                </div>
                                <div class="fw-bold text-end text-success">
                                    <?= number_format($item['price'] * $item['quantity']) ?> đ
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="input-group mb-3">
                            <input type="text" class="form-control" placeholder="Mã giảm giá">
                            <button class="btn btn-outline-success" type="button">Áp dụng</button>
                        </div>

                        <div class="border-top pt-3">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Tạm tính</span>
                                <span class="fw-bold"><?= number_format($subtotal) ?> đ</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Phí vận chuyển</span>
                                <span class="fw-bold"><?= number_format($shipping_fee) ?> đ</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Giảm giá</span>
                                <span class="fw-bold text-success">- 0 đ</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <span class="fw-bold fs-5">Tổng cộng</span>
                                <div>
                                    <span class="fw-bold fs-4 text-success"><?= number_format($total) ?> đ</span>
                                    <div class="small text-muted text-end">(Đã bao gồm VAT)</div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-success w-100 rounded-pill py-3 fw-bold shadow text-uppercase">
                                Đặt Hàng Ngay
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="addressModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold">Chọn địa chỉ giao hàng</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body pt-2">
                
                <ul class="nav nav-tabs nav-fill mb-3" id="addrTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active fw-bold" id="list-tab" data-bs-toggle="tab" data-bs-target="#list-pane" type="button">Địa chỉ của tôi</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link fw-bold" id="new-tab" data-bs-toggle="tab" data-bs-target="#new-pane" type="button">Thêm địa chỉ mới</button>
                    </li>
                </ul>

                <div class="tab-content">
                    <div class="tab-pane fade show active" id="list-pane" role="tabpanel">
                        <?php if(empty($addresses)): ?>
                            <div class="text-center py-4">
                                <p class="text-muted">Bạn chưa có địa chỉ nào.</p>
                                <button class="btn btn-sm btn-success rounded-pill" onclick="document.getElementById('new-tab').click()">Thêm mới ngay</button>
                            </div>
                        <?php else: ?>
                            <div class="list-group">
                                <?php foreach($addresses as $addr): ?>
                                    <label class="list-group-item d-flex gap-3 align-items-center cursor-pointer list-group-item-action">
                                        <input class="form-check-input flex-shrink-0" type="radio" name="modal_addr_select" value="<?= $addr['id'] ?>" 
                                            <?= ($selectedAddress && $selectedAddress['id'] == $addr['id']) ? 'checked' : '' ?>
                                            style="font-size: 1.2em;">
                                        <div class="flex-grow-1">
                                            <div class="d-flex align-items-center">
                                                <span class="fw-bold me-2"><?= $addr['full_name'] ?></span>
                                                <span class="text-muted small border-start ps-2"><?= $addr['phone'] ?></span>
                                                <?php if($addr['is_default']): ?><span class="badge bg-success ms-auto">Mặc định</span><?php endif; ?>
                                            </div>
                                            <div class="text-muted small mt-1">
                                                <?= $addr['address_line'] ?>, <?= $addr['city'] ?>, <?= $addr['province'] ?>
                                            </div>
                                        </div>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                            <div class="mt-3 text-end">
                                <button type="button" class="btn btn-primary rounded-pill px-4" onclick="confirmChangeAddress()">Xác nhận</button>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="tab-pane fade" id="new-pane" role="tabpanel">
                        <form id="addNewAddrForm">
                            <div class="alert alert-info small py-2">
                                <i class="fas fa-info-circle me-1"></i> Tên và SĐT sẽ được lấy tự động từ tài khoản.
                            </div>
                            <div class="form-group mb-3">
                                <label class="small fw-bold">Địa chỉ chi tiết <span class="text-danger">*</span></label>
                                <input type="text" name="address_line" class="form-control" placeholder="Số nhà, tên đường..." required>
                            </div>
                            <div class="row">
                                <div class="col-6 mb-3">
                                    <label class="small fw-bold">Thành phố <span class="text-danger">*</span></label>
                                    <input type="text" name="city" class="form-control" required>
                                </div>
                                <div class="col-6 mb-3">
                                    <label class="small fw-bold">Quận/Huyện</label>
                                    <input type="text" name="province" class="form-control">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6 mb-3">
                                    <label class="small fw-bold">Mã bưu điện</label>
                                    <input type="text" name="postal_code" class="form-control">
                                </div>
                                <div class="col-6 mb-3">
                                    <label class="small fw-bold">Quốc gia</label>
                                    <input type="text" name="country" class="form-control" value="Vietnam" readonly>
                                </div>
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" name="is_default" id="modalIsDefault">
                                <label class="form-check-label small" for="modalIsDefault">Đặt làm địa chỉ mặc định</label>
                            </div>
                            <div class="d-grid">
                                <button type="button" class="btn btn-success rounded-pill" onclick="submitNewAddress()">Lưu địa chỉ</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Hàm xử lý chọn địa chỉ từ Modal (Giải quyết vấn đề 2)
    function confirmChangeAddress() {
        const selected = document.querySelector('input[name="modal_addr_select"]:checked');
        if (!selected) {
            alert("Vui lòng chọn một địa chỉ!");
            return;
        }

        const addressId = selected.value;
        const confirmMsg = "Bạn có muốn đặt địa chỉ này làm địa chỉ giao hàng mặc định cho các đơn hàng sau không?";
        
        if (confirm(confirmMsg)) {
            // Gọi AJAX Set Default
            fetch(`/MY_WEB/public/ShippingAddress/setDefault/${addressId}`, {
                method: 'GET', // Hoặc POST tùy router
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(res => res.json())
            .then(data => {
                if(data.status === 'success') {
                    // Hiện Toast (nếu có function showToast)
                    if(typeof showToast === 'function') showToast(data.message);
                    else alert(data.message);
                    
                    // Reload để cập nhật UI
                    setTimeout(() => location.reload(), 500); 
                } else {
                    alert(data.message);
                }
            })
            .catch(err => console.error(err));
        } else {
            // Nếu khách không muốn set default vĩnh viễn, ta chỉ reload lại trang
            // Tuy nhiên, vì code controller Checkout lấy địa chỉ Default, 
            // nên bắt buộc phải set default mới hiển thị được ở màn hình chính.
            // Vì vậy ở đây ta vẫn phải set default để UI cập nhật theo logic hiện tại.
            alert("Hệ thống sẽ cập nhật địa chỉ này cho đơn hàng hiện tại.");
             fetch(`/MY_WEB/public/ShippingAddress/setDefault/${addressId}`, {
                method: 'GET',
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            }).then(() => location.reload());
        }
    }

    // Hàm submit form thêm mới (Giữ nguyên logic cũ)
    function submitNewAddress() {
        const form = document.getElementById('addNewAddrForm');
        const formData = new FormData(form);

        fetch('/MY_WEB/public/ShippingAddress/store', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if(data.status === 'success') {
                alert('Đã thêm địa chỉ mới!');
                location.reload();
            } else {
                alert(data.message);
            }
        })
        .catch(err => console.error(err));
    }
</script>

<?php require_once '../app/Views/client/layouts/footer.php'; ?>