<?php
$dbh = new PDO('mysql:host=localhost;dbname=laporan_bbm', 'root', '');
echo "--- losis table schema ---\n";
print_r($dbh->query('DESCRIBE losis')->fetchAll(PDO::FETCH_ASSOC));
echo "\n--- sample data ---\n";
print_r($dbh->query('SELECT * FROM losis LIMIT 3')->fetchAll(PDO::FETCH_ASSOC));
