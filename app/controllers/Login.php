<?php

class Login extends Controller {
    public function index()
    {
        if (isset($_SESSION['user'])) {
            header('Location: ' . BASEURL . '/home');
            exit;
        }

        // Ensure at least one admin exists
        $this->model('User_model')->checkAndCreateDefaultAdmin();

        $data['judul'] = 'Login';
        // We use a clean view without the standard template/sidebar
        $this->view('login/index', $data);
    }

    public function process()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nama = $_POST['nama'];
            $password = $_POST['password'];

            $user = $this->model('User_model')->login($nama, $password);

            if ($user) {
                $_SESSION['user'] = [
                    'id' => $user['id'],
                    'nama' => $user['nama'],
                    'role' => $user['role']
                ];
                header('Location: ' . BASEURL . '/home');
                exit;
            } else {
                Flasher::setFlash('Login', 'Gagal, Username atau Password salah', 'danger');
                header('Location: ' . BASEURL . '/login');
                exit;
            }
        }
    }

    public function logout()
    {
        session_destroy();
        header('Location: ' . BASEURL . '/login');
        exit;
    }
}
