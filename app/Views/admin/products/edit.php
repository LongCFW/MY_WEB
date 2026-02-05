<?php require_once '../app/Views/layouts/admin/sidebar.php'; ?>

<?php 
// Giả sử Controller truyền xuống $variants (List các biến thể của sản phẩm này)
$variants = $variants ?? []; 
?>

<div class="card shadow-sm border-0 rounded-lg">
    <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
        <h4 class="mb-0 font-weight-bold text-warning"><i class="fas fa-edit mr-2"></i> Cập nhật sản phẩm: <?= htmlspecialchars($product['name']) ?></h4>
    </div>
    <div class="card-body p-4">
        <form action="/MY_WEB/public/admin/product/update/<?= $product['id'] ?>" method="POST" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-8">
                    <div class="form-group">
                        <label class="font-weight-bold">Tên sản phẩm <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($product['name']) ?>" required>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label class="font-weight-bold">Thương hiệu</label>
                            <input type="text" name="brand" class="form-control" value="<?= htmlspecialchars($product['brand'] ?? '') ?>">
                        </div>
                        <div class="form-group col-md-6">
                            <label class="font-weight-bold">Danh mục <span class="text-danger">*</span></label>
                            <select name="category_id" class="form-control">
                                <?php foreach ($categories as $cate): ?>
                                    <option value="<?= $cate['id'] ?>" <?= ($cate['id'] == $product['category_id']) ? 'selected' : '' ?>>
                                        <?= $cate['name'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Mô tả chi tiết</label>
                        <textarea name="description" class="form-control" rows="4"><?= htmlspecialchars($product['description']) ?></textarea>
                    </div>

                    <div class="card bg-light border-0 mt-4">
                        <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 font-weight-bold text-dark"><i class="fas fa-layer-group me-2"></i> Các biến thể</h6>
                            <button type="button" class="btn btn-primary btn-sm rounded-pill shadow-sm" onclick="addVariantRow()">
                                <i class="fas fa-plus"></i> Thêm dòng
                            </button>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-bordered mb-0 bg-white" id="variantTable">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Tên biến thể</th>
                                            <th width="200">Giá bán (VNĐ)</th>
                                            <th width="150">Tồn kho</th>
                                            <th width="50" class="text-center">#</th>
                                        </tr>
                                    </thead>
                                    <tbody id="variantBody">
                                        <?php if (!empty($variants)): ?>
                                            <?php foreach ($variants as $index => $var): ?>
                                            <tr class="variant-row">
                                                <td>
                                                    <input type="hidden" name="variants[<?= $index ?>][id]" value="<?= $var['id'] ?>">
                                                    
                                                    <input type="text" name="variants[<?= $index ?>][name]" class="form-control" value="<?= htmlspecialchars($var['name']) ?>" required>
                                                </td>
                                                <td>
                                                    <input type="number" name="variants[<?= $index ?>][price]" class="form-control" value="<?= $var['price_cents'] ?>" min="0" required>
                                                </td>
                                                <td>
                                                    <input type="number" name="variants[<?= $index ?>][stock]" class="form-control" value="<?= $var['stock'] ?>" min="0" required>
                                                </td>
                                                <td class="text-center">
                                                    <button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)" <?= (count($variants) == 1) ? 'disabled' : '' ?>>
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr class="variant-row">
                                                <td><input type="text" name="variants[0][name]" class="form-control" value="Default" required></td>
                                                <td><input type="number" name="variants[0][price]" class="form-control" value="0" required></td>
                                                <td><input type="number" name="variants[0][stock]" class="form-control" value="0" required></td>
                                                <td class="text-center"><button type="button" class="btn btn-danger btn-sm" disabled><i class="fas fa-trash"></i></button></td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label class="font-weight-bold">Hình ảnh</label>
                        <div class="border rounded p-3 text-center bg-light">
                            <?php $imgUrl = !empty($product['image_url']) ? "/MY_WEB/public/" . $product['image_url'] : "https://placehold.co/300x300?text=No+Image"; ?>
                            <img id="previewImg" src="<?= $imgUrl ?>" class="img-fluid rounded mb-2" style="max-height: 200px;">
                            <input type="file" name="image" class="form-control-file" onchange="previewImage(this)">
                        </div>
                    </div>
                </div>
            </div>

            <hr class="my-4">
            <div class="d-flex justify-content-end gap-2">
                <a href="/MY_WEB/public/admin/product" class="btn btn-light rounded-pill px-4 border">Hủy bỏ</a>
                <button type="submit" class="btn btn-warning text-white rounded-pill px-5 shadow-sm font-weight-bold">Cập nhật</button>
            </div>
        </form>
    </div>
</div>

<script>
    // JS tương tự file Create, nhưng biến đếm bắt đầu từ số lượng hiện có
    let variantCount = <?= count($variants) > 0 ? count($variants) : 1 ?>;

    function addVariantRow() {
        const html = `
            <tr class="variant-row">
                <td>
                    <input type="text" name="variants[${variantCount}][name]" class="form-control" placeholder="VD: Mới" required>
                </td>
                <td><input type="number" name="variants[${variantCount}][price]" class="form-control" placeholder="0" min="0" required></td>
                <td><input type="number" name="variants[${variantCount}][stock]" class="form-control" value="100" min="0" required></td>
                <td class="text-center">
                    <button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)"><i class="fas fa-trash"></i></button>
                </td>
            </tr>
        `;
        document.getElementById('variantBody').insertAdjacentHTML('beforeend', html);
        variantCount++;
    }

    function removeRow(btn) {
        // Kiểm tra nếu còn nhiều hơn 1 dòng mới cho xóa
        const rowCount = document.querySelectorAll('.variant-row').length;
        if (rowCount > 1) {
            btn.closest('tr').remove();
        } else {
            alert('Phải có ít nhất 1 biến thể!');
        }
    }

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