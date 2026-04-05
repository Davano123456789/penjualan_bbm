<?php

class Operator_model {
    private $table = 'operators';
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function getAllOperator()
    {
        $this->db->query('SELECT * FROM ' . $this->table);
        return $this->db->resultSet();
    }

    public function tambahDataOperator($data)
    {
        $query = "INSERT INTO operators (nama, user_id) VALUES (:nama, :user_id)";
        $this->db->query($query);
        $this->db->bind('nama', $data['nama']);
        $this->db->bind('user_id', 1); // Default to admin for now
        
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function hapusDataOperator($id)
    {
        $query = "DELETE FROM operators WHERE id = :id";
        $this->db->query($query);
        $this->db->bind('id', $id);
        
        $this->db->execute();
        return $this->db->rowCount();
    }
}
