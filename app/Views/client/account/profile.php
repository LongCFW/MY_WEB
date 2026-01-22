<?php require_once '../app/Views/client/layouts/header.php'; ?>

<div class="profile-wrapper">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 mb-4">
                <div class="profile-sidebar p-4 text-center">
                    <div class="avatar-container mb-3">
                        <?php if (!empty($user['avatar_url'])): ?>
                            <img src="/MY_WEB/public/<?= $user['avatar_url'] ?>" class="avatar-img" alt="Avatar">
                        <?php else: ?>
                            <img src="https://ui-avatars.com/api/?name=<?= urlencode($user['name']) ?>&background=2e7d32&color=fff&size=150" class="avatar-img" alt="Avatar">
                        <?php endif; ?>
                    </div>
                    <h5 class="fw-bold mb-1"><?= htmlspecialchars($user['name']) ?></h5>
                    <p class="text-muted small mb-4">Thành viên EcoStore</p>

                    <div class="list-group text-start">
                        <a href="/MY_WEB/public/account" class="profile-menu-item active">
                            <i class="fas fa-user"></i> Thông tin tài khoản
                        </a>
                        <a href="#" class="profile-menu-item">
                            <i class="fas fa-file-invoice"></i> Quản lý đơn hàng
                        </a>
                        <a href="#" class="profile-menu-item">
                            <i class="fas fa-map-marker-alt"></i> Sổ địa chỉ
                        </a>
                        <a href="#" class="profile-menu-item">
                            <i class="fas fa-heart"></i> Sản phẩm yêu thích
                        </a>
                        <a href="#" class="profile-menu-item">
                            <i class="fas fa-ticket-alt"></i> Kho Voucher
                        </a>
                        <a href="#" class="profile-menu-item">
                            <i class="fas fa-bell"></i> Thông báo
                        </a>
                        <a href="#" class="profile-menu-item">
                            <i class="fas fa-lock"></i> Đổi mật khẩu
                        </a>
                        <a href="/MY_WEB/public/auth/logout" class="profile-menu-item text-danger">
                            <i class="fas fa-sign-out-alt"></i> Đăng xuất
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-lg-9">
                <div class="profile-content-card">
                    <h4 class="fw-bold text-success mb-4 border-bottom pb-3">Thông tin cá nhân</h4>

                    <div class="text-center mb-4">
                        <div class="position-relative d-inline-block">
                            <?php if (!empty($user['avatar_url'])): ?>
                                <img src="/MY_WEB/public/<?= $user['avatar_url'] ?>" class="rounded-circle shadow-sm" style="width: 100px; height: 100px; object-fit: cover;">
                            <?php else: ?>
                                <img src="https://ui-avatars.com/api/?name=<?= urlencode($user['name']) ?>&background=2e7d32&color=fff&size=150" class="rounded-circle shadow-sm" style="width: 100px; height: 100px;">
                            <?php endif; ?>
                            
                            <label for="avatarUpload" class="avatar-upload-btn" title="Đổi ảnh đại diện">
                                <i class="fas fa-camera small"></i>
                            </label>
                            <input type="file" id="avatarUpload" hidden>
                        </div>
                        <div class="small text-muted mt-2">Nhấn vào icon máy ảnh để thay đổi.</div>
                    </div>

                    <form>
                        <div class="mb-3">
                            <label class="fw-bold small text-secondary mb-1">HỌ VÀ TÊN</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fas fa-user text-muted"></i></span>
                                <input type="text" class="form-control bg-light border-start-0 ps-0" value="<?= htmlspecialchars($user['name']) ?>" readonly>
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
                                    <input type="text" class="form-control bg-light border-start-0 ps-0" value="<?= htmlspecialchars($user['phone'] ?? 'Chưa cập nhật') ?>" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="fw-bold small text-secondary mb-1">NGÀY SINH</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white"><i class="fas fa-calendar-alt text-muted"></i></span>
                                    <input type="date" class="form-control" value="1995-10-20"> </div>
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
        </div>
    </div>
</div>

<?php require_once '../app/Views/client/layouts/footer.php'; ?>