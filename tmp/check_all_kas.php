<?php
$host = 'localhost';
$db   = 'laporan_bbm';
$user = 'root';
$pass = '';
$dsn  = "mysql:host=$host;dbname=$db;charset=utf8mb4";
$options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    $stmt = $pdo->query("SELECT id, keterangan, tipe, kategori, kategori_biaya, jumlah FROM kas ORDER BY id DESC LIMIT 20");
    echo "Last 20 entries in kas:\n";
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "ID: " . $row['id'] . " | Ket: " . $row['keterangan'] . " | Tipe: " . $row['tipe'] . " | Kat: " . $row['kategori'] . " | Biaya: [" . ($row['kategori_biaya'] ?? 'NULL') . "] | Rp: " . $row['jumlah'] . "\n";
    }
} catch (\PDOException $e) {
    echo "Error: " . $e->getMessage();
}
