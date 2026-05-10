<?php

class Flasher
{
    public static function setFlash($pesan, $aksi, $tipe)
    {
        $_SESSION['flash'] = [
            'pesan' => $pesan,
            'aksi' => $aksi,
            'tipe' => $tipe
        ];
    }

    public static function flash()
    {
        if (isset($_SESSION['flash'])) {
            $f = $_SESSION['flash'];
            echo "<script>
                    Swal.fire({
                        title: 'Berhasil!',
                        text: 'Laporan harian " . $f['pesan'] . " berhasil " . $f['aksi'] . "!',
                        icon: '" . $f['tipe'] . "',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Tutup'
                    });
                  </script>";
            unset($_SESSION['flash']);
        }
    }
}
