<?php
namespace App\Controllers\Admin;

use App\Core\Controller;

class ProductController extends Controller {

    // 1
    public function index() {
        $this->checkAuth();
        if (!isset($_SESSION['admin_logged_in'])) header('Location: /MY_WEB/public/admin/auth/login');
        
        $productModel = $this->model('Product');
        $products = $productModel->getAllProducts();
        $this->view('admin/products/index', ['products' => $products]);
    }

    // 2
    public function create() {
        $this->checkAuth();
        $categoryModel = $this->model('Category');
        $categories = $categoryModel->all();
        $this->view('admin/products/create', ['categories' => $categories]);
    }

    // --- 3 LOGIC LƯU 3 BẢNG QUAN TRỌNG ---
    public function store() {
        $this->checkAuth();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'];
            $brand = $_POST['brand'] ?? '';
            $category_id = $_POST['category_id'];
            $desc = $_POST['description'];
            
            // Lấy mảng variants từ form
            $variants = $_POST['variants'] ?? []; 

            // Kiểm tra nếu chưa có biến thể nào thì báo lỗi (hoặc tạo default)
            if (empty($variants)) {
                echo "<script>alert('Vui lòng thêm ít nhất 1 biến thể!'); window.history.back();</script>";
                return;
            }

            $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name)));
            $skuMaster = 'P' . time(); // SKU chung

            // 1. Lưu PRODUCTS (Cha)
            $productModel = $this->model('Product');
            $productData = [
                'sku' => $skuMaster,
                'name' => $name,
                'slug' => $slug . '-' . time(),
                'brand' => $brand,
                'category_id' => $category_id,
                'description' => $desc,
                'short_description' => mb_substr(strip_tags($desc), 0, 150, 'UTF-8'),
                'is_active' => 1
            ];
            $productId = $productModel->create($productData); 

            // 2. LƯU PRODUCT_VARIANTS (Vòng lặp)
            $variantModel = $this->model('ProductVariant');
            
            foreach ($variants as $idx => $var) {
                // Tạo SKU con: P123-1, P123-2
                $varSku = $skuMaster . '-' . ($idx + 1);
                
                $variantData = [
                    'product_id' => $productId,
                    'sku' => $varSku,
                    'name' => $var['name'],       // VD: Gói 500g
                    'price_cents' => $var['price'],
                    'stock' => $var['stock'],
                    'is_active' => 1
                ];
                $variantModel->create($variantData);
            }

            // 3. XỬ LÝ ẢNH (Giữ nguyên logic cũ - ảnh đại diện)
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $targetDir = "../public/assets/uploads/products/";
                if (!file_exists($targetDir)) mkdir($targetDir, 0777, true);
                $fileName = time() . "_" . basename($_FILES["image"]["name"]);
                $targetFilePath = $targetDir . $fileName;

                if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFilePath)) {
                    $imageModel = $this->model('ProductImage');
                    $imageModel->create([
                        'product_id' => $productId,
                        'image_url' => "assets/uploads/products/" . $fileName,
                        'position' => 1,
                        'alt_text' => $name
                    ]);
                }
            }

            header('Location: /MY_WEB/public/admin/product');
        }
    }

    // 4 Xóa (Lưu ý: Do có khóa ngoại Cascade hoặc phải xóa bảng con trước)
    public function delete($id) {
        $this->checkAuth();
        // Database của bạn có ON DELETE CASCADE ở bảng variant/image chưa?
        // Nếu có rồi thì chỉ cần xóa bảng Product là tự bay hết.
        // Nếu chưa thì phải xóa tay từng bảng. Giả sử đã có CASCADE như DDL bạn gửi.
        $model = $this->model('Product');
        $model->delete($id);
        header('Location: /MY_WEB/public/admin/product');
    }

    // --- 5. HIỂN THỊ FORM SỬA ---
    public function edit($id) {
        $this->checkAuth();
        $productModel = $this->model('Product');
        $product = $productModel->getProductDetail($id);

        if (!$product) {
            header('Location: /MY_WEB/public/admin/product');
            exit();
        }

        // Gọi qua Model
        $variantModel = $this->model('ProductVariant');
        $variants = $variantModel->getVariantsByProductId($id);

        $categoryModel = $this->model('Category');
        $categories = $categoryModel->all();

        $this->view('admin/products/edit', [
            'product' => $product,
            'categories' => $categories,
            'variants' => $variants
        ]);
    }

    // --- 6. XỬ LÝ CẬP NHẬT (Update 3 bảng) ---
    // --- 6. XỬ LÝ CẬP NHẬT (SMART UPDATE - ĐÃ SỬA LỖI 1451) ---
    public function update($id) {
        $this->checkAuth();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'];
            $brand = $_POST['brand'] ?? '';
            $category_id = $_POST['category_id'];
            $desc = $_POST['description'];
            // Lấy dữ liệu biến thể từ form (View edit.php đã gửi đúng ID rồi)
            $variantsInput = $_POST['variants'] ?? []; 

            // 1. Update bảng PRODUCTS (Cha) - Giữ nguyên
            $productModel = $this->model('Product');
            $productData = [
                'name' => $name,
                'brand' => $brand,
                'category_id' => $category_id,
                'description' => $desc,
                'short_description' => mb_substr(strip_tags($desc), 0, 150, 'UTF-8')
            ];
            $productModel->update($id, $productData);

            // 2. XỬ LÝ BIẾN THỂ (LOGIC MỚI AN TOÀN)
            $variantModel = $this->model('ProductVariant');
            $skuMaster = 'P' . time(); 

            // A. Lấy danh sách ID biến thể ĐANG CÓ trong DB
            $currentVariants = $variantModel->getVariantsByProductId($id);
            $currentIds = array_column($currentVariants, 'id');

            // B. Lấy danh sách ID ĐƯỢC GỬI LÊN từ Form
            $submittedIds = [];
            foreach ($variantsInput as $var) {
                if (!empty($var['id'])) {
                    $submittedIds[] = $var['id'];
                }
            }

            // C. Tìm các ID cần XÓA (Có trong DB nhưng không có trong Form)
            $idsToDelete = array_diff($currentIds, $submittedIds);

            // --- THỰC HIỆN XÓA HOẶC ẨN ---
            if (!empty($idsToDelete)) {
                foreach ($idsToDelete as $delId) {
                    try {
                        // Cố gắng xóa cứng
                        $variantModel->delete($delId); 
                    } catch (\Exception $e) {
                        // QUAN TRỌNG: Nếu lỗi (do dính giỏ hàng) -> Chỉ ẨN đi (Soft Delete)
                        // Update stock = 0 và is_active = 0
                        $variantModel->update($delId, [
                            'is_active' => 0, 
                            'stock' => 0
                        ]);
                    }
                }
            }

            // --- THỰC HIỆN THÊM MỚI HOẶC CẬP NHẬT ---
            foreach ($variantsInput as $idx => $var) {
                if (!empty($var['id'])) {
                    // === TRƯỜNG HỢP 1: CẬP NHẬT (Dòng cũ) ===
                    $updateData = [
                        'name' => $var['name'],
                        'price_cents' => $var['price'],
                        'stock' => $var['stock'],
                        'is_active' => 1 // Kích hoạt lại nếu lỡ bị ẩn trước đó
                    ];
                    $variantModel->update($var['id'], $updateData);

                } else {
                    // === TRƯỜNG HỢP 2: THÊM MỚI (Dòng mới thêm) ===
                    $newData = [
                        'product_id' => $id,
                        'sku' => $skuMaster . '-N' . ($idx + 1),
                        'name' => $var['name'],
                        'price_cents' => $var['price'],
                        'stock' => $var['stock'],
                        'is_active' => 1
                    ];
                    $variantModel->create($newData);
                }
            }

            // 3. Update ảnh (Giữ nguyên)
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $targetDir = "../public/assets/uploads/products/";
                $fileName = time() . "_" . basename($_FILES["image"]["name"]);
                $targetFilePath = $targetDir . $fileName;
                if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFilePath)) {
                    $newImageUrl = "assets/uploads/products/" . $fileName;
                    $imageModel = $this->model('ProductImage');
                    $imageModel->updateByProductId($id, $newImageUrl);
                }
            }

            header('Location: /MY_WEB/public/admin/product');
        }
    }

    private function checkAuth() {
    if (!isset($_SESSION['admin_logged_in'])) {
        header('Location: /MY_WEB/public/admin/auth/login');
        exit();
    }

    // Chỉ cho phép Role 1 (Admin) và 2 (Manager)
    $allowedRoles = [1, 2];
    
    if (!in_array($_SESSION['admin_role'], $allowedRoles)) {
        echo "<script>
            alert('Nhân viên không được quyền quản lý Sản phẩm/Danh mục!'); 
            window.location.href='/MY_WEB/public/admin/dashboard';
        </script>";
        exit();
    }
}
}