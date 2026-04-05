<?php
$dbh = new PDO('mysql:host=localhost;dbname=laporan_bbm', 'root', '');

echo "--- Stok count per product (March 2026) ---\n";
$stmt = $dbh->query("SELECT produk_id, count(*) as count FROM stok WHERE month(tanggal) = 3 AND year(tanggal) = 2026 GROUP BY produk_id");
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));

echo "--- Totalisator count per product (March 2026) ---\n";
$stmt = $dbh->query("SELECT produk_id, count(*) as count FROM totalisator t JOIN harian h ON t.harian_id = h.id WHERE month(h.tanggal) = 3 AND year(h.tanggal) = 2026 GROUP BY produk_id");
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
