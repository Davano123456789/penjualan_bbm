<?php
$p = new PDO('mysql:host=localhost;dbname=laporan_bbm', 'root', '');
try {
    // Check if kas_id exists
    $stmt = $p->query("SHOW COLUMNS FROM edc LIKE 'kas_id'");
    if ($stmt->rowCount() == 0) {
        $p->exec("ALTER TABLE edc ADD COLUMN kas_id INT(10) UNSIGNED NULL AFTER kas_transaksi_id");
        echo "Column 'kas_id' added successfully to 'edc' table.\n";
    } else {
        echo "Column 'kas_id' already exists.\n";
    }

    // Make kas_transaksi_id nullable as we'll use kas_id mostly
    $p->exec("ALTER TABLE edc MODIFY kas_transaksi_id INT(10) UNSIGNED NULL");
    echo "Modified 'kas_transaksi_id' to be nullable.\n";

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
