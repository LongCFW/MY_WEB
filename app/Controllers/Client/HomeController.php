<?php
namespace App\Controllers\Client;

use App\Core\Controller;

class HomeController extends Controller {
    public function index() {
        // Gọi thử User Model
        $userModel = $this->model('User');
        $users = $userModel->all();

        // Trả về view kèm dữ liệu
        $this->view('client/home/index', ['users' => $users]);
    }
}