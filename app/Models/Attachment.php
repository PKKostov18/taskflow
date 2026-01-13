<?php
namespace App\Models;
use App\Core\Model;

class Attachment extends Model {
    protected $table = 'attachments';

    public function getByTask($taskId) {
        $sql = "SELECT attachments.*, users.username 
                FROM attachments 
                JOIN users ON attachments.user_id = users.id 
                WHERE task_id = :tid ORDER BY created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['tid' => $taskId]);
        return $stmt->fetchAll();
    }
}