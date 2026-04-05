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

$sheetsXml = $zip->getFromName('xl/worksheets/sheet2.xml'); // Stok is sheet2
preg_match('/<row[^>]*r="2"[^>]*>(.*?)<\/row>/is', $sheetsXml, $match);
$row2 = $match[1];
preg_match_all('/<c r="([A-Z]+)2"[^>]*t="s"[^>]*><v>(\d+)<\/v><\/c>/i', $row2, $cells);

echo "Header Sheet Stok (Baris 2):\n";
foreach ($cells[1] as $i => $col) {
    $val = $sharedStrings[(int)$cells[2][$i]] ?? "N/A";
    echo "Kolom $col: $val\n";
}
$zip->close();
