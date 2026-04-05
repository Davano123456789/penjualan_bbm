<?php
$dbh = new PDO('mysql:host=localhost;dbname=laporan_bbm', 'root', '');
$bulan = 3;
$tahun = 2026;
$products = [
    ['id' => 1, 'nama' => 'PERTAMAX'],
    ['id' => 3, 'nama' => 'DEX']
];

echo "Verification Losis March 2026:\n";
foreach ($products as $p) {
    $pid = $p['id'];
    echo "\n--- Product: " . $p['nama'] . " ---\n";
    
    // 1. Stok Awal
    $stmt = $dbh->prepare("SELECT stok_awal, tanggal FROM stok WHERE produk_id = :pid AND MONTH(tanggal) = :bulan AND YEAR(tanggal) = :tahun ORDER BY tanggal ASC LIMIT 1");
    $stmt->execute(['pid' => $pid, 'bulan' => $bulan, 'tahun' => $tahun]);
    $start = $stmt->fetch(PDO::FETCH_ASSOC);
    $stok_awal = $start['stok_awal'] ?? 0;
    echo "Stok Awal (First entry on " . ($start['tanggal'] ?? 'N/A') . "): " . $stok_awal . "\n";

    // 2. DO & Total Penjualan
    $stmt = $dbh->prepare("SELECT SUM(kiriman_masuk) as total_do, SUM(terjual) as total_jual FROM stok WHERE produk_id = :pid AND MONTH(tanggal) = :bulan AND YEAR(tanggal) = :tahun");
    $stmt->execute(['pid' => $pid, 'bulan' => $bulan, 'tahun' => $tahun]);
    $sums = $stmt->fetch(PDO::FETCH_ASSOC);
    $do = $sums['total_do'] ?? 0;
    $total_jual = $sums['total_jual'] ?? 0;
    echo "Total Kiriman Masuk: " . $do . "\n";
    echo "Total Terjual (Volume): " . $total_jual . "\n";

    // 3. Stok Akhir Fisik
    $stmt = $dbh->prepare("SELECT stok_akhir_fisik, tanggal FROM stok WHERE produk_id = :pid AND MONTH(tanggal) = :bulan AND YEAR(tanggal) = :tahun ORDER BY tanggal DESC LIMIT 1");
    $stmt->execute(['pid' => $pid, 'bulan' => $bulan, 'tahun' => $tahun]);
    $end = $stmt->fetch(PDO::FETCH_ASSOC);
    $stok_akhir = $end['stok_akhir_fisik'] ?? 0;
    echo "Stok Akhir Fisik (Last entry on " . ($end['tanggal'] ?? 'N/A') . "): " . $stok_akhir . "\n";

    // Calculation
    $beban = $stok_awal + $do;
    $real = $total_jual + $stok_akhir;
    $losis = $beban - $real;
    echo "Perhitungan: ($stok_awal + $do) - ($total_jual + $stok_akhir) = $losis Liter\n";
}
