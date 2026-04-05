<?php
$dbh = new PDO('mysql:host=localhost;dbname=laporan_bbm', 'root', '');
$q = $dbh->query('SHOW TABLES');
echo implode(',', $q->fetchAll(PDO::FETCH_COLUMN));
