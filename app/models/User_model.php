<?php

class User_model {
    private $table = 'users';
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function getAllUsers()
    {
        $this->db->query('SELECT * FROM ' . $this->table);
        return $this->db->resultSet();
    }

    public function getUserCount()
    {
        $this->db->query('SELECT COUNT(*) as count FROM ' . $this->table);
        return $this->db->single()['count'];
    }
}
