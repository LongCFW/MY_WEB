<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Sản phẩm</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        /* --- KHUNG CUỘN --- */
        .table-scroll-wrapper {
            max-height: 500px; /* Chiều cao cố định */
            overflow-y: auto;
            overflow-x: auto; /* Cho phép cuộn ngang nếu màn hình quá nhỏ */
            border: 1px solid #dee2e6;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            background: #fff;
        }

        /* --- STICKY HEADER (Cố định tiêu đề) --- */
        .table-scroll-wrapper thead th {
            position: sticky;
            top: 0;
            background-color: #f8f9fa; /* Màu nền xám nhạt */
            z-index: 2; /* Nổi lên trên */
            border-bottom: 2px solid #dee2e6;
            vertical-align: middle;
            white-space: nowrap; /* Không cho tiêu đề xuống dòng */
        }

        /* --- CĂN CHỈNH CỘT --- */
        /* Cột ID, Hình ảnh, Giá, Tồn kho, Hành động: Không cho xuống dòng */
        .col-fixed {
            white-space: nowrap;
            width: 1%; /* Mẹo để cột co lại vừa khít nội dung */
        }
        
        /* Cột Tên sản phẩm: Cho phép xuống dòng thoải mái */
        .col-name {
            min-width: 200px;
        }

        /* Ảnh thumbnail */
        .product-thumb {
            width: 45px;
            height: 45px;
            object-fit: cover;
            border-radius: 4px;
            border: 1px solid #eee;
        }

        .action-btn {
            width: 32px;
            height: 32px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin: 0 2px;
        }
        
        /* Custom Scrollbar */
        .table-scroll-wrapper::-webkit-scrollbar { width: 6px; height: 6px; }
        .table-scroll-wrapper::-webkit-scrollbar-thumb { background: #ccc; border-radius: 3px; }
    </style>
</head>
<body>
    
    <?php require_once '../app/Views/layouts/admin_sidebar.php'; ?>

    <div class="container-fluid mt-4 mb-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="text-dark font-weight-bold border-left border-success pl-3">Danh sách Sản phẩm</h4>
            <a href="/MY_WEB/public/admin/product/create" class="btn btn-success shadow-sm">
                <i class="fas fa-plus mr-1"></i> Thêm mới
            </a>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                
                <div class="table-scroll-wrapper">
                    <table class="table table-hover table-bordered mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th class="text-center col-fixed">ID</th>
                                <th class="text-center col-fixed">Hình ảnh</th>
                                <th class="col-name">Tên sản phẩm</th>
                                <th class="col-fixed">Thương hiệu</th>
                                <th class="col-fixed">Danh mục</th>
                                <th class="col-fixed text-right">Giá bán</th>
                                <th class="col-fixed text-center">Tồn kho</th>
                                <th class="text-center col-fixed">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($products)): ?>
                                <?php foreach ($products as $p): ?>
                                    <tr>
                                        <td class="text-center align-middle font-weight-bold text-muted">
                                            <?= $p['id'] ?>
                                        </td>
                                        
                                        <td class="text-center align-middle">
                                            <?php 
                                                $imgUrl = !empty($p['image_url']) ? "/MY_WEB/public/" . $p['image_url'] : "https://placehold.co/50";
                                            ?>
                                            <img src="<?= $imgUrl ?>" class="product-thumb">
                                        </td>
                                        
                                        <td class="align-middle">
                                            <div class="font-weight-bold text-dark mb-1"><?= htmlspecialchars($p['name']) ?></div>
                                            <small class="text-muted"><i class="fas fa-barcode mr-1"></i><?= $p['sku'] ?? 'N/A' ?></small>
                                        </td>

                                        <td class="align-middle">
                                            <?php if(!empty($p['brand'])): ?>
                                                <span class="badge badge-info font-weight-normal px-2"><?= htmlspecialchars($p['brand']) ?></span>
                                            <?php else: ?>
                                                <span class="text-muted small">-</span>
                                            <?php endif; ?>
                                        </td>

                                        <td class="align-middle">
                                            <span class="badge badge-light border text-dark">
                                                <?= htmlspecialchars($p['category_name'] ?? 'Chưa phân loại') ?>
                                            </span>
                                        </td>

                                        <td class="align-middle text-right text-success font-weight-bold">
                                            <?= number_format($p['price_cents'] ?? 0) ?> đ
                                        </td>

                                        <td class="align-middle text-center">
                                            <?php 
                                                // Lấy từ cột total_stock vừa sửa trong Model
                                                $stock = $p['total_stock'] ?? 0; 
                                            ?>
                                            <?php if ($stock > 10): ?>
                                                <span class="badge badge-success px-2"><?= $stock ?></span>
                                            <?php elseif ($stock > 0): ?>
                                                <span class="badge badge-warning px-2 text-white"><?= $stock ?></span>
                                            <?php else: ?>
                                                <span class="badge badge-danger px-2">Hết hàng</span>
                                            <?php endif; ?>
                                        </td>

                                        <td class="text-center align-middle">
                                            <a href="/MY_WEB/public/admin/product/edit/<?= $p['id'] ?>" class="btn btn-primary btn-sm action-btn shadow-sm" title="Sửa">
                                                <i class="fas fa-pen fa-xs"></i>
                                            </a>
                                            <a href="/MY_WEB/public/admin/product/delete/<?= $p['id'] ?>" 
                                               class="btn btn-danger btn-sm action-btn shadow-sm" 
                                               onclick="return confirm('Xóa sản phẩm này sẽ xóa cả kho hàng và hình ảnh. Bạn chắc chắn chứ?');" title="Xóa">
                                                <i class="fas fa-trash fa-xs"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="8" class="text-center py-5 text-muted">
                                        <i class="fas fa-box-open mb-3" style="font-size: 3rem; opacity: 0.5;"></i><br>
                                        Chưa có sản phẩm nào trong hệ thống.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

            </div>
            <div class="card-footer bg-white py-3">
                <small class="text-muted">Tổng số: <strong><?= count($products) ?></strong> sản phẩm</small>
            </div>
        </div>
    </div>

    <?php require_once '../app/Views/layouts/admin_footer.php'; ?>
</body>
</html>