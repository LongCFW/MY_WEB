<style>
    /* Hiệu ứng chìm nổi cho thông báo */
    .notif-card {
        transition: all 0.2s ease;
        border-radius: 12px !important;
        margin-bottom: 12px;
        overflow: hidden;
    }
    
    /* CHƯA ĐỌC: Nổi bật, nền trắng, có shadow, viền xanh bên trái */
    .notif-unread {
        background-color: #ffffff;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05) !important;
        border-left: 4px solid var(--eco-primary) !important;
    }
    .notif-unread .notif-title { color: #2e7d32; font-weight: 700; }
    
    /* ĐÃ ĐỌC: Chìm xuống, nền xám, không shadow */
    .notif-read {
        background-color: #f8f9fa;
        border: 1px solid #eee !important;
        opacity: 0.85;
    }
    .notif-read .notif-title { color: #6c757d; font-weight: 500; }
    .notif-read:hover { opacity: 1; background-color: #f1f3f5; }

    /* CSS Phân trang đồng bộ màu xanh EcoStore */
    .eco-pagination .page-item.active .page-link {
        background-color: #2e7d32;
        border-color: #2e7d32;
        color: white;
    }
    .eco-pagination .page-link {
        color: #2e7d32;
        border-radius: 8px;
        margin: 0 4px;
        border: 1px solid #dee2e6;
    }
    .eco-pagination .page-link:hover {
        background-color: #e8f5e9;
    }
</style>

<div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
    <h4 class="fw-bold text-success m-0"><i class="fas fa-bell me-2"></i> Thông báo của tôi</h4>
    
    <?php if(!empty($notifications)): ?>
        <div class="dropdown">
            <button class="btn btn-sm btn-outline-secondary rounded-pill px-3 dropdown-toggle" type="button" data-bs-toggle="dropdown">
                <i class="fas fa-cog me-1"></i> Tùy chọn
            </button>
            <ul class="dropdown-menu dropdown-menu-end border-0 shadow-sm mt-2">
                <li>
                    <a class="dropdown-item py-2" href="#" onclick="markAllRead(event)">
                        <i class="fas fa-check-double text-primary me-2"></i> Đánh dấu đã đọc tất cả
                    </a>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <a class="dropdown-item py-2 text-danger" href="#" onclick="deleteAllNotifications(event)">
                        <i class="fas fa-trash-alt me-2"></i> Xóa tất cả thông báo
                    </a>
                </li>
            </ul>
        </div>
    <?php endif; ?>
</div>

<div class="notification-list">
    <?php if(!empty($notifications)): ?>
        <?php foreach($notifications as $notif): ?>
            <?php 
                $meta = json_decode($notif['metadata'] ?? '{}', true);
                
                $icon = 'fa-bell text-secondary';
                $iconBg = 'bg-secondary bg-opacity-10';
                $link = '#';

                if ($notif['type'] == 'order_completed') {
                    $icon = 'fa-box-open text-success';
                    $iconBg = 'bg-success bg-opacity-10';
                    $link = "/MY_WEB/public/account?page=orders"; 
                } elseif ($notif['type'] == 'new_coupon') {
                    $icon = 'fa-ticket-alt text-warning';
                    $iconBg = 'bg-warning bg-opacity-10';
                    $link = "/MY_WEB/public/offer"; 
                } else {
                    $icon = 'fa-info-circle text-primary';
                    $iconBg = 'bg-primary bg-opacity-10';
                    $link = "/MY_WEB/public/account?page=orders"; 
                }

                // Xác định class CSS
                $cardClass = ($notif['is_read'] == 0) ? 'notif-unread' : 'notif-read';
            ?>
            
            <div class="card notif-card <?= $cardClass ?>" id="notif-<?= $notif['id'] ?>">
                <div class="card-body p-3 d-flex align-items-center gap-3 cursor-pointer" onclick="handleNotificationClick(event, <?= $notif['id'] ?>, '<?= $link ?>')">
                    
                    <div class="d-flex align-items-center justify-content-center rounded-circle <?= $iconBg ?>" style="width: 55px; height: 55px; flex-shrink: 0;">
                        <i class="fas <?= $icon ?> fs-5"></i>
                    </div>
                    
                    <div class="flex-grow-1" style="cursor: pointer;">
                        <h6 class="notif-title mb-1"><?= htmlspecialchars($notif['title']) ?></h6>
                        <p class="text-muted small mb-1" style="line-height: 1.4;">
                            <?= nl2br(htmlspecialchars($notif['message'])) ?>
                        </p>
                        <small class="text-secondary opacity-75" style="font-size: 0.75rem;">
                            <i class="far fa-clock me-1"></i> 
                            <?= date('H:i d/m/Y', strtotime($notif['created_at'])) ?>
                        </small>
                    </div>

                    <div class="ms-auto ps-2" style="flex-shrink: 0;">
                        <button class="btn btn-sm btn-light text-danger rounded-circle p-2 shadow-none" 
                                onclick="deleteSingleNotification(event, <?= $notif['id'] ?>, <?= $notif['is_read'] ?>)" 
                                title="Xóa thông báo này" style="width: 35px; height: 35px;">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>

        <?php if ($totalPages > 1): ?>
            <nav class="mt-5">
                <ul class="pagination eco-pagination justify-content-center">
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?= ($i == $pageNum) ? 'active' : '' ?>">
                            <a class="page-link shadow-sm" href="?page=notification&p=<?= $i ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
        <?php endif; ?>

    <?php else: ?>
        <div class="text-center py-5 my-5">
            <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                <i class="far fa-bell-slash fs-2 text-muted opacity-50"></i>
            </div>
            <h6 class="fw-bold text-dark mb-2">Bạn chưa có thông báo nào</h6>
            <p class="text-muted small">Những cập nhật về đơn hàng và khuyến mãi sẽ hiển thị tại đây.</p>
        </div>
    <?php endif; ?>
</div>

<script>
// 1. Hàm xử lý khi click vào 1 thông báo
function handleNotificationClick(e, id, redirectUrl) {
    // Ngăn chặn nếu người dùng đang cố bấm nút Xóa
    if (e.target.closest('button')) return;

    // Gửi AJAX đánh dấu đã đọc
    fetch(`/MY_WEB/public/account/readNotif/${id}`, { method: 'POST' })
    .then(() => {
        // Sau khi báo API thành công, lập tức chuyển hướng
        window.location.href = redirectUrl;
    })
    .catch(() => {
        // Nếu lỗi mạng, vẫn cho phép chuyển hướng
        window.location.href = redirectUrl;
    });
}

// 2. Xóa 1 thông báo (Ngăn nổi bọt)
function deleteSingleNotification(e, id, isRead) {
    e.stopPropagation(); // Ngăn kích hoạt hành động handleNotificationClick ở thẻ cha
    
    if(confirm('Xóa thông báo này?')) {
        fetch(`/MY_WEB/public/account/deleteNotif/${id}`, { method: 'POST' })
        .then(res => res.json())
        .then(data => {
            if(data.success) {
                // Hiệu ứng biến mất
                const el = document.getElementById('notif-' + id);
                el.style.opacity = 0;
                el.style.transform = 'translateY(-10px)';
                setTimeout(() => el.remove(), 200);

                // Nếu xóa thông báo CHƯA ĐỌC, ta giảm số trên cái Chuông đi 1
                if (isRead == 0) {
                    updateBadgeCount(-1);
                }
                
                // Nếu xóa hết sạch thẻ trên màn hình thì tự reload để hiện giao diện Trống
                setTimeout(() => {
                    if(document.querySelectorAll('.notif-card').length === 0) window.location.reload();
                }, 250);
            }
        });
    }
}

// Hàm phụ trợ trừ số trên Chuông Header
function updateBadgeCount(change) {
    const badge = document.querySelector('.fa-bell').nextElementSibling;
    if (badge && badge.classList.contains('badge')) {
        let currentCount = parseInt(badge.innerText.replace('+', ''));
        if (isNaN(currentCount)) return;
        
        let newCount = currentCount + change;
        if (newCount <= 0) {
            badge.remove(); // Xóa cục đỏ nếu hết
        } else {
            badge.innerText = newCount > 99 ? '99+' : newCount;
        }
    }
}

// 3. Đánh dấu đọc tất cả
function markAllRead(e) {
    e.preventDefault();
    fetch(`/MY_WEB/public/account/markAllNotifRead`, { method: 'POST' })
    .then(res => res.json())
    .then(data => {
        if(data.success) window.location.reload(); 
    });
}

// 4. Xóa tất cả
function deleteAllNotifications(e) {
    e.preventDefault();
    if(confirm('Bạn có chắc chắn muốn xóa TOÀN BỘ thông báo không? Hành động này không thể hoàn tác!')) {
        fetch(`/MY_WEB/public/account/deleteAllNotifs`, { method: 'POST' })
        .then(res => res.json())
        .then(data => {
            if(data.success) window.location.reload(); 
        });
    }
}
</script>