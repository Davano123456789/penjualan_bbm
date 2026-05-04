<?php

class Losis extends Controller {
    public function index()
    {
        date_default_timezone_set('Asia/Jakarta');
        $bulan = $_GET['bulan'] ?? date('m');
        $tahun = $_GET['tahun'] ?? date('Y');

        $data['judul'] = 'Laporan Losis Stok BBM';
        $data['view'] = 'losis/index';
        $data['bulan'] = $bulan;
        $data['tahun'] = $tahun;

        $data['results'] = $this->model('Losis_model')->getLosisBulanan($bulan, $tahun);
        
        $this->view('templates/layout', $data);
    }

    public function cetak()
    {
        date_default_timezone_set('Asia/Jakarta');
        $bulan = $_GET['bulan'] ?? date('m');
        $tahun = $_GET['tahun'] ?? date('Y');

        $data['judul'] = 'Laporan Losis Stok BBM';
        $data['bulan'] = $bulan;
        $data['tahun'] = $tahun;

        $data['results'] = $this->model('Losis_model')->getLosisBulanan($bulan, $tahun);
        
        $this->view('losis/cetak', $data);
    }
}
