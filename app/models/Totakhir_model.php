<?php

class Totakhir_model {
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function getLaporanBulanan($bulan, $tahun)
    {
        $periode = "$tahun-$bulan-01";
        
        // Cek data yang sudah tersimpan
        $this->db->query("SELECT * FROM laporan_bulanan WHERE MONTH(periode) = :bulan AND YEAR(periode) = :tahun");
        $this->db->bind('bulan', (int)$bulan);
        $this->db->bind('tahun', (int)$tahun);
        $saved_data = $this->db->single();

        // 1. Dapatkan Totalisator (Volume Terjual)
        $terjual_pertamax = 0;
        $terjual_dex = 0;
        
        // Pertamax (Nozzle 1 & 2)
        $this->db->query("SELECT SUM(totalisator_akhir - totalisator_awal) as terjual FROM penjualan_harian ph JOIN harian h ON ph.harian_id = h.id WHERE ph.produk_id IN (1,2) AND MONTH(h.tanggal) = :bulan AND YEAR(h.tanggal) = :tahun AND totalisator_akhir > 0");
        $this->db->bind('bulan', (int)$bulan);
        $this->db->bind('tahun', (int)$tahun);
        $res = $this->db->single();
        if($res) $terjual_pertamax = $res['terjual'];
        
        // Dex (Nozzle 3 & 4)
        $this->db->query("SELECT SUM(totalisator_akhir - totalisator_awal) as terjual FROM penjualan_harian ph JOIN harian h ON ph.harian_id = h.id WHERE ph.produk_id IN (3,4) AND MONTH(h.tanggal) = :bulan AND YEAR(h.tanggal) = :tahun AND totalisator_akhir > 0");
        $this->db->bind('bulan', (int)$bulan);
        $this->db->bind('tahun', (int)$tahun);
        $res = $this->db->single();
        if($res) $terjual_dex = $res['terjual'];

        // Laba Kotor (Margin 600 per liter)
        $margin = 600;
        $laba_pertamax = $terjual_pertamax * $margin;
        $laba_dex = $terjual_dex * $margin;
        $total_laba_kotor = $laba_pertamax + $laba_dex;

        // 2. Dapatkan Beban Gaji
        $this->db->query("SELECT SUM(total_diterima) as total_gaji FROM gaji WHERE MONTH(periode) = :bulan AND YEAR(periode) = :tahun");
        $this->db->bind('bulan', (int)$bulan);
        $this->db->bind('tahun', (int)$tahun);
        $gaji = $this->db->single();
        $biaya_gaji = $gaji['total_gaji'] ?? 0;

        // 3. Dapatkan Beban Losis (Dihitung Real-Time dari Tabel Stok)
        $total_losis_rupiah = 0;
        $detail_losis = [];
        $products_losis = [
            ['id' => 1, 'nama' => 'PERTAMAX'], // ID 1 = Pertamax (HPP = Harga - 600)
            ['id' => 3, 'nama' => 'DEX']      // ID 3 = Dex
        ];

        foreach ($products_losis as $p) {
            $pid = $p['id'];
            
            // Stok Awal & Harga HPP
            $this->db->query("SELECT s.stok_awal, (p.harga_jual - 600) as hpp 
                              FROM stok s
                              JOIN produk_bbm p ON s.produk_id = p.id
                              WHERE s.produk_id = :pid AND MONTH(s.tanggal) = :bulan AND YEAR(s.tanggal) = :tahun 
                              ORDER BY s.tanggal ASC LIMIT 1");
            $this->db->bind('pid', $pid);
            $this->db->bind('bulan', (int)$bulan);
            $this->db->bind('tahun', (int)$tahun);
            $start_data = $this->db->single();
            
            $stok_awal = $start_data['stok_awal'] ?? 0;
            $hpp = $start_data['hpp'] ?? 0;

            // DO & Total Penjualan
            $this->db->query("SELECT SUM(kiriman_masuk) as total_do, SUM(terjual) as total_jual 
                              FROM stok 
                              WHERE produk_id = :pid AND MONTH(tanggal) = :bulan AND YEAR(tanggal) = :tahun");
            $this->db->bind('pid', $pid);
            $this->db->bind('bulan', (int)$bulan);
            $this->db->bind('tahun', (int)$tahun);
            $sums = $this->db->single();
            $do_penebusan = $sums['total_do'] ?? 0;
            $total_penjualan = $sums['total_jual'] ?? 0;

            // Stok Akhir Fisik
            $this->db->query("SELECT stok_akhir_fisik FROM stok 
                              WHERE produk_id = :pid AND MONTH(tanggal) = :bulan AND YEAR(tanggal) = :tahun 
                              ORDER BY tanggal DESC LIMIT 1");
            $this->db->bind('pid', $pid);
            $this->db->bind('bulan', (int)$bulan);
            $this->db->bind('tahun', (int)$tahun);
            $end_data = $this->db->single();
            $stok_akhir = $end_data['stok_akhir_fisik'] ?? 0;

            // Hitung Losis
            $volume_losis = ($stok_awal + $do_penebusan) - ($total_penjualan + $stok_akhir);
            $rupiah_losis = ($volume_losis > 0) ? ($volume_losis * $hpp) : 0;
            
            $total_losis_rupiah += $rupiah_losis;
            $detail_losis[] = [
                'produk' => $p['nama'],
                'volume' => $volume_losis,
                'total_rp' => $rupiah_losis
            ];
        }
        
        // 4. Pengeluaran Operasional (Kas)
        $this->db->query("SELECT SUM(nominal) as total_kas FROM pengeluaran WHERE MONTH(tanggal) = :bulan AND YEAR(tanggal) = :tahun");
        $this->db->bind('bulan', (int)$bulan);
        $this->db->bind('tahun', (int)$tahun);
        $kas = $this->db->single();
        $biaya_kas = $kas['total_kas'] ?? 0;

        // 5. Build dynamic report array
        $report = [
            'terjual_pertamax' => $terjual_pertamax,
            'terjual_dex' => $terjual_dex,
            'laba_pertamax' => $laba_pertamax,
            'laba_dex' => $laba_dex,
            'total_laba_kotor' => $total_laba_kotor,
            'biaya_gaji' => $biaya_gaji,
            'biaya_kas' => $biaya_kas,
            'biaya_pph' => $saved_data ? $saved_data['biaya_pph'] : 0,
            'biaya_admin_edc' => $saved_data ? $saved_data['biaya_admin_edc'] : 0,
            'total_losis_rupiah' => $total_losis_rupiah,
            'detail_losis' => $detail_losis,
            'is_saved' => $saved_data ? true : false,
            'id' => $saved_data ? $saved_data['id'] : null
        ];

        // Laba Bersih
        $report['total_pengeluaran'] = $report['biaya_gaji'] + $report['biaya_kas'] + $report['biaya_pph'] + $report['biaya_admin_edc'] + $report['total_losis_rupiah'];
        $report['laba_bersih'] = $report['total_laba_kotor'] - $report['total_pengeluaran'];

        return $report;
    }

    public function simpanLaporan($data)
    {
        $periode = $data['tahun'] . "-" . $data['bulan'] . "-01";
        
        // Prepare the calculated values to ensure the DB record is accurate
        $report = $this->getLaporanBulanan($data['bulan'], $data['tahun']);
        
        if ($report['is_saved']) {
            $this->db->query("UPDATE laporan_bulanan SET 
                                total_pendapatan_kotor = :total_kotor,
                                laba_pertamax = :laba_px,
                                laba_dex = :laba_dex,
                                total_laba_kotor = :laba_kotor,
                                biaya_gaji = :gaji,
                                biaya_kas = :kas,
                                biaya_pph = :pph,
                                biaya_admin_edc = :admin_edc,
                                total_pengeluaran = :pengeluaran,
                                total_losis_rupiah = :losis,
                                laba_bersih = :laba_bersih
                              WHERE id = :id");
            $this->db->bind('id', $report['id']);
        } else {
            $this->db->query("INSERT INTO laporan_bulanan 
                                (periode, total_pendapatan_kotor, laba_pertamax, laba_dex, total_laba_kotor, biaya_gaji, biaya_kas, biaya_pph, biaya_admin_edc, total_pengeluaran, total_losis_rupiah, laba_bersih) 
                              VALUES 
                                (:periode, :total_kotor, :laba_px, :laba_dex, :laba_kotor, :gaji, :kas, :pph, :admin_edc, :pengeluaran, :losis, :laba_bersih)");
            $this->db->bind('periode', $periode);
        }

        $pph = $data['biaya_pph'] ?? 0;
        $admin_edc = $data['biaya_admin_edc'] ?? 0;
        $pengeluaran = $report['biaya_gaji'] + $report['biaya_kas'] + $pph + $admin_edc + $report['total_losis_rupiah'];
        $laba_bersih = $report['total_laba_kotor'] - $pengeluaran;

        $this->db->bind('total_kotor', 0); // Can be populated if needed
        $this->db->bind('laba_px', $report['laba_pertamax']);
        $this->db->bind('laba_dex', $report['laba_dex']);
        $this->db->bind('laba_kotor', $report['total_laba_kotor']);
        $this->db->bind('gaji', $report['biaya_gaji']);
        $this->db->bind('kas', $report['biaya_kas']);
        $this->db->bind('pph', $pph);
        $this->db->bind('admin_edc', $admin_edc);
        $this->db->bind('pengeluaran', $pengeluaran);
        $this->db->bind('losis', $report['total_losis_rupiah']);
        $this->db->bind('laba_bersih', $laba_bersih);

        return $this->db->execute();
    }
}
