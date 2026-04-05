<?php
$host = 'localhost';
$db   = 'laporan_bbm';
$user = 'root';
$pass = '';
$dsn  = "mysql:host=$host;dbname=$db;charset=utf8mb4";
$options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    $pdo->exec("ALTER TABLE kas ADD COLUMN pengeluaran_id INT(10) UNSIGNED NULL AFTER harian_id");
    echo "Kolom pengeluaran_id berhasil ditambahkan ke tabel kas!\n";
} catch (\PDOException $e) {
    echo "Error: " . $e->getMessage();
}
