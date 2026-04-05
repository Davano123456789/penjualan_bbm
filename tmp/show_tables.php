<?php
$dbh = new PDO('mysql:host=localhost;dbname=laporan_bbm', 'root', '');
print_r($dbh->query('SHOW TABLES')->fetchAll(PDO::FETCH_COLUMN));
