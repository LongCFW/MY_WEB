// 1. QUẢN LÝ TOAST (Thông báo nhỏ góc màn hình)
const Toast = {
    // Hàm nội bộ để render HTML
    _render(message, type = 'success') {
        let container = document.querySelector('.custom-toast-container');
        if (!container) {
            container = document.createElement('div');
            container.className = 'custom-toast-container';
            document.body.appendChild(container);
        }

        // Icon mapping
        const icons = {
            success: 'fa-check-circle',
            error: 'fa-times-circle',
            warning: 'fa-exclamation-circle',
            info: 'fa-info-circle'
        };

        // Màu sắc mapping (nếu cần class riêng)
        const typeClass = type === 'error' ? 'text-danger' : 
                          type === 'warning' ? 'text-warning' : 'text-success';

        const toast = document.createElement('div');
        toast.className = `custom-toast ${type}`;
        toast.innerHTML = `<i class="fas ${icons[type]} ${typeClass}"></i> <span>${message}</span>`;
        
        container.appendChild(toast);

        // Animation
        setTimeout(() => toast.classList.add('show'), 10);
        setTimeout(() => {
            toast.classList.remove('show');
            toast.addEventListener('transitionend', () => toast.remove());
        }, 3000);
    },

    // --- CÁC HÀM GỌI DÙNG CHUNG ---
    success(message) { this._render(message, 'success'); },
    error(message) { this._render(message, 'error'); },
    warning(message) { this._render(message, 'warning'); },
    info(message) { this._render(message, 'info'); }
};

// 2. QUẢN LÝ ALERT (Hộp thoại giữa màn hình - Dùng SweetAlert2)
const Alert = {
    // Báo lỗi nghiêm trọng
    error(title, message) {
        Swal.fire({
            icon: 'error',
            title: title || 'Lỗi!',
            text: message,
            confirmButtonColor: '#dc3545',
            confirmButtonText: 'Đóng'
        });
    },

    // Báo thành công (nếu muốn nhấn mạnh hơn Toast)
    success(title, message) {
        Swal.fire({
            icon: 'success',
            title: title || 'Thành công',
            text: message,
            confirmButtonColor: '#198754',
            timer: 2000,
            showConfirmButton: false
        });
    },

    // Hộp thoại xác nhận (Dùng cho nút Xóa)
    confirm(title, message, callback) {
        Swal.fire({
            title: title || 'Bạn chắc chắn chứ?',
            text: message || "Hành động này không thể hoàn tác!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Vâng, xóa nó!',
            cancelButtonText: 'Hủy'
        }).then((result) => {
            if (result.isConfirmed) {                
                if (typeof callback === 'function') callback();
            }
        });
    }
};