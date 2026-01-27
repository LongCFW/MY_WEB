<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký tài khoản - EcoStore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="/MY_WEB/public/assets/css/global.css">
    <link rel="stylesheet" href="/MY_WEB/public/assets/css/auth-profile.css">
</head>
<body>

<div class="auth-wrapper">
    <div class="auth-banner-side" style="background-image: url('https://images.unsplash.com/photo-1518531933037-91b2f5f229cc?auto=format&fit=crop&w=1000&q=80');">
        <div class="auth-banner-overlay"></div>
        <div class="auth-banner-content text-white text-center">
            <div class="bg-white p-3 rounded-circle d-inline-flex mb-4 shadow-lg" style="width: 80px; height: 80px; align-items: center; justify-content: center;">
                <i class="fas fa-leaf text-success fs-1"></i>
            </div>
            <h1 class="display-5 fw-bold mb-3">Tham Gia Ngay</h1>
            <p class="fs-5 opacity-90">Tạo tài khoản để nhận ngay Voucher 50k.</p>
        </div>
    </div>

    <div class="auth-form-side position-relative">
        <a href="/MY_WEB/public/" class="btn btn-light rounded-pill position-absolute top-0 start-0 m-4 fw-bold text-secondary shadow-sm">
            <i class="fas fa-arrow-left me-2"></i> Về trang chủ
        </a>
        
        <div class="auth-form-container" style="max-width: 450px; margin: 0 auto;">
            <div class="text-center mb-5">
                <h2 class="fw-bold text-dark mb-2">Tạo Tài Khoản Mới</h2>
                <p class="text-muted">Nhập thông tin của bạn bên dưới</p>
            </div>

            <?php if(isset($error)): ?>
                <div class="alert alert-danger text-center rounded-3 border-0 shadow-sm mb-4">
                    <i class="fas fa-exclamation-circle me-2"></i> <?= $error ?>
                </div>
            <?php endif; ?>

            <form action="/MY_WEB/public/auth/handleRegister" method="POST">
                <div class="mb-3">
                    <label class="fw-bold small text-secondary mb-1">HỌ VÀ TÊN</label>
                    <input type="text" name="name" class="form-control modern-input border-0 bg-light py-3 px-3" placeholder="Nguyễn Văn A" required>
                </div>

                <div class="mb-3">
                    <label class="fw-bold small text-secondary mb-1">EMAIL</label>
                    <input type="email" name="email" class="form-control modern-input border-0 bg-light py-3 px-3" placeholder="name@example.com" required>
                </div>

                <div class="mb-3">
                    <label class="fw-bold small text-secondary mb-1">SỐ ĐIỆN THOẠI</label>
                    <input type="text" name="phone" class="form-control modern-input border-0 bg-light py-3 px-3" placeholder="0901234567" required>
                </div>

                <div class="mb-4">
                    <label class="fw-bold small text-secondary mb-1">MẬT KHẨU</label>
                    <input type="password" name="password" class="form-control modern-input border-0 bg-light py-3 px-3" placeholder="Tối thiểu 6 ký tự" required minlength="6">
                </div>

                <button type="submit" class="btn btn-success w-100 py-3 rounded-pill fw-bold shadow-sm text-uppercase mb-4">
                    Đăng Ký Thành Viên
                </button>

                <div class="text-center">
                    <span class="text-muted">Đã có tài khoản? </span>
                    <a href="/MY_WEB/public/auth/login" class="text-decoration-none fw-bold text-success">Đăng nhập</a>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>