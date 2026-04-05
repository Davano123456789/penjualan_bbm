<?php
$host = 'localhost';
$db   = 'laporan_bbm';
$user = 'root';
$pass = '';

$dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
$options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];

try {
     $pdo = new PDO($dsn, $user, $pass, $options);
     
     // Check if reading_awal exists, if not, add both
     $stmt = $pdo->query("SHOW COLUMNS FROM penjualan_harian LIKE 'reading_awal'");
     if ($stmt->rowCount() == 0) {
         $pdo->exec("ALTER TABLE penjualan_harian 
                     ADD COLUMN reading_awal DECIMAL(10,2) DEFAULT 0 AFTER nozzle,
                     ADD COLUMN reading_akhir DECIMAL(10,2) DEFAULT 0 AFTER reading_awal");
         echo "Columns added successfully!";
     } else {
         echo "Columns already exist.";
     }
} catch (\PDOException $e) {
     echo "Error: " . $e->getMessage();
}
