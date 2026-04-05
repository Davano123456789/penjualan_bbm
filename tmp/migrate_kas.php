<?php
$host = 'localhost';
$db   = 'laporan_bbm';
$user = 'root';
$pass = '';
$dsn  = "mysql:host=$host;dbname=$db;charset=utf8mb4";
$options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    
    // 1. Add kategori_biaya
    $pdo->exec("ALTER TABLE kas ADD COLUMN kategori_biaya ENUM('Operasional', 'Curah', 'Lainnya') NULL AFTER jumlah");
    echo "Added kategori_biaya column.\n";
    
    // 2. Add transaksi_id
    $pdo->exec("ALTER TABLE kas ADD COLUMN transaksi_id VARCHAR(50) NULL AFTER kategori_biaya");
    echo "Added transaksi_id column.\n";

    // 3. Optional: Add Index
    $pdo->exec("CREATE INDEX idx_transaksi_id ON kas(transaksi_id)");
    echo "Created index on transaksi_id.\n";

} catch (\PDOException $e) {
    echo "Error: " . $e->getMessage();
}
