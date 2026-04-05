<?php
$p = new PDO('mysql:host=localhost;dbname=laporan_bbm', 'root', '');

echo "Mencari data EDC duplikat...\n";

// Query untuk mencari entri EDC yang memiliki transaksi_id yang sama (yang berarti dobel dari Lemari & Arus)
$sql = "SELECT e.id, e.kas_id, k.transaksi_id, k.kategori
        FROM edc e
        JOIN kas k ON e.kas_id = k.id
        WHERE k.transaksi_id IS NOT NULL 
        AND k.transaksi_id IN (
            SELECT k2.transaksi_id 
            FROM edc e2 
            JOIN kas k2 ON e2.kas_id = k2.id 
            GROUP BY k2.transaksi_id 
            HAVING COUNT(*) > 1
        )";

$stmt = $p->query($sql);
$duplicates = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$duplicates) {
    echo "Tidak ditemukan data duplikat.\n";
} else {
    echo "Ditemukan " . count($duplicates) . " entri duplikat (termasuk yang aslinya).\n";
    
    $deleted = 0;
    $seen_transaksi = [];
    
    foreach ($duplicates as $row) {
        $trx = $row['transaksi_id'];
        
        // Aturan: Simpan jika kategori = 'arus', hapus yang lain (lemari)
        if (!isset($seen_transaksi[$trx])) {
            // Ini yang pertama kita temukan, tapi kita ingin memprioritaskan 'arus'
            // Jadi kita cari yang 'arus' dari grup ini untuk disimpan
            $saved = false;
            foreach ($duplicates as $sub) {
                if ($sub['transaksi_id'] == $trx && $sub['kategori'] == 'arus') {
                    $seen_transaksi[$trx] = $sub['id'];
                    $saved = true;
                    break;
                }
            }
            // Jika tidak ada 'arus', ya simpan yang pertama ini saja
            if (!$saved) {
                $seen_transaksi[$trx] = $row['id'];
            }
        }
        
        // Jika ID sekarang bukan yang kita simpan, maka hapus
        if ($row['id'] != $seen_transaksi[$trx]) {
            $p->exec("DELETE FROM edc WHERE id = " . $row['id']);
            echo "Menghapus EDC ID: " . $row['id'] . " (Kas ID: " . $row['kas_id'] . ", Kategori: " . $row['kategori'] . ")\n";
            $deleted++;
        }
    }
    
    echo "Pembersihan selesai. Total menghapus $deleted entri duplikat.\n";
}
