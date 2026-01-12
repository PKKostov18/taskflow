<?php

namespace App\Core;

class Controller
{
    public function view($viewName, $data = [])
    {
        extract($data);
        
        if (file_exists(VIEW_PATH . "/$viewName.php")) {
            require_once VIEW_PATH . "/$viewName.php";
        } else {
            echo "View $viewName not found!";
        }
    }
}