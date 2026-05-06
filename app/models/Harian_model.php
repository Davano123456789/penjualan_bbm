<?php

require_once 'Kas_model.php';
require_once 'Stok_model.php';

class Harian_model {
    private $table = 'harian';
    private $db;
    private $kasModel;
    private $stokModel;

    public function __construct()
    {
        $this->db = new Database;
        $this->kasModel = new Kas_model();
        $this->stokModel = new Stok_model();
    }

    public function getAllHarian()
    {
        $this->db->query('SELECT * FROM ' . $this->table . ' ORDER BY tanggal DESC');
        return $this->db->resultSet();
    }

    public function getLatestReport()
    {
        $this->db->query('SELECT * FROM ' . $this->table . ' ORDER BY tanggal DESC LIMIT 1');
        return $this->db->single();
    }

    public function hapusDataHarian($id)
    {
        // 1. Hapus detail penjualan_harian dulu (FK restriction)
        $this->db->query('DELETE FROM penjualan_harian WHERE harian_id = :harian_id');
        $this->db->bind('harian_id', $id);
        $this->db->execute();

        // 2. Hapus dari Kas (Buku Besar)
        $this->kasModel->deleteByHarianId($id);

        // 3. Baru hapus header harian
        $this->db->query('DELETE FROM harian WHERE id = :id');
        $this->db->bind('id', $id);
        
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function getHarianById($id)
    {
        $this->db->query('SELECT h.*, o1.nama as nama_op1, o2.nama as nama_op2 
                          FROM ' . $this->table . ' h
                          LEFT JOIN operators o1 ON h.operator1_id = o1.id
                          LEFT JOIN operators o2 ON h.operator2_id = o2.id
                          WHERE h.id = :id');
        $this->db->bind('id', $id);
        return $this->db->single();
    }

    public function getPenjualanByHarianId($harian_id)
    {
        $this->db->query('SELECT p.*, b.nama as nama_produk, b.harga_jual 
                          FROM penjualan_harian p
                          LEFT JOIN produk_bbm b ON p.produk_id = b.id
                          WHERE p.harian_id = :harian_id
                          ORDER BY p.nozzle ASC');
        $this->db->bind('harian_id', $harian_id);
        return $this->db->resultSet();
    }

    public function tambahDataHarian($data)
    {
        // 1. Insert into 'harian' (Header)
        $query = "INSERT INTO harian (tanggal, jam_masuk, jam_keluar, operator1_id, operator2_id, total_penerimaan_kas, total_pengeluaran, total_titipan, total_sisa) 
                  VALUES (:tanggal, :jam_masuk, :jam_keluar, :operator1_id, :operator2_id, :total_penerimaan_kas, :total_pengeluaran, :total_titipan, :total_sisa)";
        
        $this->db->query($query);
        $this->db->bind('tanggal',              $data['tanggal']);
        $this->db->bind('jam_masuk',            !empty($data['jam_masuk']) ? $data['jam_masuk'] : null);
        $this->db->bind('jam_keluar',           !empty($data['jam_keluar']) ? $data['jam_keluar'] : null);
        $this->db->bind('operator1_id',         !empty($data['operator1_id']) ? $data['operator1_id'] : null);
        $this->db->bind('operator2_id',         !empty($data['operator2_id']) ? $data['operator2_id'] : null);
        $this->db->bind('total_penerimaan_kas', $data['total_penerimaan_kas']);
        $this->db->bind('total_pengeluaran',    $data['total_pengeluaran']);
        $this->db->bind('total_titipan',        $data['total_titipan']);
        $this->db->bind('total_sisa',           $data['total_sisa'] ?? 0);
        
        $this->db->execute();
        $harian_id = $this->db->rowCount() > 0 ? $this->getHarianIdByDate($data['tanggal']) : 0;

        if ($harian_id > 0) {
            // 2. Insert into 'penjualan_harian' for each nozzle
            foreach ($data['penjualan'] as $p) {
                // SKIP if no product selected for this nozzle
                if (empty($p['produk_id'])) continue;

                $query_p = "INSERT INTO penjualan_harian (harian_id, produk_id, nozzle, totalisator_awal, totalisator_akhir, liter_terjual, total_rupiah) 
                            VALUES (:harian_id, :produk_id, :nozzle, :awal, :akhir, :liter_terjual, :total_rupiah)";
                $this->db->query($query_p);
                $this->db->bind('harian_id', $harian_id);
                $this->db->bind('produk_id', $p['produk_id']);
                $this->db->bind('nozzle', $p['nozzle']);
                $this->db->bind('awal', $p['awal'] ?? 0);
                $this->db->bind('akhir', $p['akhir'] ?? 0);
                $this->db->bind('liter_terjual', $p['liter_terjual']);
                $this->db->bind('total_rupiah', $p['total_rupiah']);
                $this->db->execute();
            }
            
            // 3. Sync to Kas (Buku Besar)
            // Hitung total penjualan murni dari mesin (Nozzle)
            $total_penjualan = 0;
            foreach ($data['penjualan'] as $p) {
                if (!empty($p['produk_id'])) {
                    $total_penjualan += floatval($p['total_rupiah'] ?? 0);
                }
            }

            $this->kasModel->syncFromHarian(
                $harian_id, 
                $data['tanggal'], 
                "(Penjualan Harian)", 
                $total_penjualan
            );

            // 4. Sync to Stok (Stock Dashboard) - Pertamax & Dex
            $this->syncStokFisik($data['tanggal'], $data);

            return true;
        }

        return false;
    }

    public function getLatestTotalizers()
    {
        // Get latest end totalizer for each nozzle
        $this->db->query("SELECT p1.nozzle, p1.totalisator_akhir, p1.produk_id, b.harga_jual
                          FROM penjualan_harian p1
                          INNER JOIN (
                              SELECT nozzle, MAX(id) as max_id 
                              FROM penjualan_harian 
                              GROUP BY nozzle
                          ) p2 ON p1.id = p2.max_id
                          LEFT JOIN produk_bbm b ON p1.produk_id = b.id");
        return $this->db->resultSet();
    }

    private function syncStokFisik($tanggal, $data)
    {
        $products = [
            'pertamax' => ['id' => 1, 'group' => [1, 2]],
            'dex'      => ['id' => 3, 'group' => [3, 4]]
        ];

        foreach ($products as $key => $info) {
            $fisikInput = $data['stok_fisik_' . $key] ?? null;
            
            // Only sync if user provided a value
            if ($fisikInput !== null && $fisikInput !== '') {
                $produk_id = $info['id'];
                $fisik = floatval($fisikInput);

                // Get existing data to preserve Kiriman and calculate Teori
                $existing = $this->stokModel->getStokByTanggalProduk($tanggal, $produk_id);
                
                $stok_awal     = $existing ? floatval($existing['stok_awal']) : $this->stokModel->getStokAwalFromPrevious($tanggal, $produk_id);
                $kiriman_masuk = $existing ? floatval($existing['kiriman_masuk']) : 0;
                $terjual       = $this->stokModel->getTerjualByTanggalProdukGroup($tanggal, $info['group']);
                
                $total_tersedia   = $stok_awal + $kiriman_masuk;
                $stok_akhir_teori = $total_tersedia - $terjual;
                $selisih          = $fisik - $stok_akhir_teori;

                $stokData = [
                    'tanggal'          => $tanggal,
                    'produk_id'        => $produk_id,
                    'stok_awal'        => $stok_awal,
                    'kiriman_masuk'    => $kiriman_masuk,
                    'total_tersedia'   => $total_tersedia,
                    'terjual'          => $terjual,
                    'stok_akhir_teori' => $stok_akhir_teori,
                    'stok_akhir_fisik' => $fisik,
                    'selisih'          => $selisih,
                    'catatan'          => $existing['catatan'] ?? 'Auto-sync dari Laporan Harian',
                    'jadwal'           => $existing['jadwal'] ?? null,
                    'nama_supir'       => $existing['nama_supir'] ?? null
                ];

                $this->stokModel->simpanStok($stokData);
            }
        }
    }

    private function getHarianIdByDate($tanggal)
    {
        $this->db->query('SELECT id FROM harian WHERE tanggal = :tanggal');
        $this->db->bind('tanggal', $tanggal);
        $res = $this->db->single();
        return $res ? $res['id'] : 0;
    }
}
