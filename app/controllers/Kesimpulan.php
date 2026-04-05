<?php

class Kesimpulan extends Controller {
    public function index()
    {
        date_default_timezone_set('Asia/Jakarta');
        $bulan = $_GET['bulan'] ?? date('m');
        $tahun = $_GET['tahun'] ?? date('Y');

        $data['judul'] = 'Laporan Kesimpulan Bulanan';
        $data['view'] = 'kesimpulan/index';
        $data['bulan'] = $bulan;
        $data['tahun'] = $tahun;

        $data['kesimpulan'] = $this->model('Kesimpulan_model')->getKesimpulanData($bulan, $tahun);
        
        $this->view('templates/layout', $data);
    }
}
