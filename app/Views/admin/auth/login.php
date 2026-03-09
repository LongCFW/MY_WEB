<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập Admin - Ecostore</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .login-card {
            width: 100%;
            max-width: 400px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            background: white;
            border-radius: 8px;
        }

        /* --- CSS Hiệu ứng cho nút Quay lại --- */
        .back-home-link {
            display: inline-block;
            color: #6c757d; /* Màu xám mặc định giống text-muted */
            transition: all 0.3s ease; /* Chuyển động mượt 0.3s */
        }

        .back-home-link i {
            transition: transform 0.3s ease;
        }

        .back-home-link:hover {
            color: #28a745; /* Đổi sang màu xanh lá khi di chuột */
            text-decoration: none;
        }

        .back-home-link:hover i {
            transform: translateX(-5px); /* Trượt mũi tên sang trái 5px */
        }
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
            <small>Thông tin test: admin@ecostore.com / 123456</small> <br>
            <small>Thông tin test: manager@ecostore.com / 123456</small> <br>
            <small>Thông tin test: staff@ecostore.com / 123456</small>
        </div>
        
        <div class="text-center mt-4 border-top pt-3">
            <a href="/MY_WEB/public/" class="back-home-link">
                <i class="fas fa-arrow-left"></i> Quay lại trang chủ
            </a>
        </div>
    </div>

</body>

</html>