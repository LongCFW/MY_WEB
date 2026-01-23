<?php use App\Helpers\PaginationHelper; ?>

<h4 class="fw-bold text-success mb-4 border-bottom pb-3">
    <i class="fas fa-map-marker-alt me-2"></i> Sổ địa chỉ nhận hàng
</h4>

<div class="d-flex justify-content-end mb-3">
    <button class="btn btn-success rounded-pill btn-sm fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#addAddressModal">
        <i class="fas fa-plus me-1"></i> Thêm địa điểm mới
    </button>
</div>

<?php if (empty($addresses)): ?>
    <div class="text-center py-5 bg-light rounded-4">
        <i class="fas fa-map-marked-alt text-muted mb-3" style="font-size: 3rem; opacity: 0.5;"></i>
        <p class="text-muted">Bạn chưa lưu địa điểm nào.</p>
    </div>
<?php else: ?>
    <div class="list-group shadow-sm rounded-4 overflow-hidden">
        <?php foreach($addresses as $addr): ?>
        <div class="list-group-item p-3 border-0 border-bottom bg-white">
            <div class="d-flex justify-content-between align-items-center">
                
                <div class="d-flex align-items-start">
                    <div class="me-3 mt-1">
                        <i class="fas fa-map-pin text-danger fs-5"></i>
                    </div>
                    <div>
                        <div class="d-flex align-items-center mb-1">
                            <h6 class="fw-bold mb-0 text-dark">
                                <?= htmlspecialchars($addr['address'] ?? $addr['address_line'] ?? '') ?>
                            </h6>
                            <?php if(!empty($addr['is_default'])): ?>
                                <span class="badge bg-success ms-2 rounded-pill" style="font-size: 0.7rem;">Mặc định</span>
                            <?php endif; ?>
                        </div>
                        
                        <p class="text-muted small mb-0">
                            <?= htmlspecialchars($addr['city'] ?? '') ?>
                        </p>
                    </div>
                </div>
                
                <div class="dropdown">
                    <button class="btn btn-light btn-sm rounded-circle shadow-sm" data-bs-toggle="dropdown">
                        <i class="fas fa-ellipsis-v text-secondary"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end border-0 shadow rounded-3">
                        <?php if(empty($addr['is_default'])): ?>
                            <li>
                                <button class="dropdown-item cursor-pointer small py-2" onclick="setAddressDefault(<?= $addr['id'] ?>)">
                                    <i class="fas fa-check-circle text-success me-2"></i> Đặt làm mặc định
                                </button>
                            </li>
                        <?php endif; ?>
                        <li>
                            <a class="dropdown-item text-danger small py-2" href="/MY_WEB/public/ShippingAddress/delete/<?= $addr['id'] ?>" onclick="return confirm('Xóa địa điểm này?')">
                                <i class="fas fa-trash-alt me-2"></i> Xóa địa điểm
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<div class="py-3">
    <?php 
        // Biến $pageNum và $totalPages ĐƯỢC TRUYỀN TỪ AccountController
        if (isset($pageNum) && isset($totalPages)) {
            echo PaginationHelper::render($pageNum, $totalPages, 'p'); 
        }
    ?>
</div>

<div class="modal fade" id="addAddressModal" tabindex="-1" aria-labelledby="addAddressLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title fw-bold text-success" id="addAddressLabel">Thêm địa điểm mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form action="/MY_WEB/public/ShippingAddress/store" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold small">Tỉnh / Thành phố</label>
                        <input type="text" name="city" class="form-control rounded-3 bg-light" required placeholder="Ví dụ: TP. Hồ Chí Minh">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small">Địa chỉ chi tiết</label>
                        <textarea name="address" class="form-control rounded-3 bg-light" rows="3" required placeholder="Số nhà, tên đường, phường/xã..."></textarea>
                    </div>

                    <div class="form-check bg-light p-3 rounded-3 border">
                        <input class="form-check-input" type="checkbox" name="is_default" id="defaultCheck">
                        <label class="form-check-label small fw-bold cursor-pointer" for="defaultCheck">
                            Đặt làm địa chỉ nhận hàng mặc định
                        </label>
                    </div>
                </div>
                <div class="modal-footer border-top-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-success rounded-pill px-4 fw-bold shadow-sm">Lưu địa điểm</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function setAddressDefault(id) {
        fetch(`/MY_WEB/public/ShippingAddress/setDefault/${id}`, {
            method: 'GET',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(data => {
            if(data.status === 'success') {
                if(typeof showToast === 'function') {
                    showToast('Đã thay đổi địa chỉ mặc định!', 'success');
                } else {
                    alert('Đã thay đổi địa chỉ mặc định!');
                }
                setTimeout(() => location.reload(), 1000);
            } else {
                alert(data.message || 'Có lỗi xảy ra');
            }
        })
        .catch(err => {
            console.error(err);
            alert('Lỗi kết nối server');
        });
    }
</script>