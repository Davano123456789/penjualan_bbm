<?php

class Losis_model {
    private $table = 'stok';
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function getLosisBulanan($bulan, $tahun)
    {
        // Get all products that are main groups (Pertamax 1, Dex 1 etc)
        // Usually, we just use produk_id 1 for Pertamax and 3 for Dex based on Stok controller
        $products = [
            ['id' => 1, 'nama' => 'PERTAMAX'],
            ['id' => 3, 'nama' => 'DEX']
        ];

        $results = [];

        foreach ($products as $p) {
            $pid = $p['id'];
            
            // 1. Stok Awal (First entry of the month)
            $this->db->query("SELECT stok_awal FROM " . $this->table . " 
                              WHERE produk_id = :pid AND MONTH(tanggal) = :bulan AND YEAR(tanggal) = :tahun 
                              ORDER BY tanggal ASC LIMIT 1");
            $this->db->bind('pid', $pid);
            $this->db->bind('bulan', $bulan);
            $this->db->bind('tahun', $tahun);
            $start = $this->db->single();
            $stok_awal = $start['stok_awal'] ?? 0;

            // 2. DO Penebusan & Total Penjualan (Sum for the month)
            $this->db->query("SELECT SUM(kiriman_masuk) as total_do, SUM(terjual) as total_jual 
                              FROM " . $this->table . " 
                              WHERE produk_id = :pid AND MONTH(tanggal) = :bulan AND YEAR(tanggal) = :tahun");
            $this->db->bind('pid', $pid);
            $this->db->bind('bulan', $bulan);
            $this->db->bind('tahun', $tahun);
            $sums = $this->db->single();
            $do_penebusan = $sums['total_do'] ?? 0;
            $total_penjualan = $sums['total_jual'] ?? 0;

            // 3. Stok Akhir Fisik (Last entry of the month)
            $this->db->query("SELECT stok_akhir_fisik FROM " . $this->table . " 
                              WHERE produk_id = :pid AND MONTH(tanggal) = :bulan AND YEAR(tanggal) = :tahun 
                              ORDER BY tanggal DESC LIMIT 1");
            $this->db->bind('pid', $pid);
            $this->db->bind('bulan', $bulan);
            $this->db->bind('tahun', $tahun);
            $end = $this->db->single();
            $stok_akhir = $end['stok_akhir_fisik'] ?? 0;

            // 4. Calculations
            $jumlah_stok = $stok_awal + $do_penebusan;
            $penjualan_plus_akhir = $total_penjualan + $stok_akhir;
            $losis = $jumlah_stok - $penjualan_plus_akhir;
            
            // Percentage = (Losis / Total Penjualan) * 100
            $persentase = ($total_penjualan > 0) ? ($losis / $total_penjualan) * 100 : 0;

            $results[] = [
                'nama_produk' => $p['nama'],
                'stok_awal' => $stok_awal,
                'do_penebusan' => $do_penebusan,
                'jumlah_stok' => $jumlah_stok,
                'total_penjualan' => $total_penjualan,
                'stok_akhir' => $stok_akhir,
                'penjualan_plus_akhir' => $penjualan_plus_akhir,
                'losis' => $losis,
                'persentase' => $persentase
            ];
        }

        return $results;
    }
}
