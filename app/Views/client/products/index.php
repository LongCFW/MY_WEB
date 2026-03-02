<?php require_once '../app/Views/layouts/client/header.php'; ?>

<div class="d-lg-none position-fixed bottom-0 start-50 translate-middle-x mb-4 z-3">
    <button class="btn btn-success rounded-pill shadow-lg px-4 py-2 fw-bold" onclick="toggleMobileFilter()">
        <i class="fas fa-filter me-2"></i> Bộ lọc
    </button>
</div>

<div class="filter-overlay" id="filterOverlay" onclick="toggleMobileFilter()"></div>

<div class="bg-light min-vh-100 pb-5">
    <div class="position-relative py-5 mb-5 text-center text-white"
        style="background-image: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('https://images.unsplash.com/photo-1542838132-92c53300491e?auto=format&fit=crop&w=1920&q=80'); background-size: cover; background-position: center; border-radius: 0 0 50px 50px;">
        <div class="container position-relative z-1">
            <span class="badge bg-warning text-dark mb-2 px-3 py-2 rounded-pill fw-bold">Organic & Fresh</span>
            <h2 class="display-4 fw-bold mb-2">Cửa Hàng Xanh</h2>
            <p class="lead opacity-90">Lựa chọn tốt nhất cho sức khỏe gia đình bạn</p>
        </div>
    </div>

    <div class="container">
        <form id="filterForm" action="/MY_WEB/public/product" method="GET">
            <input type="hidden" name="page" id="pageInput" value="<?= $pagination['current_page'] ?>">
            <?php if (!empty($filters['keyword'])): ?>
                <input type="hidden" name="keyword" value="<?= htmlspecialchars($filters['keyword']) ?>">
            <?php endif; ?>

            <div class="row">
                <div class="col-lg-3 filter-sidebar-wrapper" id="filterSidebar">
                    <div class="d-flex justify-content-between align-items-center d-lg-none p-3 bg-white border-bottom">
                        <h5 class="fw-bold m-0">Bộ lọc</h5>
                        <button type="button" class="btn-close" onclick="toggleMobileFilter()"></button>
                    </div>

                    <div class="filter-sidebar bg-white rounded-4 shadow-sm overflow-hidden">
                        <div class="d-flex align-items-center justify-content-between p-3 border-bottom bg-white">
                            <h5 class="fw-bold m-0 text-success"><i class="fas fa-filter me-2"></i> Bộ Lọc</h5>
                            <a href="/MY_WEB/public/product" class="text-danger text-decoration-none small fw-bold">Reset</a>
                        </div>

                        <div class="filter-group border-bottom">
                            <div class="filter-header d-flex justify-content-between align-items-center p-3">
                                <h6 class="fw-bold m-0 text-dark">Loại</h6>
                                <i class="fas fa-chevron-down arrow-icon text-muted"></i>
                            </div>
                            <div class="filter-body">
                                <div class="p-3 pt-0">
                                    <?php foreach ($types as $t): ?>
                                        <?php
                                        // Bỏ qua nếu tên biến thể rỗng hoặc là 'Default'
                                        if (empty($t['type']) || strtolower($t['type']) == 'default') continue;
                                        
                                        $inputId = 'type_' . md5($t['type']);
                                        $isChecked = in_array($t['type'], $filters['types']) ? 'checked' : '';
                                        ?>
                                        <div class="custom-check-item">
                                            <input type="checkbox"
                                                class="filter-checkbox submit-on-change"
                                                name="type[]"
                                                value="<?= $t['type'] ?>"
                                                id="<?= $inputId ?>"
                                                <?= $isChecked ?>>
                                            <label class="custom-check-label" for="<?= $inputId ?>">
                                                <?= $t['type'] ?>
                                            </label>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>

                        <div class="filter-group border-bottom">
                            <div class="filter-header d-flex justify-content-between align-items-center p-3">
                                <h6 class="fw-bold m-0 text-dark">Khoảng Giá</h6>
                                <i class="fas fa-chevron-down arrow-icon text-muted"></i>
                            </div>
                            <div class="filter-body">
                                <div class="p-3 pt-0">
                                    <?php
                                    $priceRanges = [
                                        '0-100000' => 'Dưới 100k',
                                        '100000-300000' => '100k - 300k',
                                        '300000-500000' => '300k - 500k',
                                        '500000+' => 'Trên 500k'
                                    ];
                                    foreach ($priceRanges as $val => $label):
                                        $inputId = 'price_' . str_replace(['+', '-'], '', $val);
                                        $isChecked = in_array($val, $filters['price_ranges']) ? 'checked' : '';
                                    ?>
                                        <div class="custom-check-item">
                                            <input type="checkbox"
                                                class="filter-checkbox submit-on-change"
                                                name="price[]"
                                                value="<?= $val ?>"
                                                id="<?= $inputId ?>"
                                                <?= $isChecked ?>>
                                            <label class="custom-check-label" for="<?= $inputId ?>">
                                                <?= $label ?>
                                            </label>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>

                        <div class="filter-group">
                            <div class="filter-header d-flex justify-content-between align-items-center p-3">
                                <h6 class="fw-bold m-0 text-dark">Thương Hiệu</h6>
                                <i class="fas fa-chevron-down arrow-icon text-muted"></i>
                            </div>
                            <div class="filter-body">
                                <div class="p-3 pt-0">
                                    <?php foreach ($brands as $b): ?>
                                        <?php
                                        $inputId = 'brand_' . md5($b['brand']);
                                        $isChecked = in_array($b['brand'], $filters['brands']) ? 'checked' : '';
                                        ?>
                                        <div class="custom-check-item">
                                            <input type="checkbox"
                                                class="filter-checkbox submit-on-change"
                                                name="brand[]"
                                                value="<?= $b['brand'] ?>"
                                                id="<?= $inputId ?>"
                                                <?= $isChecked ?>>
                                            <label class="custom-check-label" for="<?= $inputId ?>">
                                                <?= $b['brand'] ?>
                                            </label>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="col-lg-9">
                    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 bg-white p-3 rounded-4 shadow-sm border border-light">
                        <span class="text-muted ms-2">Tìm thấy <strong class="text-dark"><?= $pagination['total_items'] ?></strong> sản phẩm</span>
                        <div class="d-flex align-items-center gap-2 mt-2 mt-md-0">
                            <label class="text-muted small fw-bold">Sắp xếp:</label>
                            <select name="sort" class="form-select form-select-sm rounded-pill border-light bg-light fw-bold submit-on-change" style="width: 170px;">
                                <option value="default" <?= $filters['sort'] == 'default' ? 'selected' : '' ?>>Mặc định</option>
                                <option value="price-asc" <?= $filters['sort'] == 'price-asc' ? 'selected' : '' ?>>Giá thấp đến cao</option>
                                <option value="price-desc" <?= $filters['sort'] == 'price-desc' ? 'selected' : '' ?>>Giá cao đến thấp</option>
                            </select>
                        </div>
                    </div>

                    <div class="row g-4">
                        <?php if (empty($products)): ?>
                            <div class="col-12 text-center py-5">
                                <img src="https://cdn-icons-png.flaticon.com/512/4076/4076432.png" width="100" class="mb-3 opacity-50">
                                <h5 class="text-muted fw-bold">Không tìm thấy sản phẩm phù hợp</h5>
                                <a href="/MY_WEB/public/product" class="btn btn-outline-success rounded-pill px-4 mt-2">Xóa bộ lọc</a>
                            </div>
                        <?php else: ?>
                            <?php foreach ($products as $p): ?>
                                <div class="col-6 col-md-4 col-lg-3">
                                    <?php require '../app/Views/client/components/product_card.php'; ?>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <?php if ($pagination['total_pages'] > 1): ?>
                        <div class="d-flex justify-content-center mt-5">
                            <nav>
                                <ul class="pagination eco-pagination">
                                    <li class="page-item <?= ($pagination['current_page'] <= 1) ? 'disabled' : '' ?>">
                                        <a class="page-link" href="#" onclick="changePage(<?= $pagination['current_page'] - 1 ?>); return false;">
                                            <i class="fas fa-chevron-left"></i>
                                        </a>
                                    </li>
                                    <?php for ($i = 1; $i <= $pagination['total_pages']; $i++): ?>
                                        <li class="page-item <?= ($i == $pagination['current_page']) ? 'active' : '' ?>">
                                            <a class="page-link" href="#" onclick="changePage(<?= $i ?>); return false;">
                                                <?= $i ?>
                                            </a>
                                        </li>
                                    <?php endfor; ?>
                                    <li class="page-item <?= ($pagination['current_page'] >= $pagination['total_pages']) ? 'disabled' : '' ?>">
                                        <a class="page-link" href="#" onclick="changePage(<?= $pagination['current_page'] + 1 ?>); return false;">
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
</div>

<div class="modal fade" id="quickViewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 overflow-hidden shadow-lg">
            <button type="button" class="btn-close position-absolute top-0 end-0 m-3 z-3 bg-white p-2 rounded-circle shadow-sm" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="row g-0">
                <div class="col-md-6 bg-light d-flex align-items-center justify-content-center p-4 position-relative overflow-hidden">
                    <img id="qv-image" src="" class="img-fluid position-relative z-2" style="max-height: 350px; object-fit: contain;">
                </div>
                <div class="col-md-6 p-4 p-lg-5 d-flex flex-column justify-content-center bg-white">
                    <span id="qv-category" class="text-uppercase fw-bold text-success small mb-2"></span>
                    <h3 id="qv-name" class="fw-bold text-dark mb-3"></h3>
                    <h2 id="qv-price" class="text-success fw-bold m-0 mb-4"></h2>
                    <p id="qv-desc" class="text-muted mb-4 small"></p>

                    <div class="mt-auto">
    <input type="hidden" id="qv-id"> 
    
    <div class="d-flex gap-3">
        <div class="input-group border rounded-pill overflow-hidden" style="width: 120px;">
            <button type="button" class="btn btn-light border-0" onclick="document.getElementById('qv-qty').stepDown()"><i class="fas fa-minus small"></i></button>
            <input type="number" id="qv-qty" value="1" min="1" class="form-control border-0 text-center fw-bold bg-white">
            <button type="button" class="btn btn-light border-0" onclick="document.getElementById('qv-qty').stepUp()"><i class="fas fa-plus small"></i></button>
        </div>

        <button type="button" 
                class="btn btn-success rounded-pill fw-bold px-4 flex-grow-1 shadow-sm"
                onclick="addToCartGlobal(document.getElementById('qv-id').value, document.getElementById('qv-qty').value)">
            Mua ngay
        </button>
    </div>
</div>

                    <div class="mt-4 pt-3 border-top text-center">
                        <a id="qv-link" href="#" class="text-secondary text-decoration-none small fw-bold">Xem chi tiết sản phẩm <i class="fas fa-arrow-right ms-1"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

        <?php require_once '../app/Views/client/components/quick_view_modal.php'; ?>
<script>
    // 1. Logic Accordion cho Bộ Lọc (QUAN TRỌNG)
    document.addEventListener('DOMContentLoaded', function() {
        
        // --- Xử lý Accordion Filter ---
        const filterGroups = document.querySelectorAll('.filter-group');

        filterGroups.forEach(group => {
            const header = group.querySelector('.filter-header');
            
            // YÊU CẦU 2: Mặc định luôn mở (Expanded by default)
            // Không cần kiểm tra hasChecked, luôn add class 'expanded'
            group.classList.add('expanded');

            // Click header để đóng/mở (Toggle)
            if (header) {
                header.addEventListener('click', function() {
                    group.classList.toggle('expanded');
                });
            }
        });

        // --- Auto Submit khi chọn checkbox/select ---
        const filters = document.querySelectorAll('.submit-on-change');
        filters.forEach(input => {
            input.addEventListener('change', function() {
                const pageInput = document.getElementById('pageInput');
                const form = document.getElementById('filterForm');
                
                if (pageInput) pageInput.value = 1; // Reset về trang 1
                if (form) form.submit();
            });
        });

       
    });

    // Mobile Toggle
    function toggleMobileFilter() {
        const sidebar = document.getElementById('filterSidebar');
        const overlay = document.getElementById('filterOverlay');
        
        if (sidebar && overlay) {
            sidebar.classList.toggle('show');
            overlay.classList.toggle('show');
        }
    }

    // Pagination
    function changePage(page) {
        const pageInput = document.getElementById('pageInput');
        const form = document.getElementById('filterForm');
        
        if (pageInput && form) {
            pageInput.value = page;
            form.submit();
        }
    }
</script>

<?php require_once '../app/Views/layouts/client/footer.php'; ?>