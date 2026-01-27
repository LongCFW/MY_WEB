<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quên mật khẩu - EcoStore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="/MY_WEB/public/assets/css/auth-profile.css">
    <link rel="stylesheet" href="/MY_WEB/public/assets/css/global.css">
</head>
<body>

<div class="auth-wrapper">
    <div class="auth-banner-side" style="background-image: url('https://images.unsplash.com/photo-1466692476868-aef1dfb1e735?auto=format&fit=crop&w=1000&q=80');">
        <div class="auth-banner-overlay"></div>
        <div class="auth-banner-content text-white text-center">
            <div class="bg-white p-3 rounded-circle d-inline-flex mb-4 shadow-lg">
                <i class="fas fa-lock-open text-success fs-1"></i>
            </div>
            <h1 class="display-5 fw-bold mb-3">Khôi Phục Tài Khoản</h1>
            <p class="fs-5 opacity-90">Chúng tôi sẽ giúp bạn lấy lại mật khẩu nhanh chóng.</p>
        </div>
    </div>

    <div class="auth-form-side position-relative">
        <a href="/MY_WEB/public/auth/login" class="text-decoration-none text-muted fw-bold position-absolute top-0 start-0 m-4">
            <i class="fas fa-arrow-left me-2"></i> Quay lại đăng nhập
        </a>
        
        <div class="auth-form-container" style="max-width: 450px; margin: 0 auto;">
            <div class="text-center mb-5">
                <h2 class="fw-bold text-dark mb-2">Quên Mật Khẩu?</h2>
                <p class="text-muted">Nhập thông tin xác thực để đặt lại mật khẩu</p>
            </div>

            <?php if(isset($error)): ?>
                <div class="alert alert-danger text-center rounded-3 border-0 shadow-sm mb-4">
                    <i class="fas fa-exclamation-triangle me-2"></i> <?= $error ?>
                </div>
            <?php endif; ?>

            <form action="/MY_WEB/public/auth/handleForgotPassword" method="POST">
                <div class="mb-3">
                    <label class="fw-bold small text-secondary">EMAIL ĐĂNG KÝ</label>
                    <input type="email" name="email" class="form-control modern-input p-3 bg-light border-0" placeholder="name@example.com" required>
                </div>
                <div class="mb-4">
                    <label class="fw-bold small text-secondary">SỐ ĐIỆN THOẠI</label>
                    <input type="text" name="phone" class="form-control modern-input p-3 bg-light border-0" placeholder="0901234567" required>
                </div>
                
                <button type="submit" class="btn btn-success w-100 py-3 rounded-pill fw-bold shadow-sm text-uppercase">
                    Xác Thực Thông Tin
                </button>
            </form>
        </div>
    </div>
</div>
</body>
</html>