<?php
namespace App\Core;

class Controller {
    // Hàm gọi Model
    public function model($model) {
        $class = "App\\Models\\" . $model;
        return new $class(); // Trả về instance của Model
    }

    // Hàm gọi View
    public function view($view, $data = []) {
        // Tự động giải nén mảng data thành biến
        // Ví dụ: ['name' => 'Long'] => biến $name = 'Long'
        extract($data);
        
        $viewFile = "../app/Views/" . $view . ".php";
        if (file_exists($viewFile)) {
            require_once $viewFile;
        } else {
            die("View does not exist: " . $view);
        }
    }
}