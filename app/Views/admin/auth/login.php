<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập Admin - Ecostore</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <style>
        body { background-color: #f8f9fa; display: flex; align-items: center; justify-content: center; height: 100vh; }
        .login-card { width: 100%; max-width: 400px; padding: 20px; box-shadow: 0 0 10px rgba(0,0,0,0.1); background: white; border-radius: 8px; }
    </style>
</head>
<body>

<div class="login-card">
    <h3 class="text-center mb-4">Admin Login</h3>    
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form action="/MY_WEB/public/admin/auth/handleLogin" method="POST">
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required placeholder="admin@ecostore.com">
        </div>
        <div class="form-group">
            <label>Mật khẩu</label>
            <input type="password" name="password" class="form-control" required placeholder="password">
        </div>
        <button type="submit" class="btn btn-primary btn-block">Đăng nhập</button>
    </form>
    <div class="text-center mt-3">
        <small>Thông tin test: admin@ecostore.com / 123456</small>
    </div>
</div>

</body>
</html>