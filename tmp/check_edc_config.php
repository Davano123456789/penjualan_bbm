<?php
$p = new PDO('mysql:host=localhost;dbname=laporan_bbm', 'root', '');
echo "--- konfigurasi_edc table ---\n";
print_r($p->query('SELECT * FROM konfigurasi_edc')->fetchAll(PDO::FETCH_ASSOC));
