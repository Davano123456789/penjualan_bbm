<?php
$host = 'localhost';
$db   = 'laporan_bbm';
$user = 'root';
$pass = '';
$dsn  = "mysql:host=$host;dbname=$db;charset=utf8mb4";
$options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    $stmt = $pdo->query("SELECT id, keterangan, kategori_biaya FROM kas ORDER BY id DESC LIMIT 5");
    echo "Recent entries in kas:\n";
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "ID: " . $row['id'] . " | Ket: " . $row['keterangan'] . " | Kat: [" . ($row['kategori_biaya'] === null ? 'NULL' : $row['kategori_biaya']) . "]\n";
    }
} catch (\PDOException $e) {
    echo "Error: " . $e->getMessage();
}
