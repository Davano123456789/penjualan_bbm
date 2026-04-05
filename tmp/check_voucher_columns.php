<?php
$file = 'C:/xampp/htdocs/penjualan_bbm/public/Harian Maret 2026.xlsx';
$zip = new ZipArchive();
if ($zip->open($file) === TRUE) {
    $sharedStringsXml = $zip->getFromName('xl/sharedStrings.xml');
    $ss = [];
    if ($sharedStringsXml) {
        $domSS = new DOMDocument();
        $domSS->loadXML($sharedStringsXml);
        foreach ($domSS->getElementsByTagName('t') as $t) {
            $ss[] = $t->nodeValue;
        }
    }

    $sheetXml = $zip->getFromName('xl/worksheets/sheet11.xml');
    if ($sheetXml) {
        $domS = new DOMDocument();
        $domS->loadXML($sheetXml);
        $rows = $domS->getElementsByTagName('row');

        echo "Data Sheet 'Voucher' (Checking all columns):\n";
        foreach ($rows as $row) {
            $rIdx = (int)$row->getAttribute('r');
            if ($rIdx < 3 || $rIdx > 20) continue; 
            
            echo "Row $rIdx: ";
            $cells = $row->getElementsByTagName('c');
            foreach ($cells as $cell) {
                $val = $cell->getElementsByTagName('v')->item(0)?->nodeValue;
                $type = $cell->getAttribute('t');
                if ($type == 's' && isset($ss[$val])) {
                    $val = $ss[$val];
                }
                echo "[" . $cell->getAttribute('r') . "] $val  ";
            }
            echo "\n";
        }
    }
    $zip->close();
}
