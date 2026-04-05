<?php
$dbh = new PDO('mysql:host=localhost;dbname=laporan_bbm', 'root', '');
$tables = ['voucher', 'penerima_voucher', 'produk_bbm'];
foreach ($tables as $table) {
    echo "--- $table table ---\n";
    $stmt = $dbh->query("DESCRIBE $table");
    print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
}
