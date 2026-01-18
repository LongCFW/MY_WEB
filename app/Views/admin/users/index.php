<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý Khách hàng</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
</head>
<body>
    <?php require_once '../app/Views/layouts/admin_sidebar.php'; ?>

    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>👥 Quản lý Khách hàng / Admin</h2>
            <a href="/MY_WEB/public/admin/user/create" class="btn btn-success">Thêm mới</a>
        </div>

        <table class="table table-bordered table-hover bg-white shadow-sm">
            <thead class="thead-light">
                <tr>
                    <th>ID</th>
                    <th>Họ tên</th>
                    <th>Email</th>
                    <th>SĐT</th>
                    <th>Vai trò</th>
                    <th>Trạng thái</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $u): ?>
                <tr>
                    <td><?= $u['id'] ?></td>
                    <td><strong><?= $u['name'] ?></strong></td>
                    <td><?= $u['email'] ?></td>
                    <td><?= $u['phone'] ?></td>
                    <td>
                        <?php if($u['role_id'] == 1): ?>
                            <span class="badge badge-danger">Admin</span>
                        <?php else: ?>
                            <span class="badge badge-info">Khách hàng</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?= ($u['status'] == 1) ? '<span class="text-success">Active</span>' : '<span class="text-muted">Blocked</span>' ?>
                    </td>
                    <td>
                        <a href="/MY_WEB/public/admin/user/edit/<?= $u['id'] ?>" class="btn btn-warning btn-sm">Sửa</a>
                        <?php if($u['id'] != $_SESSION['admin_id']): ?>
                            <a href="/MY_WEB/public/admin/user/delete/<?= $u['id'] ?>" 
                               onclick="return confirm('Xóa người dùng này sẽ xóa cả lịch sử đơn hàng của họ. Bạn chắc chứ?')" 
                               class="btn btn-danger btn-sm">Xóa</a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <?php require_once '../app/Views/layouts/admin_footer.php'; ?>
</body>
</html>