<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sửa người dùng</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
</head>
<body>
    <?php require_once '../app/Views/layouts/admin_sidebar.php'; ?>

    <div class="container mt-4">
        <div class="card shadow">
            <div class="card-header bg-warning">Cập nhật thông tin: <?= $user['name'] ?></div>
            <div class="card-body">
                <form action="/MY_WEB/public/admin/user/update/<?= $user['id'] ?>" method="POST">
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>Họ và tên</label>
                            <input type="text" name="name" class="form-control" value="<?= $user['name'] ?>" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" value="<?= $user['email'] ?>" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>Số điện thoại</label>
                            <input type="text" name="phone" class="form-control" value="<?= $user['phone'] ?>">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Mật khẩu mới (Để trống nếu không đổi)</label>
                            <input type="password" name="password" class="form-control" placeholder="******">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>Vai trò</label>
                            <select name="role_id" class="form-control">
                                <option value="2" <?= $user['role_id']==2?'selected':'' ?>>Khách hàng</option>
                                <option value="1" <?= $user['role_id']==1?'selected':'' ?>>Quản trị viên</option>
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Trạng thái</label>
                            <select name="status" class="form-control">
                                <option value="1" <?= $user['status']==1?'selected':'' ?>>Hoạt động</option>
                                <option value="0" <?= $user['status']==0?'selected':'' ?>>Bị khóa</option>
                            </select>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                    <a href="/MY_WEB/public/admin/user" class="btn btn-secondary">Hủy</a>
                </form>
            </div>
        </div>
    </div>

    <?php require_once '../app/Views/layouts/admin_footer.php'; ?>
</body>
</html>