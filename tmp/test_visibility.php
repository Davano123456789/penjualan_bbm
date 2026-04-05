<?php
require_once 'config/config.php';
require_once 'app/core/Database.php';
require_once 'app/models/Kas_model.php';
require_once 'app/models/Pengeluaran_model.php';

$kasModel = new Kas_model();
$pengModel = new Pengeluaran_model();

// 1. Add "Lemari Saja" Operasional
$testData = [
    'tanggal' => date('Y-m-d'),
    'keterangan' => 'TEST LEMARI SAJA (SHOULD SHOW)',
    'tipe' => 'kredit',
    'jumlah' => 12345,
    'kategori' => 'lemari', // Lemari Saja
    'kategori_biaya' => 'Operasional'
];

echo "Adding Lemari Saja expense...\n";
$kasModel->tambahDataKas($testData);

// 2. Check if it's in the Pengeluaran report
$all = $pengModel->getAllPengeluaran(date('m'), date('Y'));
$found = false;
foreach ($all as $p) {
    if ($p['keterangan'] == 'TEST LEMARI SAJA (SHOULD SHOW)') {
        $found = true;
        break;
    }
}

if ($found) {
    echo "Found! It is visible.\n";
} else {
    echo "NOT FOUND! It is HIDDEN.\n";
}
