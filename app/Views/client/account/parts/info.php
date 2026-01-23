<h4 class="fw-bold text-success mb-4 border-bottom pb-3">Thông tin cá nhân</h4>

<form action="/MY_WEB/public/account/update" method="POST" enctype="multipart/form-data">
    <div class="row">
        <div class="col-md-12 text-center mb-4">
            <div class="position-relative d-inline-block">
                <?php 
                    // Logic hiển thị ảnh: Ưu tiên ảnh upload, nếu không có thì dùng ảnh mặc định
                    $avatar = !empty($user['avatar_url']) ? "/MY_WEB/public/" . $user['avatar_url'] : "https://ui-avatars.com/api/?name=".urlencode($user['name'])."&background=2e7d32&color=fff";
                ?>
                <img id="previewAvatar" src="<?= $avatar ?>" class="rounded-circle shadow-sm border border-3 border-light" style="width: 120px; height: 120px; object-fit: cover;">
                
                <label for="avatarUpload" class="avatar-upload-btn" title="Đổi ảnh đại diện">
                    <i class="fas fa-camera small"></i>
                </label>
                <input type="file" id="avatarUpload" name="avatar" hidden accept="image/*" onchange="previewImage(this)">
            </div>
            <p class="small text-muted mt-2">Nhấn vào icon máy ảnh để thay đổi.</p>
        </div>

        <div class="col-md-12">
            <div class="mb-3">
                <label class="fw-bold small text-secondary mb-1">HỌ VÀ TÊN</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="fas fa-user text-muted"></i></span>
                    <input type="text" name="fullname" class="form-control bg-light border-start-0 ps-0" value="<?= htmlspecialchars($user['name']) ?>" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="fw-bold small text-secondary mb-1">EMAIL</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="fas fa-envelope text-muted"></i></span>
                        <input type="email" class="form-control bg-light border-start-0 ps-0" value="<?= htmlspecialchars($user['email']) ?>" readonly disabled>
                    </div>
                    <small class="text-muted fst-italic">* Email không thể thay đổi</small>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="fw-bold small text-secondary mb-1">SỐ ĐIỆN THOẠI</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="fas fa-phone text-muted"></i></span>
                        <input type="text" name="phone" class="form-control bg-light border-start-0 ps-0" value="<?= htmlspecialchars($user['phone'] ?? '') ?>">
                    </div>
                </div>
            </div>

            <div class="text-end mt-4">
                <button type="submit" class="btn btn-success rounded-pill px-4 fw-bold shadow-sm">
                    <i class="fas fa-save me-2"></i> Lưu Thay Đổi
                </button>
            </div>
        </div>
    </div>
</form>

<script>
    // Hàm xem trước ảnh khi chọn file
    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                document.getElementById('previewAvatar').src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Kiểm tra URL parameter để hiện thông báo
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('status') === 'success') {
        if(typeof showToast === 'function') {
            showToast('Cập nhật thông tin thành công!', 'success');
        } else {
            alert('Cập nhật thông tin thành công!');
        }
        // Xóa param trên url để tránh hiện lại khi F5
        window.history.replaceState(null, null, window.location.pathname);
    }
</script>