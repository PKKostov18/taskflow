<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;

class MessageController extends Controller
{
    public function store()
    {
        if (!isset($_SESSION['user'])) { header('Location: /login'); exit; }

        $content = trim($_POST['content']);
        $userId = $_SESSION['user']['id'];

        if (!empty($content)) {
            $userModel = new User();
            $db = $userModel->getDb();

            $stmt = $db->prepare("INSERT INTO messages (user_id, content) VALUES (:uid, :content)");
            $stmt->execute(['uid' => $userId, 'content' => $content]);
        }

        header('Location: /projects');
        exit;
    }

    public function delete()
    {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header('Location: /'); exit;
        }

        $id = $_POST['id'];
        $userModel = new User();
        $db = $userModel->getDb();

        $stmt = $db->prepare("DELETE FROM messages WHERE id = :id");
        $stmt->execute(['id' => $id]);

        header('Location: /admin');
        exit;
    }
}