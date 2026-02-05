<?php require_once '../app/Views/layouts/admin/sidebar.php'; ?>

<div class="card shadow-sm border-0 rounded-lg">
    <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
        <h4 class="mb-0 font-weight-bold text-success"><i class="fas fa-plus-circle mr-2"></i> Thêm sản phẩm mới</h4>
    </div>
    <div class="card-body p-4">
        <form action="/MY_WEB/public/admin/product/store" method="POST" enctype="multipart/form-data" id="productForm">
            <div class="row">
                <div class="col-md-8">
                    <div class="form-group">
                        <label class="font-weight-bold">Tên sản phẩm <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" required placeholder="Ví dụ: Thịt bò Úc...">
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label class="font-weight-bold">Thương hiệu</label>
                            <input type="text" name="brand" class="form-control" placeholder="VD: Vinamilk...">
                        </div>
                        <div class="form-group col-md-6">
                            <label class="font-weight-bold">Danh mục <span class="text-danger">*</span></label>
                            <select name="category_id" class="form-control" required>
                                <?php foreach ($categories as $cate): ?>
                                    <option value="<?= $cate['id'] ?>"><?= $cate['name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Mô tả chi tiết</label>
                        <textarea name="description" class="form-control" rows="4"></textarea>
                    </div>

                    <div class="card bg-light border-0 mt-4">
                        <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 font-weight-bold text-dark"><i class="fas fa-layer-group me-2"></i> Các biến thể (Quy cách đóng gói)</h6>
                            <button type="button" class="btn btn-primary btn-sm rounded-pill shadow-sm" onclick="addVariantRow()">
                                <i class="fas fa-plus"></i> Thêm dòng
                            </button>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-bordered mb-0 bg-white" id="variantTable">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Tên biến thể (VD: 500g, 1kg) <span class="text-danger">*</span></th>
                                            <th width="200">Giá bán (VNĐ) <span class="text-danger">*</span></th>
                                            <th width="150">Tồn kho <span class="text-danger">*</span></th>
                                            <th width="50" class="text-center"><i class="fas fa-trash"></i></th>
                                        </tr>
                                    </thead>
                                    <tbody id="variantBody">
                                        <tr class="variant-row">
                                            <td>
                                                <input type="text" name="variants[0][name]" class="form-control" placeholder="VD: Mặc định" required>
                                            </td>
                                            <td>
                                                <input type="number" name="variants[0][price]" class="form-control" placeholder="0" min="0" required>
                                            </td>
                                            <td>
                                                <input type="number" name="variants[0][stock]" class="form-control" value="100" min="0" required>
                                            </td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-light text-danger btn-sm border" disabled>
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label class="font-weight-bold">Hình ảnh đại diện</label>
                        <div class="border rounded p-3 text-center bg-light">
                            <img id="previewImg" src="https://placehold.co/300x300?text=Upload+Image" class="img-fluid rounded mb-2" style="max-height: 200px;">
                            <input type="file" name="image" class="form-control-file" onchange="previewImage(this)">
                        </div>
                    </div>
                </div>
            </div>

            <hr class="my-4">
            <div class="d-flex justify-content-end gap-2">
                <a href="/MY_WEB/public/admin/product" class="btn btn-light rounded-pill px-4 border">Hủy bỏ</a>
                <button type="submit" class="btn btn-success rounded-pill px-5 shadow-sm font-weight-bold">Lưu sản phẩm</button>
            </div>
        </form>
    </div>
</div>

<script>
    let variantCount = 1;

    function addVariantRow() {
        const html = `
            <tr class="variant-row">
                <td>
                    <input type="text" name="variants[${variantCount}][name]" class="form-control" placeholder="VD: Gói lớn" required>
                </td>
                <td>
                    <input type="number" name="variants[${variantCount}][price]" class="form-control" placeholder="0" min="0" required>
                </td>
                <td>
                    <input type="number" name="variants[${variantCount}][stock]" class="form-control" value="100" min="0" required>
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        `;
        document.getElementById('variantBody').insertAdjacentHTML('beforeend', html);
        variantCount++;
    }

    function removeRow(btn) {
        btn.closest('tr').remove();
    }

    // Preview ảnh
    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('previewImg').src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>

<?php require_once '../app/Views/layouts/admin/footer.php'; ?>