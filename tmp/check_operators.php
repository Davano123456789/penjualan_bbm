<?php
$host = 'localhost';
$db   = 'laporan_bbm';
$user = 'root';
$pass = '';
$dsn  = "mysql:host=$host;dbname=$db;charset=utf8mb4";
$options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    $stmt = $pdo->query('SELECT * FROM operators');
    $operators = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "Operators Table Content:\n";
    print_r($operators);
} catch (\PDOException $e) {
    echo "Error: " . $e->getMessage();
}
