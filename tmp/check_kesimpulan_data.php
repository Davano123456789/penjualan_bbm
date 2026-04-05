<?php
$dbh = new PDO('mysql:host=localhost;dbname=laporan_bbm', 'root', '');
$stmt = $dbh->query("SELECT * FROM v_kesimpulan WHERE month(periode) = 3 AND year(periode) = 2026");
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);
print_r($data);
