<?php require_once '../app/Views/layouts/admin/sidebar.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm border-0 rounded-lg">
            <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
                <h4 class="mb-0 font-weight-bold text-warning"><i class="fas fa-user-edit mr-2"></i> Cập nhật: <?= $user['name'] ?></h4>
            </div>
            <div class="card-body p-4">
                <form action="/MY_WEB/public/admin/user/update/<?= $user['id'] ?>" method="POST">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label class="font-weight-bold">Họ và tên</label>
                            <input type="text" name="name" class="form-control" value="<?= $user['name'] ?>" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="font-weight-bold">Email</label>
                            <input type="email" name="email" class="form-control" value="<?= $user['email'] ?>" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label class="font-weight-bold">Số điện thoại</label>
                            <input type="text" name="phone" class="form-control" value="<?= $user['phone'] ?>">
                        </div>
                        <div class="form-group col-md-6">
                            <label class="font-weight-bold">Mật khẩu mới (Để trống nếu không đổi)</label>
                            <input type="password" name="password" class="form-control" placeholder="******">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label class="font-weight-bold">Vai trò</label>
                            <select name="role_id" class="form-control">
                                <option value="2" <?= $user['role_id']==2?'selected':'' ?>>Khách hàng</option>
                                <option value="1" <?= $user['role_id']==1?'selected':'' ?>>Quản trị viên</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="font-weight-bold">Trạng thái</label>
                            <select name="status" class="form-control">
                                <option value="1" <?= $user['status']==1?'selected':'' ?>>Hoạt động</option>
                                <option value="0" <?= $user['status']==0?'selected':'' ?>>Bị khóa</option>
                            </select>
                        </div>
                    </div>
                    <div class="mt-4 d-flex justify-content-end">
                        <a href="/MY_WEB/public/admin/user" class="btn btn-light mr-2 rounded-pill px-4">Hủy bỏ</a>
                        <button type="submit" class="btn btn-warning text-white rounded-pill px-4 shadow-sm font-weight-bold">Cập nhật</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once '../app/Views/layouts/admin/footer.php'; ?>