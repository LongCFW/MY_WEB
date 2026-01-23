<?php
namespace App\Controllers\Admin;

use App\Core\Controller;

class CategoryController extends Controller {
    
    // 1. Xem danh sách
    public function index() {
        $this->checkAuth();
        if (!isset($_SESSION['admin_logged_in'])) {
            header('Location: /MY_WEB/public/admin/auth/login');
            exit();
        }

        $categoryModel = $this->model('Category');
        $categories = $categoryModel->all();

        $this->view('admin/categories/index', ['categories' => $categories]);
    }

    // 2. Hiện form thêm mới
    public function create() {
        $this->checkAuth();
        $this->view('admin/categories/create');
    }

    // 3. Xử lý lưu dữ liệu
    public function store() {
        $this->checkAuth();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'];
            // Tạo slug đơn giản từ tên (Ví dụ: "Áo Thun" -> "ao-thun")
            // Tạm thời ta nhập tay hoặc xử lý sau. Ở đây lấy từ input
            $slug = $_POST['slug']; 
            $desc = $_POST['description'];

            $data = [
                'name' => $name,
                'slug' => $slug,
                'description' => $desc
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
        $categoryModel->delete($id);
        header('Location: /MY_WEB/public/admin/category');
    }

    // 5. Hiển thị form sửa (GET)
    public function edit($id) {
        $this->checkAuth();
        $categoryModel = $this->model('Category');
        $category = $categoryModel->find($id); // Lấy dữ liệu cũ theo ID

        if (!$category) {
            // Nếu không tìm thấy ID thì đá về trang danh sách
            header('Location: /MY_WEB/public/admin/category');
            exit();
        }

        // Truyền biến $category sang view edit
        $this->view('admin/categories/edit', ['category' => $category]);
    }

    // 6. Xử lý cập nhật (POST)
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

            $categoryModel = $this->model('Category');
            
            // Gọi hàm update đã viết sẵn trong Core/Model
            $categoryModel->update($id, $data);

            // Xong thì quay về danh sách
            header('Location: /MY_WEB/public/admin/category');
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