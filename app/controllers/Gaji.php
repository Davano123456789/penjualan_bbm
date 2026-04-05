<?php

class Gaji extends Controller {
    public function index()
    {
        date_default_timezone_set('Asia/Jakarta');
        $bulan = $_GET['bulan'] ?? date('m');
        $tahun = $_GET['tahun'] ?? date('Y');

        $data['judul'] = 'Laporan Gaji Karyawan';
        $data['view'] = 'gaji/index';
        $data['bulan'] = $bulan;
        $data['tahun'] = $tahun;

        $gajiModel = $this->model('Gaji_model');
        $data['gaji'] = $gajiModel->getGajiBulanan($bulan, $tahun);
        
        $this->view('templates/layout', $data);
    }

    public function save()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $gajiModel = $this->model('Gaji_model');
            
            // Format data for saving
            $saveData = [
                'operator_id' => $_POST['operator_id'],
                'bulan' => $_POST['bulan'],
                'tahun' => $_POST['tahun'],
                'gaji_pokok' => $_POST['gaji_pokok'],
                'lembur' => $_POST['lembur'],
                'kas_bon' => $_POST['kas_bon']
            ];

            if ($gajiModel->saveGaji($saveData)) {
                Flasher::setFlash('berhasil', 'Gaji berhasil disimpan', 'success');
            } else {
                Flasher::setFlash('gagal', 'Gaji gagal disimpan', 'danger');
            }
            
            header('Location: ' . BASEURL . '/gaji?bulan=' . $_POST['bulan'] . '&tahun=' . $_POST['tahun']);
            exit;
        }
    }
}
