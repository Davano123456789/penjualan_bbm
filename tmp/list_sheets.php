<?php
$file = 'C:/xampp/htdocs/penjualan_bbm/public/Harian Maret 2026.xlsx';
$zip = new ZipArchive();
if ($zip->open($file) === TRUE) {
    $xml = $zip->getFromName('xl/workbook.xml');
    $dom = new DOMDocument();
    $dom->loadXML($xml);
    $sheets = $dom->getElementsByTagName('sheet');
    foreach ($sheets as $sheet) {
        echo 'Sheet ID ' . $sheet->getAttribute('sheetId') . ': ' . $sheet->getAttribute('name') . "\n";
    }
    $zip->close();
} else {
    echo "Gagal membuka file Excel.";
}
