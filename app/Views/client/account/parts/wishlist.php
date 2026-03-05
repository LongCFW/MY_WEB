<div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
    <h4 class="fw-bold text-danger m-0">
        <i class="fas fa-heart me-2"></i> Sổ tay yêu thích
    </h4>
</div>

<?php if (empty($wishlistItems)): ?>
    <div class="text-center py-5 bg-light rounded-4">
        <i class="far fa-heart text-muted mb-3" style="font-size: 4rem; opacity: 0.5;"></i>
        <p class="text-muted fw-bold">Danh sách yêu thích đang trống.</p>
        <a href="/MY_WEB/public/product" class="btn btn-success rounded-pill px-4 fw-bold shadow-sm">
            Khám phá sản phẩm ngay
        </a>
    </div>
<?php else: ?>
    <div class="eco-scroll-container pb-2">
        <div class="wishlist-container pe-1">
            <?php foreach ($wishlistItems as $item): ?>
                <div class="card border-0 shadow-sm rounded-4 mb-3 overflow-hidden position-relative group-hover" id="wishlist-item-<?= $item['variant_id'] ?>">
                    <div class="row g-0 align-items-center">
                        
                        <div class="col-4 col-md-3 col-lg-2 bg-light d-flex align-items-center justify-content-center" style="min-height: 120px;">
                            <?php $img = !empty($item['image_url']) ? "/MY_WEB/public/" . $item['image_url'] : "https://placehold.co/150"; ?>
                            <a href="/MY_WEB/public/product/detail/<?= $item['product_id'] ?>">
                                <img src="<?= $img ?>" class="img-fluid rounded p-2" style="max-height: 120px; object-fit: contain;" alt="<?= htmlspecialchars($item['product_name']) ?>">
                            </a>
                        </div>

                        <div class="col-8 col-md-9 col-lg-10">
                            <div class="card-body py-2 pe-5"> 
                                <button class="btn btn-sm btn-light rounded-circle position-absolute top-0 end-0 m-2 text-secondary hover-danger shadow-sm" 
                                        style="width: 30px; height: 30px;"
                                        onclick="if(confirm('Xóa khỏi yêu thích?')) toggleWishlist(this, null, <?= $item['variant_id'] ?>, true)"
                                        title="Xóa bỏ">
                                    <i class="fas fa-times"></i>
                                </button>

                                <div class="row align-items-center">
                                    <div class="col-md-7 mb-2 mb-md-0">
                                        <h6 class="card-title mb-1 fw-bold">
                                            <a href="/MY_WEB/public/product/detail/<?= $item['product_id'] ?>" class="text-decoration-none text-dark">
                                                <?= htmlspecialchars($item['product_name']) ?>
                                            </a>
                                        </h6>
                                        
                                        <?php $price = $item['variant_price'] ?? $item['product_price'] ?? 0; ?>
                                        <div class="text-success fw-bold fs-5">
                                            <?= number_format($price) ?> đ
                                        </div>
                                        <small class="text-muted">Biến thể ID: #<?= $item['variant_id'] ?></small>
                                    </div>

                                    <div class="col-md-5 text-md-end">
                                        <button type="button" class="btn btn-outline-success rounded-pill fw-bold px-4 btn-sm-block" 
                                                onclick="addToCartGlobal(<?= $item['product_id'] ?>, 1)">
                                            <i class="fas fa-cart-plus me-1"></i> Thêm vào giỏ
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>

<style>
    .eco-scroll-container {
        max-height: 480px; 
        overflow-y: auto;
        overflow-x: hidden;
        padding-right: 8px; 
    }
    .eco-scroll-container::-webkit-scrollbar { width: 6px; }
    .eco-scroll-container::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 10px; }
    .eco-scroll-container::-webkit-scrollbar-thumb { background: #c1c1c1; border-radius: 10px; }
    .eco-scroll-container::-webkit-scrollbar-thumb:hover { background: #a8a8a8; }

    .hover-danger:hover {
        background-color: #dc3545 !important;
        color: white !important;
    }
    @media (max-width: 768px) {
        .btn-sm-block {
            width: 100%;
            margin-top: 10px;
        }
    }
</style>