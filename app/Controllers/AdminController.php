<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;
use App\Models\Project;

class AdminController extends Controller
{
    private function checkAdmin()
    {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header('Location: /');
            exit;
        }
    }

    public function index()
    {
        $this->checkAdmin();
        $userModel = new User();

        $stmt = $userModel->getDb()->query("SELECT * FROM users ORDER BY created_at DESC");
        $users = $stmt->fetchAll();

        $stmtP = $userModel->getDb()->query("SELECT projects.*, users.username as owner_name FROM projects JOIN users ON projects.user_id = users.id ORDER BY created_at DESC");
        $projects = $stmtP->fetchAll();

        $stmtM = $userModel->getDb()->query("
            SELECT messages.*, users.username, users.email, users.avatar, users.role 
            FROM messages 
            JOIN users ON messages.user_id = users.id 
            ORDER BY messages.created_at DESC
        ");
        $messages = $stmtM->fetchAll();

        $this->view('admin/index', [
            'title' => 'Admin Panel',
            'users' => $users,
            'projects' => $projects,
            'messages' => $messages
        ]);
    }

    public function deleteUser()
    {
        $this->checkAdmin();
        $id = $_POST['user_id'];

        if ($id == $_SESSION['user']['id']) {
            header('Location: /admin');
            exit;
        }

        $userModel = new User();
        $userModel->delete($id);

        header('Location: /admin');
        exit;
    }

    public function changeRole()
    {
        $this->checkAdmin();
        $id = $_POST['user_id'];
        $newRole = $_POST['role'];

        $userModel = new User();
        $userModel->update($id, ['role' => $newRole]);

        header('Location: /admin');
        exit;
    }

    public function deleteProject()
    {
        $this->checkAdmin();
        $id = $_POST['project_id'];

        $projectModel = new Project();
        $projectModel->delete($id);

        header('Location: /admin');
        exit;
    }
}