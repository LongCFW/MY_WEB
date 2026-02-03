<?php require_once '../app/Views/layouts/client/header.php'; ?>

<style>
    /* CSS Fix riêng cho Profile */
    .profile-sidebar {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04);
        overflow: hidden;
    }

    .profile-user-info {
        background: linear-gradient(135deg, #e8f5e9 0%, #fff 100%);
        padding: 30px 20px;
        text-align: center;
        border-bottom: 1px solid #eee;
    }

    .profile-menu a {
        display: flex;
        align-items: center;
        padding: 15px 25px;
        color: #555;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.2s;
        border-left: 4px solid transparent;
    }

    .profile-menu a:hover {
        background-color: #f9f9f9;
        color: var(--eco-primary);
    }

    .profile-menu a.active {
        background-color: #f0fdf4;
        color: var(--eco-primary);
        border-left-color: var(--eco-primary);
        font-weight: 700;
    }

    .profile-menu a i {
        width: 24px;
        margin-right: 10px;
        text-align: center;
    }

    .profile-content {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04);
        padding: 30px;
        min-height: 500px;
    }
</style>

<div class="bg-light min-vh-100 py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 mb-4">
                <div class="profile-sidebar">
                    <div class="profile-user-info">
                        <?php
                        $avatar = !empty($user['avatar_url']) ? "/MY_WEB/public/" . $user['avatar_url'] : "https://ui-avatars.com/api/?name=" . urlencode($user['name']) . "&background=2e7d32&color=fff";
                        ?>
                        <img src="<?= $avatar ?>" class="rounded-circle mb-3 border border-4 border-white shadow-sm" width="100" height="100">
                        <h5 class="fw-bold mb-1"><?= htmlspecialchars($user['name']) ?></h5>
                        <div class="badge bg-success bg-opacity-10 text-success rounded-pill px-3">Thành viên Bạc</div>
                    </div>

                    <div class="profile-menu py-2">
                        <a href="/MY_WEB/public/account?page=info" class="<?= ($current_page == 'info') ? 'active' : '' ?>">
                            <i class="fas fa-user"></i> Thông tin tài khoản
                        </a>
                        <a href="/MY_WEB/public/account?page=orders" class="<?= ($current_page == 'orders') ? 'active' : '' ?>">
                            <i class="fas fa-clipboard-list"></i> Quản lý đơn hàng
                        </a>
                        <a href="/MY_WEB/public/account?page=address" class="<?= ($current_page == 'address') ? 'active' : '' ?>">
                            <i class="fas fa-map-marker-alt"></i> Sổ địa chỉ
                        </a>
                        <a href="/MY_WEB/public/account?page=wishlist" class="<?= ($current_page == 'wishlist') ? 'active' : '' ?>">
                            <i class="fas fa-heart"></i> Sản phẩm yêu thích
                        </a>
                        <a href="#" class="<?= ($current_page == 'voucher') ? 'active' : '' ?>">
                            <i class="fas fa-ticket-alt"></i> Kho Voucher
                        </a>
                        <a href="#" class="<?= ($current_page == 'notification') ? 'active' : '' ?>">
                            <i class="fas fa-bell"></i> Thông báo
                        </a>
                        <hr class="my-2 text-muted opacity-25">
                        <a href="/MY_WEB/public/auth/logout" class="text-danger">
                            <i class="fas fa-sign-out-alt"></i> Đăng xuất
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-lg-9">
                <div class="profile-content">
                    <?php
                    // Logic Router View đơn giản
                    switch ($current_page) {
                        case 'info':
                            require_once 'parts/info.php';
                            break;
                        case 'orders':
                            require_once 'parts/orders.php';
                            break;
                        case 'address':
                            require_once 'parts/address.php';
                            break;
                        case 'wishlist':
                            require_once 'parts/wishlist.php';
                            break;
                        default:
                            echo "<div class='text-center py-5 text-muted'>Chức năng đang phát triển...</div>";
                            break;
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if ($current_page == 'orders'): ?>
    <div class="modal fade" id="orderDetailModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content rounded-4 border-0">
                <div class="modal-header bg-white border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold">Chi tiết đơn hàng <span id="modalOrderNumber" class="text-success"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="modalLoading" class="text-center py-5">
                        <div class="spinner-border text-success" role="status"></div>
                    </div>
                    <div id="modalContent" style="display:none;">
                        <div class="row mb-3 mt-2">
                            <div class="col-md-6">
                                <p class="mb-1 text-muted small">Ngày đặt</p>
                                <p class="fw-bold" id="modalDate"></p>
                            </div>
                            <div class="col-md-6 text-md-end">
                                <p class="mb-1 text-muted small">Trạng thái</p>
                                <div id="modalStatusBadge"></div>
                            </div>
                        </div>

                        <div class="bg-light p-3 rounded-3 mb-3">
                            <p class="mb-1 fw-bold"><i class="fas fa-map-marker-alt text-danger me-2"></i> Địa chỉ nhận hàng</p>
                            <p class="mb-0 text-secondary" id="modalAddress"></p>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <tbody id="modalOrderItems"></tbody>
                                <tfoot class="border-top">
                                    <tr>
                                        <td colspan="2" class="text-end">Phí ship:</td>
                                        <td class="text-end" id="modalShipping"></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" class="text-end fw-bold">Tổng cộng:</td>
                                        <td class="text-end fw-bold text-success fs-5" id="modalTotal"></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Hàm xem chi tiết đơn hàng (Chỉ chạy khi ở trang orders)
        function viewOrder(id) {
            const modal = new bootstrap.Modal(document.getElementById('orderDetailModal'));
            document.getElementById('modalLoading').style.display = 'block';
            document.getElementById('modalContent').style.display = 'none';
            modal.show();

            fetch(`/MY_WEB/public/order/get_detail/${id}`)
                .then(res => res.json())
                .then(data => {
                    if (data.error) {
                        alert(data.error);
                        return;
                    }
                    const order = data.order;
                    const items = data.items;

                    // Fill Info
                    document.getElementById('modalOrderNumber').innerText = '#' + order.order_number;
                    document.getElementById('modalDate').innerText = order.created_at;
                    document.getElementById('modalAddress').innerText = `${order.ship_name} (${order.ship_phone}) - ${order.address_line}, ${order.city}`;

                    // Status Badge logic
                    let badgeClass = 'bg-secondary';
                    if (order.status == 'shipping') badgeClass = 'bg-primary';
                    if (order.status == 'completed') badgeClass = 'bg-success';
                    if (order.status == 'cancelled') badgeClass = 'bg-danger';
                    document.getElementById('modalStatusBadge').innerHTML = `<span class="badge ${badgeClass}">${order.status}</span>`;

                    // Items
                    const tbody = document.getElementById('modalOrderItems');
                    tbody.innerHTML = '';
                    items.forEach(item => {
                        let imgHtml = item.product_image ? `<img src="/MY_WEB/public/${item.product_image}" width="50" class="rounded me-2">` : '';
                        tbody.innerHTML += `
                        <tr class="border-bottom">
                            <td>
                                <div class="d-flex align-items-center">
                                    ${imgHtml}
                                    <div>
                                        <div class="fw-bold small">${item.product_name}</div>
                                        <div class="text-muted small">x${item.quantity}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="text-end align-middle">${new Intl.NumberFormat('vi-VN').format(item.total_price_cents)} đ</td>
                        </tr>
                    `;
                    });

                    // Totals
                    document.getElementById('modalShipping').innerText = new Intl.NumberFormat('vi-VN').format(order.shipping_fee_cents) + ' đ';
                    document.getElementById('modalTotal').innerText = new Intl.NumberFormat('vi-VN').format(order.total_cents) + ' đ';

                    document.getElementById('modalLoading').style.display = 'none';
                    document.getElementById('modalContent').style.display = 'block';
                })
                .catch(err => console.error(err));
        }
    </script>
<?php endif; ?>

<?php require_once '../app/Views/layouts/client/footer.php'; ?>