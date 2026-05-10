<?php

class Harian extends Controller {
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function index()
    {
        $data['judul'] = 'Laporan Harian';
        $data['view'] = 'harian/index';
        $data['harian'] = $this->model('Harian_model')->getAllHarian();
        
        $this->view('templates/layout', $data);
    }

    public function input()
    {
        $data['judul'] = 'Input Laporan Harian';
        $data['view'] = 'harian/input';
        
        // Fetch products for the input form
        $this->db->query('SELECT * FROM produk_bbm');
        $data['produk'] = $this->db->resultSet();

        // Fetch operators for the input form
        $this->db->query('SELECT * FROM operators');
        $data['operators'] = $this->db->resultSet();
        
        $this->view('templates/layout', $data);
    }

    public function tambah()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($this->model('Harian_model')->tambahDataHarian($_POST)) {
                Flasher::setFlash('Baru', 'Ditambahkan', 'success');
                header('Location: ' . BASEURL . '/harian');
                exit;
            } else {
                Flasher::setFlash('Baru', 'Gagal Ditambahkan', 'error');
                header('Location: ' . BASEURL . '/harian');
                exit;
            }
        }
    }

    public function hapus($id)
    {
        if ($this->model('Harian_model')->hapusDataHarian($id) >= 0) { // It returns rowCount, >=0 assumes execution pass. If rowCount>0 then it affects rows.
            Flasher::setFlash('Ini', 'Dihapus', 'success');
            header('Location: ' . BASEURL . '/harian');
            exit;
        } else {
            Flasher::setFlash('Ini', 'Gagal Dihapus', 'error');
            header('Location: ' . BASEURL . '/harian');
            exit;
        }
    }

    public function detail($id)
    {
        $data['judul'] = 'Detail Laporan Harian';
        $data['view'] = 'harian/detail';
        
        $data['harian'] = $this->model('Harian_model')->getHarianById($id);
        $data['penjualan'] = $this->model('Harian_model')->getPenjualanByHarianId($id);
        
        // Fetch products for reference in case it's needed in view
        $this->db->query('SELECT * FROM produk_bbm');
        $data['produk'] = $this->db->resultSet();
        
        $this->view('templates/layout', $data);
    }

    public function edit($id)
    {
        $data['judul'] = 'Edit Laporan Harian';
        $data['view'] = 'harian/edit';
        
        $data['harian'] = $this->model('Harian_model')->getHarianById($id);
        $data['penjualan'] = $this->model('Harian_model')->getPenjualanByHarianId($id);
        
        // Fetch existing stok fisik if any
        $stok_pertamax = $this->model('Stok_model')->getStokByTanggalProduk($data['harian']['tanggal'], 1);
        $stok_dex = $this->model('Stok_model')->getStokByTanggalProduk($data['harian']['tanggal'], 3);
        $data['stok_fisik_pertamax'] = $stok_pertamax ? $stok_pertamax['stok_akhir_fisik'] : '';
        $data['stok_fisik_dex'] = $stok_dex ? $stok_dex['stok_akhir_fisik'] : '';

        // Fetch products for the input form
        $this->db->query('SELECT * FROM produk_bbm');
        $data['produk'] = $this->db->resultSet();

        // Fetch operators for the input form
        $this->db->query('SELECT * FROM operators');
        $data['operators'] = $this->db->resultSet();
        
        $this->view('templates/layout', $data);
    }

    public function ubah()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($this->model('Harian_model')->ubahDataHarian($_POST)) {
                Flasher::setFlash('Ini', 'Diubah', 'success');
                header('Location: ' . BASEURL . '/harian');
                exit;
            } else {
                Flasher::setFlash('Ini', 'Gagal Diubah', 'error');
                header('Location: ' . BASEURL . '/harian');
                exit;
            }
        }
    }

    public function get_latest_readings()
    {
        $data = $this->model('Harian_model')->getLatestTotalizers();
        echo json_encode($data);
    }
}
