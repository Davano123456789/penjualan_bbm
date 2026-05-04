<?php

class Pengeluaran extends Controller {
    public function index()
    {
        date_default_timezone_set('Asia/Jakarta');
        $bulan = $_GET['bulan'] ?? 'all';
        $tahun = $_GET['tahun'] ?? date('Y');

        $data['judul'] = 'Daftar Pengeluaran Operasional';
        $data['view'] = 'pengeluaran/index';
        $data['bulan'] = $bulan;
        $data['tahun'] = $tahun;
        $data['pengeluaran'] = $this->model('Pengeluaran_model')->getAllPengeluaran($bulan, $tahun);
        $data['total'] = $this->model('Pengeluaran_model')->getTotalPengeluaranBulan($bulan, $tahun);
        $data['summary_kategori'] = $this->model('Pengeluaran_model')->getSummaryByKategori($bulan, $tahun);
        
        $this->view('templates/layout', $data);
    }

    public function cetak()
    {
        date_default_timezone_set('Asia/Jakarta');
        $bulan = $_GET['bulan'] ?? 'all';
        $tahun = $_GET['tahun'] ?? date('Y');

        $data['judul'] = 'Cetak Laporan Pengeluaran';
        $data['bulan'] = $bulan;
        $data['tahun'] = $tahun;
        $data['pengeluaran'] = $this->model('Pengeluaran_model')->getAllPengeluaran($bulan, $tahun);
        $data['total'] = $this->model('Pengeluaran_model')->getTotalPengeluaranBulan($bulan, $tahun);
        $data['summary_kategori'] = $this->model('Pengeluaran_model')->getSummaryByKategori($bulan, $tahun);
        
        $this->view('pengeluaran/cetak', $data);
    }

    public function hapus($id)
    {
        if ($this->model('Kas_model')->hapusDataKas($id) > 0) {
            Flasher::setFlash('Ini', 'Berhasil Dihapus', 'success');
            header('Location: ' . BASEURL . '/pengeluaran');
            exit;
        } else {
            Flasher::setFlash('Ini', 'Gagal Dihapus', 'error');
            header('Location: ' . BASEURL . '/pengeluaran');
            exit;
        }
    }
}
