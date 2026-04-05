<?php

class Gaji_model {
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function getGajiBulanan($bulan, $tahun)
    {
        $periode = "$tahun-$bulan-01";
        
        // Ambil SEMUA operator, lalu LEFT JOIN ke tabel gaji untuk periode tersebut
        $this->db->query("SELECT 
                            o.id as operator_id, 
                            o.nama,
                            (SELECT gaji_pokok FROM gaji WHERE operator_id = o.id AND periode < :periode ORDER BY periode DESC LIMIT 1) as gaji_pokok_prev,
                            g.id as gaji_record_id,
                            g.gaji_pokok,
                            g.lembur,
                            g.kas_bon,
                            g.total_diterima
                          FROM operators o
                          LEFT JOIN gaji g ON o.id = g.operator_id 
                               AND MONTH(g.periode) = :bulan 
                               AND YEAR(g.periode) = :tahun
                          GROUP BY o.id
                          ORDER BY o.nama ASC");
        
        $this->db->bind('periode', $periode);
        $this->db->bind('bulan', (int)$bulan);
        $this->db->bind('tahun', (int)$tahun);
        
        return $this->db->resultSet();
    }

    public function saveGaji($data)
    {
        $periode = $data['tahun'] . "-" . $data['bulan'] . "-01";
        
        // Cek apakah sudah ada record untuk operator ini di periode ini
        $this->db->query("SELECT id FROM gaji WHERE operator_id = :op_id AND periode = :periode");
        $this->db->bind('op_id', $data['operator_id']);
        $this->db->bind('periode', $periode);
        $existing = $this->db->single();

        if ($existing) {
            // Update
            $this->db->query("UPDATE gaji SET 
                                gaji_pokok = :gp, 
                                lembur = :lb, 
                                kas_bon = :kb 
                              WHERE id = :id");
            $this->db->bind('gp', $data['gaji_pokok']);
            $this->db->bind('lb', $data['lembur']);
            $this->db->bind('kb', $data['kas_bon']);
            $this->db->bind('id', $existing['id']);
        } else {
            // Insert
            $this->db->query("INSERT INTO gaji (operator_id, periode, gaji_pokok, lembur, kas_bon) 
                              VALUES (:op_id, :periode, :gp, :lb, :kb)");
            $this->db->bind('op_id', $data['operator_id']);
            $this->db->bind('periode', $periode);
            $this->db->bind('gp', $data['gaji_pokok']);
            $this->db->bind('lb', $data['lembur']);
            $this->db->bind('kb', $data['kas_bon']);
        }

        return $this->db->execute();
    }
}
