<?php

class Neraca extends Controller {
    public function index()
    {
        date_default_timezone_set('Asia/Jakarta');
        $bulan = $_GET['bulan'] ?? date('m');
        $tahun = $_GET['tahun'] ?? date('Y');

        $data['judul'] = 'Laporan Neraca Bulanan';
        $data['view'] = 'neraca/index';
        $data['bulan'] = $bulan;
        $data['tahun'] = $tahun;

        $neracaModel = $this->model('Neraca_model');
        $data['report'] = $neracaModel->getNeracaBulanan($bulan, $tahun);
        
        $this->view('templates/layout', $data);
    }

    public function simpan()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($this->model('Neraca_model')->simpanNeraca($_POST)) {
                Flasher::setFlash('berhasil', 'Laporan Neraca berhasil disimpan', 'success');
            } else {
                Flasher::setFlash('gagal', 'Laporan gagal disimpan', 'danger');
            }
            header('Location: ' . BASEURL . '/neraca?bulan=' . $_POST['bulan'] . '&tahun=' . $_POST['tahun']);
            exit;
        }
    }
}
