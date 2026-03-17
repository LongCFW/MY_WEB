<?php
namespace App\Controllers\Admin;
use App\Core\Controller;

class UserController extends Controller {
    
    // 1. Danh sách    
    public function index() {
        // $this->checkAuth();
        $userModel = $this->model('User');
        
        // Bắt các tham số lọc từ URL Params
        $filters = [
            'search' => trim($_GET['search'] ?? ''),
            'role_id' => $_GET['role_id'] ?? '',
            'status' => $_GET['status'] ?? '' 
        ];

        // Gọi hàm getAllUsers thay vì all()
        $users = $userModel->getAllUsers($filters);
        
        $this->view('admin/users/index', ['users' => $users]);
    }

    // 2. Form thêm mới
    public function create() {
        // $this->checkAuth();
        $this->view('admin/users/create');
    }

    // 3. Xử lý thêm mới
    public function store() {
        // $this->checkAuth();
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

            // --- XỬ LÝ UPLOAD ẢNH ---
            $avatarPath = null;
            if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
                $ext = strtolower(pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION));
                $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                
                if (in_array($ext, $allowed)) {
                    $newFilename = 'avatar_' . time() . '_' . rand(1000, 9999) . '.' . $ext;
                    $uploadDir = __DIR__ . '/../../../public/assets/uploads/avatars/';
                    
                    if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
                    
                    if (move_uploaded_file($_FILES['avatar']['tmp_name'], $uploadDir . $newFilename)) {
                        $avatarPath = 'assets/uploads/avatars/' . $newFilename;
                    }
                }
            }

            $data = [
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
                'password_hash' => password_hash($password, PASSWORD_DEFAULT),
                'role_id' => $role_id,
                'status' => 1,
                'avatar_url' => $avatarPath
            ];

            $userModel->create($data);
            header('Location: /MY_WEB/public/admin/user');
        }
    }

    // 4. Form sửa
    public function edit($id) {
        // $this->checkAuth();
        $userModel = $this->model('User');
        $user = $userModel->find($id);
        
        if (!$user) header('Location: /MY_WEB/public/admin/user');

        $this->view('admin/users/edit', ['user' => $user]);
    }

    // 5. Xử lý cập nhật
    public function update($id) {
        // $this->checkAuth();
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

            // Cập nhật lại Session nếu người bị sửa chính là người đang đăng nhập trên trình duyệt này
            if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $id) {
                $_SESSION['user_name'] = $name;
                $_SESSION['user_email'] = $email;
                // Nếu admin có thể đổi avatar ở đây thì update luôn $_SESSION['user_avatar']
            }
            if (isset($_SESSION['admin_id']) && $_SESSION['admin_id'] == $id) {
                $_SESSION['admin_name'] = $name;
                // Session admin thường không lưu email, nhưng nếu có thì anh update tương tự
            }
            header('Location: /MY_WEB/public/admin/user');
        }
    }

    // 6. Xóa (Smart Delete - Chuẩn MVC)
    public function delete($id) {
        // Không cho phép xóa chính mình
        if ($id == $_SESSION['admin_id']) {
            echo "<script>alert('Không thể xóa tài khoản đang đăng nhập!'); window.location.href='/MY_WEB/public/admin/user';</script>";
            return;
        }

        $userModel = $this->model('User');

        // Gọi hàm xóa ở Model (Hàm này đã tích hợp sẵn Guard kiểm tra nhiều bảng)
        $result = $userModel->delete($id);

        if ($result['status'] === false) {
            // Bị chặn lại do có dữ liệu
            echo "<script>
                    alert('" . addslashes($result['message']) . " Vui lòng khóa (đổi trạng thái) tài khoản này thay vì xóa.'); 
                    window.location.href='/MY_WEB/public/admin/user';
                  </script>";
        } else {
            // Xóa thành công (Tài khoản rác)
            echo "<script>
                    alert('" . addslashes($result['message']) . "'); 
                    window.location.href='/MY_WEB/public/admin/user';
                  </script>";
        }
    }

    // private function checkAuth() {
    //     // 1. Chưa đăng nhập -> Đá về login
    //     if (!isset($_SESSION['admin_logged_in'])) {
    //         header('Location: /MY_WEB/public/admin/auth/login');
    //         exit();
    //     }

    //     // 2. Đã đăng nhập nhưng Role không phải Admin (1) -> Báo lỗi & Đá về Dashboard
    //     if ($_SESSION['admin_role'] != 1) {
    //         echo "<script>
    //             alert('Bạn không có quyền truy cập vào Quản lý người dùng!'); 
    //             window.location.href='/MY_WEB/public/admin/dashboard';
    //         </script>";
    //         exit();
    //     }
    // }
}