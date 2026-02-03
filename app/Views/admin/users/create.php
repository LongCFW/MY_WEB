<?php require_once '../app/Views/layouts/admin/sidebar.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm border-0 rounded-lg">
            <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
                <h4 class="mb-0 font-weight-bold text-primary"><i class="fas fa-user-plus mr-2"></i> Thêm người dùng mới</h4>
            </div>
            <div class="card-body p-4">
                <form action="/MY_WEB/public/admin/user/store" method="POST">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label class="font-weight-bold">Họ và tên</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="font-weight-bold">Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label class="font-weight-bold">Số điện thoại</label>
                            <input type="text" name="phone" class="form-control">
                        </div>
                        <div class="form-group col-md-6">
                            <label class="font-weight-bold">Mật khẩu</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Vai trò</label>
                        <select name="role_id" class="form-control">
                            <option value="2">Khách hàng (User)</option>
                            <option value="1">Quản trị viên (Admin)</option>
                        </select>
                    </div>
                    <div class="mt-4 d-flex justify-content-end">
                        <a href="/MY_WEB/public/admin/user" class="btn btn-light mr-2 rounded-pill px-4">Hủy bỏ</a>
                        <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm font-weight-bold">Lưu lại</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once '../app/Views/layouts/admin/footer.php'; ?>