<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Comment;

class CommentController extends Controller
{
    public function index()
    {
        $taskId = $_GET['task_id'] ?? null;
        if (!$taskId) {
            echo json_encode([]);
            exit;
        }

        $commentModel = new Comment();
        $comments = $commentModel->getByTask($taskId);

        header('Content-Type: application/json');
        echo json_encode($comments);
        exit;
    }

    public function store()
    {
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!isset($_SESSION['user'])) {
            echo json_encode(['success' => false, 'message' => 'Not logged in']);
            exit;
        }

        $taskId = $input['task_id'] ?? null;
        $content = $input['content'] ?? '';

        if ($taskId && $content) {
            $commentModel = new Comment();
            $commentModel->create([
                'task_id' => $taskId,
                'user_id' => $_SESSION['user']['id'],
                'content' => $content
            ]);
            
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false]);
        }
        exit;
    }
}