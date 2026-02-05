// --- ADD TO CART GLOBAL ---
function addToCartGlobal(productId, quantity = 1) {
    const qty = parseInt(quantity) > 0 ? parseInt(quantity) : 1;

    fetch('/MY_WEB/public/cart/add', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({
            product_id: productId,
            quantity: qty
        })
    })
    .then(response => response.json())
    .then(data => {
        
        // 1. Xử lý chưa đăng nhập
        if (data.status === 'login_required') {
            // Dùng Alert để báo rõ ràng trước khi chuyển trang
            Alert.error('Yêu cầu đăng nhập', data.message);
            
            // Đợi 1.5s để khách đọc thông báo rồi mới chuyển
            setTimeout(() => {
                window.location.href = '/MY_WEB/public/auth/login';
            }, 1500);
            return;
        }

        // 2. Xử lý thành công
        if (data.status === 'success') {
            
            // --- Cập nhật Badge giỏ hàng  ---
            const cartIconContainer = document.querySelector('.cart-icon-hover');
            if (cartIconContainer) {
                let badge = cartIconContainer.querySelector('.badge');
                if (data.cart_count > 0) {
                    if (badge) {
                        badge.innerText = data.cart_count;
                        badge.style.display = 'inline-block';
                    } else {
                        badge = document.createElement('span');
                        badge.className = 'position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-light cart-badge';
                        badge.style.fontSize = '0.7rem';
                        badge.innerText = data.cart_count;
                        cartIconContainer.appendChild(badge);
                    }
                } else {
                    if (badge) badge.style.display = 'none';
                }
            }

            // Dùng Toast mới thay cho showToast cũ
            // Vì thêm giỏ hàng là hành động thường xuyên, dùng Toast cho nhẹ nhàng
            Toast.success(data.message);

            // Đóng Modal Quick View (nếu đang mở)
            const qvModalEl = document.getElementById('quickViewModal');
            if (qvModalEl && qvModalEl.classList.contains('show')) {
                if (typeof bootstrap !== 'undefined') {
                    const modalInstance = bootstrap.Modal.getInstance(qvModalEl);
                    if(modalInstance) modalInstance.hide();
                } else {
                    const btnClose = qvModalEl.querySelector('.btn-close');
                    if(btnClose) btnClose.click();
                }
            }
        } else {
            // 3. Xử lý lỗi (Ví dụ: Hết hàng)
            // Dùng Alert vì lỗi hết hàng là quan trọng, cần khách chú ý
            Alert.error('Rất tiếc!', data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Alert.error('Lỗi hệ thống', 'Có lỗi xảy ra, vui lòng thử lại sau!');
    });
}

function toggleWishlist(btnElement, productId = null, variantId = null, removeRow = false) {
    const icon = btnElement.querySelector('i');
    
    // Hiệu ứng nảy icon
    btnElement.style.transform = 'scale(0.8)';
    setTimeout(() => btnElement.style.transform = 'scale(1)', 200);
    
    fetch('/MY_WEB/public/wishlist/toggle', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ 
            product_id: productId,
            variant_id: variantId 
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            // --- Cập nhật UI Icon ---
            if (data.action === 'added') {
                icon.classList.remove('far');
                icon.classList.add('fas', 'text-danger');
            } else {
                icon.classList.remove('fas', 'text-danger');
                icon.classList.add('far');
            }

            // Xử lý riêng cho trang Profile (xóa dòng sản phẩm)
            if (removeRow && data.action === 'removed') {
                 const targetId = variantId ? variantId : productId;
                 const col = document.getElementById('wishlist-item-' + targetId);
                 if(col) {
                     col.style.opacity = '0'; 
                     setTimeout(() => col.remove(), 300); 
                 }
            }

            // Dùng Toast mới
            // Phân loại: Thêm thì Success (Xanh), Xóa thì Info (Xanh dương)
            if (data.action === 'added') {
                Toast.success(data.message);
            } else {
                Toast.info(data.message);
            }

        } else {
            // Xử lý lỗi
            if(data.message && data.message.includes('đăng nhập')) {
                 Alert.error('Thông báo', 'Vui lòng đăng nhập để sử dụng tính năng yêu thích.');
                 setTimeout(() => {
                    window.location.href = '/MY_WEB/public/auth/login';
                 }, 1500);
            } else {
                 Alert.error('Lỗi', data.message);
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        // Không dùng Alert ở đây để tránh spam nếu user click liên tục lúc mất mạng
        // Chỉ log ra console hoặc dùng Toast warning
        Toast.warning('Không thể kết nối đến máy chủ');
    });
}