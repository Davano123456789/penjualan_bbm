<?php
$dbh = new PDO('mysql:host=localhost;dbname=laporan_bbm', 'root', '');
print_r($dbh->query('DESCRIBE pengeluaran')->fetchAll(PDO::FETCH_ASSOC));
print_r($dbh->query('DESCRIBE laporan_bulanan')->fetchAll(PDO::FETCH_ASSOC));
print_r($dbh->query('DESCRIBE totalisator_akhir')->fetchAll(PDO::FETCH_ASSOC));
