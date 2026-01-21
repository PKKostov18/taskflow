<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;

class RegisterController extends Controller
{
    public function index()
    {
        $this->view('auth/register', [
            'title' => 'Register in TaskFlow'
        ]);
    }

    public function store()
    {
        $username = $_POST['username'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        if (empty($username) || empty($email) || empty($password)) {
            die("Please fill in all fields!");
        }

        $userModel = new User();

        $existingUser = $userModel->where('email', $email);
        
        if (!empty($existingUser)) {
            die("This email is already registered! <a href='/register'>Try again</a>");
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