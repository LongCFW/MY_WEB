<?php
// Logic kiểm tra trạng thái Like
$likedIds = $likedIds ?? [];
$isLiked = in_array($p['id'], $likedIds);
$imgUrl = !empty($p['image_url']) ? "/MY_WEB/public/" . $p['image_url'] : "https://placehold.co/300x300?text=No+Image";

// --- KIỂM TRA TRẠNG THÁI NGỪNG KINH DOANH ---
$isActive = isset($p['is_active']) ? $p['is_active'] == 1 : true;

// Kiểm tra hết hàng | total_stock lấy từ hàm getFilteredProducts trong Model
$totalStock = isset($p['total_stock']) ? (int)$p['total_stock'] : 0;
$isOutOfStock = ($totalStock <= 0);

// --- LOGIC KIỂM TRA NHIỀU BIẾN THỂ (KHOẢNG GIÁ) ---
$minPrice = $p['price_cents'] ?? 0;
$hasMultiplePrices = isset($p['max_price']) && ($p['max_price'] > $minPrice); 
?>

<div class="card h-100 product-card-wrapper border-0 shadow-sm rounded-4 overflow-hidden group-hover" 
     style="<?= !$isActive ? 'filter: grayscale(100%); opacity: 0.7; pointer-events: none;' : '' ?>">
    <div class="product-img-container position-relative bg-light" style="padding-top: 100%;"> 
        <img src="<?= $imgUrl ?>" alt="<?= htmlspecialchars($p['name']) ?>" 
             class="position-absolute top-0 start-0 w-100 h-100 object-fit-contain p-3 transition-transform"
             style="<?= $isOutOfStock || !$isActive ? 'filter: grayscale(100%); opacity: 0.8;' : '' ?>">

        <a href="<?= $isActive ? '/MY_WEB/public/product/detail/' . $p['id'] : '#' ?>" class="stretched-link z-1"></a>

        <?php if(!$isActive): ?>
            <div class="position-absolute top-50 start-50 translate-middle z-3 w-100 text-center">
                <span class="badge bg-secondary bg-opacity-90 fs-6 py-2 px-3 text-uppercase shadow-sm w-75">
                    Ngừng kinh doanh
                </span>
            </div>
        <?php elseif($isOutOfStock): ?>
            <div class="position-absolute top-50 start-50 translate-middle z-3 w-100 text-center">
                <span class="badge bg-dark bg-opacity-75 fs-6 py-2 px-3 text-uppercase shadow-sm">
                    Hết hàng
                </span>
            </div>
        <?php endif; ?>

        <?php if($isActive): ?>
        <button type="button" 
                class="btn btn-light rounded-circle position-absolute top-0 end-0 m-3 shadow-sm p-0 d-flex align-items-center justify-content-center btn-wishlist z-3"
                style="width: 35px; height: 35px; transition: all 0.3s ease; position: relative; z-index: 10;"
                onclick="toggleWishlist(this, <?= $p['id'] ?>)"
                title="<?= $isLiked ? 'Bỏ yêu thích' : 'Thêm vào yêu thích' ?>">
            <i class="<?= $isLiked ? 'fas text-danger' : 'far' ?> fa-heart fs-6"></i>
        </button>
        <?php endif; ?>

        <?php if($isActive): ?>
        <div class="card-actions-overlay position-absolute top-0 start-0 w-100 h-100 d-flex flex-column align-items-center justify-content-center gap-2 z-2 opacity-0 transition-opacity">
            <a href="/MY_WEB/public/product/detail/<?= $p['id'] ?>" class="btn btn-light rounded-pill px-3 shadow-sm btn-sm fw-bold action-btn position-relative z-3">
                <i class="fas fa-eye me-1"></i> Chi tiết
            </a>
            
            <?php if(!$isOutOfStock): ?>
            <button type="button" class="btn btn-light rounded-pill px-3 shadow-sm btn-sm fw-bold btn-quick-view action-btn position-relative z-3"
                data-id="<?= $p['id'] ?>"
                data-name="<?= htmlspecialchars($p['name']) ?>"
                data-price="<?= $minPrice ?>"
                data-image="<?= $imgUrl ?>"
                data-cat="<?= htmlspecialchars($p['category_name'] ?? '') ?>"
                data-desc="<?= htmlspecialchars($p['short_description'] ?? '') ?>">
                <i class="fas fa-bolt me-1"></i> Xem nhanh
            </button>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>

    <div class="card-body d-flex flex-column p-3">
        <div class="text-muted small mb-1 text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">
            <?= htmlspecialchars($p['category_name'] ?? 'Sản phẩm') ?>
        </div>

        <h6 class="product-title mb-2 fw-bold text-truncate">
            <a href="<?= $isActive ? '/MY_WEB/public/product/detail/' . $p['id'] : '#' ?>" class="text-decoration-none text-dark stretched-link z-1">
                <?= htmlspecialchars($p['name']) ?>
            </a>
        </h6>

        <div class="mt-auto d-flex justify-content-between align-items-center">
            <div class="product-price text-success fw-bold fs-5 <?= $isOutOfStock || !$isActive ? 'text-muted text-decoration-line-through' : '' ?>">
                <?php if ($hasMultiplePrices && !$isOutOfStock && $isActive): ?>
                    <span class="fs-6 text-muted fw-normal me-1">Từ</span><?= number_format($minPrice) ?> đ
                <?php else: ?>
                    <?= number_format($minPrice) ?> đ
                <?php endif; ?>
            </div>

            <?php if(!$isActive): ?>
                 <button type="button"
                    class="btn btn-secondary rounded-circle p-0 d-flex align-items-center justify-content-center shadow-sm z-2 position-relative"
                    style="width: 40px; height: 40px; cursor: not-allowed;"
                    title="Ngừng kinh doanh" disabled>
                    <i class="fas fa-times text-white"></i>
                </button>
            <?php elseif($isOutOfStock): ?>
                <button type="button"
                    class="btn btn-secondary rounded-circle p-0 d-flex align-items-center justify-content-center shadow-sm z-2 position-relative"
                    style="width: 40px; height: 40px; cursor: not-allowed;"
                    title="Đã hết hàng" disabled>
                    <i class="fas fa-ban text-white"></i>
                </button>
            <?php else: ?>
                <button type="button"
                    class="btn btn-success rounded-circle p-0 d-flex align-items-center justify-content-center shadow-sm z-2 position-relative btn-add-cart-hover"
                    style="width: 40px; height: 40px;"
                    title="Thêm vào giỏ"
                    onclick="addToCartGlobal(<?= $p['id'] ?>, 1)">
                    <i class="fas fa-plus text-white"></i>
                </button>
            <?php endif; ?>
        </div>
    </div>
</div>