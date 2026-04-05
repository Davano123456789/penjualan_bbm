<?php
$dbh = new PDO('mysql:host=localhost;dbname=laporan_bbm', 'root', '');

echo "--- Operators ---\n";
$stmt = $dbh->query("SELECT * FROM operators");
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));

echo "\n--- Gaji Komponen (Defaults) ---\n";
$stmt = $dbh->query("SELECT * FROM gaji_komponen");
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));

echo "\n--- Gaji Records (Actual) ---\n";
$stmt = $dbh->query("SELECT * FROM gaji");
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
