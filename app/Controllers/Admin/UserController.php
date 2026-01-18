<?php
namespace App\Controllers\Admin;
use App\Core\Controller;

class UserController extends Controller {
    public function index() {
        if (!isset($_SESSION['admin_logged_in'])) header('Location: /MY_WEB/public/admin/auth/login');
        
        $userModel = $this->model('User');
        $users = $userModel->all(); // Hàm all() có sẵn trong Model gốc
        $this->view('admin/users/index', ['users' => $users]);
    }
}