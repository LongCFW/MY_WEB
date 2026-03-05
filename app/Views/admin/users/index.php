<?php require_once '../app/Views/layouts/admin/sidebar.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="text-dark font-weight-bold">Quản lý Người dùng</h3>
    <a href="/MY_WEB/public/admin/user/create" class="btn btn-primary shadow-sm rounded-pill px-4">
        <i class="fas fa-user-plus mr-1"></i> Thêm mới
    </a>
</div>

<div class="card shadow-sm border-0 mb-4 bg-white">
    <div class="card-body p-3">
        <form method="GET" action="/MY_WEB/public/admin/user" class="row align-items-center">
            <div class="col-md-5 mb-2 mb-md-0">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text bg-light border-right-0"><i class="fas fa-search text-muted"></i></span>
                    </div>
                    <input type="text" name="search" class="form-control border-left-0" placeholder="Tên, Email hoặc Số điện thoại..." value="<?= $_GET['search'] ?? '' ?>">
                </div>
            </div>
            <div class="col-md-3 mb-2 mb-md-0">
                <select name="role_id" class="form-control custom-select" onchange="this.form.submit()">
                    <option value="">-- Tất cả vai trò --</option>
                    <option value="1" <?= (($_GET['role_id'] ?? '') == '1') ? 'selected' : '' ?>>Quản trị viên (Admin)</option>
                    <option value="2" <?= (($_GET['role_id'] ?? '') == '2') ? 'selected' : '' ?>>Quản lý (Manager)</option>
                    <option value="3" <?= (($_GET['role_id'] ?? '') == '3') ? 'selected' : '' ?>>Nhân viên (Staff)</option>
                    <option value="4" <?= (($_GET['role_id'] ?? '') == '4') ? 'selected' : '' ?>>Khách hàng (User)</option>
                    <option value="5" <?= (($_GET['role_id'] ?? '') == '5') ? 'selected' : '' ?>>Tài khoản ảo (Seeding)</option>
                </select>
            </div>
            <div class="col-md-2 mb-2 mb-md-0">
                <select name="status" class="form-control custom-select" onchange="this.form.submit()">
                    <option value="">-- Trạng thái --</option>
                    <option value="1" <?= (($_GET['status'] ?? '') == '1') ? 'selected' : '' ?>>Hoạt động (Active)</option>
                    <option value="0" <?= (($_GET['status'] ?? '') == '0') ? 'selected' : '' ?>>Đã khóa (Blocked)</option>
                </select>
            </div>
            <div class="col-md-2 d-flex">
                <button type="submit" class="btn btn-primary flex-grow-1 mr-2 font-weight-bold">Lọc</button>
                <a href="/MY_WEB/public/admin/user" class="btn btn-outline-secondary" title="Xóa bộ lọc"><i class="fas fa-sync-alt"></i></a>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-scroll-wrapper">
            <table class="table table-hover mb-0">
                <thead class="thead-light">
                    <tr>
                        <th class="text-center col-fixed">ID</th>
                        <th class="text-center" width="70">Ảnh</th>
                        <th>Họ tên</th>
                        <th>Email</th>
                        <th class="col-fixed">SĐT</th>
                        <th class="text-center col-fixed">Vai trò</th>
                        <th class="text-center col-fixed">Trạng thái</th>
                        <th class="text-center col-fixed">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($users)): ?>
                        <?php foreach ($users as $u): ?>
                        <tr <?= $u['role_id'] == 5 ? 'class="bg-light"' : '' ?>>
                            <td class="text-center align-middle font-weight-bold text-muted"><?= $u['id'] ?></td>
                            <td class="text-center align-middle">
                                <?php 
                                    $avatar = !empty($u['avatar_url']) 
                                        ? '/MY_WEB/public/' . $u['avatar_url'] 
                                        : 'https://ui-avatars.com/api/?name='.urlencode($u['name']).'&background=random&color=fff'; 
                                ?>
                                <img src="<?= $avatar ?>" class="rounded-circle object-fit-cover shadow-sm border" width="45" height="45">
                            </td>
                            <td class="align-middle">
                                <div class="font-weight-bold text-dark"><?= htmlspecialchars($u['name']) ?></div>
                            </td>
                            <td class="align-middle"><?= htmlspecialchars($u['email']) ?></td>
                            <td class="align-middle"><?= htmlspecialchars($u['phone']) ?></td>
                            <td class="align-middle text-center">
                                <?php if($u['role_id'] == 1): ?>
                                    <span class="badge badge-danger px-2 py-1">Admin</span>
                                <?php elseif($u['role_id'] == 2): ?>
                                    <span class="badge badge-warning text-white px-2 py-1">Quản lý</span>
                                <?php elseif($u['role_id'] == 3): ?>
                                    <span class="badge badge-primary px-2 py-1">Nhân viên</span>
                                <?php elseif($u['role_id'] == 5): ?>
                                    <span class="badge px-2 py-1" style="background-color: #6f42c1; color: white;"><i class="fas fa-robot mr-1"></i> Seeding</span>
                                <?php else: ?>
                                    <span class="badge badge-info px-2 py-1">User</span>
                                <?php endif; ?>
                            </td>
                            <td class="align-middle text-center">
                                <?= ($u['status'] == 1) 
                                    ? '<span class="badge badge-success px-2 py-1">Active</span>' 
                                    : '<span class="badge badge-secondary px-2 py-1">Blocked</span>' ?>
                            </td>
                            <td class="align-middle text-center">
                                <a href="/MY_WEB/public/admin/user/edit/<?= $u['id'] ?>" class="btn btn-warning btn-sm action-btn text-white shadow-sm" title="Sửa">
                                    <i class="fas fa-pen fa-xs"></i>
                                </a>
                                <?php if($u['id'] != $_SESSION['admin_id']): ?>
                                    <a href="/MY_WEB/public/admin/user/delete/<?= $u['id'] ?>" class="btn btn-danger btn-sm action-btn shadow-sm ml-1 btn-delete" title="Xóa" onclick="return confirm('Bạn có chắc chắn muốn xóa?')"> 
                                        <i class="fas fa-trash fa-xs"></i>
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="fas fa-users fa-3x mb-3 text-light"></i><br>
                                Không tìm thấy người dùng nào phù hợp với bộ lọc.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-white py-3">
        <small class="text-muted">Tổng số: <strong><?= count($users) ?></strong> tài khoản</small>
    </div>
</div>

<?php require_once '../app/Views/layouts/admin/footer.php'; ?>