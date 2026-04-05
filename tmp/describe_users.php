<?php
$dbh = new PDO('mysql:host=localhost;dbname=laporan_bbm', 'root', '');
$q = $dbh->query('DESCRIBE users');
print_r($q->fetchAll(PDO::FETCH_ASSOC));
