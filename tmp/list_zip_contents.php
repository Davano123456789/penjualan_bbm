<?php
$file = 'C:/xampp/htdocs/penjualan_bbm/public/Harian Maret 2026.xlsx';
$zip = new ZipArchive();
if ($zip->open($file) === TRUE) {
    echo "Files in Excel Zip:\n";
    for ($i = 0; $i < $zip->numFiles; $i++) {
        $name = $zip->getNameIndex($i);
        if (strpos($name, 'xl/worksheets/') !== false || strpos($name, 'workbook.xml') !== false) {
            echo $name . "\n";
        }
    }
    
    echo "\n--- workbook.xml.rels ---\n";
    echo $zip->getFromName('xl/_rels/workbook.xml.rels');
    $zip->close();
}
