<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Core\Router;
use App\Core\Database;

define('VIEW_PATH', __DIR__ . '/../views');

$router = new Router();

$router->get('/', 'HomeController', 'index');
$router->get('/projects', 'ProjectController', 'index');

$router->dispatch();