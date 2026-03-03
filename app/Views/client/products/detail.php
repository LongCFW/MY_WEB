<?php require_once '../app/Views/layouts/client/header.php'; ?>

<style>
    .product-gallery-thumb { cursor: pointer; border: 2px solid transparent; transition: all 0.2s; border-radius: 8px; overflow: hidden; }
    .product-gallery-thumb:hover, .product-gallery-thumb.active { border-color: #2e7d32; }
    .price-tag-detail { background: rgba(76, 175, 80, 0.1); padding: 10px 20px; border-radius: 10px; display: inline-block; color: #2e7d32; font-weight: bold; font-size: 1.5rem; }
    .custom-tabs .nav-link { color: #555; font-weight: 600; border: none; border-bottom: 3px solid transparent; padding-bottom: 10px; }
    .custom-tabs .nav-link.active { color: #2e7d32; border-bottom-color: #2e7d32; background: transparent; }
    input[type=number]::-webkit-inner-spin-button, input[type=number]::-webkit-outer-spin-button { -webkit-appearance: none; margin: 0; }
    .btn-wishlist-detail:hover { background-color: #dc3545; color: white !important; }
    .btn-wishlist-detail:hover i { color: white !important; }
    .btn-wishlist-detail i.fas.text-danger { color: #dc3545 !important; }

    /* CSS cho nút chọn phân loại */
    .variant-radio:checked + label { background-color: #2e7d32 !important; color: white !important; border-color: #2e7d32 !important; }
    .variant-radio:disabled + label { opacity: 0.5; cursor: not-allowed; background-color: #f8f9fa; text-decoration: line-through; }
    .variant-label { cursor: pointer; transition: all 0.2s; }
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
                        <div class="text-warning small"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><span class="text-dark fw-bold ms-2">5.0</span></div>
                        <div class="vr"></div>
                        <div class="text-muted small">Đã bán: 100+</div>
                        <div class="vr"></div>
                        <div class="text-muted small">SKU: <span id="display-sku" class="fw-bold"><?= htmlspecialchars($product['sku']) ?></span></div>
                    </div>

                    <div class="mb-4">
                        <span class="price-tag-detail" id="display-price">
                            <?= number_format($product['price_cents']) ?> đ
                        </span>
                    </div>

                    <?php if(!empty($product['variants']) && count($product['variants']) > 1): ?>
                        <div class="mb-4 bg-light p-3 rounded-3 border">
                            <label class="fw-bold mb-2 text-dark">Chọn phân loại (Khối lượng/Kích cỡ):</label>
                            <div class="d-flex flex-wrap gap-2">
                                <?php foreach($product['variants'] as $idx => $v): ?>
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
                    <?php elseif(!empty($product['variants'])): ?>
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
                            <?php $p = $rel; require '../app/Views/client/components/product_card.php'; ?>
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
                if(this.checked) {
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