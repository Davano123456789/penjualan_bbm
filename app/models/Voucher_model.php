<?php

class Voucher_model {
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function getVoucherBulanan($bulan, $tahun)
    {
        $this->db->query("SELECT v.*, p.nama as nama_produk, p.harga_jual
                          FROM voucher v
                          JOIN produk_bbm p ON v.produk_id = p.id
                          WHERE MONTH(v.periode) = :bulan AND YEAR(v.periode) = :tahun
                          ORDER BY v.periode DESC, v.id DESC");
        
        $this->db->bind('bulan', (int)$bulan);
        $this->db->bind('tahun', (int)$tahun);
        
        return $this->db->resultSet();
    }

    public function getRekapPenerima($bulan, $tahun)
    {
        $this->db->query("SELECT penerima, SUM(jumlah_rupiah) as total_rupiah, COUNT(*) as jumlah_transaksi
                          FROM voucher
                          WHERE MONTH(periode) = :bulan AND YEAR(periode) = :tahun
                          GROUP BY penerima
                          ORDER BY total_rupiah DESC");
        
        $this->db->bind('bulan', (int)$bulan);
        $this->db->bind('tahun', (int)$tahun);
        
        return $this->db->resultSet();
    }

    public function tambahVoucher($data)
    {
        $this->db->query("INSERT INTO voucher (periode, penerima, produk_id, jumlah_rupiah, keterangan, jenis) 
                          VALUES (:periode, :penerima, :produk_id, :jumlah_rupiah, :keterangan, :jenis)");
        
        $this->db->bind('periode', $data['periode']);
        $this->db->bind('penerima', $data['penerima']);
        $this->db->bind('produk_id', $data['produk_id']);
        $this->db->bind('jumlah_rupiah', $data['jumlah_rupiah']);
        $this->db->bind('keterangan', $data['keterangan']);
        $this->db->bind('jenis', $data['jenis'] ?? 'eksternal');

        return $this->db->execute();
    }

    public function getProdukBBM()
    {
        $this->db->query("SELECT * FROM produk_bbm ORDER BY nama ASC");
        return $this->db->resultSet();
    }
    
    public function hapusVoucher($id)
    {
        $this->db->query("DELETE FROM voucher WHERE id = :id");
        $this->db->bind('id', $id);
        return $this->db->execute();
    }
}
