<?php
$host = 'localhost';
$db   = 'laporan_bbm';
$user = 'root';
$pass = '';
$dsn  = "mysql:host=$host;dbname=$db;charset=utf8mb4";
$options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    $pdo->exec("ALTER TABLE stok 
                ADD COLUMN jadwal VARCHAR(50) NULL AFTER selisih, 
                ADD COLUMN nama_supir VARCHAR(100) NULL AFTER jadwal");
    echo "Database Stok berhasil diupdate dengan kolom Jadwal & Nama Supir!\n";
} catch (\PDOException $e) {
    if (strpos($e->getMessage(), 'Duplicate column name') !== false) {
        echo "Kolom sudah ada.\n";
    } else {
        echo "Error: " . $e->getMessage();
    }
}
