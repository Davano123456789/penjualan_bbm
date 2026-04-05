<?php
$p = new PDO('mysql:host=localhost;dbname=laporan_bbm', 'root', '');
echo "--- edc table ---\n";
print_r($p->query('DESCRIBE edc')->fetchAll(PDO::FETCH_ASSOC));
echo "\n--- kas table ---\n";
print_r($p->query('DESCRIBE kas')->fetchAll(PDO::FETCH_ASSOC));
echo "\n--- kas_transaksi table ---\n";
print_r($p->query('DESCRIBE kas_transaksi')->fetchAll(PDO::FETCH_ASSOC));
