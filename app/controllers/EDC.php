<?php

class EDC extends Controller {
    public function index()
    {
        date_default_timezone_set('Asia/Jakarta');
        $bulan = $_GET['bulan'] ?? 'all';
        $tahun = $_GET['tahun'] ?? date('Y');

        $data['judul'] = 'Laporan EDC (QRIS & Debit)';
        $data['view'] = 'edc/index';
        $data['bulan'] = $bulan;
        $data['tahun'] = $tahun;

        $data['edc'] = $this->model('EDC_model')->getEDCByMonth($bulan, $tahun);
        
        $this->view('templates/layout', $data);
    }

    public function cetak()
    {
        date_default_timezone_set('Asia/Jakarta');
        $bulan = $_GET['bulan'] ?? 'all';
        $tahun = $_GET['tahun'] ?? date('Y');

        $data['judul'] = 'Laporan EDC (QRIS & Debit)';
        $data['bulan'] = $bulan;
        $data['tahun'] = $tahun;

        $data['edc'] = $this->model('EDC_model')->getEDCByMonth($bulan, $tahun);
        
        $this->view('edc/cetak', $data);
    }
}
