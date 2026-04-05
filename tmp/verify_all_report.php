<?php
require_once 'config/config.php';
require_once 'app/core/Database.php';
require_once 'app/models/Pengeluaran_model.php';

$pengModel = new Pengeluaran_model();

echo "Testing 'all' months reporting...\n";

// 1. Get all expenses for the year (including my test ones)
$all = $pengModel->getAllPengeluaran('all', date('Y'));
echo "Total entries found: " . count($all) . "\n";

// 2. Get Total Sum for 'all'
$total = $pengModel->getTotalPengeluaranBulan('all', date('Y'));
echo "Total Sum: Rp " . number_format($total, 0, ',', '.') . "\n";

// 3. Get Summary by Category for 'all'
$summary = $pengModel->getSummaryByKategori('all', date('Y'));
echo "Summary by Category:\n";
foreach ($summary as $s) {
    echo "- " . $s['kategori'] . ": Rp " . number_format($s['total'], 0, ',', '.') . "\n";
}

// Check if my test entry from tomorrow is included (if I added one for tomorrow)
// Wait, my previous test used date('Y-m-d'). Let's add one specifically for next month.
require_once 'app/models/Kas_model.php';
$kasModel = new Kas_model();
$tomorrow = date('Y-m-d', strtotime('+1 day'));
echo "Adding an expense for tomorrow ($tomorrow)...\n";
$kasModel->tambahDataKas([
    'tanggal' => $tomorrow,
    'keterangan' => 'BIAYA BESOK (FUTURE TEST)',
    'tipe' => 'kredit',
    'jumlah' => 999000,
    'kategori' => 'arus',
    'kategori_biaya' => 'Operasional'
]);

$allUpdated = $pengModel->getAllPengeluaran('all', date('Y'));
$foundFuture = false;
foreach($allUpdated as $p) {
    if ($p['keterangan'] == 'BIAYA BESOK (FUTURE TEST)') {
        $foundFuture = true;
        break;
    }
}

if ($foundFuture) {
    echo "SUCCESS: Future expense is VISIBLE in 'all months' view.\n";
} else {
    echo "FAILURE: Future expense is still HIDDEN.\n";
}
