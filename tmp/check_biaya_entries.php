<?php
$host = 'localhost';
$db   = 'laporan_bbm';
$user = 'root';
$pass = '';
$dsn  = "mysql:host=$host;dbname=$db;charset=utf8mb4";
$options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    $stmt = $pdo->query("SELECT id, keterangan, kategori, kategori_biaya, jumlah FROM kas WHERE kategori_biaya IS NOT NULL ORDER BY id DESC LIMIT 10");
    echo "Entries with kategori_biaya:\n";
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "ID: " . $row['id'] . " | Ket: " . $row['keterangan'] . " | Kat: " . $row['kategori'] . " | Biaya: " . $row['kategori_biaya'] . " | Rp: " . $row['jumlah'] . "\n";
    }
} catch (\PDOException $e) {
    echo "Error: " . $e->getMessage();
}
