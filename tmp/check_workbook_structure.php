<?php
$file = 'C:/xampp/htdocs/penjualan_bbm/public/Harian Maret 2026.xlsx';
$zip = new ZipArchive();
if ($zip->open($file) === TRUE) {
    echo "Workbook content:\n";
    echo $zip->getFromName('xl/workbook.xml');
    $zip->close();
}
