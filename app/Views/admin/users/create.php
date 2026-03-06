<?php require_once '../app/Views/layouts/admin/sidebar.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm border-0 rounded-lg mb-5">
            <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
                <h4 class="mb-0 font-weight-bold text-primary"><i class="fas fa-user-plus mr-2"></i> Thêm người dùng mới</h4>
            </div>
            <div class="card-body p-4">
                <form action="/MY_WEB/public/admin/user/store" method="POST" enctype="multipart/form-data">
                    
                    <div class="form-group text-center mb-4">
                        <div class="d-inline-block position-relative">
                            <img src="https://placehold.co/120x120?text=Avatar" class="rounded-circle img-thumbnail shadow-sm mb-2" width="120" height="120" id="previewAvatar" style="object-fit: cover;">
                        </div>
                        <div class="custom-file mt-2 text-left mx-auto" style="max-width: 300px; display: block;">
                            <input type="file" name="avatar" class="custom-file-input" id="avatarInput" accept="image/*" onchange="previewImage(this)">
                            <label class="custom-file-label" for="avatarInput">Chọn ảnh đại diện...</label>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label class="font-weight-bold">Họ và tên <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="font-weight-bold">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label class="font-weight-bold">Số điện thoại</label>
                            <input type="text" name="phone" class="form-control">
                        </div>
                        <div class="form-group col-md-6">
                            <label class="font-weight-bold">Mật khẩu <span class="text-danger">*</span></label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Vai trò</label>
                        <select name="role_id" class="form-control">
                            <option value="4">Khách hàng (User)</option>
                            <option value="3">Nhân viên (Staff)</option>
                            <option value="2">Quản lý (Manager)</option>
                            <option value="1">Quản trị viên (Admin)</option>
                            <option value="5">Tài khoản ảo (Seeding Reviews)</option>
                        </select>
                        <small class="form-text text-muted mt-1">Tài khoản ảo (Seeding) dùng để đăng bình luận đánh giá mồi cho sản phẩm.</small>
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

<script>
// Logic hiển thị ảnh Preview khi người dùng chọn file
function previewImage(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('previewAvatar').src = e.target.result;
        }
        reader.readAsDataURL(input.files[0]);
        // Đổi tên nhãn label
        var fileName = input.files[0].name;
        input.nextElementSibling.innerHTML = fileName;
    }
}
</script>

<?php require_once '../app/Views/layouts/admin/footer.php'; ?>