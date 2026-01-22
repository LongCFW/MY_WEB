<h4 class="fw-bold text-success mb-4 border-bottom pb-3">Thông tin cá nhân</h4>

<div class="row">
    <div class="col-md-12 text-center mb-4">
        <div class="position-relative d-inline-block">
            <?php 
                $avatar = !empty($user['avatar_url']) ? "/MY_WEB/public/" . $user['avatar_url'] : "https://ui-avatars.com/api/?name=".urlencode($user['name'])."&background=2e7d32&color=fff";
            ?>
            <img src="<?= $avatar ?>" class="rounded-circle shadow-sm border border-3 border-light" style="width: 120px; height: 120px; object-fit: cover;">
            
            <label for="avatarUpload" class="avatar-upload-btn" title="Đổi ảnh đại diện">
                <i class="fas fa-camera small"></i>
            </label>
            <input type="file" id="avatarUpload" hidden>
        </div>
        <p class="small text-muted mt-2">Nhấn vào icon máy ảnh để thay đổi.</p>
    </div>

    <div class="col-md-12">
        <form>
            <div class="mb-3">
                <label class="fw-bold small text-secondary mb-1">HỌ VÀ TÊN</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="fas fa-user text-muted"></i></span>
                    <input type="text" class="form-control bg-light border-start-0 ps-0" value="<?= htmlspecialchars($user['name']) ?>">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="fw-bold small text-secondary mb-1">EMAIL</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="fas fa-envelope text-muted"></i></span>
                        <input type="email" class="form-control bg-light border-start-0 ps-0" value="<?= htmlspecialchars($user['email']) ?>" readonly>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="fw-bold small text-secondary mb-1">SỐ ĐIỆN THOẠI</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="fas fa-phone text-muted"></i></span>
                        <input type="text" class="form-control bg-light border-start-0 ps-0" value="<?= htmlspecialchars($user['phone'] ?? '') ?>">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="fw-bold small text-secondary mb-1">NGÀY SINH</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="fas fa-calendar-alt text-muted"></i></span>
                        <input type="date" class="form-control" value="1995-10-20">
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="fw-bold small text-secondary mb-1">GIỚI TÍNH</label>
                    <div class="gender-selector">
                        <label class="gender-option">
                            <input type="radio" name="gender" value="male" checked>
                            <span class="gender-label">Nam</span>
                        </label>
                        <label class="gender-option">
                            <input type="radio" name="gender" value="female">
                            <span class="gender-label">Nữ</span>
                        </label>
                        <label class="gender-option">
                            <input type="radio" name="gender" value="other">
                            <span class="gender-label">Khác</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="text-end mt-4">
                <button type="button" class="btn btn-success rounded-pill px-4 fw-bold shadow-sm">
                    <i class="fas fa-save me-2"></i> Lưu Thay Đổi
                </button>
            </div>
        </form>
    </div>
</div>