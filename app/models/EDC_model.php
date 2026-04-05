<?php

class EDC_model {
    private $table = 'edc';
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function getEDCByMonth($bulan, $tahun)
    {
        $sql = "SELECT e.*, k.tanggal, k.keterangan 
                FROM " . $this->table . " e
                JOIN kas k ON e.kas_id = k.id
                WHERE YEAR(k.tanggal) = :tahun";
        
        if ($bulan !== 'all') {
            $sql .= " AND MONTH(k.tanggal) = :bulan";
        }
        $sql .= " ORDER BY k.tanggal ASC, e.id ASC";

        $this->db->query($sql);
        $this->db->bind('tahun', $tahun);
        if ($bulan !== 'all') {
            $this->db->bind('bulan', $bulan);
        }
        return $this->db->resultSet();
    }

    public function syncFromKas($kas_id, $data_kas)
    {
        $ket = strtolower($data_kas['keterangan']);
        $metode = '';
        if (strpos($ket, 'qris') !== false) {
            $metode = 'qris';
        } elseif (strpos($ket, 'debit') !== false) {
            $metode = 'debit';
        }

        if (!$metode) return false;

        // 1. Get Active Configuration
        $this->db->query("SELECT id, persen_potongan FROM konfigurasi_edc 
                          WHERE metode = :metode 
                          AND berlaku_mulai <= :tanggal 
                          ORDER BY berlaku_mulai DESC LIMIT 1");
        $this->db->bind('metode', $metode);
        $this->db->bind('tanggal', $data_kas['tanggal']);
        $config = $this->db->single();

        if (!$config) return false;

        $nominal = $data_kas['jumlah'];
        $persen = $config['persen_potongan'];
        $jumlah_potongan = $nominal * ($persen / 100);
        $jumlah_masuk = $nominal - $jumlah_potongan;

        // 2. Check if already exists
        $this->db->query("SELECT id FROM " . $this->table . " WHERE kas_id = :kas_id");
        $this->db->bind('kas_id', $kas_id);
        $existing = $this->db->single();

        if ($existing) {
            // Update
            $this->db->query("UPDATE " . $this->table . " SET 
                                konfigurasi_edc_id = :config_id,
                                tanggal = :tanggal,
                                metode = :metode,
                                nominal = :nominal,
                                persen_potongan = :persen,
                                jumlah_masuk = :jumlah_masuk,
                                jumlah_potongan = :jumlah_potongan
                              WHERE id = :id");
            $this->db->bind('id', $existing['id']);
        } else {
            // Insert
            $this->db->query("INSERT INTO " . $this->table . " 
                                (kas_id, konfigurasi_edc_id, tanggal, metode, nominal, persen_potongan, jumlah_masuk, jumlah_potongan)
                              VALUES
                                (:kas_id, :config_id, :tanggal, :metode, :nominal, :persen, :jumlah_masuk, :jumlah_potongan)");
            $this->db->bind('kas_id', $kas_id);
        }

        $this->db->bind('config_id', $config['id']);
        $this->db->bind('tanggal',   $data_kas['tanggal']);
        $this->db->bind('metode',    $metode);
        $this->db->bind('nominal',   $nominal);
        $this->db->bind('persen',    $persen);
        $this->db->bind('jumlah_masuk', $jumlah_masuk);
        $this->db->bind('jumlah_potongan', $jumlah_potongan);

        $this->db->execute();
        return true;
    }

    public function deleteByKasId($kas_id)
    {
        $this->db->query("DELETE FROM " . $this->table . " WHERE kas_id = :kas_id");
        $this->db->bind('kas_id', $kas_id);
        $this->db->execute();
    }
}
