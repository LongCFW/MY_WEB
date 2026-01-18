<div class="d-flex" id="wrapper">
    <div class="bg-dark border-right" id="sidebar-wrapper" style="min-width: 250px; min-height: 100vh;">
        <div class="sidebar-heading text-white font-weight-bold p-3">ECOSTORE ADMIN</div>
        <div class="list-group list-group-flush">
            <a href="/MY_WEB/public/admin/dashboard" class="list-group-item list-group-item-action bg-dark text-white">
                Dashboard
            </a>
            <a href="/MY_WEB/public/admin/category" class="list-group-item list-group-item-action bg-dark text-white">
                Quản lý Danh mục
            </a>
            <a href="/MY_WEB/public/admin/product" class="list-group-item list-group-item-action bg-dark text-white">
                Quản lý Sản phẩm
            </a>
            <a href="/MY_WEB/public/admin/order" class="list-group-item list-group-item-action bg-dark text-white">
                Quản lý Đơn hàng
            </a>
            <a href="/MY_WEB/public/admin/user" class="list-group-item list-group-item-action bg-dark text-white">
                Quản lý Khách hàng
            </a>
            <a href="/MY_WEB/public/admin/auth/logout" class="list-group-item list-group-item-action bg-danger text-white mt-5">
                Đăng xuất
            </a>
        </div>
    </div>
    <div id="page-content-wrapper" class="w-100">
        <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
            <span class="navbar-text ml-auto">
                Xin chào, <strong><?= $_SESSION['admin_name'] ?? 'Admin' ?></strong>
            </span>
        </nav>
        <div class="container-fluid p-4"></div>