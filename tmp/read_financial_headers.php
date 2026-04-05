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

function readHeaders($zip, $sheetName, $sharedStrings) {
    $workbookXml = $zip->getFromName('xl/workbook.xml');
    preg_match('/<sheet[^>]*name="' . $sheetName . '"[^>]*r:id="(.*?)"/i', $workbookXml, $m);
    if (!$m) return "Sheet $sheetName tidak ditemukan.\n";
    $rId = $m[1];

    $relsXml = $zip->getFromName('xl/_rels/workbook.xml.rels');
    preg_match('/<Relationship[^>]*Id="' . $rId . '"[^>]*Target="(.*?)"/i', $relsXml, $m);
    $target = 'xl/' . $m[1];

    $sheetXml = $zip->getFromName($target);
    preg_match('/<row[^>]*r="2"[^>]*>(.*?)<\/row>/is', $sheetXml, $match); // Row 2 is usually header
    if (!$match) {
        preg_match('/<row[^>]*r="1"[^>]*>(.*?)<\/row>/is', $sheetXml, $match);
    }
    $rowContent = $match[1];
    preg_match_all('/<c r="([A-Z]+)\d+"[^>]*>(.*?)<\/c>/i', $rowContent, $cells);
    
    $out = "Header $sheetName:\n";
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
        $out .= "[$col] $val  ";
    }
    return $out . "\n";
}

echo readHeaders($zip, 'Pengeluaran', $sharedStrings);
echo readHeaders($zip, 'Neraca', $sharedStrings);
$zip->close();
