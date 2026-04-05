<?php

class Voucher extends Controller {
    public function index()
    {
        date_default_timezone_set('Asia/Jakarta');
        $bulan = $_GET['bulan'] ?? date('m');
        $tahun = $_GET['tahun'] ?? date('Y');

        $data['judul'] = 'Laporan Voucher BBM';
        $data['view'] = 'voucher/index';
        $data['bulan'] = $bulan;
        $data['tahun'] = $tahun;

        $voucherModel = $this->model('Voucher_model');
        $data['vouchers'] = $voucherModel->getVoucherBulanan($bulan, $tahun);
        $data['rekap'] = $voucherModel->getRekapPenerima($bulan, $tahun);
        $data['produk'] = $voucherModel->getProdukBBM();
        
        $this->view('templates/layout', $data);
    }

    public function tambah()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($this->model('Voucher_model')->tambahVoucher($_POST)) {
                Flasher::setFlash('berhasil', 'Voucher berhasil dicatat', 'success');
            } else {
                Flasher::setFlash('gagal', 'Voucher gagal dicatat', 'danger');
            }
            
            $bulan = date('m', strtotime($_POST['periode']));
            $tahun = date('Y', strtotime($_POST['periode']));
            header('Location: ' . BASEURL . '/voucher?bulan=' . $bulan . '&tahun=' . $tahun);
            exit;
        }
    }

    public function hapus($id)
    {
        if ($this->model('Voucher_model')->hapusVoucher($id)) {
            Flasher::setFlash('berhasil', 'Voucher berhasil dihapus', 'success');
        } else {
            Flasher::setFlash('gagal', 'Voucher gagal dihapus', 'danger');
        }
        header('Location: ' . BASEURL . '/voucher');
        exit;
    }
}
