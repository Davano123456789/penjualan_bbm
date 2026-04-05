<?php
$host = 'localhost';
$db   = 'laporan_bbm';
$user = 'root';
$pass = '';
$dsn  = "mysql:host=$host;dbname=$db;charset=utf8mb4";
$options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    $count = $pdo->exec("UPDATE kas SET kategori_biaya = NULL WHERE kategori_biaya = ''");
    echo "Successfully updated $count records.";
} catch (\PDOException $e) {
    echo "Error: " . $e->getMessage();
}
