<?php
$dbh = new PDO('mysql:host=localhost;dbname=laporan_bbm', 'root', '');
$stmt = $dbh->query("SELECT id, tanggal FROM harian WHERE month(tanggal) = 3 AND year(tanggal) = 2026");
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);
print_r($data);
