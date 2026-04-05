<?php
$dbh = new PDO('mysql:host=localhost;dbname=laporan_bbm', 'root', '');
$q = $dbh->query('SELECT * FROM users');
print_r($q->fetchAll(PDO::FETCH_ASSOC));
