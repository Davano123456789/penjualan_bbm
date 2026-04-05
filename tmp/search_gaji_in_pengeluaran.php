<?php
$file = 'C:/xampp/htdocs/penjualan_bbm/public/Harian Maret 2026.xlsx';
$zip = new ZipArchive();
if ($zip->open($file) === TRUE) {
    // Read Shared Strings
    $sharedStringsXml = $zip->getFromName('xl/sharedStrings.xml');
    $ss = [];
    if ($sharedStringsXml) {
        $domSS = new DOMDocument();
        $domSS->loadXML($sharedStringsXml);
        foreach ($domSS->getElementsByTagName('t') as $t) {
            $ss[] = $t->nodeValue;
        }
    }

    // Read Worksheet 4 (Pengeluaran)
    $sheetXml = $zip->getFromName('xl/worksheets/sheet4.xml');
    $domS = new DOMDocument();
    $domS->loadXML($sheetXml);
    $rows = $domS->getElementsByTagName('row');

    echo "Data Sheet 'Pengeluaran' (Mencari Gaji):\n";
    foreach ($rows as $row) {
        $rIdx = (int)$row->getAttribute('r');
        if ($rIdx > 50) break; // Read first 50 rows
        
        $rowOutput = "";
        $foundGaji = false;
        foreach ($row->getElementsByTagName('c') as $cell) {
            $val = $cell->getElementsByTagName('v')->item(0)?->nodeValue;
            $type = $cell->getAttribute('t');
            if ($type == 's' && isset($ss[$val])) {
                $val = $ss[$val];
            }
            if (stripos($val, 'gaji') !== false) $foundGaji = true;
            $rowOutput .= "[" . $cell->getAttribute('r') . "] $val  ";
        }
        
        if ($foundGaji || $rIdx < 5) { // Show header or if 'gaji' found
            echo "Row $rIdx: $rowOutput\n";
        }
    }
    $zip->close();
}
