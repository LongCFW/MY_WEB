<?php require_once '../app/Views/layouts/admin/sidebar.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="text-dark font-weight-bold">Sửa Mã Giảm Giá: <?= $coupon['code'] ?></h3>
    <a href="/MY_WEB/public/admin/coupon" class="btn btn-light shadow-sm"><i class="fas fa-arrow-left"></i> Quay lại</a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body">
        <form action="/MY_WEB/public/admin/coupon/update/<?= $coupon['id'] ?>" method="POST">
            <div class="row">
                <div class="col-md-6 form-group">
                    <label class="font-weight-bold">Mã Code <span class="text-danger">*</span></label>
                    <input type="text" name="code" class="form-control text-uppercase" value="<?= $coupon['code'] ?>" required>
                </div>
                
                <div class="col-md-3 form-group">
                    <label class="font-weight-bold">Loại giảm giá <span class="text-danger">*</span></label>
                    <select name="type" class="form-control" required>
                        <option value="percent" <?= $coupon['type'] == 'percent' ? 'selected' : '' ?>>Theo phần trăm (%)</option>
                        <option value="fixed" <?= $coupon['type'] == 'fixed' ? 'selected' : '' ?>>Số tiền cố định (VNĐ)</option>
                    </select>
                </div>
                
                <div class="col-md-3 form-group">
                    <label class="font-weight-bold">Giá trị giảm <span class="text-danger">*</span></label>
                    <input type="number" name="value" class="form-control" value="<?= $coupon['value'] ?>" required>
                </div>
                
                <div class="col-md-6 form-group">
                    <label class="font-weight-bold">Đơn hàng tối thiểu (VNĐ)</label>
                    <input type="number" name="min_order_cents" class="form-control" value="<?= $coupon['min_order_cents'] ?>">
                </div>
                
                <div class="col-md-6 form-group">
                    <label class="font-weight-bold">Giới hạn lượt dùng</label>
                    <input type="number" name="usage_limit" class="form-control" value="<?= $coupon['usage_limit'] ?>" placeholder="Để trống nếu vô hạn">
                </div>
                
                <div class="col-md-6 form-group">
                    <label class="font-weight-bold">Ngày bắt đầu <span class="text-danger">*</span></label>
                    <input type="datetime-local" name="starts_at" class="form-control" value="<?= date('Y-m-d\TH:i', strtotime($coupon['starts_at'])) ?>" required>
                </div>
                
                <div class="col-md-6 form-group">
                    <label class="font-weight-bold">Ngày kết thúc <span class="text-danger">*</span></label>
                    <input type="datetime-local" name="ends_at" class="form-control" value="<?= date('Y-m-d\TH:i', strtotime($coupon['ends_at'])) ?>" required>
                </div>
            </div>
            
            <button type="submit" class="btn btn-primary mt-3"><i class="fas fa-save mr-2"></i> Cập nhật</button>
        </form>
    </div>
</div>

<?php require_once '../app/Views/layouts/admin/footer.php'; ?>