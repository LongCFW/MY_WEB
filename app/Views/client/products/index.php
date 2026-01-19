<?php require_once '../app/Views/client/layouts/header.php'; ?>

<div class="bg-light min-vh-100 pb-5">
    <div class="position-relative py-5 mb-4 text-center text-white" style="background-image: url('https://images.unsplash.com/photo-1542838132-92c53300491e?auto=format&fit=crop&w=1920&q=80'); background-size: cover;">
        <div class="position-absolute top-0 start-0 w-100 h-100 bg-dark opacity-50"></div>
        <div class="container position-relative z-1">
            <h2 class="display-5 fw-bold mb-2">Cửa Hàng Xanh</h2>
            <p class="lead mb-0 opacity-90">Sản phẩm thiên nhiên - Vì sức khỏe của bạn</p>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-lg-3 d-none d-lg-block">
                <div class="filter-sidebar bg-white p-4 rounded-4 shadow-sm border">
                    <div class="d-flex align-items-center justify-content-between mb-4 pb-3 border-bottom">
                        <h5 class="fw-bold m-0"><i class="fas fa-filter text-success me-2"></i> Bộ Lọc</h5>
                        <a href="/MY_WEB/public/product" class="text-muted text-decoration-none small"><i class="fas fa-redo me-1"></i> Reset</a>
                    </div>
                    
                    <div class="mb-4">
                        <h6 class="fw-bold mb-3">Danh Mục</h6>
                        <div class="form-check mb-2"><input class="form-check-input" type="checkbox"><label class="form-check-label">Rau Củ Quả</label></div>
                        <div class="form-check mb-2"><input class="form-check-input" type="checkbox"><label class="form-check-label">Đồ Uống</label></div>
                        <div class="form-check mb-2"><input class="form-check-input" type="checkbox"><label class="form-check-label">Hạt & Ngũ Cốc</label></div>
                    </div>

                    <div class="mb-3">
                        <h6 class="fw-bold mb-3">Khoảng Giá</h6>
                        <div class="form-check mb-2"><input class="form-check-input" type="checkbox"><label class="form-check-label">Dưới 100k</label></div>
                        <div class="form-check mb-2"><input class="form-check-input" type="checkbox"><label class="form-check-label">100k - 300k</label></div>
                        <div class="form-check mb-2"><input class="form-check-input" type="checkbox"><label class="form-check-label">Trên 500k</label></div>
                    </div>
                </div>
            </div>

            <div class="col-lg-9">
                <div class="d-flex justify-content-between align-items-center mb-4 bg-white p-3 rounded shadow-sm border">
                    <span class="text-muted">Hiển thị <strong><?= count($products) ?></strong> sản phẩm</span>
                    <div class="d-flex align-items-center gap-2">
                        <span class="text-muted d-none d-md-block small">Sắp xếp:</span>
                        <select class="form-select form-select-sm rounded-pill" style="width: 160px;">
                            <option value="default">Mặc định</option>
                            <option value="price-asc">Giá thấp đến cao</option>
                        </select>
                    </div>
                </div>

                <div class="row g-3">
                    <?php if (empty($products)): ?>
                        <div class="col-12 text-center py-5">
                            <p class="fs-5 text-muted">Không tìm thấy sản phẩm nào.</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($products as $p): ?>
                        <div class="col-6 col-md-4 col-lg-3">
                            <div class="card h-100 border-0 product-card-wrapper shadow-sm">
                                <div class="product-img-container position-relative overflow-hidden" style="height: 200px; padding: 10px;">
                                    <?php $imgUrl = !empty($p['image_url']) ? "/MY_WEB/public/" . $p['image_url'] : "https://placehold.co/300x300"; ?>
                                    <img src="<?= $imgUrl ?>" class="img-fluid w-100 h-100 object-fit-contain" alt="<?= $p['name'] ?>">
                                    <div class="card-actions-overlay position-absolute top-0 start-0 w-100 h-100 d-flex flex-column align-items-center justify-content-center bg-dark bg-opacity-25 opacity-0 hover-opacity-100" style="transition: all 0.3s;">
                                        <a href="/MY_WEB/public/product/detail/<?= $p['id'] ?>" class="btn btn-light rounded-pill px-3 mb-2 btn-sm fw-bold text-success shadow">
                                            <i class="fas fa-eye me-1"></i> Chi tiết
                                        </a>
                                    </div>
                                </div>
                                <div class="card-body p-3 d-flex flex-column">
                                    <div class="text-muted small text-uppercase fw-bold mb-1" style="font-size: 0.7rem;"><?= $p['category_name'] ?? 'Sản phẩm' ?></div>
                                    <h6 class="card-title mb-2 text-truncate"><a href="#" class="text-decoration-none text-dark fw-bold"><?= $p['name'] ?></a></h6>
                                    <div class="mt-auto">
                                        <div class="fw-bold text-success fs-5 mb-2"><?= number_format($p['price_cents']) ?> đ</div>
                                        <form action="/MY_WEB/public/cart/add" method="POST">
                                            <input type="hidden" name="product_id" value="<?= $p['id'] ?>">
                                            <input type="hidden" name="quantity" value="1">
                                            <button type="submit" class="btn btn-outline-success w-100 rounded-pill btn-sm fw-bold"><i class="fas fa-shopping-cart me-1"></i> Thêm</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../app/Views/client/layouts/footer.php'; ?>