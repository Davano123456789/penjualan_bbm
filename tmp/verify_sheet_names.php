<?php
$file = 'C:/xampp/htdocs/penjualan_bbm/public/Harian Maret 2026.xlsx';
$zip = new ZipArchive();
if ($zip->open($file) === TRUE) {
    echo "Peta Nama Sheet:\n";
    $workbookXml = $zip->getFromName('xl/workbook.xml');
    $dom = new DOMDocument();
    $dom->loadXML($workbookXml);
    $sheets = $dom->getElementsByTagName('sheet');
    foreach ($sheets as $sheet) {
        echo "Name: " . $sheet->getAttribute('name') . " | ID: " . $sheet->getAttribute('r:id') . "\n";
    }
    $zip->close();
}
