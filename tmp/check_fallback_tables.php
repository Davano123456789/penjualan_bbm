<?php
$dbh = new PDO('mysql:host=localhost;dbname=laporan_bbm', 'root', '');

echo "--- penjualan_harian table ---\n";
$stmt = $dbh->query("SELECT * FROM penjualan_harian LIMIT 5");
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));

echo "\n--- totalisator_akhir table (any) ---\n";
$stmt = $dbh->query("SELECT * FROM totalisator_akhir LIMIT 5");
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
