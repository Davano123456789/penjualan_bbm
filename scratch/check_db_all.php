<?php
require_once 'app/core/Database.php';
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'laporan_bbm');

$db = new Database();

echo "--- TABLES ---\n";
$db->query("SHOW TABLES");
$tables = $db->resultSet();
foreach ($tables as $t) {
    $tableName = array_values($t)[0];
    echo "TABLE: $tableName\n";
    $db->query("DESCRIBE $tableName");
    print_r($db->resultSet());
    echo "\n";
}
