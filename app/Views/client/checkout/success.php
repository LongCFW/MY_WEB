<?php require_once '../app/Views/client/layouts/header.php'; ?>

<div class="container py-5 text-center min-vh-100 d-flex flex-column align-items-center justify-content-center">
    <div class="mb-4">
        <i class="fas fa-check-circle text-success" style="font-size: 80px;"></i>
    </div>
    <h2 class="fw-bold mb-3">Đặt hàng thành công!</h2>
    <p class="text-muted mb-4" style="max-width: 500px;">
        Cảm ơn bạn đã mua sắm tại EcoStore. Mã đơn hàng của bạn là 
        <strong class="text-dark">#<?= isset($order_id) ? $order_id : '...' ?></strong>. 
        Chúng tôi sẽ liên hệ để xác nhận đơn hàng sớm nhất.
    </p>
    
    <div class="d-flex gap-3">
        <a href="/MY_WEB/public/profile/orders" class="btn btn-outline-secondary rounded-pill px-4">Xem đơn hàng</a>
        <a href="/MY_WEB/public/product" class="btn btn-success rounded-pill px-4">Tiếp tục mua sắm</a>
    </div>
</div>

<?php require_once '../app/Views/client/layouts/footer.php'; ?>