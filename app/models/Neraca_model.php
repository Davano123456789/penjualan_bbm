<?php

class Neraca_model {
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function getNeracaBulanan($bulan, $tahun)
    {
        // 1. Cek data tersimpan di tabel neraca
        $this->db->query("SELECT * FROM neraca WHERE MONTH(periode) = :bulan AND YEAR(periode) = :tahun");
        $this->db->bind('bulan', (int)$bulan);
        $this->db->bind('tahun', (int)$tahun);
        $saved = $this->db->single();

        // 2. Ambil KAS TERAKHIR (Kas Lemari)
        $this->db->query("SELECT uang_lembar, uang_koin FROM kas_saldo 
                          WHERE jenis_kas = 'kas_lemari' 
                          AND MONTH(tanggal) = :bulan AND YEAR(tanggal) = :tahun 
                          ORDER BY tanggal DESC, id DESC LIMIT 1");
        $this->db->bind('bulan', (int)$bulan);
        $this->db->bind('tahun', (int)$tahun);
        $kas = $this->db->single();
        
        $kas_spbu = $kas['uang_lembar'] ?? 0;
        $koin = $kas['uang_koin'] ?? 0;

        // 3. Ambil STOK TERAKHIR (Volume Liter)
        // Pertamax (ID 1)
        $this->db->query("SELECT s.stok_akhir_fisik, (p.harga_jual - 600) as hpp 
                          FROM stok s JOIN produk_bbm p ON s.produk_id = p.id 
                          WHERE s.produk_id = 1 AND MONTH(s.tanggal) = :bulan AND YEAR(s.tanggal) = :tahun 
                          ORDER BY s.tanggal DESC LIMIT 1");
        $this->db->bind('bulan', (int)$bulan);
        $this->db->bind('tahun', (int)$tahun);
        $px = $this->db->single();
        $stok_px_liter = $px['stok_akhir_fisik'] ?? 0;
        $hpp_px = $px['hpp'] ?? 0;

        // Dex (ID 3)
        $this->db->query("SELECT s.stok_akhir_fisik, (p.harga_jual - 600) as hpp 
                          FROM stok s JOIN produk_bbm p ON s.produk_id = p.id 
                          WHERE s.produk_id = 3 AND MONTH(s.tanggal) = :bulan AND YEAR(s.tanggal) = :tahun 
                          ORDER BY s.tanggal DESC LIMIT 1");
        $this->db->bind('bulan', (int)$bulan);
        $this->db->bind('tahun', (int)$tahun);
        $dx = $this->db->single();
        $stok_dx_liter = $dx['stok_akhir_fisik'] ?? 0;
        $hpp_dx = $dx['hpp'] ?? 0;

        // 4. Ambil Laba Bersih dari TOT Akhir (Jika sudah dikunci)
        $this->db->query("SELECT laba_bersih FROM laporan_bulanan WHERE MONTH(periode) = :bulan AND YEAR(periode) = :tahun");
        $this->db->bind('bulan', (int)$bulan);
        $this->db->bind('tahun', (int)$tahun);
        $lb = $this->db->single();
        $laba_bersih = $lb['laba_bersih'] ?? 0;

        // 5. Gabungkan menjadi satu object laporan
        return [
            'id' => $saved['id'] ?? null,
            'is_saved' => $saved ? true : false,
            // AKTIVA - Kas (Auto)
            'kas_spbu' => $kas_spbu,
            'koin' => $koin,
            // AKTIVA - Kas (Manual/Saved)
            'tabanas_bank' => $saved['tabanas_bank'] ?? 0,
            'inventaris' => $saved['inventaris'] ?? 0,
            // AKTIVA - Stok (Auto)
            'stok_pertamax_liter' => $stok_px_liter,
            'stok_pertamax_nilai' => $stok_px_liter * $hpp_px,
            'stok_dex_liter' => $stok_dx_liter,
            'stok_dex_nilai' => $stok_dx_liter * $hpp_dx,
            // AKTIVA - DO (Manual/Saved)
            'do_pertamax_liter' => $saved['do_pertamax_liter'] ?? 0,
            'do_pertamax_nilai' => $saved['do_pertamax_nilai'] ?? 0,
            'do_dex_liter' => $saved['do_dex_liter'] ?? 0,
            'do_dex_nilai' => $saved['do_dex_nilai'] ?? 0,
            // PASIVA - Utang (Manual/Saved)
            'utang_jangka_pendek' => $saved['utang_jangka_pendek'] ?? 0,
            'utang_jangka_panjang' => $saved['utang_jangka_panjang'] ?? 0,
            // PASIVA - Modal (Manual/Saved)
            'modal_oli' => $saved['modal_oli'] ?? 0,
            'modal_gas' => $saved['modal_gas'] ?? 0,
            'catatan_modal_1' => $saved['catatan_modal_1'] ?? 0,
            'catatan_modal_2' => $saved['catatan_modal_2'] ?? 0,
            // PASIVA - Laba (Auto dari TOT Akhir)
            'laba_berjalan' => $laba_bersih
        ];
    }

    public function simpanNeraca($data)
    {
        $periode = $data['tahun'] . "-" . $data['bulan'] . "-01";
        
        // Cek apakah sudah ada
        $this->db->query("SELECT id FROM neraca WHERE MONTH(periode) = :bulan AND YEAR(periode) = :tahun");
        $this->db->bind('bulan', (int)$data['bulan']);
        $this->db->bind('tahun', (int)$data['tahun']);
        $exist = $this->db->single();

        if ($exist) {
            $this->db->query("UPDATE neraca SET 
                                kas_spbu = :kas_spbu,
                                koin = :koin,
                                tabanas_bank = :tabanas_bank,
                                inventaris = :inventaris,
                                stok_pertamax_liter = :stok_px_l,
                                stok_pertamax_nilai = :stok_px_n,
                                stok_dex_liter = :stok_dx_l,
                                stok_dex_nilai = :stok_dx_n,
                                do_pertamax_liter = :do_px_l,
                                do_pertamax_nilai = :do_px_n,
                                do_dex_liter = :do_dx_l,
                                do_dex_nilai = :do_dx_n,
                                utang_jangka_pendek = :utang_pndk,
                                utang_jangka_panjang = :utang_pjng,
                                modal_oli = :modal_oli,
                                modal_gas = :modal_gas,
                                catatan_modal_1 = :catatan_1,
                                catatan_modal_2 = :catatan_2,
                                total_arus_kas = :tot_arus,
                                total_stok_nilai = :tot_stok,
                                total_modal = :tot_modal,
                                jumlah_harta_lancar = :harta_lancar
                              WHERE id = :id");
            $this->db->bind('id', $exist['id']);
        } else {
            $this->db->query("INSERT INTO neraca 
                                (periode, kas_spbu, koin, tabanas_bank, inventaris, stok_pertamax_liter, stok_pertamax_nilai, stok_dex_liter, stok_dex_nilai, do_pertamax_liter, do_pertamax_nilai, do_dex_liter, do_dex_nilai, utang_jangka_pendek, utang_jangka_panjang, modal_oli, modal_gas, catatan_modal_1, catatan_modal_2, total_arus_kas, total_stok_nilai, total_modal, jumlah_harta_lancar)
                              VALUES 
                                (:periode, :kas_spbu, :koin, :tabanas_bank, :inventaris, :stok_px_l, :stok_px_n, :stok_dx_l, :stok_dx_n, :do_px_l, :do_px_n, :do_dx_l, :do_dx_n, :utang_pndk, :utang_pjng, :modal_oli, :modal_gas, :catatan_1, :catatan_2, :tot_arus, :tot_stok, :tot_modal, :harta_lancar)");
            $this->db->bind('periode', $periode);
        }

        // Hitung total untuk disimpan (Sesuai rumus Excel: J13 + J17 + J23 + J28 + J29)
        $tot_arus = $data['kas_spbu'] + $data['koin'] + $data['tabanas_bank'] + $data['inventaris'];
        $tot_stok = $data['stok_px_n'] + $data['stok_dx_n'];
        $tot_modal = ($data['modal_oli'] ?? 0) + ($data['modal_gas'] ?? 0) + ($data['catatan_1'] ?? 0) + ($data['catatan_2'] ?? 0);
        $harta_lancar = $tot_arus + $tot_stok + ($data['do_px_n'] ?? 0) + ($data['do_dx_n'] ?? 0) + ($data['modal_oli'] ?? 0) + ($data['modal_gas'] ?? 0);

        $this->db->bind('kas_spbu', $data['kas_spbu']);
        $this->db->bind('koin', $data['koin']);
        $this->db->bind('tabanas_bank', $data['tabanas_bank']);
        $this->db->bind('inventaris', $data['inventaris']);
        $this->db->bind('stok_px_l', $data['stok_px_l']);
        $this->db->bind('stok_px_n', $data['stok_px_n']);
        $this->db->bind('stok_dx_l', $data['stok_dx_l']);
        $this->db->bind('stok_dx_n', $data['stok_dx_n']);
        $this->db->bind('do_px_l', $data['do_px_l'] ?? 0);
        $this->db->bind('do_px_n', $data['do_px_n'] ?? 0);
        $this->db->bind('do_dx_l', $data['do_dx_l'] ?? 0);
        $this->db->bind('do_dx_n', $data['do_dx_n'] ?? 0);
        $this->db->bind('utang_pndk', $data['utang_pndk'] ?? 0);
        $this->db->bind('utang_pjng', $data['utang_pjng'] ?? 0);
        $this->db->bind('modal_oli', $data['modal_oli'] ?? 0);
        $this->db->bind('modal_gas', $data['modal_gas'] ?? 0);
        $this->db->bind('catatan_1', $data['catatan_1'] ?? 0);
        $this->db->bind('catatan_2', $data['catatan_2'] ?? 0);
        $this->db->bind('tot_arus', $tot_arus);
        $this->db->bind('tot_stok', $tot_stok);
        $this->db->bind('tot_modal', $tot_modal);
        $this->db->bind('harta_lancar', $harta_lancar);

        return $this->db->execute();
    }
}
