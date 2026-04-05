<?php
$host = 'localhost';
$db   = 'laporan_bbm';
$user = 'root';
$pass = '';
$dsn  = "mysql:host=$host;dbname=$db;charset=utf8mb4";
$options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    $stmt = $pdo->query("SHOW TABLES");
    echo "Tables in database:\n";
    while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
        $table = $row[0];
        echo "Table: $table\n";
        $stmt2 = $pdo->query("DESC $table");
        while ($row2 = $stmt2->fetch(PDO::FETCH_ASSOC)) {
            echo "  " . $row2['Field'] . " | " . $row2['Type'] . "\n";
        }
        echo "\n";
    }
} catch (\PDOException $e) {
    echo "Error: " . $e->getMessage();
}
