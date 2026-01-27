<?php require_once '../app/Views/client/layouts/header.php'; ?>
<link rel="stylesheet" href="/MY_WEB/public/assets/css/checkout.css">

<style>
    /* CSS Riêng cho trang Success để tạo cảm giác Hóa đơn */
    .invoice-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        overflow: hidden;
        border: 1px solid rgba(0,0,0,0.05);
    }
    .invoice-header {
        background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%);
        padding: 30px;
        text-align: center;
        border-bottom: 1px dashed #a5d6a7;
    }
    .invoice-body {
        padding: 30px;
    }
    .invoice-label {
        font-size: 0.85rem;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 600;
        margin-bottom: 5px;
    }
    .invoice-value {
        font-weight: 600;
        color: #212529;
    }
    .dashed-line {
        border-top: 2px dashed #eee;
        margin: 20px 0;
    }
</style>

<div class="bg-light min-vh-100 pb-5">
    <div class="container py-4">
        
        <div class="step-indicator mb-5">
            <div class="step completed">
                <div class="step-num"><i class="fas fa-check"></i></div> Giỏ hàng
            </div>
            <div class="line"></div>
            <div class="step completed">
                <div class="step-num"><i class="fas fa-check"></i></div> Thanh toán
            </div>
            <div class="line"></div>
            <div class="step active">
                <div class="step-num"><i class="fas fa-flag-checkered"></i></div> Hoàn tất
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="invoice-card">
                    <div class="invoice-header">
                        <div class="mb-3">
                            <div class="d-inline-flex align-items-center justify-content-center bg-success text-white rounded-circle shadow-sm" style="width: 60px; height: 60px;">
                                <i class="fas fa-check fs-2"></i>
                            </div>
                        </div>
                        <h3 class="fw-bold text-success mb-2">Đặt hàng thành công!</h3>
                        <p class="text-muted mb-0">Cảm ơn bạn đã mua sắm tại EcoStore.</p>
                        <p class="text-muted">Mã đơn hàng: <strong class="text-dark">#<?= $order['order_number'] ?></strong></p>
                    </div>

                    <div class="invoice-body">
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <div class="invoice-label"><i class="far fa-calendar-alt me-1"></i> Ngày đặt hàng</div>
                                <div class="invoice-value"><?= date('d/m/Y H:i:s', strtotime($order['created_at'])) ?></div>
                            </div>
                            <div class="col-md-6 text-md-end">
                                <div class="invoice-label"><i class="fas fa-truck-loading me-1"></i> Dự kiến giao hàng</div>
                                <div class="invoice-value text-primary"><?= $expectedDate ?></div>
                            </div>
                        </div>

                        <div class="row mb-4 bg-light p-3 rounded-3 mx-0">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <div class="invoice-label">Người nhận & Địa chỉ</div>
                                <div class="fw-bold text-dark"><?= $order['ship_name'] ?></div>
                                <div class="small text-muted"><?= $order['ship_phone'] ?></div>
                                <div class="small text-secondary mt-1">
                                    <?= $order['address_line'] ?>, <?= $order['city'] ?>, <?= $order['province'] ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="invoice-label">Phương thức thanh toán</div>
                                <div class="fw-bold text-dark">
                                    <?php 
                                        $methodName = [
                                            'cod' => 'Thanh toán khi nhận hàng (COD)',
                                            'banking' => 'Chuyển khoản ngân hàng',
                                            'ewallet' => 'Ví điện tử'
                                        ];
                                        echo $methodName[$order['payment_method']] ?? strtoupper($order['payment_method']);
                                    ?>
                                </div>
                                <div class="mt-2">
                                    <span class="badge bg-warning text-dark border border-warning">
                                        <?= ucfirst($order['payment_status']) ?>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="dashed-line"></div>

                        <h6 class="fw-bold mb-3">Chi tiết đơn hàng</h6>
                        <div class="table-responsive">
                            <table class="table table-borderless align-middle mb-0">
                                <thead class="text-muted small border-bottom">
                                    <tr>
                                        <th>Sản phẩm</th>
                                        <th class="text-center">SL</th>
                                        <th class="text-end">Đơn giá</th>
                                        <th class="text-end">Thành tiền</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($items as $item): ?>
                                    <tr class="border-bottom border-light">
                                        <td class="py-3">
                                            <div class="d-flex align-items-center">
                                                <img src="<?= $item['display_image'] ?>" width="50" height="50" class="rounded border me-3 object-fit-cover">
                                                <div>
                                                    <div class="fw-bold small text-dark"><?= $item['display_name'] ?></div>
                                                    <div class="text-muted small" style="font-size: 0.75rem;">SKU: <?= $item['product_sku'] ?? 'N/A' ?></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">x<?= $item['quantity'] ?></td>
                                        <td class="text-end small"><?= number_format($item['unit_price_cents']) ?> đ</td>
                                        <td class="text-end fw-bold"><?= number_format($item['total_price_cents']) ?> đ</td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="row justify-content-end mt-3">
                            <div class="col-md-5">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Tạm tính:</span>
                                    <span class="fw-bold"><?= number_format($order['subtotal_cents']) ?> đ</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Phí vận chuyển:</span>
                                    <span class="fw-bold"><?= number_format($order['shipping_fee_cents']) ?> đ</span>
                                </div>
                                <?php if($order['tax_cents'] > 0): ?>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Thuế VAT:</span>
                                    <span class="fw-bold"><?= number_format($order['tax_cents']) ?> đ</span>
                                </div>
                                <?php endif; ?>
                                <div class="dashed-line my-2"></div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="fw-bold fs-5 text-dark">Tổng cộng:</span>
                                    <span class="fw-bold fs-4 text-success"><?= number_format($order['total_cents']) ?> đ</span>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="bg-light p-4 text-center border-top">
                        <div class="d-flex justify-content-center gap-3">
                            <a href="/MY_WEB/public/account?page=orders" class="btn btn-outline-secondary rounded-pill px-4 fw-bold">
                                <i class="fas fa-clipboard-list me-2"></i> Quản lý đơn hàng
                            </a>
                            <a href="/MY_WEB/public/product" class="btn btn-success rounded-pill px-4 fw-bold shadow-sm">
                                <i class="fas fa-shopping-bag me-2"></i> Tiếp tục mua sắm
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<?php require_once '../app/Views/client/layouts/footer.php'; ?>