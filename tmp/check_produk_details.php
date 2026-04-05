<?php
$dbh = new PDO('mysql:host=localhost;dbname=laporan_bbm', 'root', '');
echo "--- produk_bbm data ---\n";
print_r($dbh->query('SELECT * FROM produk_bbm')->fetchAll(PDO::FETCH_ASSOC));
