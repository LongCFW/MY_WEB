<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm mới người dùng</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
</head>
<body>
    <?php require_once '../app/Views/layouts/admin_sidebar.php'; ?>

    <div class="container mt-4">
        <div class="card shadow">
            <div class="card-header bg-success text-white">Thêm người dùng mới</div>
            <div class="card-body">
                <form action="/MY_WEB/public/admin/user/store" method="POST">
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>Họ và tên</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>Số điện thoại</label>
                            <input type="text" name="phone" class="form-control">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Mật khẩu</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Vai trò</label>
                        <select name="role_id" class="form-control">
                            <option value="2">Khách hàng (User)</option>
                            <option value="1">Quản trị viên (Admin)</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-success">Lưu lại</button>
                    <a href="/MY_WEB/public/admin/user" class="btn btn-secondary">Hủy</a>
                </form>
            </div>
        </div>
    </div>

    <?php require_once '../app/Views/layouts/admin_footer.php'; ?>
</body>
</html>