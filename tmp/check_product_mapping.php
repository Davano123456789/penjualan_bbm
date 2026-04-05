<?php
$dbh = new PDO('mysql:host=localhost;dbname=laporan_bbm', 'root', '');
$stmt = $dbh->query("SELECT * FROM produk_bbm");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
print_r($products);
