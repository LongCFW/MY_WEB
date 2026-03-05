<?php require_once '../app/Views/layouts/client/header.php'; ?>

<style>
    .product-gallery-thumb {
        cursor: pointer;
        border: 2px solid transparent;
        transition: all 0.2s;
        border-radius: 8px;
        overflow: hidden;
    }

    .product-gallery-thumb:hover,
    .product-gallery-thumb.active {
        border-color: #2e7d32;
    }

    .price-tag-detail {
        background: rgba(76, 175, 80, 0.1);
        padding: 10px 20px;
        border-radius: 10px;
        display: inline-block;
        color: #2e7d32;
        font-weight: bold;
        font-size: 1.5rem;
    }

    .custom-tabs .nav-link {
        color: #555;
        font-weight: 600;
        border: none;
        border-bottom: 3px solid transparent;
        padding-bottom: 10px;
    }

    .custom-tabs .nav-link.active {
        color: #2e7d32;
        border-bottom-color: #2e7d32;
        background: transparent;
    }

    input[type=number]::-webkit-inner-spin-button,
    input[type=number]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    .btn-wishlist-detail:hover {
        background-color: #dc3545;
        color: white !important;
    }

    .btn-wishlist-detail:hover i {
        color: white !important;
    }

    .btn-wishlist-detail i.fas.text-danger {
        color: #dc3545 !important;
    }

    /* CSS cho nút chọn phân loại */
    .variant-radio:checked+label {
        background-color: #2e7d32 !important;
        color: white !important;
        border-color: #2e7d32 !important;
    }

    .variant-radio:disabled+label {
        opacity: 0.5;
        cursor: not-allowed;
        background-color: #f8f9fa;
        text-decoration: line-through;
    }

    .variant-label {
        cursor: pointer;
        transition: all 0.2s;
    }
</style>

<div class="bg-white pb-5">
    <div class="bg-light py-3 mb-4">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb m-0 small bg-transparent p-0">
                    <li class="breadcrumb-item"><a href="/MY_WEB/public/" class="text-success text-decoration-none">Trang chủ</a></li>
                    <li class="breadcrumb-item"><a href="/MY_WEB/public/product" class="text-success text-decoration-none">Sản phẩm</a></li>
                    <li class="breadcrumb-item active text-dark fw-bold" aria-current="page"><?= htmlspecialchars($product['name']) ?></li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="container">
        <div class="row mb-5">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <div class="border rounded-4 overflow-hidden mb-3 position-relative shadow-sm d-flex align-items-center justify-content-center bg-white" style="height: 450px;">
                    <?php $mainImg = !empty($product['images'][0]) ? "/MY_WEB/public/" . $product['images'][0] : "https://placehold.co/600x600?text=No+Image"; ?>
                    <img id="mainImage" src="<?= $mainImg ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="mw-100 mh-100 object-fit-contain">
                </div>

                <?php if (!empty($product['images']) && count($product['images']) > 1): ?>
                    <div class="d-flex gap-2 justify-content-center">
                        <?php foreach ($product['images'] as $idx => $img): ?>
                            <div class="product-gallery-thumb <?= $idx === 0 ? 'active' : '' ?>" style="width: 80px; height: 80px;" onclick="changeMainImage(this, '/MY_WEB/public/<?= $img ?>')">
                                <img src="/MY_WEB/public/<?= $img ?>" class="w-100 h-100 object-fit-cover" alt="Thumb">
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="col-lg-6">
                <div class="ps-lg-4">
                    <span class="badge bg-success mb-2 bg-opacity-75 rounded-pill px-3"><?= htmlspecialchars($product['category_name'] ?? 'Sản phẩm') ?></span>

                    <h2 class="fw-bold mb-3 text-dark display-6"><?= htmlspecialchars($product['name']) ?></h2>

                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div class="d-flex align-items-center">
                            <?php if ($ratingInfo['total_reviews'] > 0): ?>
                                <div class="text-warning small me-2">
                                    <?php
                                    $avg = round($ratingInfo['avg_rating']);
                                    for ($i = 1; $i <= 5; $i++) {
                                        echo $i <= $avg ? '<i class="fas fa-star"></i>' : '<i class="far fa-star"></i>';
                                    }
                                    ?>
                                </div>
                                <span class="text-dark fw-bold"><?= number_format($ratingInfo['avg_rating'], 1) ?></span>
                                <a href="#reviews" class="text-muted small ms-2 text-decoration-none hover-success">
                                    (<?= $ratingInfo['total_reviews'] ?> đánh giá)
                                </a>
                            <?php else: ?>
                                <div class="text-muted small opacity-50 me-2">
                                    <i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i>
                                </div>
                                <span class="text-muted small">Chưa có đánh giá</span>
                            <?php endif; ?>
                        </div>

                        <div class="vr"></div>
                        <div class="text-muted small">Đã bán: <?= isset($product['sold_count']) ? $product['sold_count'] : '0' ?></div>
                        <div class="vr"></div>
                        <div class="text-muted small">SKU: <span id="display-sku" class="fw-bold"><?= htmlspecialchars($product['sku']) ?></span></div>
                    </div>

                    <div class="mb-4">
                        <span class="price-tag-detail" id="display-price">
                            <?= number_format($product['price_cents']) ?> đ
                        </span>
                    </div>

                    <?php if (!empty($product['variants']) && count($product['variants']) > 1): ?>
                        <div class="mb-4 bg-light p-3 rounded-3 border">
                            <label class="fw-bold mb-2 text-dark">Chọn phân loại (Khối lượng/Kích cỡ):</label>
                            <div class="d-flex flex-wrap gap-2">
                                <?php foreach ($product['variants'] as $idx => $v): ?>
                                    <div>
                                        <input type="radio" class="btn-check variant-radio" name="variant_id" id="var_<?= $v['id'] ?>"
                                            value="<?= $v['id'] ?>"
                                            data-price="<?= $v['price_cents'] ?>"
                                            data-stock="<?= $v['stock'] ?>"
                                            data-sku="<?= htmlspecialchars($v['sku']) ?>"
                                            <?= $idx === 0 ? 'checked' : '' ?>
                                            <?= $v['stock'] <= 0 ? 'disabled' : '' ?>>
                                        <label class="btn btn-outline-success rounded-pill px-3 py-1 variant-label fw-medium shadow-sm bg-white" for="var_<?= $v['id'] ?>">
                                            <?= htmlspecialchars($v['name']) ?>
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php elseif (!empty($product['variants'])): ?>
                        <input type="hidden" class="variant-radio" name="variant_id" id="var_<?= $product['variants'][0]['id'] ?>"
                            value="<?= $product['variants'][0]['id'] ?>"
                            data-stock="<?= $product['variants'][0]['stock'] ?>" checked>
                    <?php endif; ?>
                    <div class="mb-4">
                        <p class="text-muted opacity-75" style="line-height: 1.8;">
                            <?= !empty($product['description']) ? mb_substr(strip_tags($product['description']), 0, 150) . '...' : 'Mô tả đang cập nhật...' ?>
                        </p>
                    </div>

                    <div>
                        <div class="d-flex flex-wrap gap-3 mb-4 align-items-center">
                            <div class="input-group border rounded-pill overflow-hidden bg-white" style="width: 140px; height: 48px;">
                                <button type="button" class="btn btn-light border-0 px-3" onclick="updateQty(-1)" id="btnMinus" <?= ($product['stock'] <= 0) ? 'disabled' : '' ?>>
                                    <i class="fas fa-minus text-secondary" style="font-size: 0.8rem;"></i>
                                </button>
                                <input type="number" id="qtyInput" class="form-control border-0 text-center bg-white fw-bold h-100"
                                    value="1" min="1" max="<?= $product['stock'] ?>" <?= ($product['stock'] <= 0) ? 'disabled' : '' ?>>
                                <button type="button" class="btn btn-light border-0 px-3" onclick="updateQty(1)" id="btnPlus" <?= ($product['stock'] <= 0) ? 'disabled' : '' ?>>
                                    <i class="fas fa-plus text-secondary" style="font-size: 0.8rem;"></i>
                                </button>
                            </div>

                            <button type="button"
                                id="btnAddToCart"
                                class="btn <?= ($product['stock'] <= 0) ? 'btn-secondary' : 'btn-success' ?> btn-lg rounded-pill px-5 fw-bold shadow-sm flex-grow-1"
                                onclick="addDetailToCart()"
                                <?= ($product['stock'] <= 0) ? 'disabled' : '' ?>>
                                <i class="fas <?= ($product['stock'] <= 0) ? 'fa-ban' : 'fa-shopping-cart' ?> me-2"></i>
                                <?= ($product['stock'] <= 0) ? 'Tạm hết hàng' : 'Thêm vào giỏ' ?>
                            </button>

                            <?php
                            $likedIds = $likedIds ?? [];
                            $isLiked = in_array($product['id'], $likedIds);
                            ?>
                            <button type="button"
                                class="btn btn-outline-danger rounded-circle p-0 d-flex align-items-center justify-content-center border-2 btn-wishlist-detail"
                                style="width: 48px; height: 48px; transition: all 0.3s ease;"
                                onclick="toggleWishlist(this, <?= $product['id'] ?>)"
                                title="<?= $isLiked ? 'Bỏ yêu thích' : 'Thêm vào yêu thích' ?>">
                                <i class="<?= $isLiked ? 'fas text-danger' : 'far' ?> fa-heart fs-5"></i>
                            </button>
                        </div>
                    </div>

                    <div class="d-flex gap-4 pt-4 border-top">
                        <div class="d-flex align-items-center gap-2"><i class="fas fa-truck text-success fs-4"></i><span class="small fw-medium text-dark">FreeShip <br> từ 300k</span></div>
                        <div class="d-flex align-items-center gap-2"><i class="fas fa-shield-alt text-success fs-4"></i><span class="small fw-medium text-dark">Hàng chính hãng <br> 100%</span></div>
                        <div class="d-flex align-items-center gap-2"><i class="fas fa-undo text-success fs-4"></i><span class="small fw-medium text-dark">Đổi trả <br> trong 7 ngày</span></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-5">
            <div class="col-12">
                <ul class="nav nav-tabs custom-tabs justify-content-center mb-4" id="productTabs" role="tablist">
                    <li class="nav-item" role="presentation"><button class="nav-link active" id="desc-tab" data-bs-toggle="tab" data-bs-target="#desc" type="button" role="tab">Mô tả chi tiết</button></li>
                    <li class="nav-item" role="presentation"><button class="nav-link" id="brand-tab" data-bs-toggle="tab" data-bs-target="#brand" type="button" role="tab">Thương hiệu</button></li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="review-tab" data-bs-toggle="tab" data-bs-target="#reviews" type="button" role="tab">
                            Đánh giá (<?= $ratingInfo['total_reviews'] ?>)
                        </button>
                    </li>
                </ul>
                <div class="tab-content" id="productTabsContent">
                    <div class="tab-pane fade show active" id="desc" role="tabpanel">
                        <div class="bg-light p-4 rounded-4 shadow-sm text-secondary" style="line-height: 1.8;">
                            <h5 class="fw-bold text-dark mb-3">Thông tin sản phẩm</h5>
                            <?= nl2br(htmlspecialchars($product['description'])) ?>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="brand" role="tabpanel">
                        <div class="bg-light p-4 rounded-4 shadow-sm">
                            <p class="mb-0">Thương hiệu: <strong><?= !empty($product['brand']) ? htmlspecialchars($product['brand']) : "EcoStore Original" ?></strong></p>
                            <p class="small text-muted">Cam kết chất lượng chuẩn Organic.</p>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="reviews" role="tabpanel">
                        <div class="row">
                            <div class="col-md-4 mb-4">
                                <div class="bg-light p-4 rounded-4 shadow-sm text-center h-100 d-flex flex-column justify-content-center">
                                    <h1 class="display-3 fw-bold text-success mb-0"><?= $ratingInfo['avg_rating'] ?></h1>
                                    <div class="text-warning fs-4 mb-2">
                                        <?php
                                        $avg = round($ratingInfo['avg_rating']);
                                        for ($i = 1; $i <= 5; $i++) {
                                            echo $i <= $avg ? '<i class="fas fa-star"></i>' : '<i class="far fa-star"></i>';
                                        }
                                        ?>
                                    </div>
                                    <p class="text-muted m-0">Dựa trên <?= $ratingInfo['total_reviews'] ?> đánh giá</p>
                                </div>
                            </div>

                            <div class="col-md-8">
                                <?php if ($eligibleOrderId): ?>
                                    <div class="card border-success border-opacity-25 shadow-sm rounded-4 mb-4">
                                        <div class="card-body p-4">
                                            <h6 class="fw-bold mb-3">Sản phẩm này thế nào? Viết đánh giá của bạn nhé!</h6>
                                            <form action="/MY_WEB/public/product/submitReview" method="POST">
                                                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                                <input type="hidden" name="order_id" value="<?= $eligibleOrderId ?>">

                                                <div class="mb-3 d-flex align-items-center gap-2">
                                                    <label class="fw-medium">Đánh giá sao:</label>
                                                    <select name="rating" class="form-select w-auto border-warning text-warning fw-bold" required>
                                                        <option value="5">⭐⭐⭐⭐⭐ (Tuyệt vời)</option>
                                                        <option value="4">⭐⭐⭐⭐ (Tốt)</option>
                                                        <option value="3">⭐⭐⭐ (Bình thường)</option>
                                                        <option value="2">⭐⭐ (Tệ)</option>
                                                        <option value="1">⭐ (Rất tệ)</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <textarea name="comment" rows="3" class="form-control" placeholder="Chia sẻ cảm nhận của bạn về chất lượng sản phẩm..." required></textarea>
                                                </div>
                                                <button type="submit" class="btn btn-success rounded-pill px-4">Gửi đánh giá</button>
                                            </form>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <div class="review-list">
                                    <?php if (!empty($reviews)): ?>
                                        <?php foreach ($reviews as $rv): ?>
                                            <div class="d-flex gap-3 mb-4 pb-3 border-bottom">
                                                <img src="<?= !empty($rv['avatar_url']) ? '/MY_WEB/public/' . $rv['avatar_url'] : 'https://placehold.co/50' ?>" class="rounded-circle object-fit-cover" width="50" height="50">
                                                <div>
                                                    <div class="d-flex align-items-center gap-2 mb-1">
                                                        <strong class="text-dark"><?= htmlspecialchars($rv['user_name']) ?></strong>
                                                        <span class="text-warning small">
                                                            <?php for ($i = 1; $i <= 5; $i++) echo $i <= $rv['rating'] ? '<i class="fas fa-star"></i>' : '<i class="far fa-star"></i>'; ?>
                                                        </span>
                                                    </div>
                                                    <?php if (!empty($rv['order_id'])): ?>
                                                        <div class="text-success small mb-2"><i class="fas fa-check-circle me-1"></i>Đã mua hàng tại EcoStore</div>
                                                    <?php endif; ?>
                                                    <p class="text-secondary mb-1"><?= nl2br(htmlspecialchars($rv['comment'])) ?></p>
                                                    <small class="text-muted"><?= date('d/m/Y', strtotime($rv['created_at'])) ?></small>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <div class="text-center py-4 text-muted">
                                            <i class="far fa-comments fs-1 mb-2 opacity-50"></i>
                                            <p>Chưa có đánh giá nào. Hãy mua hàng ngay để trở thành người đầu tiên đánh giá!</p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php if (!empty($relatedProducts)): ?>
            <div class="py-5 border-top">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 class="fw-bold m-0">Sản phẩm tương tự</h3>
                    <a href="/MY_WEB/public/product" class="btn btn-outline-success rounded-pill btn-sm fw-bold">Xem tất cả</a>
                </div>
                <div class="row g-4">
                    <?php foreach ($relatedProducts as $rel): ?>
                        <div class="col-6 col-md-4 col-lg-3">
                            <?php $p = $rel;
                            require '../app/Views/client/components/product_card.php'; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once '../app/Views/client/components/quick_view_modal.php'; ?>

<script>
    function changeMainImage(element, src) {
        document.getElementById('mainImage').src = src;
        document.querySelectorAll('.product-gallery-thumb').forEach(el => el.classList.remove('active'));
        element.classList.add('active');
    }

    function updateQty(change) {
        const input = document.getElementById('qtyInput');
        let newVal = parseInt(input.value) + change;
        const max = parseInt(input.getAttribute('max')) || 100;
        if (newVal < 1) newVal = 1;
        if (newVal > max) newVal = max;
        input.value = newVal;
    }

    // --- LOGIC JS CHỌN BIẾN THỂ (GIÁ, KHO TỰ ĐỘNG NHẢY) ---
    document.addEventListener('DOMContentLoaded', function() {
        const variants = document.querySelectorAll('.variant-radio');
        const priceDisplay = document.getElementById('display-price');
        const skuDisplay = document.getElementById('display-sku');
        const stockInput = document.getElementById('qtyInput');
        const btnAddToCart = document.getElementById('btnAddToCart');
        const btnPlus = document.getElementById('btnPlus');
        const btnMinus = document.getElementById('btnMinus');

        variants.forEach(radio => {
            radio.addEventListener('change', function() {
                if (this.checked) {
                    // Update HTML elements
                    const price = parseInt(this.dataset.price);
                    const stock = parseInt(this.dataset.stock);
                    const sku = this.dataset.sku;

                    priceDisplay.innerText = price.toLocaleString('vi-VN') + ' đ';
                    skuDisplay.innerText = sku;
                    stockInput.max = stock;

                    // Nếu stock = 0 -> Disable nút giỏ hàng và input
                    if (stock <= 0) {
                        stockInput.value = 1;
                        stockInput.disabled = true;
                        btnPlus.disabled = true;
                        btnMinus.disabled = true;
                        btnAddToCart.disabled = true;
                        btnAddToCart.classList.remove('btn-success');
                        btnAddToCart.classList.add('btn-secondary');
                        btnAddToCart.innerHTML = '<i class="fas fa-ban me-2"></i> Tạm hết hàng';
                    } else {
                        if (parseInt(stockInput.value) > stock) stockInput.value = stock;
                        stockInput.disabled = false;
                        btnPlus.disabled = false;
                        btnMinus.disabled = false;
                        btnAddToCart.disabled = false;
                        btnAddToCart.classList.remove('btn-secondary');
                        btnAddToCart.classList.add('btn-success');
                        btnAddToCart.innerHTML = '<i class="fas fa-shopping-cart me-2"></i> Thêm vào giỏ';
                    }
                }
            });
        });
    });

    // --- HÀM THÊM VÀO GIỎ DÀNH RIÊNG CHO TRANG CHI TIẾT ---
    function addDetailToCart() {
        const selectedVariant = document.querySelector('.variant-radio:checked');
        if (!selectedVariant) {
            alert('Vui lòng chọn loại sản phẩm!');
            return;
        }

        const variantId = selectedVariant.value;
        const qty = document.getElementById('qtyInput').value;

        // Gửi AJAX (Fetch API) y hệt như logic global
        fetch('/MY_WEB/public/cart/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    variant_id: variantId, // Gửi Variant ID thay vì Product ID
                    quantity: qty
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'login_required') {
                    window.location.href = '/MY_WEB/public/auth/login';
                } else if (data.status === 'success') {
                    alert(data.message);
                    if (data.cart_count !== undefined) {
                        const badge = document.querySelector('.fa-shopping-cart').nextElementSibling;
                        if (badge) badge.innerText = data.cart_count;
                    }
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Đã xảy ra lỗi, vui lòng thử lại.');
            });
    }
</script>

<?php require_once '../app/Views/layouts/client/footer.php'; ?>