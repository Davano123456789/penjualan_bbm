<?php

class Kas_model {
    private $table = 'kas';
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function getKasBulan($bulan, $tahun, $kategori = 'lemari')
    {
        $sql = "SELECT * FROM " . $this->table . " WHERE kategori = :kategori AND YEAR(tanggal) = :tahun";
        if ($bulan !== 'all') {
            $sql .= " AND MONTH(tanggal) = :bulan";
        }
        $sql .= " ORDER BY tanggal ASC, id ASC";

        $this->db->query($sql);
        $this->db->bind('kategori', $kategori);
        $this->db->bind('tahun', $tahun);
        if ($bulan !== 'all') {
            $this->db->bind('bulan', $bulan);
        }
        return $this->db->resultSet();
    }

    public function getSaldoAwal($tanggal, $kategori = 'lemari')
    {
        // Get sum of all previous transactions
        $this->db->query("SELECT 
                            SUM(CASE WHEN tipe = 'debit' THEN jumlah ELSE 0 END) as total_debit,
                            SUM(CASE WHEN tipe = 'kredit' THEN jumlah ELSE 0 END) as total_kredit
                          FROM " . $this->table . " 
                          WHERE kategori = :kategori AND tanggal < :tanggal");
        $this->db->bind('kategori', $kategori);
        $this->db->bind('tanggal', $tanggal);
        $res = $this->db->single();
        
        $debit = $res['total_debit'] ?? 0;
        $kredit = $res['total_kredit'] ?? 0;
        
        return $debit - $kredit;
    }

    public function tambahDataKas($data)
    {
        // Handle "Keduanya" (Lemari & Arus)
        if (isset($data['kategori']) && $data['kategori'] === 'keduanya') {
            $transaksi_id = 'TRX-' . time() . '-' . bin2hex(random_bytes(4));
            
            // Insert for Lemari
            $data['kategori'] = 'lemari';
            $data['transaksi_id'] = $transaksi_id;
            $this->tambahSingleRow($data);
            
            // Insert for Arus
            $data['kategori'] = 'arus';
            $data['transaksi_id'] = $transaksi_id;
            $this->tambahSingleRow($data);
            
            return 2;
        }

        return $this->tambahSingleRow($data);
    }

    private function tambahSingleRow($data)
    {
        $query = "INSERT INTO " . $this->table . " 
                    (tanggal, keterangan, tipe, jumlah, kategori, harian_id, pengeluaran_id, kategori_biaya, transaksi_id) 
                   VALUES 
                    (:tanggal, :keterangan, :tipe, :jumlah, :kategori, :harian_id, :pengeluaran_id, :kategori_biaya, :transaksi_id)";
        
        $this->db->query($query);
        $this->db->bind('tanggal',          $data['tanggal']);
        $this->db->bind('keterangan',       $data['keterangan']);
        $this->db->bind('tipe',             $data['tipe']);
        $this->db->bind('jumlah',           $data['jumlah']);
        $this->db->bind('kategori',         $data['kategori'] ?? 'lemari');
        $this->db->bind('harian_id',        $data['harian_id'] ?? null);
        $this->db->bind('pengeluaran_id',   $data['pengeluaran_id'] ?? null);
        $this->db->bind('kategori_biaya',   (!empty($data['kategori_biaya'])) ? $data['kategori_biaya'] : null);
        $this->db->bind('transaksi_id',     $data['transaksi_id'] ?? null);

        $this->db->execute();
        $kas_id = $this->db->lastInsertId();

        // Automatic Sync to EDC if it's a non-cash transaction
        $ket = strtolower($data['keterangan']);
        if (strpos($ket, 'qris') !== false || strpos($ket, 'debit') !== false) {
            // AVOID DUPLICATION: 
            // If it is a dual transaction (has transaksi_id), only sync for 'arus' category.
            // If it is a single transaction (no transaksi_id), sync for whatever category is given.
            if (empty($data['transaksi_id']) || $data['kategori'] === 'arus') {
                require_once 'EDC_model.php';
                $edc_model = new EDC_model();
                $edc_model->syncFromKas($kas_id, $data);
            }
        }

        return 1;
    }

    public function syncFromPengeluaran($pengeluaran_id, $tanggal, $keterangan, $jumlah)
    {
        // 1. Delete existing entries for this pengeluaran_id to avoid duplication
        $this->deleteByPengeluaranId($pengeluaran_id);

        // 2. Add Kredit for both Arus and Lemari
        $this->tambahDataKas([
            'tanggal' => $tanggal,
            'keterangan' => '[Pengeluaran] ' . $keterangan,
            'tipe' => 'kredit',
            'jumlah' => $jumlah,
            'kategori' => 'arus',
            'pengeluaran_id' => $pengeluaran_id
        ]);
        $this->tambahDataKas([
            'tanggal' => $tanggal,
            'keterangan' => '[Pengeluaran] ' . $keterangan,
            'tipe' => 'kredit',
            'jumlah' => $jumlah,
            'kategori' => 'lemari',
            'pengeluaran_id' => $pengeluaran_id
        ]);
    }

    public function deleteByPengeluaranId($id)
    {
        // 1. Find all Kas entries to trigger EDC delete
        $this->db->query("SELECT id FROM " . $this->table . " WHERE pengeluaran_id = :id");
        $this->db->bind('id', $id);
        $rows = $this->db->resultSet();
        
        require_once 'EDC_model.php';
        $edc_model = new EDC_model();
        foreach ($rows as $row) {
            $edc_model->deleteByKasId($row['id']);
        }

        // 2. Delete Kas entries
        $this->db->query("DELETE FROM " . $this->table . " WHERE pengeluaran_id = :id");
        $this->db->bind('id', $id);
        $this->db->execute();
    }

    public function deleteByHarianId($id)
    {
        // 1. Find all Kas entries to trigger EDC delete
        $this->db->query("SELECT id FROM " . $this->table . " WHERE harian_id = :id");
        $this->db->bind('id', $id);
        $rows = $this->db->resultSet();
        
        require_once 'EDC_model.php';
        $edc_model = new EDC_model();
        foreach ($rows as $row) {
            $edc_model->deleteByKasId($row['id']);
        }

        // 2. Delete Kas entries
        $this->db->query("DELETE FROM " . $this->table . " WHERE harian_id = :id");
        $this->db->bind('id', $id);
        $this->db->execute();
    }

    public function syncFromHarian($harian_id, $tanggal, $keterangan, $jumlah_penjualan)
    {
        // 1. Delete existing entries for this harian_id to avoid duplication
        $this->deleteByHarianId($harian_id);

        // 2. Add Debit: Penjualan (Lemari & Arus)
        if ($jumlah_penjualan > 0) {
            $this->tambahDataKas([
                'tanggal' => $tanggal,
                'keterangan' => 'Penjualan ' . $keterangan,
                'tipe' => 'debit',
                'jumlah' => $jumlah_penjualan,
                'kategori' => 'lemari',
                'harian_id' => $harian_id
            ]);
            $this->tambahDataKas([
                'tanggal' => $tanggal,
                'keterangan' => 'Penjualan ' . $keterangan,
                'tipe' => 'debit',
                'jumlah' => $jumlah_penjualan,
                'kategori' => 'arus',
                'harian_id' => $harian_id
            ]);
        }
    }

    public function hapusDataKas($id)
    {
        // 1. Delete EDC record first
        require_once 'EDC_model.php';
        $edc_model = new EDC_model();

        // Check if it's a paired transaction
        $this->db->query("SELECT transaksi_id FROM " . $this->table . " WHERE id = :id");
        $this->db->bind('id', $id);
        $row = $this->db->single();

        if ($row && !empty($row['transaksi_id'])) {
            // Find all IDs with same transaksi_id for EDC cleanup
            $this->db->query("SELECT id FROM " . $this->table . " WHERE transaksi_id = :transaksi_id");
            $this->db->bind('transaksi_id', $row['transaksi_id']);
            $ids = $this->db->resultSet();
            foreach ($ids as $i) {
                $edc_model->deleteByKasId($i['id']);
            }

            // Delete all with the same transaksi_id
            $this->db->query("DELETE FROM " . $this->table . " WHERE transaksi_id = :transaksi_id");
            $this->db->bind('transaksi_id', $row['transaksi_id']);
        } else {
            // Delete only this EDC record
            $edc_model->deleteByKasId($id);

            // Delete only this ID
            $this->db->query('DELETE FROM ' . $this->table . ' WHERE id = :id');
            $this->db->bind('id', $id);
        }
        
        $this->db->execute();
        return $this->db->rowCount();
    }
}
