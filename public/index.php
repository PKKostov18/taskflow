<?php
session_start();
require_once __DIR__ . '/../vendor/autoload.php';

use App\Core\Router;

define('VIEW_PATH', __DIR__ . '/../views');

$router = new Router();

$router->get('/', 'HomeController', 'index');
$router->get('/projects', 'ProjectController', 'index');
$router->get('/register', 'RegisterController', 'index');
$router->post('/register', 'RegisterController', 'store');
$router->get('/login', 'LoginController', 'index');
$router->post('/login', 'LoginController', 'login');
$router->get('/logout', 'LoginController', 'logout');
$router->get('/projects', 'ProjectController', 'index');
$router->get('/projects/create', 'ProjectController', 'create');
$router->post('/projects/store', 'ProjectController', 'store');
$router->get('/board', 'BoardController', 'index');
$router->post('/tasks/store', 'BoardController', 'store');
$router->post('/tasks/update-status', 'BoardController', 'updateStatus');
$router->post('/tasks/update', 'BoardController', 'update');
$router->post('/tasks/delete', 'BoardController', 'delete');
$router->get('/comments', 'CommentController', 'index');
$router->post('/comments/store', 'CommentController', 'store');
$router->post('/projects/add-member', 'ProjectController', 'addMember');
$router->get('/attachments', 'AttachmentController', 'index');
$router->post('/attachments/upload', 'AttachmentController', 'upload');
$router->get('/logs', 'BoardController', 'logs');
$router->get('/profile', 'ProfileController', 'index');
$router->post('/profile/update', 'ProfileController', 'update');
$router->get('/admin', 'AdminController', 'index');
$router->post('/admin/users/delete', 'AdminController', 'deleteUser');
$router->post('/admin/users/role', 'AdminController', 'changeRole');
$router->post('/admin/projects/delete', 'AdminController', 'deleteProject');
$router->post('/messages/store', 'MessageController', 'store');
$router->post('/messages/delete', 'MessageController', 'delete');

$router->dispatch();