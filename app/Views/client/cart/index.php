<?php require_once '../app/Views/client/layouts/header.php'; ?>

<div class="bg-light min-vh-100 pb-5">
    <div class="container py-4">
        <div class="d-flex justify-content-center mb-5">
            <div class="d-flex align-items-center text-success fw-bold">
                <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">1</div>
                <span class="ms-2">Giỏ hàng</span>
            </div>
            <div class="bg-secondary mx-3" style="width: 50px; height: 2px;"></div>
            <div class="d-flex align-items-center text-muted">
                <div class="bg-light border text-muted rounded-circle d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">2</div>
                <span class="ms-2">Thanh toán</span>
            </div>
        </div>

        <?php if (empty($cart)): ?>
            <div class="text-center py-5">
                <i class="fas fa-shopping-cart fa-4x text-muted mb-3"></i>
                <h3 class="fw-bold">Giỏ hàng đang trống</h3>
                <p class="text-muted mb-4">Hãy thêm vài món đồ xanh vào giỏ nhé!</p>
                <a href="/MY_WEB/public/product" class="btn btn-success rounded-pill px-5 shadow fw-bold">Mua Sắm Ngay</a>
            </div>
        <?php else: ?>
            <div class="row">
                <div class="col-lg-8">
                    <?php foreach ($cart as $item): ?>
                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-body p-3">
                            <div class="row align-items-center">
                                <div class="col-3 col-md-2">
                                    <img src="/MY_WEB/public/<?= $item['image'] ?>" class="img-fluid rounded border">
                                </div>
                                <div class="col-9 col-md-10">
                                    <div class="d-flex justify-content-between">
                                        <h6 class="fw-bold mb-1"><a href="#" class="text-dark text-decoration-none"><?= $item['name'] ?></a></h6>
                                        <a href="/MY_WEB/public/cart/remove/<?= $item['id'] ?>" class="text-danger btn btn-light rounded-circle p-2" onclick="return confirm('Xóa?')"><i class="fas fa-trash"></i></a>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mt-2">
                                        <span class="text-success fw-bold"><?= number_format($item['price']) ?> đ</span>
                                        <div class="input-group input-group-sm border rounded-pill overflow-hidden" style="width: 100px;">
                                            <button class="btn btn-light border-0 px-2"><i class="fas fa-minus small"></i></button>
                                            <input type="text" class="form-control border-0 text-center bg-white p-0 fw-bold" value="<?= $item['quantity'] ?>" readonly>
                                            <button class="btn btn-light border-0 px-2"><i class="fas fa-plus small"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-4">Cộng giỏ hàng</h5>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Tạm tính</span>
                                <span class="fw-bold"><?= number_format($total) ?> đ</span>
                            </div>
                            <div class="d-flex justify-content-between mb-4 pb-3 border-bottom">
                                <span class="text-muted">Giảm giá</span>
                                <span class="text-success">- 0 đ</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <span class="fw-bold fs-5">Tổng tiền</span>
                                <span class="fw-bold fs-4 text-success"><?= number_format($total) ?> đ</span>
                            </div>
                            <a href="/MY_WEB/public/checkout" class="btn btn-success w-100 rounded-pill fw-bold py-3 text-uppercase shadow-sm">
                                Thanh Toán <i class="fas fa-arrow-right ms-2"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once '../app/Views/client/layouts/footer.php'; ?>