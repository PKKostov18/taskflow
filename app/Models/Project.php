<?php

namespace App\Models;

use App\Core\Model;

class Project extends Model
{
    protected $table = 'projects';

    public function addMember($projectId, $userId)
    {
        $stmt = $this->db->prepare("SELECT id FROM project_users WHERE project_id = ? AND user_id = ?");
        $stmt->execute([$projectId, $userId]);
        if ($stmt->fetch()) return false; 

        $sql = "INSERT INTO project_users (project_id, user_id) VALUES (?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$projectId, $userId]);
    }

    public function getTeam($projectId)
    {
        $sql = "SELECT u.id, u.username, u.email, u.role, u.avatar 
                FROM users u
                JOIN project_users pu ON u.id = pu.user_id
                WHERE pu.project_id = :pid1
                UNION
                SELECT u.id, u.username, u.email, u.role, u.avatar
                FROM users u
                JOIN projects p ON u.id = p.user_id
                WHERE p.id = :pid2";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['pid1' => $projectId, 'pid2' => $projectId]);
        return $stmt->fetchAll();
    }

    public function getAllForUser($userId)
    {
        $sql = "SELECT * FROM projects WHERE user_id = :uid1
                UNION
                SELECT p.* FROM projects p
                JOIN project_users pu ON p.id = pu.project_id
                WHERE pu.user_id = :uid2";
        
        $stmt = $this->db->prepare($sql);
        
        $stmt->execute([
            'uid1' => $userId,
            'uid2' => $userId
        ]);
        
        return $stmt->fetchAll();
    }
}