<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý Khách hàng</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
</head>
<body>
    <?php require_once '../app/Views/layouts/admin_sidebar.php'; ?>

    <h2>Danh sách Khách hàng</h2>
    <table class="table table-hover mt-3 bg-white">
        <thead>
            <tr>
                <th>ID</th>
                <th>Họ tên</th>
                <th>Email</th>
                <th>SĐT</th>
                <th>Vai trò</th>
                <th>Ngày tham gia</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $u): ?>
            <tr>
                <td><?= $u['id'] ?></td>
                <td><?= $u['name'] ?></td>
                <td><?= $u['email'] ?></td>
                <td><?= $u['phone'] ?></td>
                <td><?= ($u['role_id'] == 1) ? '<span class="badge badge-danger">Admin</span>' : '<span class="badge badge-success">User</span>' ?></td>
                <td><?= $u['created_at'] ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php require_once '../app/Views/layouts/admin_footer.php'; ?>