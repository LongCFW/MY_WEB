<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt lại mật khẩu - EcoStore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="/MY_WEB/public/assets/css/auth-profile.css">
    <link rel="stylesheet" href="/MY_WEB/public/assets/css/global.css">
</head>
<body>

<div class="auth-wrapper">
    <div class="auth-banner-side" style="background-image: url('https://images.unsplash.com/photo-1550989460-0adf9ea622e2?auto=format&fit=crop&w=1000&q=80');">
        <div class="auth-banner-overlay"></div>
        <div class="auth-banner-content text-white text-center">
            <div class="bg-white p-3 rounded-circle d-inline-flex mb-4 shadow-lg">
                <i class="fas fa-key text-success fs-1"></i>
            </div>
            <h1 class="display-5 fw-bold mb-3">Bảo Mật Tài Khoản</h1>
            <p class="fs-5 opacity-90">Đặt mật khẩu mới để bảo vệ tài khoản của bạn.</p>
        </div>
    </div>

    <div class="auth-form-side position-relative">
        <div class="auth-form-container" style="max-width: 450px; margin: 0 auto;">
            <div class="text-center mb-5">
                <h2 class="fw-bold text-dark mb-2">Đặt Mật Khẩu Mới</h2>
                <p class="text-muted">Vui lòng nhập mật khẩu mới cho tài khoản của bạn</p>
            </div>

            <?php if(isset($error)): ?>
                <div class="alert alert-danger text-center rounded-3 border-0 shadow-sm mb-4">
                    <?= $error ?>
                </div>
            <?php endif; ?>

            <form action="/MY_WEB/public/auth/handleResetPassword" method="POST">
                <div class="mb-3">
                    <label class="fw-bold small text-secondary">MẬT KHẨU MỚI</label>
                    <input type="password" name="password" class="form-control modern-input p-3 bg-light border-0" placeholder="••••••••" required minlength="6">
                </div>
                <div class="mb-4">
                    <label class="fw-bold small text-secondary">XÁC NHẬN MẬT KHẨU</label>
                    <input type="password" name="confirm_password" class="form-control modern-input p-3 bg-light border-0" placeholder="••••••••" required minlength="6">
                </div>
                
                <button type="submit" class="btn btn-success w-100 py-3 rounded-pill fw-bold shadow-sm text-uppercase">
                    Cập Nhật Mật Khẩu
                </button>
            </form>
        </div>
    </div>
</div>
</body>
</html>