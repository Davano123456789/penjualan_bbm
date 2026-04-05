<?php
require_once 'config/config.php';
require_once 'app/core/Database.php';
require_once 'app/models/Losis_model.php';

$model = new Losis_model();

// Test with March 2026
$results = $model->getLosisBulanan('03', '2026');

echo "=== Laporan Losis Stok - Maret 2026 ===\n\n";
foreach ($results as $r) {
    echo "PRODUK: " . $r['nama_produk'] . "\n";
    echo "  Stok Awal          : " . number_format($r['stok_awal'], 2) . " L\n";
    echo "  DO Penebusan       : " . number_format($r['do_penebusan'], 2) . " L\n";
    echo "  Jumlah Stok (Buku) : " . number_format($r['jumlah_stok'], 2) . " L\n";
    echo "  Total Penjualan    : " . number_format($r['total_penjualan'], 2) . " L\n";
    echo "  Stok Akhir Fisik   : " . number_format($r['stok_akhir'], 2) . " L\n";
    echo "  Stok Riil          : " . number_format($r['penjualan_plus_akhir'], 2) . " L\n";
    echo "  -----------------------------------------\n";
    echo "  LOSIS              : " . number_format($r['losis'], 2) . " L\n";
    echo "  PERSENTASE         : " . number_format($r['persentase'], 4) . " %\n";
    echo "\n";
}
