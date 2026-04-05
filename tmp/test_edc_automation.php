<?php
require_once 'config/config.php';
require_once 'app/core/Database.php';
require_once 'app/models/Kas_model.php';
require_once 'app/models/EDC_model.php';

$kasModel = new Kas_model();
$edcModel = new EDC_model();

echo "Testing EDC Automation...\n";

// 1. Add Kas with QRIS Keterangan
$testData = [
    'tanggal' => date('Y-m-d'),
    'keterangan' => 'Uji Coba Bayar QRIS',
    'tipe' => 'kredit',
    'jumlah' => 100000,
    'kategori' => 'lemari'
];

echo "Adding Kas entry: 'Uji Coba Bayar QRIS' for Rp 100.000...\n";
$kasModel->tambahDataKas($testData);

// 2. Check EDC table
$db = new Database();
$db->query("SELECT * FROM edc ORDER BY id DESC LIMIT 1");
$edc = $db->single();

if ($edc && strpos(strtolower($testData['keterangan']), 'qris') !== false) {
    echo "SUCCESS: EDC record created automatically!\n";
    echo "  Nominal: " . $edc['nominal'] . "\n";
    echo "  Fee (%): " . $edc['persen_potongan'] . "%\n";
    echo "  Potongan: " . $edc['jumlah_potongan'] . "\n";
    echo "  Net: " . $edc['jumlah_masuk'] . "\n";
} else {
    echo "FAILURE: EDC record NOT created.\n";
}

// 3. Test Delete
echo "Testing cleanup on delete...\n";
$kas_id = $edc['kas_id'];
$kasModel->hapusDataKas($kas_id);

$db->query("SELECT id FROM edc WHERE kas_id = :kas_id");
$db->bind('kas_id', $kas_id);
if (!$db->single()) {
    echo "SUCCESS: EDC record deleted automatically with Kas.\n";
} else {
    echo "FAILURE: EDC record still exists after Kas delete.\n";
}
