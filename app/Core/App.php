<?php
namespace App\Core;

class App {
    protected $controller = 'HomeController'; // Mặc định vào trang chủ
    protected $method = 'index';             // Mặc định gọi hàm index
    protected $params = [];                  // Tham số trên URL

    public function __construct() {
        $url = $this->parseUrl();

        // 1. Kiểm tra xem Controller có tồn tại không
        // Giả sử URL là: /admin/dashboard
        // Cần xử lý logic để tìm đúng file Controller
        
        // Demo đơn giản: URL dạng /Controller/Method/Param
        if (isset($url[0])) {
            // Chuẩn hóa tên: home -> HomeController
            $controllerName = ucfirst($url[0]) . 'Controller';
            
            // Kiểm tra file trong folder Client trước (mặc định)
            if (file_exists('../app/Controllers/Client/' . $controllerName . '.php')) {
                $this->controller = $controllerName;
                unset($url[0]);
                require_once '../app/Controllers/Client/' . $this->controller . '.php';
                $this->controller = new ("App\\Controllers\\Client\\" . $this->controller);
            } 
            // Kiểm tra folder Admin (nếu URL bắt đầu bằng 'admin')
            // ... Logic này ta sẽ nâng cấp sau cho chặt chẽ
        } else {
             // Mặc định require HomeController
             require_once '../app/Controllers/Client/HomeController.php';
             $this->controller = new \App\Controllers\Client\HomeController;
        }

        // 2. Kiểm tra Method
        if (isset($url[1])) {
            if (method_exists($this->controller, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            }
        }

        // 3. Lấy tham số
        $this->params = $url ? array_values($url) : [];

        // 4. Gọi hàm thực thi: Controller->method(params)
        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    public function parseUrl() {
        if (isset($_GET['url'])) {
            // Cắt chuỗi URL, lọc ký tự lạ
            return explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
        }
        return [];
    }
}