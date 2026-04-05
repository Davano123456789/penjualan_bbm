<?php
$dbh = new PDO('mysql:host=localhost;dbname=laporan_bbm', 'root', '');
$tables = ['harian', 'stok', 'nozzle', 'detail_harian'];
foreach ($tables as $table) {
    echo "--- $table table ---\n";
    try {
        $stmt = $dbh->query("DESCRIBE $table");
        print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
    } catch (Exception $e) {
        echo "Table $table not found\n";
    }
}
