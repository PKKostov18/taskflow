<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\User; 

class ProfileController extends Controller
{
    public function index()
    {
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }
        
        $this->view('profile/index', [
            'title' => 'Моят Профил',
            'user' => $_SESSION['user']
        ]);
    }

    public function update()
    {
        if (!isset($_SESSION['user'])) { header('Location: /login'); exit; }

        $userId = $_SESSION['user']['id'];
        
        $userModel = new User(); 

        if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === 0) {
            $uploadDir = __DIR__ . '/../../public/uploads/avatars/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

            $fileName = time() . '_' . basename($_FILES['avatar']['name']);
            $targetPath = $uploadDir . $fileName;
            $publicPath = '/uploads/avatars/' . $fileName;

            if (move_uploaded_file($_FILES['avatar']['tmp_name'], $targetPath)) {
                
                $userModel->update($userId, ['avatar' => $publicPath]);
                
                $_SESSION['user']['avatar'] = $publicPath;
            }
        }

        if (!empty($_POST['password'])) {
            $hashedPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $userModel->update($userId, ['password' => $hashedPassword]);
        }

        header('Location: /profile');
        exit;
    }
}