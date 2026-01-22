<?php require_once '../app/Views/client/layouts/header.php'; ?>

<style>
    /* CSS Riêng cho trang Cart để không ảnh hưởng trang khác */
    .cart-item-img {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 8px;
    }
    .qty-input {
        width: 50px;
        text-align: center;
        border: none;
        background: transparent;
        font-weight: bold;
    }
    .qty-btn {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        border: 1px solid #ddd;
        background: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
    }
    .qty-btn:hover {
        background: var(--eco-primary);
        color: white;
        border-color: var(--eco-primary);
    }
    .cart-summary {
        position: sticky;
        top: 100px;
    }
</style>

<div class="bg-light min-vh-100 py-5">
    <div class="container">
        <h2 class="fw-bold mb-4 text-success"><i class="fas fa-shopping-cart me-2"></i>Giỏ hàng của bạn</h2>

        <?php if (empty($cart)): ?>
            <div class="text-center py-5 bg-white rounded-4 shadow-sm">
                <img src="https://cdn-icons-png.flaticon.com/512/11329/11329060.png" width="150" class="mb-3 opacity-50">
                <h4 class="text-muted">Giỏ hàng đang trống</h4>
                <a href="/MY_WEB/public/product" class="btn btn-success rounded-pill px-4 mt-3">Tiếp tục mua sắm</a>
            </div>
        <?php else: ?>
            <div class="row">
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-3">
                        <div class="card-header bg-white py-3 border-bottom">
                            <div class="form-check d-flex align-items-center">
                                <input class="form-check-input me-2" type="checkbox" id="checkAll" style="width: 20px; height: 20px; cursor: pointer;">
                                <label class="form-check-label fw-bold cursor-pointer pt-1" for="checkAll">Chọn tất cả (<?= count($cart) ?> sản phẩm)</label>
                            </div>
                        </div>
                        
                        <div class="card-body p-0">
                            <?php foreach ($cart as $id => $item): ?>
                                <div class="cart-item p-3 border-bottom" id="item-<?= $id ?>">
                                    <div class="d-flex align-items-center">
                                        <div class="me-3">
                                            <input class="form-check-input item-checkbox" type="checkbox" 
                                                   value="<?= $id ?>" 
                                                   data-price="<?= $item['price'] ?>" 
                                                   style="width: 20px; height: 20px; cursor: pointer;">
                                        </div>

                                        <a href="/MY_WEB/public/product/detail/<?= $id ?>" class="me-3">
                                            <?php $img = !empty($item['image']) ? "/MY_WEB/public/" . $item['image'] : "https://placehold.co/100"; ?>
                                            <img src="<?= $img ?>" class="cart-item-img border">
                                        </a>

                                        <div class="flex-grow-1">
                                            <div class="d-flex justify-content-between mb-1">
                                                <h6 class="fw-bold mb-0">
                                                    <a href="/MY_WEB/public/product/detail/<?= $id ?>" class="text-dark text-decoration-none"><?= $item['name'] ?></a>
                                                </h6>
                                                <a href="/MY_WEB/public/cart/remove/<?= $id ?>" class="text-danger small text-decoration-none" onclick="return confirm('Xóa sản phẩm này?')">
                                                    <i class="fas fa-trash-alt"></i>
                                                </a>
                                            </div>
                                            
                                            <div class="d-flex justify-content-between align-items-end mt-2">
                                                <div class="d-flex align-items-center bg-light rounded-pill px-2 py-1 border">
                                                    <button class="qty-btn small" onclick="updateCartQty(<?= $id ?>, -1)"><i class="fas fa-minus small"></i></button>
                                                    <input type="number" id="qty-<?= $id ?>" class="qty-input" value="<?= $item['quantity'] ?>" readonly>
                                                    <button class="qty-btn small" onclick="updateCartQty(<?= $id ?>, 1)"><i class="fas fa-plus small"></i></button>
                                                </div>

                                                <div class="text-end">
                                                    <div class="small text-muted text-decoration-line-through"></div>
                                                    <span class="fw-bold text-success" id="total-price-<?= $id ?>" data-unit-price="<?= $item['price'] ?>">
                                                        <?= number_format($item['price'] * $item['quantity']) ?> đ
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm rounded-4 cart-summary">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-3">Thanh toán</h5>
                            
                            <div class="d-flex justify-content-between mb-2 text-muted">
                                <span>Đã chọn:</span>
                                <span id="selected-count">0 sản phẩm</span>
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center mb-4 pt-3 border-top">
                                <span class="fw-bold fs-5">Tổng cộng:</span>
                                <span class="fw-bold fs-4 text-danger" id="grand-total">0 đ</span>
                            </div>

                            <button onclick="proceedToCheckout()" class="btn btn-success w-100 rounded-pill py-3 fw-bold shadow-sm text-uppercase">
                                Mua Hàng Ngay
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
    // --- 1. LOGIC CHECKBOX "CHỌN TẤT CẢ" ---
    const checkAll = document.getElementById('checkAll');
    // Lưu ý: dùng class .item-checkbox thay vì ID để chọn nhiều
    const checkboxes = document.querySelectorAll('.item-checkbox');
    const grandTotalEl = document.getElementById('grand-total');
    const selectedCountEl = document.getElementById('selected-count');

    function calculateTotal() {
        let total = 0;
        let count = 0;

        checkboxes.forEach(cb => {
            if (cb.checked) {
                const id = cb.value;
                const qtyInput = document.getElementById(`qty-${id}`);
                const price = parseInt(cb.dataset.price);
                
                // Phòng trường hợp input qty chưa load xong
                const qty = qtyInput ? parseInt(qtyInput.value) : 0;
                
                total += price * qty;
                count++;
            }
        });

        grandTotalEl.innerText = total.toLocaleString('vi-VN') + ' đ';
        selectedCountEl.innerText = count + ' sản phẩm';
    }

    // A. Khi bấm "Chọn tất cả"
    if(checkAll) {
        checkAll.addEventListener('change', function() {
            const isChecked = this.checked;
            checkboxes.forEach(cb => {
                cb.checked = isChecked;
            });
            calculateTotal();
        });
    }

    // B. Khi bấm từng checkbox con
    checkboxes.forEach(cb => {
        cb.addEventListener('change', function() {
            // Logic: Nếu bỏ tick 1 cái -> Bỏ tick "Chọn tất cả"
            if (!this.checked) {
                checkAll.checked = false;
            } 
            // Logic: Nếu tick đủ tất cả -> Tick "Chọn tất cả"
            else {
                const allChecked = document.querySelectorAll('.item-checkbox:checked').length === checkboxes.length;
                if (allChecked) {
                    checkAll.checked = true;
                }
            }
            calculateTotal();
        });
    });

    // --- 2. CẬP NHẬT SỐ LƯỢNG (Giữ nguyên logic cũ) ---
    function updateCartQty(id, change) {
        const input = document.getElementById(`qty-${id}`);
        let newQty = parseInt(input.value) + change;
        if (newQty < 1) return;

        fetch('/MY_WEB/public/cart/update', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id: id, quantity: newQty })
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                input.value = data.new_quantity;
                const itemTotalEl = document.getElementById(`total-price-${id}`);
                itemTotalEl.innerText = data.item_total.toLocaleString('vi-VN') + ' đ';
                calculateTotal(); // Tính lại tổng sau khi đổi số lượng
            } else {
                showToast(data.message); // Dùng showToast thay alert cho đẹp
            }
        })
        .catch(error => console.error('Error:', error));
    }

    function proceedToCheckout() {
        const selectedIds = [];
        checkboxes.forEach(cb => {
            if (cb.checked) selectedIds.push(cb.value);
        });

        if (selectedIds.length === 0) {
            showToast('Vui lòng chọn ít nhất 1 sản phẩm để thanh toán!');
            return;
        }
        window.location.href = '/MY_WEB/public/checkout?ids=' + selectedIds.join(',');
    }
</script>

<?php require_once '../app/Views/client/layouts/footer.php'; ?>