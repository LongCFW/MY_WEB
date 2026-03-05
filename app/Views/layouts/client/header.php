<?php
// --- [LOGIC ĐẾM GIỎ HÀNG] ---
$cartCount = 0;

if (isset($_SESSION['user_logged_in'])) {
    // Nếu đã đăng nhập: Gọi Model CartItem để đếm từ Database
    try {
        if (class_exists('\App\Models\CartItem')) {
            $cartModelHeader = new \App\Models\CartItem();
            $cartCount = $cartModelHeader->countCartItems($_SESSION['user_id']);
        }
    } catch (\Exception $e) {
        $cartCount = 0;
    }
} else {
    // Nếu chưa đăng nhập: Đếm từ Session
    $cartCount = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
}

// --- [LOGIC LẤY CÂY DANH MỤC CHO HEADER] ---
$catTree = [];
try {
    if (class_exists('\App\Models\Category')) {
        $categoryModelHeader = new \App\Models\Category();
        $categoriesHeader = $categoryModelHeader->all();

        // Chuyển mảng phẳng thành cây (Cha -> Con)
        foreach ($categoriesHeader as $c) {
            if (empty($c['parent_id'])) {
                $catTree[$c['id']] = $c;
                $catTree[$c['id']]['children'] = [];
            }
        }
        foreach ($categoriesHeader as $c) {
            if (!empty($c['parent_id']) && isset($catTree[$c['parent_id']])) {
                $catTree[$c['parent_id']]['children'][] = $c;
            }
        }
    }
} catch (\Exception $e) {
    // Bỏ qua nếu lỗi
}

// --- [LOGIC ĐẾM THÔNG BÁO CHƯA ĐỌC] ---
$unreadCount = 0;
if (isset($_SESSION['user_logged_in'])) {
    try {
        if (class_exists('\App\Models\Notification')) {
            $notifModelHeader = new \App\Models\Notification();
            $unreadCount = $notifModelHeader->countUnread($_SESSION['user_id']);
        }
    } catch (\Exception $e) {
    }
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoStore - Sống Xanh</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="/MY_WEB/public/assets/css/global.css">
    <link rel="stylesheet" href="/MY_WEB/public/assets/css/home.css">
    <link rel="stylesheet" href="/MY_WEB/public/assets/css/product.css">
    <link rel="stylesheet" href="/MY_WEB/public/assets/css/auth-profile.css">
    <link rel="stylesheet" href="/MY_WEB/public/assets/css/cart-checkout.css">

    <style>
        /* CSS cho Menu Danh Mục (Dropdown Bách Hóa Xanh) */
        .category-menu-wrapper {
            position: relative;
            display: inline-block;
        }

        .category-dropdown-menu {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            background: #fff;
            min-width: 250px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            border-radius: 12px;
            z-index: 1000;
            padding: 10px 0;
            border: 1px solid #eee;            
            transform: translateY(10px);
        }

        .category-dropdown-menu::before {
            content: '';
            position: absolute;
            top: -15px; /* Phủ kín khoảng cách 10px phía trên */
            left: 0;
            width: 100%;
            height: 15px;
            background: transparent;
        }

        .category-menu-wrapper:hover .category-dropdown-menu {
            display: block;
            animation: fadeIn 0.2s ease-in-out forwards;
        }

        .cat-parent {
            position: relative;
            padding: 12px 20px;
            font-weight: 500;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: all 0.2s;
        }

        .cat-parent:hover {
            background: #f1f8f5;
            color: #2e7d32;
        }

        .cat-parent a {
            color: inherit;
            text-decoration: none;
            display: block;
            width: 100%;
        }

        /* Submenu con */
        .cat-submenu {
            display: none;
            position: absolute;
            top: -1px;
            left: 100%;
            background: #fff;
            min-width: 220px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            border-radius: 12px;
            min-height: 100%;
            padding: 10px 0;
            border: 1px solid #eee;
        }

        .cat-parent:hover .cat-submenu {
            display: block;
            animation: fadeInLeft 0.2s ease-in-out;
        }

        .cat-submenu a {
            padding: 10px 20px;
            display: block;
            text-decoration: none;
            color: #555;
            transition: all 0.2s;
        }

        .cat-submenu a:hover {
            background: #f1f8f5;
            color: #2e7d32;
            font-weight: bold;
        }

        @keyframes fadeInMenu {
            from {
                opacity: 0;
                transform: translateY(15px);
            }
            to {
                opacity: 1;
                transform: translateY(10px); /* Giữ lại khoảng hở đẹp mắt */
            }
        }

        @keyframes fadeInLeft {
            from {
                opacity: 0;
                transform: translateX(10px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg sticky-top py-3 bg-white shadow-sm" style="z-index: 1020;">
        <div class="container">
            <a class="navbar-brand fw-bold fs-3 d-flex align-items-center gap-2 me-lg-4" href="/MY_WEB/public/">
                <div class="bg-success bg-opacity-10 p-2 rounded-circle d-flex align-items-center justify-content-center">
                    <i class="fas fa-leaf text-success"></i>
                </div>
                <span class="text-gradient">EcoStore</span>
            </a>

            <button class="navbar-toggler border-0 text-success" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar">
                <i class="fas fa-bars fs-2"></i>
            </button>

            <div class="collapse navbar-collapse d-none d-lg-flex" id="basic-navbar-nav">

                <div class="category-menu-wrapper me-2">
                    <button class="btn btn-success fw-bold rounded-pill px-4 shadow-sm py-2">
                        <i class="fas fa-bars me-2"></i> Danh Mục
                    </button>
                    <div class="category-dropdown-menu">
                        <?php if (!empty($catTree)): ?>
                            <?php foreach ($catTree as $parent): ?>
                                <div class="cat-parent">
                                    <a href="/MY_WEB/public/product?category=<?= $parent['id'] ?>">
                                        <?= htmlspecialchars($parent['name']) ?>
                                    </a>
                                    <?php if (!empty($parent['children'])): ?>
                                        <i class="fas fa-chevron-right small text-muted"></i>
                                        <div class="cat-submenu">
                                            <?php foreach ($parent['children'] as $child): ?>
                                                <a href="/MY_WEB/public/product?category=<?= $child['id'] ?>">
                                                    <?= htmlspecialchars($child['name']) ?>
                                                </a>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="p-3 text-muted small text-center">Đang cập nhật danh mục...</div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="mx-auto w-100 px-lg-3" style="max-width: 500px;">
                    <div class="position-relative w-100 cursor-pointer" data-bs-toggle="modal" data-bs-target="#searchModal">
                        <input type="text" placeholder="Tìm kiếm sản phẩm xanh..."
                            class="form-control rounded-pill border-0 bg-light py-2 ps-4 pe-5 shadow-sm"
                            readonly style="cursor: pointer; background-color: #f8f9fa !important;">
                        <button type="button" class="btn btn-link position-absolute top-50 end-0 translate-middle-y text-success pe-3">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-4 ms-auto">
                    <div class="d-flex gap-3 fw-medium">
                        <a href="/MY_WEB/public/" class="text-dark text-decoration-none hover-green pb-1">Trang chủ</a>
                        <a href="/MY_WEB/public/product" class="text-dark text-decoration-none hover-green pb-1">Sản phẩm</a>
                        <a href="/MY_WEB/public/offer" class="text-dark text-decoration-none hover-green pb-1">Ưu đãi</a>
                    </div>

                    <div class="vr text-secondary opacity-25" style="height: 25px;"></div>

                    <div class="d-flex align-items-center gap-3">

                        <?php if (isset($_SESSION['user_logged_in'])): ?>
                            <a href="/MY_WEB/public/account?page=notification" class="position-relative btn btn-light rounded-circle shadow-sm d-flex align-items-center justify-content-center text-success" style="width: 42px; height: 42px;">
                                <i class="fas fa-bell"></i>
                                <?php if ($unreadCount > 0): ?>
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-light" style="font-size: 0.65rem;">
                                        <?= $unreadCount > 99 ? '99+' : $unreadCount ?>
                                    </span>
                                <?php endif; ?>
                            </a>
                        <?php endif; ?>

                        <a href="/MY_WEB/public/cart" class="position-relative btn btn-light rounded-circle shadow-sm d-flex align-items-center justify-content-center text-success cart-icon-hover" style="width: 42px; height: 42px;">
                            <i class="fas fa-shopping-cart"></i>
                            <?php if ($cartCount > 0): ?>
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-light" style="font-size: 0.7rem;">
                                    <?= $cartCount ?>
                                </span>
                            <?php endif; ?>
                        </a>

                        <div class="dropdown">
                            <button class="btn btn-light rounded-circle d-flex align-items-center justify-content-center shadow-sm user-icon-hover border border-success p-0" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="width: 42px; height: 42px;">
                                <?php if (isset($_SESSION['user_logged_in'])): ?>
                                    <?php if (!empty($_SESSION['user_avatar'])): ?>
                                        <img src="/MY_WEB/public/<?= $_SESSION['user_avatar'] ?>" class="rounded-circle w-100 h-100 object-fit-cover">
                                    <?php else: ?>
                                        <span class="fw-bold text-success"><?= strtoupper(substr($_SESSION['user_name'], 0, 1)) ?></span>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <i class="fas fa-user-circle fs-4 text-success"></i>
                                <?php endif; ?>
                            </button>

                            <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg p-2 mt-3 rounded-4" style="min-width: 240px;">
                                <?php if (isset($_SESSION['user_logged_in'])): ?>
                                    <li>
                                        <div class="px-3 py-2 border-bottom mb-2 bg-light rounded-top-3 mx-n2 mt-n2">
                                            <div class="fw-bold text-dark">Xin chào <?= $_SESSION['user_name'] ?></div>
                                            <small class="text-muted"><?= $_SESSION['user_email'] ?></small>
                                        </div>
                                    </li>
                                    <?php
                                    // Kiểm tra xem User đăng nhập có phải là Admin/Staff không (dựa vào session admin_role hoặc user_role)
                                    $role = $_SESSION['admin_role'] ?? $_SESSION['user_role'] ?? null;
                                    if ($role && in_array($role, [1, 2, 3])):
                                    ?>
                                        <li><a class="dropdown-item rounded-2 py-2 mb-1 fw-bold text-success" href="/MY_WEB/public/admin/dashboard">
                                                <i class="fas fa-tachometer-alt me-2"></i> Vào Admin Dashboard
                                            </a></li>
                                    <?php endif; ?>
                                    <li><a class="dropdown-item rounded-2 py-2 mb-1 fw-medium" href="/MY_WEB/public/account">
                                            <i class="fas fa-user-circle me-2 text-success"></i> Tài khoản
                                        </a></li>
                                    <li><a class="dropdown-item rounded-2 py-2 mb-1 fw-medium" href="/MY_WEB/public/account?page=orders">
                                            <i class="fa-solid fa-file-invoice-dollar me-2"></i> Lịch sử đơn hàng
                                        </a></li>
                                    <li><a class="dropdown-item rounded-2 py-2 mb-1 fw-medium" href="/MY_WEB/public/account?page=wishlist"><i class="fas fa-heart me-2 text-danger"></i> Yêu Thích</a></li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
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

    <div class="modal fade" id="searchModal" tabindex="-1" aria-hidden="true" style="z-index: 1060;">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content bg-dark bg-opacity-95 text-white">
                <div class="modal-header border-0">
                    <button type="button" class="btn-close btn-close-white ms-auto transform-hover" data-bs-dismiss="modal" style="font-size: 1.5rem;"></button>
                </div>
                <div class="modal-body d-flex flex-column align-items-center justify-content-center">
                    <h2 class="fw-bold mb-4 animate-up">Bạn đang tìm gì hôm nay?</h2>
                    <form action="/MY_WEB/public/product/search" class="w-100" style="max-width: 600px;">
                        <div class="input-group input-group-lg border-bottom border-light">
                            <span class="input-group-text bg-transparent border-0 text-white"><i class="fas fa-search fs-3"></i></span>
                            <input type="text" name="keyword" class="form-control bg-transparent border-0 text-white fs-2 shadow-none placeholder-white" placeholder="Nhập tên sản phẩm..." autofocus>
                        </div>
                    </form>
                    <div class="mt-4 text-white-50">
                        <small>Gợi ý: Rau cải, Mật ong, Sữa hạt...</small>
                    </div>
                </div>
            </div>
        </div>
    </div>