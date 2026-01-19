<?php require_once '../app/Views/client/layouts/header.php'; ?>

<section class="mb-5">
    <div id="heroCarousel" class="carousel slide hero-section rounded-4 overflow-hidden shadow" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <div class="hero-slide d-flex align-items-center" style="background-image: url('https://images.unsplash.com/photo-1542838132-92c53300491e?auto=format&fit=crop&w=1500&q=80'); height: 500px; background-size: cover; background-position: center;">
                    <div class="hero-overlay w-100 h-100 d-flex align-items-center ps-5" style="background: linear-gradient(to right, rgba(15, 61, 40, 0.9) 0%, rgba(30, 100, 50, 0.4) 60%, transparent 100%);">
                        <div class="hero-content text-white ps-5" style="max-width: 600px;">
                            <span class="badge bg-warning text-dark mb-3 px-3 py-2 rounded-pill">100% Organic Food</span>
                            <h1 class="display-4 fw-bold mb-3">Thực Phẩm Xanh <br> Cho Cuộc Sống Lành</h1>
                            <p class="lead mb-4 opacity-75">Tươi ngon từ nông trại đến bàn ăn của bạn. Giảm thiểu rác thải, bảo vệ môi trường.</p>
                            <a href="/MY_WEB/public/product" class="btn btn-success btn-lg rounded-pill px-5 shadow fw-bold">Mua Ngay <i class="fas fa-arrow-right ms-2"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="carousel-item">
                <div class="hero-slide d-flex align-items-center" style="background-image: url('https://images.unsplash.com/photo-1605647540924-852290f6b0d5?auto=format&fit=crop&w=1500&q=80'); height: 500px; background-size: cover; background-position: center;">
                    <div class="hero-overlay w-100 h-100 d-flex align-items-center ps-5" style="background: linear-gradient(to right, rgba(15, 61, 40, 0.9) 0%, rgba(30, 100, 50, 0.4) 60%, transparent 100%);">
                        <div class="hero-content text-white ps-5" style="max-width: 600px;">
                            <span class="badge bg-info mb-3 px-3 py-2 rounded-pill">Zero Waste</span>
                            <h1 class="display-4 fw-bold mb-3">Nói Không Với <br> Rác Thải Nhựa</h1>
                            <p class="lead mb-4 opacity-75">Bộ sưu tập bàn chải tre, ống hút gạo thân thiện.</p>
                            <a href="/MY_WEB/public/product" class="btn btn-light text-success btn-lg rounded-pill px-5 shadow fw-bold">Khám Phá <i class="fas fa-arrow-right ms-2"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
        </button>
    </div>
</section>

<section class="mb-5">
    <div class="container">
        <div class="row g-4">
            <?php 
            $features = [
                ['icon' => 'fa-leaf text-success', 'title' => '100% Tự Nhiên', 'desc' => 'Nguồn gốc hữu cơ minh bạch'],
                ['icon' => 'fa-shipping-fast text-primary', 'title' => 'Giao Hàng Nhanh', 'desc' => 'Miễn phí vận chuyển đơn từ 300k'],
                ['icon' => 'fa-medal text-warning', 'title' => 'Chất Lượng Cao', 'desc' => 'Được kiểm định nghiêm ngặt'],
            ];
            foreach($features as $f): ?>
            <div class="col-md-4">
                <div class="d-flex align-items-center bg-white p-4 rounded-4 shadow-sm h-100 border border-light">
                    <div class="me-3"><i class="fas <?= $f['icon'] ?> fs-1"></i></div>
                    <div>
                        <h5 class="fw-bold mb-1"><?= $f['title'] ?></h5>
                        <p class="text-muted small mb-0"><?= $f['desc'] ?></p>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="mb-5 py-5 bg-light rounded-4" style="background-image: linear-gradient(to bottom, #f1f8e9, white);">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end mb-4">
            <div>
                <div class="d-flex align-items-center gap-2 mb-1">
                    <h2 class="fw-bold mb-0 text-danger">Flash Sale 🔥</h2>
                    <span class="badge bg-danger">Kết thúc sau 12:00</span>
                </div>
                <p class="text-muted mb-0">Săn deal giá sốc mỗi ngày</p>
            </div>
            <a href="/MY_WEB/public/product" class="btn btn-outline-danger rounded-pill fw-bold">Xem tất cả</a>
        </div>

        <div class="row g-4">
            <?php if(empty($products)): ?>
                <div class="col-12 text-center py-5 text-muted">Đang cập nhật sản phẩm...</div>
            <?php else: ?>
                <?php foreach ($products as $p): ?>
                <div class="col-6 col-md-3">
                    <div class="card h-100 border-0 product-card-wrapper shadow-sm">
                        <div class="product-img-container position-relative overflow-hidden" style="height: 220px; padding: 10px;">
                            <?php if(!empty($p['image_url'])): ?>
                                <img src="/MY_WEB/public/<?= $p['image_url'] ?>" class="img-fluid w-100 h-100 object-fit-contain transition-transform" alt="<?= $p['name'] ?>">
                            <?php else: ?>
                                <div class="d-flex align-items-center justify-content-center h-100 bg-light text-muted">No Image</div>
                            <?php endif; ?>
                            
                            <div class="card-actions-overlay position-absolute top-0 start-0 w-100 h-100 d-flex flex-column align-items-center justify-content-center bg-dark bg-opacity-25 opacity-0 hover-opacity-100" style="transition: all 0.3s;">
                                <a href="/MY_WEB/public/product/detail/<?= $p['id'] ?>" class="btn btn-light rounded-pill px-4 mb-2 btn-sm fw-bold text-success shadow">
                                    <i class="fas fa-eye me-1"></i> Chi tiết
                                </a>
                                <button class="btn btn-light rounded-pill px-4 btn-sm fw-bold text-success shadow" data-bs-toggle="modal" data-bs-target="#quickViewModal<?= $p['id'] ?>">
                                    <i class="fas fa-bolt me-1"></i> Xem nhanh
                                </button>
                            </div>
                        </div>
                        <div class="card-body p-3 d-flex flex-column">
                            <div class="text-muted small text-uppercase fw-bold mb-1" style="font-size: 0.7rem;">
                                <?= $p['category_name'] ?? 'Sản phẩm' ?>
                            </div>
                            <h6 class="card-title mb-2 text-truncate">
                                <a href="/MY_WEB/public/product/detail/<?= $p['id'] ?>" class="text-decoration-none text-dark fw-bold"><?= $p['name'] ?></a>
                            </h6>
                            <div class="mt-auto">
                                <div class="fw-bold text-success fs-5 mb-2"><?= number_format($p['price_cents']) ?> đ</div>
                                <form action="/MY_WEB/public/cart/add" method="POST">
                                    <input type="hidden" name="product_id" value="<?= $p['id'] ?>">
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" class="btn btn-outline-success w-100 rounded-pill btn-sm fw-bold">
                                        <i class="fas fa-shopping-cart me-1"></i> Thêm
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="quickViewModal<?= $p['id'] ?>" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content border-0 rounded-4 overflow-hidden">
                            <div class="modal-body p-0">
                                <button type="button" class="btn-close position-absolute top-0 end-0 m-3 z-3" data-bs-dismiss="modal" aria-label="Close"></button>
                                <div class="row g-0">
                                    <div class="col-md-6 bg-light d-flex align-items-center justify-content-center p-4">
                                        <img src="/MY_WEB/public/<?= $p['image_url'] ?>" class="img-fluid" style="max-height: 300px;">
                                    </div>
                                    <div class="col-md-6 p-4">
                                        <span class="badge bg-success bg-opacity-10 text-success mb-2"><?= $p['category_name'] ?></span>
                                        <h4 class="fw-bold"><?= $p['name'] ?></h4>
                                        <h3 class="text-success fw-bold my-3"><?= number_format($p['price_cents']) ?> đ</h3>
                                        <p class="text-muted small"><?= $p['short_description'] ?? 'Mô tả đang cập nhật...' ?></p>
                                        <form action="/MY_WEB/public/cart/add" method="POST" class="mt-4">
                                            <input type="hidden" name="product_id" value="<?= $p['id'] ?>">
                                            <div class="d-flex gap-3">
                                                <input type="number" name="quantity" value="1" min="1" class="form-control rounded-pill text-center fw-bold" style="width: 80px;">
                                                <button type="submit" class="btn btn-success rounded-pill fw-bold px-4 flex-grow-1">Thêm vào giỏ</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php require_once '../app/Views/client/layouts/footer.php'; ?>