<?php
namespace App\Controllers;

use App\Core\Controller;

class HomeController extends Controller
{
    public function index()
    {
        $this->view('home/index', [
            'title' => 'Enterprise TaskFlow',
            'message' => 'Здравей, това е твоята MVC структура!'
        ]);
    }
}