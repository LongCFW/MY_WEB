<?php require_once '../app/Views/client/layouts/header.php'; ?>

<style>
    /* Bắt buộc phải có để overlay đè lên ảnh */
    .product-img-container {
        position: relative;
        overflow: hidden;
    }
    
    /* Mặc định ẩn overlay */
    .card-actions-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.3); /* Màu nền tối mờ */
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 10px;
        opacity: 0; /* Ẩn */
        transition: opacity 0.3s ease;
        z-index: 10;
    }

    /* Khi hover vào container thì hiện overlay */
    .product-card-wrapper:hover .card-actions-overlay {
        opacity: 1; /* Hiện */
    }

    /* Hiệu ứng phóng to ảnh khi hover */
    .product-card-wrapper:hover .card-img-top {
        transform: scale(1.1);
        transition: transform 0.5s ease;
    }
    
    .card-img-top {
        transition: transform 0.5s ease;
    }
</style>

<div class="bg-light min-vh-100 pb-5">
    <div class="position-relative py-5 mb-4 text-center text-white" 
         style="background-image: url('https://images.unsplash.com/photo-1542838132-92c53300491e?auto=format&fit=crop&w=1920&q=80'); background-size: cover; background-position: center;">
        <div class="position-absolute top-0 start-0 w-100 h-100 bg-dark opacity-50"></div>
        <div class="container position-relative z-1">
            <h2 class="display-5 fw-bold mb-2">Cửa Hàng Xanh</h2>
            <p class="lead mb-3 opacity-90">Sản phẩm thiên nhiên - Vì sức khỏe của bạn</p>
        </div>
    </div>

    <div class="container">
        <form id="filterForm" action="/MY_WEB/public/product" method="GET">
            <input type="hidden" name="page" id="pageInput" value="<?= $pagination['current_page'] ?>">
            
            <?php if(!empty($filters['keyword'])): ?>
                <input type="hidden" name="keyword" value="<?= htmlspecialchars($filters['keyword']) ?>">
            <?php endif; ?>

            <div class="row">
                <div class="col-lg-3 d-none d-lg-block">
                    <div class="filter-sidebar bg-white p-4 rounded-4 shadow-sm border">
                        <div class="d-flex align-items-center justify-content-between mb-4 pb-3 border-bottom">
                            <h5 class="fw-bold m-0"><i class="fas fa-filter text-success me-2"></i> Bộ Lọc</h5>
                            <a href="/MY_WEB/public/product" class="text-muted text-decoration-none small"><i class="fas fa-redo me-1"></i> Reset</a>
                        </div>

                        <div class="accordion accordion-flush" id="filterAccordion">
                            
                            <div class="accordion-item mb-3 border-0">
                                <h2 class="accordion-header">
                                    <button class="accordion-button shadow-none p-0 bg-transparent fw-bold text-dark mb-2" type="button" data-bs-toggle="collapse" data-bs-target="#catCollapse" aria-expanded="true">
                                        Danh Mục
                                    </button>
                                </h2>
                                <div id="catCollapse" class="accordion-collapse collapse show">
                                    <div class="accordion-body p-0">
                                        <?php foreach ($categories as $cat): ?>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input submit-on-change" type="checkbox" name="category[]" value="<?= $cat['id'] ?>" id="cat_<?= $cat['id'] ?>" <?= in_array($cat['id'], $filters['category_ids']) ? 'checked' : '' ?>>
                                                <label class="form-check-label text-secondary hover-green cursor-pointer" for="cat_<?= $cat['id'] ?>"><?= $cat['name'] ?></label>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item mb-3 border-0">
                                <h2 class="accordion-header">
                                    <button class="accordion-button shadow-none p-0 bg-transparent fw-bold text-dark mb-2" type="button" data-bs-toggle="collapse" data-bs-target="#priceCollapse" aria-expanded="true">
                                        Khoảng Giá
                                    </button>
                                </h2>
                                <div id="priceCollapse" class="accordion-collapse collapse show">
                                    <div class="accordion-body p-0">
                                        <?php 
                                            $priceRanges = [
                                                '0-100000' => 'Dưới 100k',
                                                '100000-300000' => '100k - 300k',
                                                '300000-500000' => '300k - 500k',
                                                '500000+' => 'Trên 500k'
                                            ];
                                        ?>
                                        <?php foreach ($priceRanges as $val => $label): ?>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input submit-on-change" type="checkbox" name="price[]" value="<?= $val ?>" id="price_<?= $val ?>" <?= in_array($val, $filters['price_ranges']) ? 'checked' : '' ?>>
                                                <label class="form-check-label text-secondary hover-green cursor-pointer" for="price_<?= $val ?>"><?= $label ?></label>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>

                             <div class="accordion-item border-0">
                                <h2 class="accordion-header">
                                    <button class="accordion-button shadow-none p-0 bg-transparent fw-bold text-dark mb-2" type="button" data-bs-toggle="collapse" data-bs-target="#brandCollapse" aria-expanded="true">
                                        Thương Hiệu
                                    </button>
                                </h2>
                                <div id="brandCollapse" class="accordion-collapse collapse show">
                                    <div class="accordion-body p-0">
                                        <?php foreach ($brands as $b): ?>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input submit-on-change" type="checkbox" name="brand[]" value="<?= $b['brand'] ?>" id="brand_<?= md5($b['brand']) ?>" <?= in_array($b['brand'], $filters['brands']) ? 'checked' : '' ?>>
                                                <label class="form-check-label text-secondary hover-green cursor-pointer" for="brand_<?= md5($b['brand']) ?>"><?= $b['brand'] ?></label>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="col-lg-9">
                    <div class="d-flex justify-content-between align-items-center mb-4 bg-white p-3 rounded shadow-sm border">
                        <span class="text-muted">Hiển thị <strong><?= count($products) ?></strong> / <?= $pagination['total_items'] ?> sản phẩm</span>
                        <div class="d-flex align-items-center gap-2">
                            <span class="text-muted d-none d-md-block small">Sắp xếp:</span>
                            <select name="sort" class="form-select form-select-sm rounded-pill submit-on-change" style="width: 160px;">
                                <option value="default" <?= $filters['sort'] == 'default' ? 'selected' : '' ?>>Mặc định</option>
                                <option value="price-asc" <?= $filters['sort'] == 'price-asc' ? 'selected' : '' ?>>Giá thấp đến cao</option>
                                <option value="price-desc" <?= $filters['sort'] == 'price-desc' ? 'selected' : '' ?>>Giá cao đến thấp</option>
                            </select>
                        </div>
                    </div>

                    <div class="row g-3">
                        <?php if (empty($products)): ?>
                            <div class="col-12 text-center py-5">
                                <p class="fs-5 text-muted">Không tìm thấy sản phẩm nào.</p>
                                <a href="/MY_WEB/public/product" class="btn btn-outline-success rounded-pill px-4">Xóa bộ lọc</a>
                            </div>
                        <?php else: ?>
                            <?php foreach ($products as $p): ?>
                            <div class="col-6 col-md-4 col-lg-3">
                                <div class="card h-100 border-0 product-card-wrapper shadow-sm overflow-hidden">
                                    
                                    <div class="product-img-container position-relative bg-white" style="height: 220px; display: flex; align-items: center; justify-content: center;">
                                        <?php $imgUrl = !empty($p['image_url']) ? "/MY_WEB/public/" . $p['image_url'] : "https://placehold.co/300x300?text=No+Image"; ?>
                                        <img src="<?= $imgUrl ?>" class="card-img-top mw-100 mh-100 object-fit-contain" alt="<?= $p['name'] ?>">
                                        
                                        <div class="card-actions-overlay">
                                            <a href="/MY_WEB/public/product/detail/<?= $p['id'] ?>" class="btn btn-light rounded-pill px-3 mb-2 btn-sm fw-bold text-success shadow action-btn" style="width: 130px;">
                                                <i class="fas fa-eye me-1"></i> Chi tiết
                                            </a>
                                            <button type="button" class="btn btn-light rounded-pill px-3 btn-sm fw-bold text-success shadow action-btn btn-quick-view" style="width: 130px;"
                                                    data-id="<?= $p['id'] ?>"
                                                    data-name="<?= $p['name'] ?>"
                                                    data-price="<?= $p['price_cents'] ?>"
                                                    data-image="<?= $imgUrl ?>"
                                                    data-cat="<?= $p['category_name'] ?>"
                                                    data-desc="<?= htmlspecialchars($p['short_description'] ?? '') ?>">
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
                                                <button type="submit" class="btn btn-outline-success w-100 rounded-pill btn-sm fw-bold"><i class="fas fa-shopping-cart me-1"></i> Thêm</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <?php if ($pagination['total_pages'] > 1): ?>
                    <div class="d-flex justify-content-center mt-5">
                        <nav aria-label="Page navigation">
                            <ul class="pagination eco-pagination">
                                <li class="page-item <?= ($pagination['current_page'] <= 1) ? 'disabled' : '' ?>">
                                    <a class="page-link rounded-circle mx-1" href="#" onclick="changePage(<?= $pagination['current_page'] - 1 ?>); return false;">
                                        <i class="fas fa-chevron-left"></i>
                                    </a>
                                </li>

                                <?php for($i = 1; $i <= $pagination['total_pages']; $i++): ?>
                                    <li class="page-item <?= ($i == $pagination['current_page']) ? 'active' : '' ?>">
                                        <a class="page-link rounded-circle mx-1" href="#" onclick="changePage(<?= $i ?>); return false;">
                                            <?= $i ?>
                                        </a>
                                    </li>
                                <?php endfor; ?>

                                <li class="page-item <?= ($pagination['current_page'] >= $pagination['total_pages']) ? 'disabled' : '' ?>">
                                    <a class="page-link rounded-circle mx-1" href="#" onclick="changePage(<?= $pagination['current_page'] + 1 ?>); return false;">
                                        <i class="fas fa-chevron-right"></i>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                    <?php endif; ?>

                </div>
            </div>
        </form>
    </div>

    <div class="modal fade" id="quickViewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 rounded-4 overflow-hidden">
                <div class="modal-body p-0">
                    <button type="button" class="btn-close position-absolute top-0 end-0 m-3 z-3 bg-white p-2 rounded-circle shadow-sm" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="row g-0">
                        <div class="col-md-6 bg-light d-flex align-items-center justify-content-center p-4">
                            <img id="qv-image" src="" class="img-fluid" style="max-height: 300px;">
                        </div>
                        <div class="col-md-6 p-4">
                            <span id="qv-category" class="badge bg-success bg-opacity-10 text-success mb-2"></span>
                            <h4 id="qv-name" class="fw-bold"></h4>
                            <h3 id="qv-price" class="text-success fw-bold my-3"></h3>
                            <p id="qv-desc" class="text-muted small"></p>
                            <form action="/MY_WEB/public/cart/add" method="POST" class="mt-4">
                                <input type="hidden" name="product_id" id="qv-id">
                                <div class="d-flex gap-3">
                                    <input type="number" name="quantity" value="1" min="1" class="form-control rounded-pill text-center fw-bold" style="width: 80px;">
                                    <button type="submit" class="btn btn-success rounded-pill fw-bold px-4 flex-grow-1">Thêm vào giỏ</button>
                                </div>
                            </form>
                            <div class="mt-3 text-center">
                                <a id="qv-link" href="#" class="text-success text-decoration-none small fw-bold">Xem chi tiết <i class="fas fa-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Hàm chuyển trang: Gán giá trị vào input hidden rồi submit form
    function changePage(page) {
        document.getElementById('pageInput').value = page;
        document.getElementById('filterForm').submit();
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Tự động submit khi đổi filter -> Reset về trang 1
        const filters = document.querySelectorAll('.submit-on-change');
        filters.forEach(input => {
            input.addEventListener('change', function() {
                document.getElementById('pageInput').value = 1; // Reset về trang 1 khi lọc
                document.getElementById('filterForm').submit();
            });
        });

        // Xử lý Quick View (Giữ nguyên logic cũ)
        const qvModalEl = document.getElementById('quickViewModal');
        if (typeof bootstrap !== 'undefined' && qvModalEl) {
            const qvModal = new bootstrap.Modal(qvModalEl);
            document.querySelectorAll('.btn-quick-view').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    document.getElementById('qv-id').value = this.dataset.id;
                    document.getElementById('qv-name').innerText = this.dataset.name;
                    document.getElementById('qv-price').innerText = this.dataset.price + ' đ';
                    document.getElementById('qv-image').src = this.dataset.image;
                    document.getElementById('qv-category').innerText = this.dataset.cat;
                    document.getElementById('qv-desc').innerText = this.dataset.desc;
                    document.getElementById('qv-link').href = '/MY_WEB/public/product/detail/' + this.dataset.id;
                    qvModal.show();
                });
            });
        }
    });
</script>

<?php require_once '../app/Views/client/layouts/footer.php'; ?>