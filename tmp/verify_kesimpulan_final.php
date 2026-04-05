<?php
require_once 'config/config.php';
require_once 'app/core/Database.php';
require_once 'app/models/Kesimpulan_model.php';

$model = new Kesimpulan_model();
$data = $model->getKesimpulanData('03', '2026');

echo "Verifikasi Laporan Kesimpulan Maret 2026:\n";
foreach ($data as $k) {
    echo "Produk: " . $k['produk'] . "\n";
    echo "  Stok Awal: " . $k['stok_awal'] . "\n";
    echo "  Penerimaan: " . $k['penerimaan_bbm'] . "\n";
    echo "  Stok Akhir: " . $k['stok_akhir'] . "\n";
    foreach ($k['nozzles'] as $n) {
        echo "    " . $n['label'] . ": " . $n['totalisator_awal'] . " -> " . $n['totalisator_akhir'] . " (Jual: " . $n['total_penjualan'] . ")\n";
    }
}
