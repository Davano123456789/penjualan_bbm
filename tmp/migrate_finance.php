<?php
$host = 'localhost';
$db   = 'laporan_bbm';
$user = 'root';
$pass = '';
$dsn  = "mysql:host=$host;dbname=$db;charset=utf8mb4";
$options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    
    // 1. Create 'pengeluaran' table
    $pdo->exec("CREATE TABLE IF NOT EXISTS pengeluaran (
        id INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        tanggal DATE NOT NULL,
        kategori VARCHAR(100) DEFAULT 'Operasional',
        keterangan VARCHAR(255) NOT NULL,
        jumlah DECIMAL(15, 2) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci");
    
    // 2. Create 'kas' table
    $pdo->exec("CREATE TABLE IF NOT EXISTS kas (
        id INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        tanggal DATE NOT NULL,
        keterangan VARCHAR(255) NOT NULL,
        tipe ENUM('debit', 'kredit') NOT NULL,
        jumlah DECIMAL(15, 2) NOT NULL,
        kategori ENUM('lemari', 'arus') NOT NULL DEFAULT 'lemari',
        harian_id INT(10) UNSIGNED NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci");

    echo "Database Berhasil Diupdate (Tabel Kas & Pengeluaran)!\n";
} catch (\PDOException $e) {
    echo "Error: " . $e->getMessage();
}
