<?php require_once '../app/Views/layouts/admin/sidebar.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="text-dark font-weight-bold">Thêm Mã Giảm Giá</h3>
    <a href="/MY_WEB/public/admin/coupon" class="btn btn-light shadow-sm"><i class="fas fa-arrow-left"></i> Quay lại</a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body">
        <form action="/MY_WEB/public/admin/coupon/store" method="POST">
            <div class="row">
                <div class="col-md-6 form-group">
                    <label class="font-weight-bold">Mã Code <span class="text-danger">*</span></label>
                    <input type="text" name="code" class="form-control text-uppercase" required placeholder="VD: SUMMER2024">
                </div>
                
                <div class="col-md-3 form-group">
                    <label class="font-weight-bold">Loại giảm giá <span class="text-danger">*</span></label>
                    <select name="type" class="form-control" required>
                        <option value="percent">Theo phần trăm (%)</option>
                        <option value="fixed">Số tiền cố định (VNĐ)</option>
                    </select>
                </div>
                
                <div class="col-md-3 form-group">
                    <label class="font-weight-bold">Giá trị giảm <span class="text-danger">*</span></label>
                    <input type="number" name="value" class="form-control" required placeholder="10 (%) hoặc 50000 (VND)">
                </div>
                
                <div class="col-md-6 form-group">
                    <label class="font-weight-bold">Đơn hàng tối thiểu áp dụng (VNĐ)</label>
                    <input type="number" name="min_order_cents" class="form-control" placeholder="0 nếu không yêu cầu">
                </div>
                
                <div class="col-md-6 form-group">
                    <label class="font-weight-bold">Giới hạn số lượt dùng</label>
                    <input type="number" name="usage_limit" class="form-control" placeholder="Để trống nếu vô hạn">
                </div>
                
                <div class="col-md-6 form-group">
                    <label class="font-weight-bold">Ngày bắt đầu <span class="text-danger">*</span></label>
                    <input type="datetime-local" name="starts_at" class="form-control" required>
                </div>
                
                <div class="col-md-6 form-group">
                    <label class="font-weight-bold">Ngày kết thúc <span class="text-danger">*</span></label>
                    <input type="datetime-local" name="ends_at" class="form-control" required>
                </div>
            </div>
            
            <button type="submit" class="btn btn-success mt-3"><i class="fas fa-save mr-2"></i> Lưu Mã Giảm Giá</button>
        </form>
    </div>
</div>

<?php require_once '../app/Views/layouts/admin/footer.php'; ?>