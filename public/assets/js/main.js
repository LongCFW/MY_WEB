// --- TOAST FUNCTION ---
function showToast(message) {
    // 1. Tạo container nếu chưa có
    let container = document.querySelector('.custom-toast-container');
    if (!container) {
        container = document.createElement('div');
        container.className = 'custom-toast-container';
        document.body.appendChild(container);
    }

    // 2. Tạo element toast
    const toast = document.createElement('div');
    toast.className = 'custom-toast';
    toast.innerHTML = `<i class="fas fa-check-circle"></i> <span>${message}</span>`;
    
    // 3. Thêm vào DOM
    container.appendChild(toast);

    // 4. Hiệu ứng hiện (sau 10ms để kích hoạt transition CSS)
    setTimeout(() => {
        toast.classList.add('show');
    }, 10);

    // 5. Tự động ẩn và xóa sau 2 giây
    setTimeout(() => {
        toast.classList.remove('show');
        toast.addEventListener('transitionend', () => {
            toast.remove();
        });
    }, 2000);
}

// --- ADD TO CART GLOBAL (UPDATED) ---
function addToCartGlobal(productId, quantity = 1) {
    // Ép kiểu quantity về số để tránh lỗi
    const qty = parseInt(quantity) > 0 ? parseInt(quantity) : 1;

    fetch('/MY_WEB/public/cart/add', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            product_id: productId,
            quantity: qty
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            // 1. Cập nhật số lượng icon giỏ hàng
            const cartCountBadge = document.querySelector('.cart-count-badge');
            // Tìm tất cả các badge giỏ hàng (mobile + desktop)
            const badges = document.querySelectorAll('.cart-count-badge, .badge.bg-danger'); 
            
            badges.forEach(badge => {
                badge.innerText = data.cart_count;
                badge.style.display = 'block'; // Hoặc 'flex' tùy CSS
            });

            // 2. Hiển thị Toast thay vì Alert
            showToast(data.message);
            
            // 3. Đóng Modal Quick View nếu đang mở
            const qvModalEl = document.getElementById('quickViewModal');
            if (qvModalEl && qvModalEl.classList.contains('show')) {
                // Kiểm tra xem bootstrap có tồn tại không
                if (typeof bootstrap !== 'undefined') {
                    const modalInstance = bootstrap.Modal.getInstance(qvModalEl);
                    if(modalInstance) modalInstance.hide();
                } else {
                    // Fallback đóng thủ công nếu ko load đc instance
                    const btnClose = qvModalEl.querySelector('.btn-close');
                    if(btnClose) btnClose.click();
                }
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Có lỗi xảy ra, vui lòng thử lại!');
    });
}


/**
 * Toggle Wishlist
 * @param {HTMLElement} btnElement - Nút được bấm
 * @param {Number|null} productId - ID sản phẩm (dùng ở trang Home/List)
 * @param {Number|null} variantId - ID biến thể (dùng ở trang Wishlist/Detail)
 * @param {Boolean} removeRow - Có xóa dòng khỏi giao diện không (Dùng cho trang Profile)
 */
function toggleWishlist(btnElement, productId = null, variantId = null, removeRow = false) {
    const icon = btnElement.querySelector('i');
    // Hiệu ứng nảy nhẹ khi click để tạo cảm giác tương tác
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
            // --- XỬ LÝ UI NGAY LẬP TỨC ---
            if (data.action === 'added') {
                // Nếu đã thêm: đổi sang tim đặc màu đỏ
                icon.classList.remove('far');
                icon.classList.add('fas', 'text-danger');
            } else {
                // Nếu đã xóa: đổi sang tim rỗng
                icon.classList.remove('fas', 'text-danger');
                icon.classList.add('far');
            }

            // Xử lý riêng cho trang Profile (xóa card)
            if (removeRow && data.action === 'removed') {
                 const targetId = variantId ? variantId : productId;
                 const col = document.getElementById('wishlist-item-' + targetId);
                 if(col) {
                     col.style.opacity = '0'; // Hiệu ứng mờ dần
                     setTimeout(() => col.remove(), 300); // Xóa sau khi mờ
                 }
            }

            // --- HIỆN TOAST THÔNG BÁO ---            
            if (typeof showToast === 'function') {
                // data.message từ controller gửi về là "Đã thêm..." hoặc "Đã xóa..."
                showToast(data.message, data.action === 'added' ? 'success' : 'info');
            }
        } else {
            // Xử lý lỗi (ví dụ chưa đăng nhập)
            if(data.message && data.message.includes('đăng nhập')) {
                 // Có thể chuyển hướng login hoặc hiện toast lỗi
                 window.location.href = '/MY_WEB/public/auth/login';
            } else {
                 alert(data.message);
            }
        }
    })
    .catch(error => console.error('Error:', error));
}