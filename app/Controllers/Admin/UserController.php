<?php
namespace App\Controllers\Admin;
use App\Core\Controller;

class UserController extends Controller {
    
    // 1. Danh sách
    public function index() {
        $this->checkAuth();
        $userModel = $this->model('User');
        $users = $userModel->all();
        $this->view('admin/users/index', ['users' => $users]);
    }

    // 2. Form thêm mới
    public function create() {
        $this->checkAuth();
        $this->view('admin/users/create');
    }

    // 3. Xử lý thêm mới
    public function store() {
        $this->checkAuth();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'];
            $email = $_POST['email'];
            $phone = $_POST['phone'];
            $password = $_POST['password'];
            $role_id = $_POST['role_id'];

            $userModel = $this->model('User');

            // Validate Email
            if ($userModel->checkEmailExists($email)) {
                echo "<script>alert('Email đã tồn tại!'); window.history.back();</script>";
                return;
            }

            $data = [
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
                'password_hash' => password_hash($password, PASSWORD_DEFAULT),
                'role_id' => $role_id,
                'status' => 1 // Active
            ];

            $userModel->create($data);
            header('Location: /MY_WEB/public/admin/user');
        }
    }

    // 4. Form sửa
    public function edit($id) {
        $this->checkAuth();
        $userModel = $this->model('User');
        $user = $userModel->find($id);
        
        if (!$user) header('Location: /MY_WEB/public/admin/user');

        $this->view('admin/users/edit', ['user' => $user]);
    }

    // 5. Xử lý cập nhật
    public function update($id) {
        $this->checkAuth();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'];
            $email = $_POST['email'];
            $phone = $_POST['phone'];
            $role_id = $_POST['role_id'];
            $status = $_POST['status'];
            $password = $_POST['password']; // Nếu nhập thì đổi pass, ko nhập thì thôi

            $userModel = $this->model('User');

            // Validate Email (trừ chính user này)
            if ($userModel->checkEmailExists($email, $id)) {
                echo "<script>alert('Email này đã được sử dụng bởi người khác!'); window.history.back();</script>";
                return;
            }

            $data = [
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
                'role_id' => $role_id,
                'status' => $status
            ];

            // Chỉ cập nhật mật khẩu nếu người dùng nhập vào
            if (!empty($password)) {
                $data['password_hash'] = password_hash($password, PASSWORD_DEFAULT);
            }

            $userModel->update($id, $data);
            header('Location: /MY_WEB/public/admin/user');
        }
    }

    // 6. Xóa
    public function delete($id) {
        $this->checkAuth();
        // Không cho phép xóa chính mình
        if ($id == $_SESSION['admin_id']) {
            echo "<script>alert('Không thể xóa tài khoản đang đăng nhập!'); window.location.href='/MY_WEB/public/admin/user';</script>";
            return;
        }

        $userModel = $this->model('User');
        $userModel->delete($id);
        header('Location: /MY_WEB/public/admin/user');
    }

    private function checkAuth() {
        // 1. Chưa đăng nhập -> Đá về login
        if (!isset($_SESSION['admin_logged_in'])) {
            header('Location: /MY_WEB/public/admin/auth/login');
            exit();
        }

        // 2. Đã đăng nhập nhưng Role không phải Admin (1) -> Báo lỗi & Đá về Dashboard
        if ($_SESSION['admin_role'] != 1) {
            echo "<script>
                alert('Bạn không có quyền truy cập vào Quản lý người dùng!'); 
                window.location.href='/MY_WEB/public/admin/dashboard';
            </script>";
            exit();
        }
    }
}