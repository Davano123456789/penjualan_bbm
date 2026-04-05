<?php
$file = 'C:/xampp/htdocs/penjualan_bbm/public/Harian Maret 2026.xlsx';
$zip = new ZipArchive();
if ($zip->open($file) === TRUE) {
    $workbookXml = $zip->getFromName('xl/workbook.xml');
    $domW = new DOMDocument();
    $domW.loadXML($workbookXml);
    $sheets = $domW->getElementsByTagName('sheet');
    foreach ($sheets as $sheet) {
        echo $sheet->getAttribute('name') . " (id: " . $sheet->getAttribute('sheetId') . ")\n";
    }
    $zip->close();
}
