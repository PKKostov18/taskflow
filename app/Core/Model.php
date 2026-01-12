<?php

namespace App\Core;

abstract class Model
{
    protected $db;
    protected $table = "";

    public function __construct()
    {
        // Взимаме връзката автоматично
        $this->db = Database::getInstance()->getConnection();
    }

    // Взима всички записи
    public function all()
    {
        $stmt = $this->db->query("SELECT * FROM {$this->table}");
        return $stmt->fetchAll();
    }

    // Взима запис по ID
    public function find($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    // Търсене по колона (напр. email)
    public function where($column, $value)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE $column = :value");
        $stmt->execute(['value' => $value]);
        return $stmt->fetchAll();
    }

    // Създаване на нов запис (Това ще ни трябва за регистрацията!)
    public function create($data)
    {
        $keys = array_keys($data);
        $fields = implode(', ', $keys);
        $placeholders = ':' . implode(', :', $keys);

        $sql = "INSERT INTO {$this->table} ($fields) VALUES ($placeholders)";
        $stmt = $this->db->prepare($sql);
        
        return $stmt->execute($data);
    }
}