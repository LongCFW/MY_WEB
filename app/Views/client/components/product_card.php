<div class="card h-100 product-card-wrapper border-0">
    <div class="product-img-container">
        <?php $imgUrl = !empty($p['image_url']) ? "/MY_WEB/public/" . $p['image_url'] : "https://placehold.co/300x300?text=No+Image"; ?>
        <img src="<?= $imgUrl ?>" alt="<?= $p['name'] ?>">
        
        <div class="card-actions-overlay position-absolute top-0 start-0 w-100 h-100 d-flex flex-column align-items-center justify-content-center gap-2">
            <a href="/MY_WEB/public/product/detail/<?= $p['id'] ?>" class="btn action-btn rounded-pill px-3 shadow-sm btn-sm" style="width: 130px;">
                <i class="fas fa-eye me-2"></i>Chi tiết
            </a>
            <button type="button" class="btn action-btn rounded-pill px-3 shadow-sm btn-sm btn-quick-view" style="width: 130px;"
                    data-id="<?= $p['id'] ?>"
                    data-name="<?= $p['name'] ?>"
                    data-price="<?= $p['price_cents'] ?>"
                    data-image="<?= $imgUrl ?>"
                    data-cat="<?= $p['category_name'] ?>"
                    data-desc="<?= htmlspecialchars($p['short_description'] ?? '') ?>">
                <i class="fas fa-bolt me-2"></i>Xem nhanh
            </button>
        </div>
    </div>

    <div class="card-body d-flex flex-column">
        <div class="product-category"><?= $p['category_name'] ?? 'Sản phẩm' ?></div>
        <h6 class="product-title mb-2">
            <a href="/MY_WEB/public/product/detail/<?= $p['id'] ?>"><?= $p['name'] ?></a>
        </h6>
        <div class="mt-auto d-flex justify-content-between align-items-center">
            <div class="product-price"><?= number_format($p['price_cents']) ?> đ</div>
            
            <button type="button" 
                    class="btn-add-cart-mini" 
                    title="Thêm vào giỏ" 
                    onclick="addToCartGlobal(<?= $p['id'] ?>, 1)">
                <i class="fas fa-plus"></i>
            </button>
        </div>
    </div>
</div>