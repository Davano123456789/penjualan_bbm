<?php

class Stok extends Controller {
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function index()
    {
        date_default_timezone_set('Asia/Jakarta');
        $bulan = $_GET['bulan'] ?? 'all';
        $tahun = $_GET['tahun'] ?? date('Y');

        $data['judul']  = 'Laporan Stok BBM';
        $data['view']   = 'stok/index';
        $data['bulan']  = $bulan;
        $data['tahun']  = $tahun;

        $rawStok = $this->model('Stok_model')->getStokBulan($bulan, $tahun);

        // Group by product name type (Pertamax & Dex)
        $data['stok_pertamax'] = [];
        $data['stok_dex']      = [];
        foreach ($rawStok as $row) {
            if (stripos($row['nama_produk'], 'pertamax') !== false) {
                $data['stok_pertamax'][] = $row;
            } elseif (stripos($row['nama_produk'], 'dex') !== false) {
                $data['stok_dex'][] = $row;
            }
        }

        $this->view('templates/layout', $data);
    }

    public function input()
    {
        date_default_timezone_set('Asia/Jakarta');
        $data['judul']  = 'Input Stok BBM';
        $data['view']   = 'stok/input';
        $data['tanggal'] = $_GET['tanggal'] ?? date('Y-m-d');

        // Get stok awal automatically for each product group
        // Pertamax group: produk_id 1, Dex group: produk_id 3
        $model = $this->model('Stok_model');
        $data['stok_awal_pertamax'] = $model->getStokAwalFromPrevious($data['tanggal'], 1);
        $data['stok_awal_dex']      = $model->getStokAwalFromPrevious($data['tanggal'], 3);

        // Auto-fetch terjual from penjualan_harian
        $data['terjual_pertamax'] = $model->getTerjualByTanggalProdukGroup($data['tanggal'], [1, 2]);
        $data['terjual_dex']      = $model->getTerjualByTanggalProdukGroup($data['tanggal'], [3, 4]);

        $this->view('templates/layout', $data);
    }

    public function simpan()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $model = $this->model('Stok_model');
            $tanggal = $_POST['tanggal'];

            // Process both Pertamax (produk_id=1) and Dex (produk_id=3)
            $groups = [
                ['produk_id' => 1, 'prefix' => 'pertamax'],
                ['produk_id' => 3, 'prefix' => 'dex'],
            ];

            $success = true;
            foreach ($groups as $g) {
                $prefix    = $g['prefix'];
                $produk_id = $g['produk_id'];

                $stok_awal        = floatval($_POST["{$prefix}_stok_awal"] ?? 0);
                $kiriman_masuk    = floatval($_POST["{$prefix}_kiriman"] ?? 0);
                $terjual          = floatval($_POST["{$prefix}_terjual"] ?? 0);
                $stok_akhir_fisik = floatval($_POST["{$prefix}_stok_fisik"] ?? 0);

                $total_tersedia   = $stok_awal + $kiriman_masuk;
                $stok_akhir_teori = $total_tersedia - $terjual;
                $selisih          = $stok_akhir_fisik - $stok_akhir_teori;

                $entry = [
                    'tanggal'          => $tanggal,
                    'produk_id'        => $produk_id,
                    'stok_awal'        => $stok_awal,
                    'kiriman_masuk'    => $kiriman_masuk,
                    'total_tersedia'   => $total_tersedia,
                    'terjual'          => $terjual,
                    'stok_akhir_teori' => $stok_akhir_teori,
                    'stok_akhir_fisik' => $stok_akhir_fisik,
                    'selisih'          => $selisih,
                    'catatan'          => $_POST["{$prefix}_catatan"] ?? null,
                    'jadwal'           => $_POST["{$prefix}_jadwal"] ?? null,
                    'nama_supir'       => $_POST["{$prefix}_nama_supir"] ?? null,
                ];

                if (!$model->simpanStok($entry)) {
                    $success = false;
                }
            }

            if ($success) {
                Flasher::setFlash('Stok', 'Disimpan', 'success');
            } else {
                Flasher::setFlash('Stok', 'Gagal Disimpan', 'error');
            }
            header('Location: ' . BASEURL . '/stok');
            exit;
        }
    }

    public function detail($id)
    {
        $model = $this->model('Stok_model');
        $thisStok = $model->getStokById($id);
        
        if (!$thisStok) {
            header('Location: ' . BASEURL . '/stok');
            exit;
        }

        $tanggal = $thisStok['tanggal'];
        $allStok = $model->getStokByTanggal($tanggal);

        $data['judul']  = 'Detail Stok BBM';
        $data['view']   = 'stok/detail';
        $data['tanggal'] = $tanggal;
        
        // Group by product name type (Pertamax & Dex)
        $data['stok_p'] = null;
        $data['stok_d'] = null;
        foreach ($allStok as $row) {
            if (stripos($row['nama_produk'], 'pertamax') !== false) {
                $data['stok_p'] = $row;
            } elseif (stripos($row['nama_produk'], 'dex') !== false) {
                $data['stok_d'] = $row;
            }
        }

        $this->view('templates/layout', $data);
    }

    public function edit($id)
    {
        $model = $this->model('Stok_model');
        $thisStok = $model->getStokById($id);
        
        if (!$thisStok) {
            header('Location: ' . BASEURL . '/stok');
            exit;
        }

        $tanggal = $thisStok['tanggal'];
        $allStok = $model->getStokByTanggal($tanggal);

        $data['judul']  = 'Edit Stok BBM';
        $data['view']   = 'stok/edit';
        $data['tanggal'] = $tanggal;
        
        // Group by product name type (Pertamax & Dex)
        $data['stok_p'] = null;
        $data['stok_d'] = null;
        foreach ($allStok as $row) {
            if (stripos($row['nama_produk'], 'pertamax') !== false) {
                $data['stok_p'] = $row;
            } elseif (stripos($row['nama_produk'], 'dex') !== false) {
                $data['stok_d'] = $row;
            }
        }

        $this->view('templates/layout', $data);
    }

    public function hapus($id)
    {
        $this->model('Stok_model')->hapusStok($id);
        Flasher::setFlash('Stok', 'Dihapus', 'success');
        header('Location: ' . BASEURL . '/stok');
        exit;
    }
}
