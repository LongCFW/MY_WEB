<?php
namespace App\Controllers\Admin;

use App\Core\Controller;

class CategoryController extends Controller {
    
    // 1. Xem danh sách
    public function index() {
        $this->checkAuth();
        $categoryModel = $this->model('Category');
        $categories = $categoryModel->all();
        $this->view('admin/categories/index', ['categories' => $categories]);
    }

    // 2. Hiện form thêm mới
    public function create() {
        $this->checkAuth();
        $this->view('admin/categories/create');
    }

    // 3. Xử lý lưu dữ liệu (STORE)
    public function store() {
        $this->checkAuth();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'];
            $slug = $_POST['slug']; 
            $desc = $_POST['description'];

            // Xử lý upload ảnh
            $imageUrl = null;
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $imageUrl = $this->uploadImage($_FILES['image']);
            }

            $data = [
                'name' => $name,
                'slug' => $slug,
                'description' => $desc,
                'image_url' => $imageUrl // Lưu đường dẫn ảnh vào DB
            ];

            $categoryModel = $this->model('Category');
            $categoryModel->create($data);

            header('Location: /MY_WEB/public/admin/category');
        }
    }

    // 4. Xóa
    public function delete($id) {
        $this->checkAuth();
        $categoryModel = $this->model('Category');
        
        // (Tùy chọn) Xóa file ảnh cũ nếu cần thiết
        // $oldCat = $categoryModel->find($id);
        // if ($oldCat['image_url'] && file_exists('../public/' . $oldCat['image_url'])) { unlink('../public/' . $oldCat['image_url']); }

        $categoryModel->delete($id);
        header('Location: /MY_WEB/public/admin/category');
    }

    // 5. Hiển thị form sửa
    public function edit($id) {
        $this->checkAuth();
        $categoryModel = $this->model('Category');
        $category = $categoryModel->find($id);

        if (!$category) {
            header('Location: /MY_WEB/public/admin/category');
            exit();
        }

        $this->view('admin/categories/edit', ['category' => $category]);
    }

    // 6. Xử lý cập nhật (UPDATE)
    public function update($id) {
        $this->checkAuth();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'];
            $slug = $_POST['slug'];
            $desc = $_POST['description'];

            $data = [
                'name' => $name,
                'slug' => $slug,
                'description' => $desc
            ];

            // Kiểm tra nếu có upload ảnh mới
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                // Upload ảnh mới
                $newImage = $this->uploadImage($_FILES['image']);
                if ($newImage) {
                    $data['image_url'] = $newImage;
                }
            }
            // Nếu không upload ảnh mới thì giữ nguyên ảnh cũ (không update trường image_url)

            $categoryModel = $this->model('Category');
            $categoryModel->update($id, $data);

            header('Location: /MY_WEB/public/admin/category');
        }
    }

    // --- HÀM PHỤ TRỢ UPLOAD ẢNH ---
    private function uploadImage($file) {
        $targetDir = "../public/assets/uploads/categories/"; // Thư mục lưu ảnh
        
        // Tạo thư mục nếu chưa tồn tại
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        // Tạo tên file ngẫu nhiên để tránh trùng
        $fileName = time() . "_" . basename($file["name"]);
        $targetFilePath = $targetDir . $fileName;
        
        // Di chuyển file
        if (move_uploaded_file($file["tmp_name"], $targetFilePath)) {
            // Trả về đường dẫn tương đối để lưu DB
            return "assets/uploads/categories/" . $fileName;
        }
        return null;
    }

    private function checkAuth() {
        if (!isset($_SESSION['admin_logged_in'])) {
            header('Location: /MY_WEB/public/admin/auth/login');
            exit();
        }
        $allowedRoles = [1, 2];
        if (!in_array($_SESSION['admin_role'], $allowedRoles)) {
            echo "<script>alert('Không có quyền truy cập!'); window.location.href='/MY_WEB/public/admin/dashboard';</script>";
            exit();
        }
    }
}