<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoStore - Sống Xanh</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="/MY_WEB/public/assets/css/global.css">
    <link rel="stylesheet" href="/MY_WEB/public/assets/css/home.css">
    <link rel="stylesheet" href="/MY_WEB/public/assets/css/product.css">
    <link rel="stylesheet" href="/MY_WEB/public/assets/css/auth-profile.css">
    <link rel="stylesheet" href="/MY_WEB/public/assets/css/cart-checkout.css">
</head>
<body>

<nav class="navbar navbar-expand-lg sticky-top py-3 bg-white shadow-sm" style="z-index: 1020;">
    <div class="container">
        <a class="navbar-brand fw-bold fs-3 d-flex align-items-center gap-2 me-lg-5" href="/MY_WEB/public/">
            <div class="bg-success bg-opacity-10 p-2 rounded-circle d-flex align-items-center justify-content-center">
                <i class="fas fa-leaf text-success"></i>
            </div>
            <span class="text-gradient">EcoStore</span>
        </a>

        <button class="navbar-toggler border-0 text-success" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar">
            <i class="fas fa-bars fs-2"></i>
        </button>

        <div class="collapse navbar-collapse d-none d-lg-flex" id="basic-navbar-nav">
            <div class="mx-auto w-100 px-lg-5" style="max-width: 600px;">
                <form action="/MY_WEB/public/product/search" class="d-flex position-relative w-100">
                    <input type="search" name="keyword" placeholder="Tìm kiếm sản phẩm xanh..." 
                           class="form-control rounded-pill border-0 bg-light py-2 ps-4 pe-5 shadow-sm">
                    <button type="submit" class="btn btn-link position-absolute top-50 end-0 translate-middle-y text-success pe-3">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>

            <div class="d-flex align-items-center gap-4">
                <div class="d-flex gap-3 fw-medium">
                    <a href="/MY_WEB/public/" class="text-dark text-decoration-none hover-green pb-1">Trang chủ</a>
                    <a href="/MY_WEB/public/product" class="text-dark text-decoration-none hover-green pb-1">Sản phẩm</a>
                    <a href="/MY_WEB/public/offers" class="text-dark text-decoration-none hover-green pb-1">Ưu đãi</a>
                </div>

                <div class="vr text-secondary opacity-25" style="height: 25px;"></div>

                <div class="d-flex align-items-center gap-3">
                    <a href="/MY_WEB/public/cart" class="position-relative btn btn-light rounded-circle shadow-sm d-flex align-items-center justify-content-center text-success cart-icon-hover" style="width: 42px; height: 42px;">
                        <i class="fas fa-shopping-cart"></i>
                        <?php $cartCount = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0; ?>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-light"><?= $cartCount ?></span>
                    </a>

                    <div class="dropdown">
                        <button class="btn btn-light rounded-circle d-flex align-items-center justify-content-center shadow-sm user-icon-hover border border-success p-0" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="width: 42px; height: 42px;">
                            <?php if(isset($_SESSION['user_logged_in'])): ?>
                                <?php if(!empty($_SESSION['user_avatar'])): ?>
                                    <img src="/MY_WEB/public/<?= $_SESSION['user_avatar'] ?>" class="rounded-circle w-100 h-100 object-fit-cover">
                                <?php else: ?>
                                    <span class="fw-bold text-success"><?= strtoupper(substr($_SESSION['user_name'], 0, 1)) ?></span>
                                <?php endif; ?>
                            <?php else: ?>
                                <i class="fas fa-user-circle fs-4 text-success"></i>
                            <?php endif; ?>
                        </button>

                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg p-2 mt-3 rounded-4" style="min-width: 240px;">
                            <?php if(isset($_SESSION['user_logged_in'])): ?>
                                <li>
                                    <div class="px-3 py-2 border-bottom mb-2 bg-light rounded-top-3 mx-n2 mt-n2">
                                        <div class="fw-bold text-dark">Xin chào <?= $_SESSION['user_name'] ?></div>
                                        <small class="text-muted"><?= $_SESSION['user_email'] ?></small>
                                    </div>
                                </li>
                                <li><a class="dropdown-item rounded-2 py-2 mb-1 fw-medium" href="/MY_WEB/public/profile"><i class="fas fa-user-circle me-2 text-success"></i> Tài khoản</a></li>
                                <li><a class="dropdown-item rounded-2 py-2 mb-1 fw-medium" href="/MY_WEB/public/profile/orders"><i class="fas fa-box-open me-2 text-primary"></i> Đơn mua</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger rounded-2 py-2 fw-bold" href="/MY_WEB/public/auth/logout"><i class="fas fa-sign-out-alt me-2"></i> Đăng xuất</a></li>
                            <?php else: ?>
                                <li class="px-3 py-2 text-center">
                                    <span class="fw-bold d-block mb-1">Chào mừng bạn!</span>
                                    <small class="text-muted">Đăng nhập để mua sắm dễ dàng hơn</small>
                                </li>
                                <li class="p-2 d-grid gap-2">
                                    <a href="/MY_WEB/public/auth/login" class="btn btn-success rounded-pill fw-bold btn-sm shadow-sm">
                                        <i class="fas fa-sign-in-alt me-2"></i> Đăng nhập
                                    </a>
                                    <a href="/MY_WEB/public/auth/register" class="btn btn-outline-success rounded-pill fw-bold btn-sm">
                                        <i class="fas fa-user-plus me-2"></i> Đăng ký
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>