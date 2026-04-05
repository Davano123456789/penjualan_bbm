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

function dumpSheet($zip, $sheetName, $sharedStrings, $maxRows = 10) {
    $workbookXml = $zip->getFromName('xl/workbook.xml');
    preg_match('/<sheet[^>]*name="' . $sheetName . '"[^>]*r:id="(.*?)"/i', $workbookXml, $m);
    if (!$m) return "Sheet $sheetName tidak ditemukan.\n";
    $rId = $m[1];

    $relsXml = $zip->getFromName('xl/_rels/workbook.xml.rels');
    preg_match('/<Relationship[^>]*Id="' . $rId . '"[^>]*Target="(.*?)"/i', $relsXml, $m);
    $target = 'xl/' . $m[1];

    $sheetXml = $zip->getFromName($target);
    preg_match_all('/<row[^>]*r="(\d+)"[^>]*>(.*?)<\/row>/is', $sheetXml, $rows);
    
    $out = "Dumping Sheet $sheetName:\n";
    for ($i = 0; $i < min($maxRows, count($rows[0])); $i++) {
        $rowNum = $rows[1][$i];
        $rowContent = $rows[2][$i];
        $out .= "Row $rowNum: ";
        preg_match_all('/<c r="([A-Z]+)\d+"[^>]*>(.*?)<\/c>/i', $rowContent, $cells);
        foreach ($cells[1] as $j => $col) {
            $cellVal = $cells[2][$j];
            $val = "---";
            if (strpos($cells[0][$j], 't="s"') !== false) {
                preg_match('/<v>(\d+)<\/v>/i', $cellVal, $vMatch);
                $val = $sharedStrings[(int)$vMatch[1]] ?? "N/A";
            } else {
                preg_match('/<v>(.*?)<\/v>/i', $cellVal, $vMatch);
                $val = $vMatch[1] ?? "N/A";
            }
            $out .= "[$col] $val  ";
        }
        $out .= "\n";
    }
    return $out . "\n";
}

echo dumpSheet($zip, 'Kas', $sharedStrings);
echo dumpSheet($zip, 'Pengeluaran', $sharedStrings);
echo dumpSheet($zip, 'Neraca', $sharedStrings);
$zip->close();
