<?php
namespace App\Core;

class App {
    protected $controller = 'HomeController'; 
    protected $method = 'index';
    protected $params = [];

    public function __construct() {
        $url = $this->parseUrl();

        // 1. XỬ LÝ ADMIN ROUTE
        if (isset($url[0]) && strtolower($url[0]) == 'admin') {
            array_shift($url);
            $folder = 'Admin';
            $this->controller = 'DashboardController'; 
            
            if (isset($url[0])) {
                $controllerName = $this->convertToPascalCase($url[0]) . 'Controller';
                if (file_exists('../app/Controllers/Admin/' . $controllerName . '.php')) {
                    $this->controller = $controllerName;
                    array_shift($url);
                }
            }
        } else {
            // 2. XỬ LÝ CLIENT ROUTE
            $folder = 'Client';
            if (isset($url[0])) {
                $controllerName = $this->convertToPascalCase($url[0]) . 'Controller';
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
            $class = "App\\Controllers\\$folder\\" . $this->controller;
            $this->controller = new $class;
        } else {
            die("Lỗi 404: Controller '{$this->controller}' không tồn tại.");
        }

        // 4. XỬ LÝ METHOD
        if (isset($url[0])) {
            $methodName = $this->convertToCamelCase($url[0]);
            if (method_exists($this->controller, $methodName)) {
                $this->method = $methodName;
                array_shift($url);
            } 
            elseif (method_exists($this->controller, str_replace('-', '_', $url[0]))) {
                $this->method = str_replace('-', '_', $url[0]);
                array_shift($url);
            }
        }

        $this->params = $url ? array_values($url) : [];
        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    public function parseUrl() {
        if (isset($_GET['url'])) {
            return explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
        }
        return [];
    }

    private function convertToPascalCase($string) {
        return str_replace(' ', '', ucwords(str_replace('-', ' ', $string)));
    }

    private function convertToCamelCase($string) {
        return lcfirst($this->convertToPascalCase($string));
    }
}