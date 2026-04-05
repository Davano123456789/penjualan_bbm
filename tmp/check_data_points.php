<?php
$dbh = new PDO('mysql:host=localhost;dbname=laporan_bbm', 'root', '');

echo "--- March 2026 Stok Data (Pertamax) ---\n";
$stmt = $dbh->query("SELECT * FROM stok WHERE month(tanggal) = 3 AND year(tanggal) = 2026 AND produk_id = 1 ORDER BY tanggal ASC");
$stok_pertamax = $stmt->fetchAll(PDO::FETCH_ASSOC);
print_r($stok_pertamax);

echo "--- March 2026 Totalisator Akhir (Pertamax) ---\n";
$stmt = $dbh->query("SELECT * FROM totalisator_akhir WHERE month(periode) = 3 AND year(periode) = 2026 AND produk_id = 1");
$tot_pertamax = $stmt->fetchAll(PDO::FETCH_ASSOC);
print_r($tot_pertamax);

echo "--- Products ---\n";
$stmt = $dbh->query("SELECT * FROM produk_bbm");
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
