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

    public function login($nama, $password)
    {
        $this->db->query('SELECT * FROM ' . $this->table . ' WHERE nama = :nama');
        $this->db->bind('nama', $nama);
        $user = $this->db->single();

        if ($user) {
            // Support both hashed and plain text for legacy/initial setup
            if (password_verify($password, $user['password']) || $password === $user['password']) {
                return $user;
            }
        }
        return false;
    }

    public function checkAndCreateDefaultAdmin()
    {
        if ($this->getUserCount() == 0) {
            $query = "INSERT INTO " . $this->table . " (nama, role, password) VALUES (:nama, :role, :pass)";
            $this->db->query($query);
            $this->db->bind('nama', 'admin');
            $this->db->bind('role', 'admin');
            $this->db->bind('pass', password_hash('admin123', PASSWORD_DEFAULT));
            $this->db->execute();
        }
    }
}
