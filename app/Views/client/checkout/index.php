<?php require_once '../app/Views/client/layouts/header.php'; ?>
<link rel="stylesheet" href="/MY_WEB/public/assets/css/checkout.css">

<div class="bg-light min-vh-100 pb-5">
    <div class="container py-4">
        <a href="/MY_WEB/public/cart" class="text-decoration-none text-muted mb-4 d-inline-block">
            <i class="fas fa-arrow-left me-1"></i> Quay lại giỏ hàng
        </a>

        <div class="step-indicator">
            <div class="step completed">
                <div class="step-num"><i class="fas fa-check"></i></div> Giỏ hàng
            </div>
            <div class="line"></div>
            <div class="step active">
                <div class="step-num">2</div> Thanh toán
            </div>
            <div class="line"></div>
            <div class="step">
                <div class="step-num">3</div> Hoàn tất
            </div>
        </div>

        <form action="/MY_WEB/public/checkout/process" method="POST">
            <div class="row">
                <div class="col-lg-8">
                    
                    <div class="card border-0 shadow-sm rounded-4 mb-4">
                        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                            <h6 class="fw-bold m-0 text-success"><div class="badge bg-success rounded-circle me-2">1</div> Thông tin giao hàng</h6>
                            
                            <button type="button" class="btn btn-outline-success btn-sm rounded-pill" data-bs-toggle="modal" data-bs-target="#addAddressModal">
                                <i class="fas fa-plus"></i> Nhập địa chỉ khác
                            </button>
                        </div>
                        <div class="card-body">
                            <?php if(empty($addresses)): ?>
                                <div class="text-center py-3">
                                    <p class="text-muted mb-3">Bạn chưa có địa chỉ nhận hàng nào.</p>
                                    <button type="button" class="btn btn-success rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#addAddressModal">
                                        Thêm địa chỉ mới ngay
                                    </button>
                                </div>
                            <?php else: ?>
                                <?php foreach($addresses as $addr): ?>
                                    <label class="selection-card d-block">
                                        <input type="radio" name="address_id" value="<?= $addr['id'] ?>" <?= $addr['is_default'] ? 'checked' : '' ?>>
                                        <div class="d-flex justify-content-between pe-5">
                                            <div>
                                                <strong><?= $addr['full_name'] ?></strong> 
                                                <span class="text-muted mx-2">|</span> 
                                                <span class="text-muted"><?= $addr['phone'] ?></span>
                                                
                                                <div class="mt-1">
                                                    <?php if($addr['is_default']): ?><span class="address-tag tag-default">Mặc định</span><?php endif; ?>
                                                    <span class="address-tag tag-home"><?= $addr['type'] ?? 'Nhà riêng' ?></span>
                                                </div>
                                                <p class="mb-0 mt-2 small text-secondary">
                                                    <?= $addr['address_line'] ?>, <?= $addr['city'] ?>, <?= $addr['country'] ?>
                                                </p>
                                            </div>
                                        </div>
                                        <span class="custom-radio-circle"></span>
                                    </label>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-header bg-white py-3">
                            <h6 class="fw-bold m-0 text-success"><div class="badge bg-success rounded-circle me-2">2</div> Phương thức thanh toán</h6>
                        </div>
                        <div class="card-body">
                            <label class="selection-card d-flex align-items-center">
                                <input type="radio" name="payment_method" value="cod" checked>
                                <i class="fas fa-money-bill-wave text-success fs-4 me-3"></i>
                                <div>
                                    <h6 class="mb-0 fw-bold">Thanh toán khi nhận hàng (COD)</h6>
                                    <small class="text-muted">Thanh toán tiền mặt cho shipper</small>
                                </div>
                                <span class="custom-radio-circle"></span>
                            </label>

                            <label class="selection-card d-flex align-items-center">
                                <input type="radio" name="payment_method" value="banking">
                                <i class="fas fa-university text-primary fs-4 me-3"></i>
                                <div>
                                    <h6 class="mb-0 fw-bold">Chuyển khoản ngân hàng</h6>
                                    <small class="text-muted">VietQR, Internet Banking</small>
                                </div>
                                <span class="custom-radio-circle"></span>
                            </label>

                            <label class="selection-card d-flex align-items-center">
                                <input type="radio" name="payment_method" value="ewallet">
                                <i class="fas fa-wallet text-danger fs-4 me-3"></i>
                                <div>
                                    <h6 class="mb-0 fw-bold">Ví điện tử</h6>
                                    <small class="text-muted">Momo, ZaloPay, ShopeePay</small>
                                </div>
                                <span class="custom-radio-circle"></span>
                            </label>
                        </div>
                    </div>

                </div>

                <div class="col-lg-4">
                    <div class="card border-0 checkout-summary-card p-3">
                        <h5 class="fw-bold mb-3">Đơn hàng của bạn (<?= count($cart) ?>)</h5>
                        
                        <div class="checkout-items mb-3" style="max-height: 300px; overflow-y: auto;">
                            <?php foreach($cart as $item): ?>
                            <div class="d-flex mb-3 position-relative">
                                <div class="position-relative me-3">
                                    <?php $img = !empty($item['image']) ? "/MY_WEB/public/" . $item['image'] : "https://placehold.co/60"; ?>
                                    <img src="<?= $img ?>" width="60" height="60" class="rounded border" style="object-fit: cover;">
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-secondary border border-light">
                                        <?= $item['quantity'] ?>
                                    </span>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-0 small text-truncate" style="max-width: 180px;"><?= $item['name'] ?></h6>
                                    <small class="text-muted"><?= number_format($item['price']) ?> đ</small>
                                </div>
                                <div class="fw-bold text-end">
                                    <?= number_format($item['price'] * $item['quantity']) ?> đ
                                </div>
                            </div>
                            <?php endforeach; ?>
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

<div class="modal fade" id="addAddressModal" tabindex="-1" aria-labelledby="addAddressModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title fw-bold" id="addAddressModalLabel">Thêm địa chỉ giao hàng mới</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form action="/MY_WEB/public/shipping-address/store" method="POST">
                    <div class="form-group mb-3">
                        <label class="fw-bold small mb-1">Họ tên người nhận <span class="text-danger">*</span></label>
                        <input type="text" name="full_name" class="form-control rounded-3" required placeholder="Ví dụ: Nguyễn Văn A">
                    </div>
                    <div class="form-group mb-3">
                        <label class="fw-bold small mb-1">Số điện thoại <span class="text-danger">*</span></label>
                        <input type="text" name="phone" class="form-control rounded-3" required placeholder="0909xxxxxx">
                    </div>
                    <div class="form-group mb-3">
                        <label class="fw-bold small mb-1">Địa chỉ chi tiết <span class="text-danger">*</span></label>
                        <input type="text" name="address_line" class="form-control rounded-3" required placeholder="Số nhà, tên đường, phường/xã...">
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="fw-bold small mb-1">Tỉnh/Thành phố <span class="text-danger">*</span></label>
                            <input type="text" name="city" class="form-control rounded-3" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="fw-bold small mb-1">Quận/Huyện</label>
                            <input type="text" name="province" class="form-control rounded-3">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="fw-bold small mb-1">Mã bưu điện</label>
                            <input type="text" name="postal_code" class="form-control rounded-3">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="fw-bold small mb-1">Quốc gia</label>
                            <input type="text" name="country" class="form-control rounded-3" value="Vietnam" readonly>
                        </div>
                    </div>
                    
                    <div class="form-check mb-4 p-2 bg-light rounded border">
                        <div class="ms-2">
                            <input type="checkbox" name="is_default" class="form-check-input" id="defaultAddr">
                            <label class="form-check-label user-select-none" for="defaultAddr">Đặt làm địa chỉ mặc định</label>
                        </div>
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-success rounded-pill fw-bold py-2">Lưu địa chỉ</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once '../app/Views/client/layouts/footer.php'; ?>