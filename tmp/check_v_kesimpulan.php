<?php
$dbh = new PDO('mysql:host=localhost;dbname=laporan_bbm', 'root', '');

echo "--- totalisator table ---\n";
$stmt = $dbh->query("DESCRIBE totalisator");
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));

echo "\n--- v_kesimpulan view definition ---\n";
$stmt = $dbh->query("SHOW CREATE VIEW v_kesimpulan");
$view = $stmt->fetch(PDO::FETCH_ASSOC);
echo $view['Create View'] ?? 'View not found';

echo "\n--- produk_bbm table ---\n";
$stmt = $dbh->query("DESCRIBE produk_bbm");
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
