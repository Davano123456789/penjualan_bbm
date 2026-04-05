<?php
$dbh = new PDO('mysql:host=localhost;dbname=laporan_bbm', 'root', '');
echo "--- produk_bbm table schema ---\n";
print_r($dbh->query('DESCRIBE produk_bbm')->fetchAll(PDO::FETCH_ASSOC));
