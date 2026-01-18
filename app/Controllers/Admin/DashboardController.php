<?php
namespace App\Controllers\Admin;

use App\Core\Controller;

class DashboardController extends Controller {
    public function index() {
        // Kiểm tra xem đã đăng nhập chưa
        if (!isset($_SESSION['admin_logged_in'])) {
            header('Location: /MY_WEB/public/admin/auth/login');
            exit();
        }

        // Gọi View Dashboard
        $this->view('admin/dashboard/index');
    }
}