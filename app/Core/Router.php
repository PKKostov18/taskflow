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
        $url = $_GET['url'] ?? ''; 
        $url = '/' . rtrim($url, '/');
        if ($url == '//') $url = '/';

        $method = $_SERVER['REQUEST_METHOD'];

        if (isset($this->routes[$method][$url])) {
            $controllerName = $this->routes[$method][$url]['controller'];
            $action = $this->routes[$method][$url]['action'];

            $controllerClass = "App\\Controllers\\$controllerName";
            $controller = new $controllerClass();
            
            $controller->$action();
        } else {
            echo "404 Not Found";
        }
    }
}