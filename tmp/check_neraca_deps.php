<?php
$dbh = new PDO('mysql:host=localhost;dbname=laporan_bbm', 'root', '');
echo "--- neraca table schema ---\n";
print_r($dbh->query('DESCRIBE neraca')->fetchAll(PDO::FETCH_ASSOC));
echo "\n--- kas_saldo table schema ---\n";
print_r($dbh->query('DESCRIBE kas_saldo')->fetchAll(PDO::FETCH_ASSOC));
echo "\n--- latest kas_saldo data ---\n";
print_r($dbh->query('SELECT * FROM kas_saldo ORDER BY id DESC LIMIT 1')->fetchAll(PDO::FETCH_ASSOC));
