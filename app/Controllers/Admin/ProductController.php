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
            // 1. Lấy dữ liệu từ Form
            $name = $_POST['name'];
            $brand = $_POST['brand'] ?? '';
            $category_id = $_POST['category_id'];
            $desc = $_POST['description'];
            $price = $_POST['price']; // Giá
            $stock = $_POST['stock']; // Số lượng kho
            
            // Tạo Slug tự động từ tên (Ví dụ: "Áo Thun" -> "ao-thun")
            $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name)));

            // === BƯỚC 1: LƯU VÀO BẢNG PRODUCTS ===
            $productModel = $this->model('Product');
            $productData = [
                'name' => $name,
                'slug' => $slug . '-' . time(), // Thêm time để tránh trùng slug
                'brand' => $brand,
                'category_id' => $category_id,
                'description' => $desc,
                'short_description' => mb_substr($desc, 0, 150, 'UTF-8'), 
                'is_active' => 1,
                'sku' => 'P' . time() // SKU tạm cho product cha
            ];
            // Hàm create trả về ID vừa tạo
            $productId = $productModel->create($productData); 

            // === BƯỚC 2: LƯU VÀO BẢNG PRODUCT_VARIANTS (Mặc định 1 variant) ===
            $variantModel = $this->model('ProductVariant');
            $variantData = [
                'product_id' => $productId,
                'name' => 'Default',
                'sku' => 'V' . time(),
                'price_cents' => $price, // Lưu thẳng giá trị nhập vào
                'stock' => $stock,
                'is_active' => 1
            ];
            $variantModel->create($variantData);

            // === BƯỚC 3: XỬ LÝ UPLOAD ẢNH VÀ LƯU VÀO PRODUCT_IMAGES ===
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $targetDir = "../public/assets/uploads/";
                if (!file_exists($targetDir)) mkdir($targetDir, 0777, true);

                $fileName = time() . "_" . basename($_FILES["image"]["name"]);
                $targetFilePath = $targetDir . $fileName;

                if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFilePath)) {
                    // Insert vào DB
                    $imageModel = $this->model('ProductImage');
                    $imageData = [
                        'product_id' => $productId,
                        'image_url' => "assets/uploads/" . $fileName,
                        'position' => 1,
                        'alt_text' => $name
                    ];
                    $imageModel->create($imageData);
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

        // Lấy danh mục để hiển thị lại dropdown
        $categoryModel = $this->model('Category');
        $categories = $categoryModel->all();

        $this->view('admin/products/edit', [
            'product' => $product,
            'categories' => $categories
        ]);
    }

    // --- 6. XỬ LÝ CẬP NHẬT (Update 3 bảng) ---
    public function update($id) {
        $this->checkAuth();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'];
            $brand = $_POST['brand'] ?? '';
            $category_id = $_POST['category_id'];
            $desc = $_POST['description'];
            $price = $_POST['price'];
            $stock = $_POST['stock'];
            
            // 1. Update bảng PRODUCTS
            $productModel = $this->model('Product');
            $productData = [
                'name' => $name,
                'brand' => $brand,
                'category_id' => $category_id,
                'description' => $desc,
                'short_description' => mb_substr($desc, 0, 150, 'UTF-8')
            ];
            $productModel->update($id, $productData);

            // 2. Update bảng VARIANTS
            $variantModel = $this->model('ProductVariant');
            $variantModel->updateByProductId($id, [
                'price_cents' => $price,
                'stock' => $stock
            ]);

            // 3. Update bảng IMAGES (Nếu có upload ảnh mới)
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $targetDir = "../public/assets/uploads/";
                $fileName = time() . "_" . basename($_FILES["image"]["name"]);
                $targetFilePath = $targetDir . $fileName;

                if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFilePath)) {
                    $newImageUrl = "assets/uploads/" . $fileName;
                    
                    // Cập nhật đường dẫn mới vào DB
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