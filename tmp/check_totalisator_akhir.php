<?php
$dbh = new PDO('mysql:host=localhost;dbname=laporan_bbm', 'root', '');

echo "--- totalisator_akhir table ---\n";
$stmt = $dbh->query("DESCRIBE totalisator_akhir");
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
