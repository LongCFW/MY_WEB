<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập - EcoStore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="/MY_WEB/public/assets/css/auth-profile.css">
    <link rel="stylesheet" href="/MY_WEB/public/assets/css/global.css">
</head>

<body>

    <div class="auth-wrapper">
        <div class="auth-banner-side" style="background-image: url('https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?auto=format&fit=crop&w=1200&q=80')">
            <div class="auth-banner-overlay"></div>
            <div class="auth-banner-content text-white text-center">
                <div class="bg-white p-3 rounded-circle d-inline-flex mb-4 shadow-lg">
                    <i class="fas fa-leaf text-success fs-1"></i>
                </div>
                <h1 class="display-4 fw-bold mb-3">Sống Xanh <br /> Cùng EcoStore</h1>
                <p class="fs-5 opacity-90">Đăng nhập để nhận ưu đãi.</p>
            </div>
        </div>

        <div class="auth-form-side position-relative">
            <a href="/MY_WEB/public/" class="text-decoration-none text-muted fw-bold position-absolute top-0 start-0 m-4">
                <i class="fas fa-arrow-left me-2"></i> Về trang chủ
            </a>

            <div class="auth-form-container" style="max-width: 450px; margin: 0 auto;">
                <div class="text-center mb-5">
                    <h2 class="fw-bold text-dark mb-2">Chào Mừng Trở Lại!</h2>
                    <p class="text-muted">Vui lòng đăng nhập tài khoản của bạn</p>
                </div>

                <?php if (isset($error)): ?>
                    <div class="alert alert-danger text-center"><?= $error ?></div>
                <?php endif; ?>

                <form action="/MY_WEB/public/auth/handleLogin" method="POST">
                    <div class="mb-3">
                        <label class="fw-bold small text-secondary">EMAIL</label>
                        <input type="email" name="email" class="form-control modern-input p-3 bg-light border-0" placeholder="name@example.com" required>
                    </div>
                    <div class="mb-3">
                        <label class="fw-bold small text-secondary">MẬT KHẨU</label>
                        <input type="password" name="password" class="form-control modern-input p-3 bg-light border-0" placeholder="••••••••" required>
                    </div>
                    <div class="d-flex justify-content-end mb-3">
                        <a href="/MY_WEB/public/auth/forgotPassword" class="text-decoration-none text-muted small hover-success">Quên mật khẩu?</a>
                    </div>
                    <button type="submit" class="btn btn-success w-100 py-3 rounded-pill fw-bold shadow-sm mb-3 text-uppercase">
                        Đăng Nhập
                    </button>

                    <div class="d-flex align-items-center mb-4">
                        <hr class="flex-grow-1">
                        <span class="px-3 text-muted small">HOẶC</span>
                        <hr class="flex-grow-1">
                    </div>
                    
                    <a href="<?= isset($googleLoginUrl) ? $googleLoginUrl : '#' ?>" class="btn btn-outline-dark w-100 py-3 rounded-pill fw-bold shadow-sm d-flex align-items-center justify-content-center mb-4 transition-hover">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/c/c1/Google_%22G%22_logo.svg" alt="Google Logo" style="width: 20px; margin-right: 10px;">
                        Đăng nhập bằng Google
                    </a>
                    <div class="text-center mt-4">
                        <span class="text-muted">Chưa có tài khoản? </span>
                        <a href="/MY_WEB/public/auth/register" class="text-decoration-none fw-bold text-success">Đăng ký ngay</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>