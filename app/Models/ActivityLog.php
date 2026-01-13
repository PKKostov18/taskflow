<?php
namespace App\Models;
use App\Core\Model;

class ActivityLog extends Model {
    protected $table = 'activity_logs';

    public function log($taskId, $userId, $text) {
        $this->create([
            'task_id' => $taskId,
            'user_id' => $userId,
            'action_text' => $text
        ]);
    }

    public function getByTask($taskId) {
        $sql = "SELECT activity_logs.*, users.username, users.role 
                FROM activity_logs 
                JOIN users ON activity_logs.user_id = users.id 
                WHERE task_id = :tid ORDER BY created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['tid' => $taskId]);
        return $stmt->fetchAll();
    }
}