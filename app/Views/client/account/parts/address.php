<h4 class="fw-bold text-success mb-4 border-bottom pb-3">Sổ địa chỉ</h4>

<div class="d-flex justify-content-end mb-3">
    <button class="btn btn-success rounded-pill btn-sm fw-bold" data-bs-toggle="modal" data-bs-target="#addAddressModal">
        <i class="fas fa-plus"></i> Thêm địa chỉ mới
    </button>
</div>

<?php if (empty($addresses)): ?>
    <p class="text-muted text-center py-5">Bạn chưa lưu địa chỉ nào.</p>
<?php else: ?>
    <div class="list-group">
        <?php foreach($addresses as $addr): ?>
        <div class="list-group-item p-3 border rounded mb-3 shadow-sm bg-white">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="d-flex align-items-center mb-1">
                        <h6 class="fw-bold mb-0"><?= $addr['full_name'] ?></h6>
                        <?php if($addr['is_default']): ?>
                            <span class="badge bg-success ms-2 rounded-pill" style="font-size: 0.7rem;">Mặc định</span>
                        <?php endif; ?>
                    </div>
                    <p class="text-muted small mb-1">SĐT: <?= $addr['phone'] ?></p>
                    <p class="text-dark small mb-0">
                        <?= $addr['address_line'] ?>, <?= $addr['city'] ?>, <?= $addr['province'] ?>
                    </p>
                </div>
                
                <div class="dropdown">
                    <button class="btn btn-light btn-sm rounded-circle" data-bs-toggle="dropdown"><i class="fas fa-ellipsis-v"></i></button>
                    <ul class="dropdown-menu dropdown-menu-end border-0 shadow">
                        <?php if(!$addr['is_default']): ?>
                            <li>
                                <a class="dropdown-item cursor-pointer" onclick="setAddressDefault(<?= $addr['id'] ?>)">
                                    <i class="fas fa-check-circle text-success me-2"></i> Đặt làm mặc định
                                </a>
                            </li>
                        <?php endif; ?>
                        <li>
                            <a class="dropdown-item text-danger" href="/MY_WEB/public/ShippingAddress/delete/<?= $addr['id'] ?>" onclick="return confirm('Xóa địa chỉ này?')">
                                <i class="fas fa-trash-alt me-2"></i> Xóa
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<script>
    function setAddressDefault(id) {
        // Gọi AJAX tới hàm setDefault
        fetch(`/MY_WEB/public/ShippingAddress/setDefault/${id}`, {
            method: 'GET',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(data => {
            if(data.status === 'success') {
                // Hiện Toast thông báo
                if(typeof showToast === 'function') {
                    showToast('Đã thay đổi địa chỉ mặc định!');
                } else {
                    alert('Đã thay đổi địa chỉ mặc định!');
                }
                
                // Reload trang sau 1s để cập nhật Badge "Mặc định"
                setTimeout(() => location.reload(), 1000);
            } else {
                alert(data.message);
            }
        })
        .catch(err => console.error(err));
    }
</script>