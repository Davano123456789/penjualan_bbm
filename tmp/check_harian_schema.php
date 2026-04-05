<?php
$host = 'localhost';
$db   = 'laporan_bbm';
$user = 'root';
$pass = '';
$dsn  = "mysql:host=$host;dbname=$db;charset=utf8mb4";
$options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    $stmt = $pdo->query('SHOW CREATE TABLE harian');
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "Table Schema:\n";
    echo $row['Create Table'];
} catch (\PDOException $e) {
    echo "Error: " . $e->getMessage();
}
