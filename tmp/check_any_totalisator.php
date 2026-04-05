<?php
$dbh = new PDO('mysql:host=localhost;dbname=laporan_bbm', 'root', '');
$stmt = $dbh->query("SELECT * FROM totalisator LIMIT 10");
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);
print_r($data);
