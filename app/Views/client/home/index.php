<?php require_once '../app/Views/client/layouts/header.php'; ?>

<section class="mb-5 position-relative">
    <div id="heroCarousel" class="carousel slide hero-section rounded-bottom-5 overflow-hidden shadow-lg" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1"></button>
        </div>
        <div class="carousel-inner">
            <div class="carousel-item active">
                <div class="hero-slide d-flex align-items-center" style="background-image: url('https://images.unsplash.com/photo-1495107334309-fcf20504a5ab?q=80&w=1920&auto=format&fit=crop');">
                    <div class="hero-overlay">
                        <div class="container">
                            <div class="hero-content ps-md-5">
                                <span class="badge bg-warning text-dark mb-3 px-3 py-2 rounded-pill shadow-sm animate-fade-down">🌿 100% Organic</span>
                                <h1 class="display-3 fw-bolder mb-3 text-white animate-fade-right">Nông Sản Sạch <br> Từ Tâm Người Việt</h1>
                                <p class="lead mb-4 text-white-50 animate-fade-up">Mang thiên nhiên tươi mát vào từng bữa ăn gia đình bạn.</p>
                                <div class="d-flex gap-3 animate-fade-up" style="animation-delay: 0.2s;">
                                    <a href="/MY_WEB/public/product" class="btn btn-success btn-lg rounded-pill px-5 shadow-lg fw-bold btn-hover-scale">Mua Ngay</a>
                                    <button class="btn btn-outline-light btn-lg rounded-circle shadow-lg" style="width: 50px; height: 50px;" data-bs-toggle="modal" data-bs-target="#videoModal">
                                        <i class="fas fa-play"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="carousel-item">
                <div class="hero-slide d-flex align-items-center" style="background-image: url('https://images.unsplash.com/photo-1506484381205-f7945653044d?q=80&w=1920&auto=format&fit=crop');">
                    <div class="hero-overlay">
                        <div class="container">
                            <div class="hero-content ps-md-5">
                                <span class="badge bg-info mb-3 px-3 py-2 rounded-pill shadow-sm">🌊 Fresh & Healthy</span>
                                <h1 class="display-3 fw-bolder mb-3 text-white">Sống Xanh <br> Ăn Lành Mạnh</h1>
                                <p class="lead mb-4 text-white-50">Khám phá bộ sưu tập thực phẩm không hóa chất.</p>
                                <a href="/MY_WEB/public/product" class="btn btn-light text-success btn-lg rounded-pill px-5 shadow-lg fw-bold btn-hover-scale">Khám Phá</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>    
</section>

<div class="modal fade" id="videoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content bg-transparent border-0">
            <div class="modal-body p-0 position-relative">
                <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3 z-3" data-bs-dismiss="modal"></button>
                <div class="ratio ratio-16x9 rounded-4 overflow-hidden shadow-lg">
                    <iframe src="https://www.youtube.com/embed/ScMzIvxBSi4?autoplay=0&mute=0" title="EcoStore Story" allowfullscreen></iframe>
                </div>
            </div>
        </div>
    </div>
</div>

<section class="mb-5 mt-n5 position-relative z-2">
    <div class="container">
        <div class="row g-4 justify-content-center">
            <?php 
            $features = [
                ['icon' => 'fa-truck-fast', 'title' => 'Giao Nhanh', 'desc' => 'Trong 2h nội thành', 'modal' => '#policyModal', 'tab' => 'shipping'],
                ['icon' => 'fa-shield-halved', 'title' => 'Đổi Trả', 'desc' => 'Hoàn tiền nếu lỗi', 'modal' => '#policyModal', 'tab' => 'return'],
                ['icon' => 'fa-tags', 'title' => 'Ưu Đãi', 'desc' => 'Săn mã giảm giá', 'modal' => '#voucherModal', 'tab' => '']
            ];
            foreach($features as $f): ?>
            <div class="col-md-4 col-10">
                <div class="feature-card bg-white p-4 rounded-4 shadow-sm h-100 d-flex align-items-center cursor-pointer btn-hover-up" 
                     data-bs-toggle="modal" data-bs-target="<?= $f['modal'] ?>" onclick="switchPolicyTab('<?= $f['tab'] ?>')">
                    <div class="icon-box bg-success bg-opacity-10 text-success rounded-circle p-3 me-3">
                        <i class="fas <?= $f['icon'] ?> fs-3"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1 text-dark"><?= $f['title'] ?></h6>
                        <p class="text-muted small mb-0"><?= $f['desc'] ?></p>
                    </div>
                    <i class="fas fa-chevron-right ms-auto text-muted opacity-25"></i>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="mb-5 py-4">
    <div class="container text-center">
        <h3 class="section-title mb-5">Danh Mục Nổi Bật</h3>
        <div class="row g-4 justify-content-center">
            <?php foreach($categories as $cat): ?>
            <div class="col-6 col-md-4 col-lg-2">
                <a href="/MY_WEB/public/product?cat=<?= $cat['name'] ?>" class="text-decoration-none">
                    <div class="category-item">
                        <div class="cat-img-wrap mb-3 shadow-sm">
                            <img src="<?= $cat['img'] ?>" alt="<?= $cat['name'] ?>">
                        </div>
                        <h6 class="fw-bold text-dark"><?= $cat['name'] ?></h6>
                        <span class="badge bg-light text-muted border"><?= $cat['count'] ?></span>
                    </div>
                </a>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="mb-5 py-5" style="background: linear-gradient(180deg, #f0fdf4 0%, #ffffff 100%);">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end mb-4">
            <div>
                <h2 class="fw-bold text-danger mb-2"><i class="fas fa-bolt me-2"></i>Flash Sale</h2>
                <div class="d-flex align-items-center gap-2">
                    <span class="text-muted">Kết thúc trong:</span>
                    <div class="countdown d-flex gap-2">
                        <span class="bg-danger text-white px-2 rounded fw-bold" id="hours">02</span>:
                        <span class="bg-danger text-white px-2 rounded fw-bold" id="minutes">45</span>:
                        <span class="bg-danger text-white px-2 rounded fw-bold" id="seconds">30</span>
                    </div>
                </div>
            </div>
            <a href="/MY_WEB/public/product" class="btn btn-outline-success rounded-pill fw-bold px-4">Xem tất cả</a>
        </div>

        <div class="row g-4">
            <?php foreach ($products as $p): ?>
            <div class="col-6 col-md-3">
                <div class="card h-100 border-0 shadow-sm product-card overflow-hidden">
                    <div class="position-relative product-thumb-container">
                        <?php $img = !empty($p['image_url']) ? "/MY_WEB/public/" . $p['image_url'] : "https://placehold.co/300"; ?>
                        <img src="<?= $img ?>" class="card-img-top object-fit-contain p-3" height="200" alt="<?= $p['name'] ?>">
                        
                        <div class="product-action-overlay">
                            <?php 
        // Kiểm tra xem sản phẩm này có trong danh sách đã thích không
        $isLiked = in_array($p['id'], $wishlistProductIds ?? []); 
    ?>
    <button class="btn btn-white rounded-circle shadow m-1 text-danger" 
            title="Yêu thích" 
            onclick="toggleWishlist(this, <?= $p['id'] ?>)">
        
        <i class="<?= $isLiked ? 'fas' : 'far' ?> fa-heart"></i>
    </button>
                            <button class="btn btn-white rounded-circle shadow m-1 text-primary" title="Xem nhanh" 
                                    data-bs-toggle="modal" data-bs-target="#quickViewModal<?= $p['id'] ?>">
                                <i class="far fa-eye"></i>
                            </button>
                        </div>
                        
                        <?php if(rand(0,1)): ?>
                            <span class="position-absolute top-0 start-0 m-2 badge bg-danger rounded-pill">-20%</span>
                        <?php endif; ?>
                    </div>
                    <div class="card-body p-3 d-flex flex-column">
                        <div class="small text-muted mb-1"><?= $p['category_name'] ?? 'Organic' ?></div>
                        <h6 class="card-title fw-bold text-truncate mb-2"><?= $p['name'] ?></h6>
                        <div class="mt-auto d-flex justify-content-between align-items-center">
                            <span class="text-success fw-bold"><?= number_format($p['price_cents']) ?>đ</span>
                            <button class="btn btn-success btn-sm rounded-circle" onclick="addToCartGlobal(<?= $p['id'] ?>, 1)"><i class="fas fa-plus"></i></button>
                        </div>
                    </div>
                </div>
                
                <div class="modal fade" id="quickViewModal<?= $p['id'] ?>" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content border-0 rounded-4 overflow-hidden">
                            <div class="modal-body p-0">
                                <button type="button" class="btn-close position-absolute top-0 end-0 m-3 z-3" data-bs-dismiss="modal"></button>
                                <div class="row g-0">
                                    <div class="col-md-6 bg-light d-flex align-items-center justify-content-center p-4">
                                        <img src="<?= $img ?>" class="img-fluid rounded shadow-sm" style="max-height: 300px;">
                                    </div>
                                    <div class="col-md-6 p-4 d-flex flex-column justify-content-center">
                                        <h4 class="fw-bold mb-2"><?= $p['name'] ?></h4>
                                        <div class="mb-3 text-warning small">
                                            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i>
                                            <span class="text-muted ms-2">(4.5)</span>
                                        </div>
                                        <h3 class="text-success fw-bold mb-3"><?= number_format($p['price_cents']) ?> đ</h3>
                                        <p class="text-muted small mb-4"><?= $p['short_description'] ?? 'Sản phẩm hữu cơ tươi ngon, đảm bảo an toàn vệ sinh thực phẩm.' ?></p>
                                        
                                        <div class="d-flex gap-2">
                                            <a href="/MY_WEB/public/product/detail/<?= $p['id'] ?>" class="btn btn-outline-success rounded-pill px-4">Chi tiết</a>
                                            <button onclick="addToCartGlobal(<?= $p['id'] ?>, 1)" class="btn btn-success rounded-pill px-4 flex-grow-1">
                                                <i class="fas fa-shopping-cart me-2"></i> Thêm ngay
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<div class="modal fade" id="voucherModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0" style="background: linear-gradient(135deg, #d4fc79 0%, #96e6a1 100%);">
            <div class="modal-body p-4 text-center position-relative">
                <button type="button" class="btn-close position-absolute top-0 end-0 m-3" data-bs-dismiss="modal"></button>
                <div class="bg-white rounded-circle d-inline-flex p-3 mb-3 shadow-sm text-success">
                    <i class="fas fa-gift fs-1"></i>
                </div>
                <h3 class="fw-bold text-dark">Quà Tặng Cho Bạn!</h3>
                <p class="text-dark opacity-75">Nhập mã bên dưới để được giảm 20% cho đơn hàng đầu tiên.</p>
                
                <div class="bg-white border-2 border-dashed border-success p-3 rounded-3 mb-3 mx-auto" style="max-width: 300px; border-style: dashed;">
                    <h2 class="fw-bold text-success mb-0 ls-2">ECO2026</h2>
                </div>
                
                <button class="btn btn-dark rounded-pill px-4 w-100" onclick="navigator.clipboard.writeText('ECO2026'); alert('Đã sao chép mã!');">
                    Sao chép mã
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="policyModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 border-0 overflow-hidden">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="fas fa-shield-alt me-2"></i> Chính Sách Dịch Vụ</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <div class="d-flex">
                    <div class="nav flex-column nav-pills p-3 bg-light" style="width: 200px;" id="v-pills-tab" role="tablist">
                        <button class="nav-link active text-start rounded-3 mb-2 fw-bold" id="tab-shipping" data-bs-toggle="pill" data-bs-target="#content-shipping" type="button">
                            <i class="fas fa-truck me-2"></i> Giao hàng
                        </button>
                        <button class="nav-link text-start rounded-3 mb-2 fw-bold" id="tab-return" data-bs-toggle="pill" data-bs-target="#content-return" type="button">
                            <i class="fas fa-undo me-2"></i> Đổi trả
                        </button>
                        <button class="nav-link text-start rounded-3 fw-bold" id="tab-contact" data-bs-toggle="pill" data-bs-target="#content-contact" type="button">
                            <i class="fas fa-headset me-2"></i> Liên hệ
                        </button>
                    </div>
                    <div class="tab-content p-4 flex-grow-1" id="v-pills-tabContent">
                        <div class="tab-pane fade show active" id="content-shipping">
                            <h5 class="fw-bold text-success mb-3">Chính sách giao hàng</h5>
                            <ul class="list-unstyled">
                                <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Miễn phí ship cho đơn từ 300k.</li>
                                <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Giao nhanh 2h trong nội thành.</li>
                                <li><i class="fas fa-check-circle text-success me-2"></i> Đóng gói bao bì giấy thân thiện môi trường.</li>
                            </ul>
                        </div>
                        <div class="tab-pane fade" id="content-return">
                            <h5 class="fw-bold text-success mb-3">Chính sách đổi trả</h5>
                            <p>Hoàn tiền 100% hoặc 1 đổi 1 trong vòng 24h nếu sản phẩm bị hư hỏng, dập nát hoặc không đúng chất lượng cam kết.</p>
                        </div>
                        <div class="tab-pane fade" id="content-contact">
                            <h5 class="fw-bold text-success mb-3">Tổng đài hỗ trợ</h5>
                            <p class="fs-4 fw-bold text-dark"><i class="fas fa-phone-alt me-2"></i> 1900 1234</p>
                            <p>Email: support@ecostore.com</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // JS cho Countdown Flash Sale
    function startTimer(duration, displayHours, displayMinutes, displaySeconds) {
        var timer = duration, hours, minutes, seconds;
        setInterval(function () {
            hours = parseInt(timer / 3600, 10);
            minutes = parseInt((timer % 3600) / 60, 10);
            seconds = parseInt(timer % 60, 10);

            hours = hours < 10 ? "0" + hours : hours;
            minutes = minutes < 10 ? "0" + minutes : minutes;
            seconds = seconds < 10 ? "0" + seconds : seconds;

            displayHours.textContent = hours;
            displayMinutes.textContent = minutes;
            displaySeconds.textContent = seconds;

            if (--timer < 0) {
                timer = duration;
            }
        }, 1000);
    }

    // JS để chuyển Tab trong modal Policy khi click từ ngoài
    function switchPolicyTab(tabName) {
        if(!tabName) return;
        var triggerEl = document.querySelector('#tab-' + tabName);
        if(triggerEl) {
            var tab = new bootstrap.Tab(triggerEl);
            tab.show();
        }
    }

    window.onload = function () {
        // Timer 2 tiếng
        startTimer(7200, document.querySelector('#hours'), document.querySelector('#minutes'), document.querySelector('#seconds'));
    };
</script>

<?php require_once '../app/Views/client/layouts/footer.php'; ?>