<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Project;
use App\Models\Task;
use App\Models\ActivityLog;

class BoardController extends Controller
{
    public function index()
    {
        if (!isset($_SESSION['user'])) { header('Location: /login'); exit; }

        $projectId = $_GET['id'] ?? null;
        if (!$projectId) { header('Location: /projects'); exit; }

        $projectModel = new Project();
        $project = $projectModel->find($projectId);
        $team = $projectModel->getTeam($projectId);

        $taskModel = new Task();
        $tasks = $taskModel->where('project_id', $projectId);

        $columns = ['todo' => [], 'in_progress' => [], 'done' => []];
        
        $stats = ['todo' => 0, 'in_progress' => 0, 'done' => 0];

        foreach ($tasks as $task) {
            $columns[$task['status']][] = $task;
            $stats[$task['status']]++;
        }

        $this->view('board/index', [
            'title' => 'Дъска: ' . $project['name'],
            'project' => $project,
            'columns' => $columns,
            'team' => $team,
            'stats' => $stats
        ]);
    }

    public function store()
    {
        $projectId = $_POST['project_id'];
        $title = $_POST['title'];
        $priority = $_POST['priority'] ?? 'medium';
        $dueDate = !empty($_POST['due_date']) ? $_POST['due_date'] : null; 

        if (!empty($title)) {
            $taskModel = new Task();
            $newTaskId = $taskModel->create([
                'project_id' => $projectId,
                'title' => $title,
                'status' => 'todo',
                'priority' => $priority,
                'assigned_to' => $_SESSION['user']['id'],
                'due_date' => $dueDate 
            ]);

            $log = new ActivityLog();
            $log->log($newTaskId, $_SESSION['user']['id'], "създаде задачата.");
        }
        
        header("Location: /board?id=$projectId");
        exit;
    }

    public function update()
    {
        $id = $_POST['id'];
        $title = $_POST['title'];
        $description = $_POST['description'];
        $priority = $_POST['priority'];
        $projectId = $_POST['project_id'];
        $assignedTo = $_POST['assigned_to'] ?: null;
        $dueDate = !empty($_POST['due_date']) ? $_POST['due_date'] : null;

        $taskModel = new Task();
        $taskModel->update($id, [
            'title' => $title,
            'description' => $description,
            'priority' => $priority,
            'assigned_to' => $assignedTo,
            'due_date' => $dueDate 
        ]);

        $log = new ActivityLog();
        $log->log($id, $_SESSION['user']['id'], "редактира детайлите.");

        header("Location: /board?id=$projectId");
        exit;
    }

    public function updateStatus()
    {
        $input = json_decode(file_get_contents('php://input'), true);
        $taskId = $input['taskId'] ?? null;
        $status = $input['status'] ?? null;

        if ($taskId && $status) {
            $taskModel = new Task();
            $taskModel->update($taskId, ['status' => $status]);

            $log = new ActivityLog();
            $log->log($taskId, $_SESSION['user']['id'], "промени статуса на $status");
            
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false]);
        }
        exit;
    }

    public function delete()
    {
        $id = $_POST['id'];
        $projectId = $_POST['project_id'];
        $taskModel = new Task();
        $taskModel->delete($id);
        header("Location: /board?id=$projectId");
        exit;
    }
    
    public function logs()
    {
        $taskId = $_GET['task_id'];
        $log = new ActivityLog();
        echo json_encode($log->getByTask($taskId));
    }
}