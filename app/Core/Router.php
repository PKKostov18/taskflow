<?php

namespace App\Core;

class Router
{
    protected $routes = [];

    public function get($path, $controller, $action)
    {
        $this->routes['GET'][$path] = ['controller' => $controller, 'action' => $action];
    }

    public function post($path, $controller, $action)
    {
        $this->routes['POST'][$path] = ['controller' => $controller, 'action' => $action];
    }

    public function dispatch()
    {
        $uri = $_SERVER['REQUEST_URI'];
        
        $path = parse_url($uri, PHP_URL_PATH);

        $method = $_SERVER['REQUEST_METHOD'];

        if (isset($this->routes[$method][$path])) {
            $controllerName = $this->routes[$method][$path]['controller'];
            $action = $this->routes[$method][$path]['action'];

            $controllerClass = "App\\Controllers\\$controllerName";
            $controller = new $controllerClass();
            
            $controller->$action();
        } else {
            http_response_code(404);
            echo "404 Not Found";
        }
    }
}