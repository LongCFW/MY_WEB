<?php require_once '../app/Views/layouts/client/header.php'; ?>

<style>
    /* CSS Riêng cho trang Cart */
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
    .cart-scroll-container {
        max-height: 420px;
        overflow-y: auto;
        overflow-x: hidden;
    }
    /* Scrollbar đẹp */
    .cart-scroll-container::-webkit-scrollbar { width: 6px; }
    .cart-scroll-container::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 10px; }
    .cart-scroll-container::-webkit-scrollbar-thumb { background: #ccc; border-radius: 10px; transition: background 0.2s; }
    .cart-scroll-container::-webkit-scrollbar-thumb:hover { background: #999; }
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
                        <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                            <div class="form-check d-flex align-items-center m-0">
                                <input class="form-check-input me-2" type="checkbox" id="checkAll" style="width: 20px; height: 20px; cursor: pointer;">
                                <label class="form-check-label fw-bold cursor-pointer pt-1" for="checkAll">Chọn tất cả (<?= count($cart) ?>)</label>
                            </div>
                            <button id="btn-delete-selected" class="btn btn-sm btn-outline-danger rounded-pill fw-bold d-none" onclick="deleteSelectedItems()">
                                <i class="fas fa-trash-alt me-1"></i> Xóa đã chọn
                            </button>
                        </div>

                        <div class="card-body p-0">
                            <div class="cart-scroll-container custom-scrollbar">
                                <?php foreach ($cart as $id => $item): ?>
                                    <?php 
                                        // --- LOGIC: KIỂM TRA TRẠNG THÁI VÀ TỒN KHO ---
                                        $isActive = isset($item['is_active']) ? $item['is_active'] == 1 : true; 
                                        $stock = isset($item['stock']) ? (int)$item['stock'] : 0;
                                        $isOutOfStock = ($stock <= 0); // Kiểm tra hết hàng
                                        $canBuy = $isActive && !$isOutOfStock; // Chỉ cho mua khi Đang bán VÀ Còn hàng
                                    ?>

                                    <div class="cart-item p-3 border-bottom <?= !$canBuy ? 'bg-secondary bg-opacity-10' : '' ?>" id="item-<?= $item['id'] ?>">
                                        <div class="d-flex align-items-center">
                                            
                                            <div class="me-3">
                                                <?php if ($canBuy): ?>
                                                    <input class="form-check-input item-checkbox" type="checkbox"
                                                        value="<?= $item['id'] ?>"
                                                        data-price="<?= $item['price'] ?>"
                                                        style="width: 20px; height: 20px; cursor: pointer;">
                                                <?php else: ?>
                                                    <input class="form-check-input" type="checkbox" disabled 
                                                           title="<?= !$isActive ? 'Sản phẩm ngừng kinh doanh' : 'Sản phẩm đã hết hàng' ?>"
                                                           style="width: 20px; height: 20px; cursor: not-allowed; opacity: 0.5;">
                                                <?php endif; ?>
                                            </div>

                                            <a href="<?= $isActive ? "/MY_WEB/public/product/detail/" . $item['product_id'] : '#' ?>" class="me-3 flex-shrink-0">
                                                <?php $img = !empty($item['image']) ? "/MY_WEB/public/" . $item['image'] : "https://placehold.co/100"; ?>
                                                <img src="<?= $img ?>" class="cart-item-img border" style="<?= !$canBuy ? 'filter: grayscale(100%); opacity: 0.6;' : '' ?>">
                                            </a>

                                            <div class="flex-grow-1">
                                                <div class="d-flex justify-content-between mb-1">
                                                    <h6 class="fw-bold mb-0 text-truncate" style="max-width: 200px;">
                                                        <?php if ($canBuy): ?>
                                                            <a href="/MY_WEB/public/product/detail/<?= $item['product_id'] ?>" class="text-dark text-decoration-none">
                                                                <?= htmlspecialchars($item['name']) ?>
                                                            </a>
                                                        <?php else: ?>
                                                            <span class="text-muted text-decoration-line-through">
                                                                <?= htmlspecialchars($item['name']) ?>
                                                            </span>
                                                            <?php if (!$isActive): ?>
                                                                <span class="badge bg-danger ms-2" style="font-size: 0.7em;">Ngừng kinh doanh</span>
                                                            <?php elseif ($isOutOfStock): ?>
                                                                <span class="badge bg-secondary ms-2" style="font-size: 0.7em;">Hết hàng</span>
                                                            <?php endif; ?>
                                                        <?php endif; ?>
                                                    </h6>

                                                    <a href="/MY_WEB/public/cart/remove/<?= $item['id'] ?>" class="text-danger small text-decoration-none" onclick="return confirm('Xóa sản phẩm này khỏi giỏ hàng?')">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </a>
                                                </div>

                                                <div class="d-flex justify-content-between align-items-end mt-2">
                                                    <?php if ($canBuy): ?>
                                                        <div class="d-flex align-items-center bg-light rounded-pill px-2 py-1 border">
                                                            <button class="qty-btn small" onclick="updateCartQty(<?= $item['id'] ?>, -1)"><i class="fas fa-minus small"></i></button>
                                                            <input type="number" id="qty-<?= $item['id'] ?>" class="qty-input" value="<?= $item['quantity'] ?>" readonly>
                                                            <button class="qty-btn small" onclick="updateCartQty(<?= $item['id'] ?>, 1)"><i class="fas fa-plus small"></i></button>
                                                        </div>
                                                        <div class="text-end">
                                                            <span class="fw-bold text-success" id="total-price-<?= $item['id'] ?>" data-unit-price="<?= $item['price'] ?>">
                                                                <?= number_format($item['price'] * $item['quantity']) ?> đ
                                                            </span>
                                                        </div>
                                                    <?php else: ?>
                                                        <div class="text-danger small fst-italic">
                                                            <i class="fas fa-exclamation-circle"></i> <?= !$isActive ? 'Sản phẩm tạm ngưng bán' : 'Sản phẩm hiện đã hết hàng' ?>
                                                        </div>
                                                        <div class="text-end text-muted text-decoration-line-through">
                                                            <?= number_format($item['price'] * $item['quantity']) ?> đ
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
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
    const checkAll = document.getElementById('checkAll');
    const checkboxes = document.querySelectorAll('.item-checkbox');
    const grandTotalEl = document.getElementById('grand-total');
    const selectedCountEl = document.getElementById('selected-count');
    const btnDeleteSelected = document.getElementById('btn-delete-selected');

    function calculateTotal() {
        let total = 0;
        let count = 0;
        checkboxes.forEach(cb => {
            if (cb.checked) {
                const id = cb.value;
                const qtyInput = document.getElementById(`qty-${id}`);
                const price = parseInt(cb.dataset.price);
                const qty = qtyInput ? parseInt(qtyInput.value) : 0;
                total += price * qty;
                count++;
            }
        });
        grandTotalEl.innerText = total.toLocaleString('vi-VN') + ' đ';
        selectedCountEl.innerText = count + ' sản phẩm';
        if (count > 0 && btnDeleteSelected) btnDeleteSelected.classList.remove('d-none');
        else if (btnDeleteSelected) btnDeleteSelected.classList.add('d-none');
    }

    if(checkAll) {
        checkAll.addEventListener('change', function() {
            const isChecked = this.checked;
            checkboxes.forEach(cb => { 
                // CHỈ CHECK NHỮNG ITEM KHÔNG BỊ DISABLED (Đang kinh doanh & Còn hàng)
                if (!cb.disabled) {
                    cb.checked = isChecked; 
                }
            });
            calculateTotal();
        });
    }

    checkboxes.forEach(cb => {
        cb.addEventListener('change', function() {
            if (!this.checked) checkAll.checked = false;
            else {
                // Kiểm tra xem số lượng ô checkbox ĐÃ CHỌN có bằng số lượng ô checkbox CÓ THỂ CHỌN hay không
                const availableCheckboxes = document.querySelectorAll('.item-checkbox:not(:disabled)').length;
                const checkedCheckboxes = document.querySelectorAll('.item-checkbox:checked').length;
                if (checkedCheckboxes > 0 && checkedCheckboxes === availableCheckboxes) {
                    checkAll.checked = true;
                }
            }
            calculateTotal();
        });
    });

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
                calculateTotal();
            } else {
                if(typeof showToast === 'function') showToast(data.message);
                else alert(data.message);
            }
        });
    }

    function deleteSelectedItems() {
        const selectedIds = [];
        checkboxes.forEach(cb => { if (cb.checked) selectedIds.push(cb.value); });
        if (selectedIds.length === 0) return;

        if (confirm(`Xóa ${selectedIds.length} sản phẩm đã chọn?`)) {
            fetch('/MY_WEB/public/cart/removeMulti', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ ids: selectedIds })
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') location.reload();
                else alert('Có lỗi xảy ra!');
            });
        }
    }

    function proceedToCheckout() {
        const selectedIds = [];
        checkboxes.forEach(cb => { if (cb.checked) selectedIds.push(cb.value); });
        if (selectedIds.length === 0) {
            if(typeof showToast === 'function') showToast('Vui lòng chọn sản phẩm!');
            else alert('Vui lòng chọn sản phẩm!');
            return;
        }
        window.location.href = '/MY_WEB/public/checkout?ids=' + selectedIds.join(',');
    }
</script>

<?php require_once '../app/Views/layouts/client/footer.php'; ?>