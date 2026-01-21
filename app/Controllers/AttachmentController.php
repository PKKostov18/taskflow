<?php
namespace App\Controllers;
use App\Core\Controller;
use App\Models\Attachment;
use App\Models\ActivityLog;

class AttachmentController extends Controller
{
    public function index() {
        $taskId = $_GET['task_id'] ?? null;
        $model = new Attachment();
        echo json_encode($model->getByTask($taskId));
    }

    public function upload() {
        if (!isset($_FILES['file']) || !isset($_POST['task_id'])) {
            echo json_encode(['success' => false, 'message' => 'No file']);
            exit;
        }

        $taskId = $_POST['task_id'];
        $userId = $_SESSION['user']['id'];
        
        $uploadDir = __DIR__ . '/../../public/uploads/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

        $fileName = basename($_FILES['file']['name']);
        $uniqueName = time() . '_' . $fileName;
        $targetPath = $uploadDir . $uniqueName;
        $publicPath = '/uploads/' . $uniqueName;

        if (move_uploaded_file($_FILES['file']['tmp_name'], $targetPath)) {
            $attachModel = new Attachment();
            $attachModel->create([
                'task_id' => $taskId,
                'user_id' => $userId,
                'filename' => $fileName,
                'filepath' => $publicPath
            ]);

            $logModel = new ActivityLog();
            $logModel->log($taskId, $userId, "Attach file: $fileName");

            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false]);
        }
    }
}