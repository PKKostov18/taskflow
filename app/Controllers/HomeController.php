<?php
namespace App\Controllers;

use App\Core\Controller;

class HomeController extends Controller
{
    public function index()
    {
        $user = $_SESSION['user'] ?? null;

        $this->view('home/index', [
            'title' => 'Building Manager',
            'user' => $user
        ]);
    }
}