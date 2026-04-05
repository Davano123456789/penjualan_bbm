<?php

class Operator extends Controller {
    public function index()
    {
        $data['judul'] = 'Daftar Operator';
        $data['view'] = 'operator/index';
        $data['operator'] = $this->model('Operator_model')->getAllOperator();
        
        $this->view('templates/layout', $data);
    }

    public function tambah()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($this->model('Operator_model')->tambahDataOperator($_POST) > 0) {
                header('Location: ' . BASEURL . '/operator');
                exit;
            }
        }
    }

    public function hapus($id)
    {
        if ($this->model('Operator_model')->hapusDataOperator($id) > 0) {
            header('Location: ' . BASEURL . '/operator');
            exit;
        }
    }
}
