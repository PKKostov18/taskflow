<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;

class LoginController extends Controller
{
    public function index()
    {
        $this->view('auth/login', ['title' => 'Вход в системата']);
    }

    public function login()
    {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $userModel = new \App\Models\User();
        $users = $userModel->where('email', $email);
        $user = $users[0] ?? null;

        if ($user && password_verify($password, $user['password'])) {
            
            $_SESSION['user'] = [
                'id' => $user['id'],
                'username' => $user['username'],
                'email' => $user['email'],
                'role' => $user['role'],
                'avatar' => $user['avatar'] ?? null
            ];

            header('Location: /');
            exit;
        }

        $this->view('auth/login', ['error' => 'Invalid credentials']);
    }

    public function logout()
    {
        session_destroy();
        header('Location: /login');
        exit;
    }
}