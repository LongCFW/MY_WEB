<?php
namespace App\Core;

class App {
    protected $controller = 'HomeController'; 
    protected $method = 'index';
    protected $params = [];

    public function __construct() {
        $url = $this->parseUrl(); // Ví dụ: ['admin', 'auth', 'login']

        // 1. XỬ LÝ ADMIN ROUTE
        // Nếu URL bắt đầu bằng 'admin'
        if (isset($url[0]) && strtolower($url[0]) == 'admin') {
            
            // Xóa chữ 'admin' khỏi mảng và sắp xếp lại index
            array_shift($url); // Mảng còn: ['auth', 'login']
            
            // Mặc định Admin Controller
            $this->controller = 'DashboardController'; 
            $folder = 'Admin';

            // Kiểm tra xem phần tử tiếp theo có phải Controller không
            if (isset($url[0])) {
                $controllerName = ucfirst($url[0]) . 'Controller';
                if (file_exists('../app/Controllers/Admin/' . $controllerName . '.php')) {
                    $this->controller = $controllerName;
                    array_shift($url); // Xóa tên controller, mảng còn: ['login']
                }
            }
        } else {
            // 2. XỬ LÝ CLIENT ROUTE
            $folder = 'Client';
            if (isset($url[0])) {
                $controllerName = ucfirst($url[0]) . 'Controller';
                if (file_exists('../app/Controllers/Client/' . $controllerName . '.php')) {
                    $this->controller = $controllerName;
                    array_shift($url);
                }
            }
        }

        // 3. REQUIRE FILE CONTROLLER
        $file = '../app/Controllers/' . $folder . '/' . $this->controller . '.php';
        
        if (file_exists($file)) {
            require_once $file;
            // Namespace đầy đủ
            $class = "App\\Controllers\\$folder\\" . $this->controller;
            $this->controller = new $class;
        } else {
            // Nếu không tìm thấy file, báo lỗi hoặc về trang chủ
            die("Controller không tồn tại: " . $this->controller);
        }

        // 4. XỬ LÝ METHOD (Hàm)
        // Lúc này trong mảng $url, phần tử đầu tiên [0] chính là method (nếu có)
        if (isset($url[0])) {
            if (method_exists($this->controller, $url[0])) {
                $this->method = $url[0];
                array_shift($url); // Xóa method, còn lại là params
            }
        }

        // 5. LẤY THAM SỐ CÒN LẠI
        $this->params = $url ? array_values($url) : [];

        // 6. GỌI HÀM
        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    public function parseUrl() {
        if (isset($_GET['url'])) {
            return explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
        }
        return [];
    }
}