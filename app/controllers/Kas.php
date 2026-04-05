<?php

class Kas extends Controller {
    public function index()
    {
        date_default_timezone_set('Asia/Jakarta');
        $bulan = $_GET['bulan'] ?? 'all';
        $tahun = $_GET['tahun'] ?? date('Y');

        $data['judul'] = 'Laporan Buku Kas';
        $data['view'] = 'kas/index';
        $data['bulan'] = $bulan;
        $data['tahun'] = $tahun;

        $model = $this->model('Kas_model');
        
        // Get data for both Ledger types
        $data['lemari'] = $model->getKasBulan($bulan, $tahun, 'lemari');
        $data['arus']   = $model->getKasBulan($bulan, $tahun, 'arus');

        // Initial balances (at start of the month or year)
        $firstDate = ($bulan === 'all') ? "$tahun-01-01" : "$tahun-$bulan-01";
        $data['saldo_awal_lemari'] = $model->getSaldoAwal($firstDate, 'lemari');
        $data['saldo_awal_arus']   = $model->getSaldoAwal($firstDate, 'arus');
        
        $this->view('templates/layout', $data);
    }

    public function tambah()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($this->model('Kas_model')->tambahDataKas($_POST) > 0) {
                Flasher::setFlash('Baru', 'Berhasil Ditambahkan', 'success');
                header('Location: ' . BASEURL . '/kas');
                exit;
            } else {
                Flasher::setFlash('Baru', 'Gagal Ditambahkan', 'error');
                header('Location: ' . BASEURL . '/kas');
                exit;
            }
        }
    }

    public function hapus($id)
    {
        if ($this->model('Kas_model')->hapusDataKas($id) > 0) {
            Flasher::setFlash('Ini', 'Berhasil Dihapus', 'success');
            header('Location: ' . BASEURL . '/kas');
            exit;
        } else {
            Flasher::setFlash('Ini', 'Gagal Dihapus', 'error');
            header('Location: ' . BASEURL . '/kas');
            exit;
        }
    }
}
