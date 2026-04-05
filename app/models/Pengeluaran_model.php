<?php

class Pengeluaran_model {
    private $table = 'kas'; // Changed from 'pengeluaran'
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function getAllPengeluaran($bulan = null, $tahun = null)
    {
        // Query from Kas where kategori_biaya exists, but avoid double counting for "Keduanya" (TRX)
        // We pick 'arus' side for TRX, and anything else that is 'lemari' only.
        $sql = "SELECT *, kategori_biaya as kategori, jumlah as nominal 
                FROM " . $this->table . " 
                WHERE kategori_biaya IS NOT NULL 
                AND kategori_biaya != ''
                AND (kategori = 'arus' OR (kategori = 'lemari' AND transaksi_id IS NULL))";
        
        if ($bulan && $tahun && $bulan !== 'all') {
            $sql .= " AND MONTH(tanggal) = :bulan AND YEAR(tanggal) = :tahun";
        } elseif ($tahun) {
            $sql .= " AND YEAR(tanggal) = :tahun";
        }
        $sql .= " ORDER BY tanggal DESC, id DESC";

        $this->db->query($sql);
        if ($bulan && $tahun && $bulan !== 'all') {
            $this->db->bind('bulan', $bulan);
            $this->db->bind('tahun', $tahun);
        } elseif ($tahun) {
            $this->db->bind('tahun', $tahun);
        }
        return $this->db->resultSet();
    }

    public function getTotalPengeluaranBulan($bulan, $tahun)
    {
        $sql = "SELECT SUM(jumlah) as total 
                FROM " . $this->table . " 
                WHERE kategori_biaya IS NOT NULL 
                AND kategori_biaya != ''
                AND (kategori = 'arus' OR (kategori = 'lemari' AND transaksi_id IS NULL))
                AND YEAR(tanggal) = :tahun";
        
        if ($bulan !== 'all') {
            $sql .= " AND MONTH(tanggal) = :bulan";
        }

        $this->db->query($sql);
        $this->db->bind('tahun', $tahun);
        if ($bulan !== 'all') {
            $this->db->bind('bulan', $bulan);
        }
        
        $res = $this->db->single();
        return $res['total'] ?? 0;
    }

    public function getSummaryByKategori($bulan, $tahun)
    {
        $sql = "SELECT kategori_biaya as kategori, SUM(jumlah) as total 
                FROM " . $this->table . " 
                WHERE kategori_biaya IS NOT NULL 
                AND kategori_biaya != ''
                AND (kategori = 'arus' OR (kategori = 'lemari' AND transaksi_id IS NULL))
                AND YEAR(tanggal) = :tahun";

        if ($bulan !== 'all') {
            $sql .= " AND MONTH(tanggal) = :bulan";
        }

        $sql .= " GROUP BY kategori_biaya";

        $this->db->query($sql);
        $this->db->bind('tahun', $tahun);
        if ($bulan !== 'all') {
            $this->db->bind('bulan', $bulan);
        }
        
        return $this->db->resultSet();
    }
}
