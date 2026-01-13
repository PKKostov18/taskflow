<?php

namespace App\Models;

use App\Core\Model;

class Comment extends Model
{
    protected $table = 'comments';

    public function getByTask($taskId)
    {
        $sql = "SELECT comments.*, users.username 
                FROM comments 
                JOIN users ON comments.user_id = users.id 
                WHERE task_id = :task_id 
                ORDER BY created_at DESC";
                
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['task_id' => $taskId]);
        return $stmt->fetchAll();
    }
}