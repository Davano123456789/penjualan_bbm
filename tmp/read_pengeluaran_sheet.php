<?php
require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

$file = 'laporan.xlsx';
$spreadsheet = IOFactory::load($file);
$sheet = $spreadsheet->getSheetByName('Pengeluaran');
$data = $sheet->toArray();

echo "Data Sheet Pengeluaran (5 baris pertama):\n";
for ($i = 0; $i < 5; $i++) {
    if (isset($data[$i])) {
        echo "Baris " . ($i + 1) . ": ";
        foreach ($data[$i] as $k => $v) {
            if ($v !== null) {
                echo "[" . chr(65 + $k) . "] $v  ";
            }
        }
        echo "\n";
    }
}
