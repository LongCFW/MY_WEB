<?php require_once '../app/Views/layouts/admin/sidebar.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm border-0 rounded-lg mb-5">
            <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
                <h4 class="mb-0 font-weight-bold text-warning"><i class="fas fa-user-edit mr-2"></i> Cập nhật: <?= htmlspecialchars($user['name']) ?></h4>
            </div>
            <div class="card-body p-4">
                <form action="/MY_WEB/public/admin/user/update/<?= $user['id'] ?>" method="POST" enctype="multipart/form-data">
                    
                    <div class="form-group text-center mb-4">
                        <div class="d-inline-block position-relative">
                            <?php 
                                $currentAvatar = !empty($user['avatar_url']) 
                                    ? '/MY_WEB/public/' . $user['avatar_url'] 
                                    : 'https://ui-avatars.com/api/?name='.urlencode($user['name']).'&background=random&color=fff'; 
                            ?>
                            <img src="<?= $currentAvatar ?>" class="rounded-circle img-thumbnail shadow-sm mb-2" width="120" height="120" id="previewAvatar" style="object-fit: cover;">
                        </div>
                        <div class="custom-file mt-2 text-left mx-auto" style="max-width: 300px; display: block;">
                            <input type="file" name="avatar" class="custom-file-input" id="avatarInput" accept="image/*" onchange="previewImage(this)">
                            <label class="custom-file-label" for="avatarInput">Chọn ảnh đại diện mới...</label>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label class="font-weight-bold">Họ và tên <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($user['name']) ?>" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="font-weight-bold">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label class="font-weight-bold">Số điện thoại</label>
                            <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($user['phone'] ?? '') ?>">
                        </div>
                        <div class="form-group col-md-6">
                            <label class="font-weight-bold">Mật khẩu mới</label>
                            <input type="password" name="password" class="form-control" placeholder="Để trống nếu không muốn đổi">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label class="font-weight-bold">Vai trò</label>
                            <select name="role_id" class="form-control">
                                <option value="4" <?= $user['role_id']==4 ? 'selected' : '' ?>>Khách hàng</option>
                                <option value="1" <?= $user['role_id']==1 ? 'selected' : '' ?>>Quản trị viên</option>
                                <option value="5" <?= $user['role_id']==5 ? 'selected' : '' ?>>Tài khoản ảo (Seeding Reviews)</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="font-weight-bold">Trạng thái</label>
                            <select name="status" class="form-control">
                                <option value="1" <?= $user['status']==1 ? 'selected' : '' ?>>Hoạt động</option>
                                <option value="0" <?= $user['status']==0 ? 'selected' : '' ?>>Bị khóa</option>
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

<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('previewAvatar').src = e.target.result;
        }
        reader.readAsDataURL(input.files[0]);
        var fileName = input.files[0].name;
        input.nextElementSibling.innerHTML = fileName;
    }
}
</script>

<?php require_once '../app/Views/layouts/admin/footer.php'; ?>