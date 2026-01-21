<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Project;

class ProjectController extends Controller
{
    public function index()
    {
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }

        $userId = $_SESSION['user']['id'];
        $projectModel = new Project();
        
        $projects = $projectModel->getAllForUser($userId);

        $this->view('projects/index', [
            'title' => 'My projects',
            'projects' => $projects
        ]);
    }

    public function create()
    {
        if ($_SESSION['user']['role'] === 'developer') {
            header('Location: /projects');
            exit;
        }
        
        $this->view('projects/create', ['title' => 'New project']);
    }

    public function store()
    {
        if ($_SESSION['user']['role'] === 'developer') {
            die('You do not have permission to create projects!');
        }

        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }

        $name = $_POST['name'] ?? '';
        $description = $_POST['description'] ?? '';
        $userId = $_SESSION['user']['id'];

        if (empty($name)) {
            die('Project name is required!');
        }

        $projectModel = new Project();
        $projectModel->create([
            'name' => $name,
            'description' => $description,
            'user_id' => $userId
        ]);

        header('Location: /projects');
        exit;
    }

    public function addMember()
    {
        if ($_SESSION['user']['role'] === 'developer') {
             $projectId = $_POST['project_id'];
             header("Location: /board?id=$projectId");
             exit;
        }

        $projectId = $_POST['project_id'];
        $email = $_POST['email'];

        $userModel = new \App\Models\User();
        $users = $userModel->where('email', $email);
        $userToAdd = $users[0] ?? null;

        if ($userToAdd) {
            $projectModel = new \App\Models\Project();
            $projectModel->addMember($projectId, $userToAdd['id']);
        } else {}

        header("Location: /board?id=$projectId");
        exit;
    }
}