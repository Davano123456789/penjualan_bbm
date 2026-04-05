<?php
require_once 'config/config.php';
require_once 'app/core/Database.php';
require_once 'app/models/Kas_model.php';

// Mock config if file not reachable or constants not defined
if (!defined('DB_HOST')) define('DB_HOST', 'localhost');
if (!defined('DB_USER')) define('DB_USER', 'root');
if (!defined('DB_PASS')) define('DB_PASS', '');
if (!defined('DB_NAME')) define('DB_NAME', 'laporan_bbm');

$model = new Kas_model();
$testData = [
    'tanggal' => date('Y-m-d'),
    'keterangan' => 'TEST BIAYA OPERASIONAL',
    'tipe' => 'kredit',
    'jumlah' => 50000,
    'kategori' => 'keduanya',
    'kategori_biaya' => 'Operasional'
];

echo "Adding test data...\n";
$res = $model->tambahDataKas($testData);
echo "Result: $res rows added.\n";

$pdo = new PDO('mysql:host=localhost;dbname=laporan_bbm', 'root', '');
$stmt = $pdo->query("SELECT * FROM kas WHERE keterangan = 'TEST BIAYA OPERASIONAL'");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "ID: " . $row['id'] . " | Kat: " . $row['kategori'] . " | Biaya: " . ($row['kategori_biaya'] ?? 'NULL') . "\n";
}
