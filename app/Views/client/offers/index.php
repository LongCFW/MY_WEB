<?php require_once '../app/Views/client/layouts/header.php'; ?>

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
                        <?php foreach ($vouchers as $v): ?>
                        <div class="col-md-6 col-lg-4">
                            <div class="voucher-ticket d-flex h-100 border rounded-3 overflow-hidden bg-white shadow-sm position-relative">
                                <div class="voucher-left bg-success text-white p-3 d-flex flex-column align-items-center justify-content-center" style="width: 100px; background: linear-gradient(135deg, #43a047 0%, #1de9b6 100%);">
                                    <i class="fas fa-ticket-alt fs-3 mb-1"></i>
                                    <span class="small fw-bold border border-white px-1 rounded"><?= strtoupper($v['type']) ?></span>
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
                                    <button class="btn btn-outline-success btn-sm rounded-pill w-100 fw-bold" onclick="alert('Đã sao chép: <?= $v['code'] ?>')">
                                        <i class="fas fa-copy me-1"></i> Sao chép
                                    </button>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                </div>
        </div>
    </div>
</div>

<?php require_once '../app/Views/client/layouts/footer.php'; ?>