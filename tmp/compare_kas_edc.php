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

    // Function to read first few rows of a sheet
    function read_sheet($zip, $sheetName, $ss) {
        $sheetXml = $zip->getFromName("xl/worksheets/$sheetName.xml");
        $dom = new DOMDocument();
        $dom->loadXML($sheetXml);
        $rows = $dom->getElementsByTagName('row');
        $output = "";
        foreach ($rows as $row) {
            $rIdx = (int)$row->getAttribute('r');
            if ($rIdx > 30) break; // Read 30 rows
            $output .= "Row $rIdx: ";
            foreach ($row->getElementsByTagName('c') as $cell) {
                $val = $cell->getElementsByTagName('v')->item(0)?->nodeValue;
                $type = $cell->getAttribute('t');
                if ($type == 's' && isset($ss[$val])) $val = $ss[$val];
                $output .= "[" . $cell->getAttribute('r') . "] $val  ";
            }
            $output .= "\n";
        }
        return $output;
    }

    echo "--- DATA SHEET KAS (Sheet 3) ---\n";
    echo read_sheet($zip, 'sheet3', $ss);
    
    echo "\n--- DATA SHEET EDC (Sheet 7) ---\n";
    echo read_sheet($zip, 'sheet7', $ss);

    $zip->close();
}
