<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;

class RegisterController extends Controller
{
    public function index()
    {
        $this->view('auth/register', [
            'title' => 'Регистрация в TaskFlow'
        ]);
    }

    public function store()
    {
        $username = $_POST['username'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        if (empty($username) || empty($email) || empty($password)) {
            die("Моля, попълнете всички полета!");
        }

        $userModel = new User();

        $existingUser = $userModel->where('email', $email);
        
        if (!empty($existingUser)) {
            die("Този имейл вече е регистриран! <a href='/register'>Опитай пак</a>");
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $userModel->create([
            'username' => $username,
            'email' => $email,
            'password' => $hashedPassword
        ]);

        header('Location: /login');
        exit;
    }
}