<?php
$file = 'C:/xampp/htdocs/penjualan_bbm/public/Harian Maret 2026.xlsx';
$zip = new ZipArchive();
if ($zip->open($file) !== TRUE) { die("Gagal."); }

$sharedStringsXml = $zip->getFromName('xl/sharedStrings.xml');
$sharedStrings = [];
if ($sharedStringsXml) {
    preg_match_all('/<si>(.*?)<\/si>/is', $sharedStringsXml, $siMatches);
    foreach ($siMatches[1] as $si) {
        preg_match_all('/<t[^>]*>([^<]*)<\/t>/i', $si, $tMatches);
        $sharedStrings[] = implode('', $tMatches[1]);
    }
}

// Find path for sheet "Kas" (Sheet ID 3)
$workbookXml = $zip->getFromName('xl/workbook.xml');
preg_match('/<sheet[^>]*name="Kas"[^>]*r:id="(.*?)"/i', $workbookXml, $m);
$rId = $m[1];

$relsXml = $zip->getFromName('xl/_rels/workbook.xml.rels');
preg_match('/<Relationship[^>]*Id="' . $rId . '"[^>]*Target="(.*?)"/i', $relsXml, $m);
$target = 'xl/' . $m[1];

$sheetXml = $zip->getFromName($target);
// Read first few rows
preg_match_all('/<row[^>]*r="(\d+)"[^>]*>(.*?)<\/row>/is', $sheetXml, $rows);

echo "Data Sheet Kas (5 baris pertama):\n";
for ($i = 0; $i < min(5, count($rows[0])); $i++) {
    $rowNum = $rows[1][$i];
    $rowContent = $rows[2][$i];
    echo "Baris $rowNum: ";
    preg_match_all('/<c r="([A-Z]+)\d+"[^>]*>(.*?)<\/c>/i', $rowContent, $cells);
    foreach ($cells[1] as $j => $col) {
        $cellVal = $cells[2][$j];
        $val = "N/A";
        if (strpos($cells[0][$j], 't="s"') !== false) {
            preg_match('/<v>(\d+)<\/v>/i', $cellVal, $vMatch);
            $val = $sharedStrings[(int)$vMatch[1]] ?? "N/A";
        } else {
            preg_match('/<v>(.*?)<\/v>/i', $cellVal, $vMatch);
            $val = $vMatch[1] ?? "N/A";
        }
        echo "[$col] $val  ";
    }
    echo "\n";
}
$zip->close();
