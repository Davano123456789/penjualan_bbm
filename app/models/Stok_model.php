<?php

class Stok_model {
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    // Get all stok records grouped by produk for a given month/year
    public function getStokBulan($bulan, $tahun)
    {
        $sql = "
            SELECT s.*, p.nama as nama_produk
            FROM stok s
            JOIN produk_bbm p ON s.produk_id = p.id
            WHERE YEAR(s.tanggal) = :tahun
        ";
        
        if ($bulan !== 'all') {
            $sql .= " AND MONTH(s.tanggal) = :bulan";
        }
        
        $sql .= " ORDER BY p.id ASC, s.tanggal ASC";

        $this->db->query($sql);
        $this->db->bind('tahun', $tahun);
        if ($bulan !== 'all') {
            $this->db->bind('bulan', $bulan);
        }
        
        return $this->db->resultSet();
    }

    public function getStokByTanggal($tanggal)
    {
        $this->db->query("
            SELECT s.*, p.nama as nama_produk
            FROM stok s
            JOIN produk_bbm p ON s.produk_id = p.id
            WHERE s.tanggal = :tanggal
        ");
        $this->db->bind('tanggal', $tanggal);
        return $this->db->resultSet();
    }

    public function getStokById($id)
    {
        $this->db->query("SELECT * FROM stok WHERE id = :id");
        $this->db->bind('id', $id);
        return $this->db->single();
    }

    // Get stok for a specific date and product
    public function getStokByTanggalProduk($tanggal, $produk_id)
    {
        $this->db->query("SELECT * FROM stok WHERE tanggal = :tanggal AND produk_id = :produk_id");
        $this->db->bind('tanggal', $tanggal);
        $this->db->bind('produk_id', $produk_id);
        return $this->db->single();
    }

    // Get total liter terjual from penjualan_harian for a given date and produk group
    // Pertamax = produk_id 1 & 2, Dex = produk_id 3 & 4
    public function getTerjualByTanggalProdukGroup($tanggal, $produk_ids)
    {
        $placeholders = implode(',', array_fill(0, count($produk_ids), '?'));
        $pdo = $this->db->getPDO();
        $stmt = $pdo->prepare("
            SELECT COALESCE(SUM(ph.liter_terjual), 0) as total_liter
            FROM penjualan_harian ph
            JOIN harian h ON ph.harian_id = h.id
            WHERE h.tanggal = ? AND ph.produk_id IN ($placeholders)
        ");
        $params = array_merge([$tanggal], $produk_ids);
        $stmt->execute($params);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total_liter'] ?? 0;
    }

    // Get last stok akhir fisik for a product before a given date (for auto stok_awal)
    public function getStokAwalFromPrevious($tanggal, $produk_id)
    {
        $this->db->query("
            SELECT stok_akhir_fisik FROM stok 
            WHERE produk_id = :produk_id AND tanggal < :tanggal
            ORDER BY tanggal DESC LIMIT 1
        ");
        $this->db->bind('produk_id', $produk_id);
        $this->db->bind('tanggal', $tanggal);
        $row = $this->db->single();
        return $row ? $row['stok_akhir_fisik'] : 0;
    }

    // Simpan atau update stok entry
    public function simpanStok($data)
    {
        // Check if record already exists
        $existing = $this->getStokByTanggalProduk($data['tanggal'], $data['produk_id']);

        if ($existing) {
            $this->db->query("
                UPDATE stok SET
                    stok_awal        = :stok_awal,
                    kiriman_masuk    = :kiriman_masuk,
                    total_tersedia   = :total_tersedia,
                    terjual          = :terjual,
                    stok_akhir_teori = :stok_akhir_teori,
                    stok_akhir_fisik = :stok_akhir_fisik,
                    selisih          = :selisih,
                    catatan          = :catatan,
                    jadwal           = :jadwal,
                    nama_supir       = :nama_supir
                WHERE tanggal = :tanggal AND produk_id = :produk_id
            ");
        } else {
            $this->db->query("
                INSERT INTO stok (tanggal, produk_id, stok_awal, kiriman_masuk, total_tersedia, terjual, stok_akhir_teori, stok_akhir_fisik, selisih, catatan, jadwal, nama_supir)
                VALUES (:tanggal, :produk_id, :stok_awal, :kiriman_masuk, :total_tersedia, :terjual, :stok_akhir_teori, :stok_akhir_fisik, :selisih, :catatan, :jadwal, :nama_supir)
            ");
        }

        $this->db->bind('tanggal',          $data['tanggal']);
        $this->db->bind('produk_id',        $data['produk_id']);
        $this->db->bind('stok_awal',        $data['stok_awal']);
        $this->db->bind('kiriman_masuk',    $data['kiriman_masuk']);
        $this->db->bind('total_tersedia',   $data['total_tersedia']);
        $this->db->bind('terjual',          $data['terjual']);
        $this->db->bind('stok_akhir_teori', $data['stok_akhir_teori']);
        $this->db->bind('stok_akhir_fisik', $data['stok_akhir_fisik']);
        $this->db->bind('selisih',          $data['selisih']);
        $this->db->bind('catatan',          $data['catatan'] ?? null);
        $this->db->bind('jadwal',           $data['jadwal'] ?? null);
        $this->db->bind('nama_supir',       $data['nama_supir'] ?? null);
        
        try {
            $this->db->execute();
            return true; // Always return true if no exception
        } catch (Exception $e) {
            return false;
        }
    }

    public function hapusStok($id)
    {
        $this->db->query("DELETE FROM stok WHERE id = :id");
        $this->db->bind('id', $id);
        $this->db->execute();
        return $this->db->rowCount();
    }
}
