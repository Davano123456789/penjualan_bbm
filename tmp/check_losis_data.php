<?php
$dbh = new PDO('mysql:host=localhost;dbname=laporan_bbm', 'root', '');
echo "--- losis table data ---\n";
print_r($dbh->query('SELECT * FROM losis LIMIT 5')->fetchAll(PDO::FETCH_ASSOC));
