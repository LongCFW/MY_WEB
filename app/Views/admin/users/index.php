<?php require_once '../app/Views/layouts/admin/sidebar.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="text-dark font-weight-bold">Quản lý Người dùng</h3>
    <a href="/MY_WEB/public/admin/user/create" class="btn btn-primary shadow-sm rounded-pill px-4">
        <i class="fas fa-user-plus mr-1"></i> Thêm mới
    </a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        
        <div class="table-scroll-wrapper">
            <table class="table table-hover mb-0">
                <thead class="thead-light">
                    <tr>
                        <th class="text-center col-fixed">ID</th>
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
                        <tr>
                            <td class="text-center align-middle font-weight-bold text-muted"><?= $u['id'] ?></td>
                            <td class="align-middle">
                                <div class="font-weight-bold text-dark"><?= $u['name'] ?></div>
                            </td>
                            <td class="align-middle"><?= $u['email'] ?></td>
                            <td class="align-middle"><?= $u['phone'] ?></td>
                            <td class="align-middle text-center">
                                <?php if($u['role_id'] == 1): ?>
                                    <span class="badge badge-danger px-2">Admin</span>
                                <?php else: ?>
                                    <span class="badge badge-info px-2">User</span>
                                <?php endif; ?>
                            </td>
                            <td class="align-middle text-center">
                                <?= ($u['status'] == 1) 
                                    ? '<span class="badge badge-success px-2">Active</span>' 
                                    : '<span class="badge badge-secondary px-2">Blocked</span>' ?>
                            </td>
                            <td class="align-middle text-center">
                                <a href="/MY_WEB/public/admin/user/edit/<?= $u['id'] ?>" class="btn btn-warning btn-sm action-btn text-white shadow-sm" title="Sửa">
                                    <i class="fas fa-pen fa-xs"></i>
                                </a>
                                <?php if($u['id'] != $_SESSION['admin_id']): ?>
                                    <a href="/MY_WEB/public/admin/user/delete/<?= $u['id'] ?>" 
                                       class="btn btn-danger btn-sm action-btn shadow-sm ml-1"
                                       onclick="return confirm('Xóa người dùng này sẽ xóa cả lịch sử liên quan. Bạn chắc chứ?')" title="Xóa">
                                        <i class="fas fa-trash fa-xs"></i>
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">Chưa có người dùng nào.</td>
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