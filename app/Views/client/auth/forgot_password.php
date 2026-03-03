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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .otp-input { letter-spacing: 5px; font-size: 1.5rem; text-align: center; }
    </style>
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
            
            <div id="step1">
                <div class="text-center mb-5">
                    <h2 class="fw-bold text-dark mb-2">Quên Mật Khẩu?</h2>
                    <p class="text-muted">Nhập Email đăng ký để nhận mã xác minh</p>
                </div>

                <div id="emailError" class="alert alert-danger text-center rounded-3 border-0 shadow-sm mb-4 d-none">
                    <i class="fas fa-exclamation-triangle me-2"></i> <span></span>
                </div>

                <form id="forgotForm">
                    <div class="mb-4">
                        <label class="fw-bold small text-secondary">EMAIL ĐĂNG KÝ</label>
                        <input type="email" name="email" id="regEmail" class="form-control modern-input p-3 bg-light border-0" placeholder="name@example.com" required>
                    </div>
                    
                    <button type="submit" id="btnSendCode" class="btn btn-success w-100 py-3 rounded-pill fw-bold shadow-sm text-uppercase">
                        Gửi Mã Xác Nhận
                    </button>
                </form>
            </div>

            <div id="step2" class="d-none">
                <div class="text-center mb-4">
                    <div class="bg-success bg-opacity-10 text-success p-3 rounded-circle d-inline-flex mb-3">
                        <i class="fas fa-key fs-2"></i>
                    </div>
                    <h2 class="fw-bold text-dark mb-2">Xác Thực Mã</h2>
                    <p class="text-muted">Chúng tôi đã gửi mã OTP 6 số đến email <br><strong id="displayEmail" class="text-dark"></strong></p>
                </div>

                <div id="otpError" class="alert alert-danger text-center rounded-3 border-0 shadow-sm mb-4 d-none"></div>

                <form id="otpForm">
                    <input type="hidden" name="user_id" id="hiddenUserId">
                    
                    <div class="mb-4">
                        <input type="text" name="otp" class="form-control modern-input border-0 bg-light py-3 px-3 otp-input" placeholder="------" required maxlength="6" autocomplete="off">
                    </div>

                    <button type="submit" id="btnVerify" class="btn btn-success w-100 py-3 rounded-pill fw-bold shadow-sm text-uppercase mb-3">
                        Xác Nhận OTP
                    </button>
                    
                    <div class="text-center">
                        <span class="text-muted small">Không nhận được mã? </span>
                        <a href="javascript:void(0)" onclick="document.getElementById('btnSendCode').click()" class="text-decoration-none fw-bold text-success small">Gửi lại</a>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<script>
    // AJAX BƯỚC 1: GỬI YÊU CẦU OTP
    document.getElementById('forgotForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const btn = document.getElementById('btnSendCode');
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Đang gửi mã...';
        btn.disabled = true;
        
        const formData = new FormData(this);

        fetch('/MY_WEB/public/auth/handleForgotPassword', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            btn.innerHTML = 'Gửi Mã Xác Nhận';
            btn.disabled = false;

            if(data.status === 'success') {
                // Ẩn Form 1, Hiện Form 2
                document.getElementById('step1').classList.add('d-none');
                document.getElementById('step2').classList.remove('d-none');
                
                document.getElementById('hiddenUserId').value = data.user_id;
                document.getElementById('displayEmail').innerText = document.getElementById('regEmail').value;
                
                Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: data.message, showConfirmButton: false, timer: 4000 });
            } else {
                const errBox = document.getElementById('emailError');
                errBox.classList.remove('d-none');
                errBox.querySelector('span').innerText = data.message;
            }
        })
        .catch(error => {
            btn.innerHTML = 'Gửi Mã Xác Nhận'; btn.disabled = false;
            alert("Có lỗi xảy ra, vui lòng thử lại!");
        });
    });

    // AJAX BƯỚC 2: KIỂM TRA OTP
    document.getElementById('otpForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const btn = document.getElementById('btnVerify');
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Đang kiểm tra...';
        btn.disabled = true;

        const formData = new FormData(this);

        fetch('/MY_WEB/public/auth/verifyResetOTP', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            btn.innerHTML = 'Xác Nhận OTP';
            btn.disabled = false;

            if(data.status === 'success') {
                Swal.fire({ icon: 'success', title: 'Thành công!', text: 'Đang chuyển hướng...', showConfirmButton: false, timer: 1500 })
                .then(() => {
                    // Chuyển thẳng sang trang đặt lại mật khẩu
                    window.location.href = '/MY_WEB/public/auth/resetPassword';
                });
            } else {
                const errBox = document.getElementById('otpError');
                errBox.classList.remove('d-none');
                errBox.innerText = data.message;
            }
        })
        .catch(error => {
            btn.innerHTML = 'Xác Nhận OTP'; btn.disabled = false;
            alert("Lỗi kết nối máy chủ!");
        });
    });
</script>

</body>
</html>