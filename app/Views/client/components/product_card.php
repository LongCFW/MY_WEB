<?php
// Logic kiểm tra trạng thái Like
// Đảm bảo biến $likedIds luôn tồn tại (tránh lỗi undefined)
$likedIds = $likedIds ?? [];
$isLiked = in_array($p['id'], $likedIds);
$imgUrl = !empty($p['image_url']) ? "/MY_WEB/public/" . $p['image_url'] : "https://placehold.co/300x300?text=No+Image";
?>

<div class="card h-100 product-card-wrapper border-0 shadow-sm rounded-4 overflow-hidden group-hover">
    <div class="product-img-container position-relative bg-light" style="padding-top: 100%;"> <img src="<?= $imgUrl ?>" alt="<?= htmlspecialchars($p['name']) ?>" 
             class="position-absolute top-0 start-0 w-100 h-100 object-fit-contain p-3 transition-transform">

        <a href="/MY_WEB/public/product/detail/<?= $p['id'] ?>" class="stretched-link z-1"></a>

        <button type="button" 
                class="btn btn-light rounded-circle position-absolute top-0 end-0 m-3 shadow-sm p-0 d-flex align-items-center justify-content-center btn-wishlist z-3"
                style="width: 35px; height: 35px; transition: all 0.3s ease; position: relative; z-index: 10;"
                onclick="toggleWishlist(this, <?= $p['id'] ?>)"
                title="Thêm vào yêu thích">
            <i class="<?= $isLiked ? 'fas text-danger' : 'far' ?> fa-heart fs-6"></i>
        </button>

        <div class="card-actions-overlay position-absolute top-0 start-0 w-100 h-100 d-flex flex-column align-items-center justify-content-center gap-2 z-2 opacity-0 transition-opacity">
            <a href="/MY_WEB/public/product/detail/<?= $p['id'] ?>" class="btn btn-light rounded-pill px-3 shadow-sm btn-sm fw-bold action-btn position-relative">
                <i class="fas fa-eye me-1"></i> Chi tiết
            </a>
            <button type="button" class="btn btn-light rounded-pill px-3 shadow-sm btn-sm fw-bold btn-quick-view action-btn position-relative"
                data-id="<?= $p['id'] ?>"
                data-name="<?= htmlspecialchars($p['name']) ?>"
                data-price="<?= $p['price_cents'] ?>"
                data-image="<?= $imgUrl ?>"
                data-cat="<?= htmlspecialchars($p['category_name'] ?? '') ?>"
                data-desc="<?= htmlspecialchars($p['short_description'] ?? '') ?>">
                <i class="fas fa-bolt me-1"></i> Xem nhanh
            </button>
        </div>
    </div>

    <div class="card-body d-flex flex-column p-3">
        <div class="text-muted small mb-1 text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">
            <?= htmlspecialchars($p['category_name'] ?? 'Sản phẩm') ?>
        </div>

        <h6 class="product-title mb-2 fw-bold text-truncate">
            <a href="/MY_WEB/public/product/detail/<?= $p['id'] ?>" class="text-decoration-none text-dark stretched-link z-1">
                <?= htmlspecialchars($p['name']) ?>
            </a>
        </h6>

        <div class="mt-auto d-flex justify-content-between align-items-center">
            <div class="product-price text-success fw-bold fs-5">
                <?= number_format($p['price_cents']) ?> đ
            </div>

            <button type="button"
                class="btn btn-success rounded-circle p-0 d-flex align-items-center justify-content-center shadow-sm z-2 position-relative btn-add-cart-hover"
                style="width: 40px; height: 40px;"
                title="Thêm vào giỏ"
                onclick="addToCartGlobal(<?= $p['id'] ?>, 1)">
                <i class="fas fa-plus text-white"></i>
            </button>
        </div>
    </div>
</div>