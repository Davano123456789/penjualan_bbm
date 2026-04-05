<?php

class Totakhir extends Controller {
    public function index()
    {
        date_default_timezone_set('Asia/Jakarta');
        $bulan = $_GET['bulan'] ?? date('m');
        $tahun = $_GET['tahun'] ?? date('Y');

        $data['judul'] = 'Laporan Laba Rugi Bulanan';
        $data['view'] = 'tot-akhir/index';
        $data['bulan'] = $bulan;
        $data['tahun'] = $tahun;

        $totakhirModel = $this->model('Totakhir_model');
        $data['report'] = $totakhirModel->getLaporanBulanan($bulan, $tahun);
        
        $this->view('templates/layout', $data);
    }

    public function simpan()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($this->model('Totakhir_model')->simpanLaporan($_POST)) {
                Flasher::setFlash('berhasil', 'Laporan Laba Rugi berhasil dikunci/disimpan', 'success');
            } else {
                Flasher::setFlash('gagal', 'Laporan gagal disimpan', 'danger');
            }
            header('Location: ' . BASEURL . '/totakhir?bulan=' . $_POST['bulan'] . '&tahun=' . $_POST['tahun']);
            exit;
        }
    }
}
