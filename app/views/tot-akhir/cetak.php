<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak - <?= $data['judul']; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: white; }
        @media print {
            .no-print { display: none; }
            body { padding: 0; margin: 1.5cm; }
            .print-container { width: 100%; max-width: none; border: none; shadow: none; }
        }
        @page {
            size: auto;
            margin: 0;
        }
    </style>
</head>
<body class="p-8 text-slate-900">
    <?php 
    $r = $data['report']; 
    $bulan_nama = date('F Y', mktime(0,0,0,$data['bulan'],1,$data['tahun']));
    ?>
    <div class="print-container max-w-5xl mx-auto">
        <div class="flex items-center justify-between border-b-2 border-slate-900 pb-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold uppercase tracking-tight text-slate-900">Laporan Laba Rugi Bulanan</h1>
                <p class="text-sm text-slate-500">SPBU COCO - Rekapitulasi TOT Akhir & Profitabilitas</p>
            </div>
            <div class="text-right">
                <p class="text-sm font-bold uppercase"><?= $bulan_nama; ?></p>
                <p class="text-[10px] text-slate-400">Dicetak pada: <?= date('d/m/Y H:i'); ?></p>
            </div>
        </div>

        <!-- Summary Section -->
        <div class="grid grid-cols-3 gap-4 mb-8">
            <div class="border border-slate-200 p-4 rounded-xl">
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Laba Kotor</p>
                <p class="text-lg font-black text-slate-800">Rp <?= number_format($r['total_laba_kotor'], 0, ',', '.'); ?></p>
            </div>
            <div class="border border-slate-200 p-4 rounded-xl">
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Beban & Losis</p>
                <p class="text-lg font-black text-slate-800">Rp <?= number_format($r['total_pengeluaran'], 0, ',', '.'); ?></p>
            </div>
            <div class="border border-slate-900 p-4 rounded-xl bg-slate-900 text-white">
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Laba Bersih</p>
                <p class="text-lg font-black <?= $r['laba_bersih'] >= 0 ? 'text-emerald-400' : 'text-rose-400'; ?>">
                    Rp <?= number_format(abs($r['laba_bersih']), 0, ',', '.'); ?>
                    <?= $r['laba_bersih'] < 0 ? ' (Rugi)' : ''; ?>
                </p>
            </div>
        </div>

        <!-- SECTION A: Detail Pendapatan Nozzle -->
        <div class="mb-8">
            <h3 class="text-xs font-black uppercase tracking-widest text-slate-700 mb-3 border-l-4 border-slate-900 pl-3">A. Pendapatan Laba Kotor (Margin Nozzle)</h3>
            <table class="w-full text-left text-[10px] border-collapse border border-slate-300">
                <thead>
                    <tr class="bg-slate-100 font-bold uppercase">
                        <th class="border border-slate-300 px-2 py-2">Nozzle</th>
                        <th class="border border-slate-300 px-2 py-2">Produk</th>
                        <th class="border border-slate-300 px-2 py-2 text-right">Tot Awal</th>
                        <th class="border border-slate-300 px-2 py-2 text-right">Tot Akhir</th>
                        <th class="border border-slate-300 px-2 py-2 text-right">Liter</th>
                        <th class="border border-slate-300 px-2 py-2 text-right">Margin</th>
                        <th class="border border-slate-300 px-2 py-2 text-right">Laba Kotor</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($r['detail_nozzle'] as $dn): ?>
                    <tr>
                        <td class="border border-slate-300 px-2 py-1.5 font-bold"><?= $dn['nozzle']; ?></td>
                        <td class="border border-slate-300 px-2 py-1.5"><?= $dn['produk']; ?></td>
                        <td class="border border-slate-300 px-2 py-1.5 text-right"><?= number_format($dn['tot_awal'], 2, ',', '.'); ?></td>
                        <td class="border border-slate-300 px-2 py-1.5 text-right"><?= number_format($dn['tot_akhir'], 2, ',', '.'); ?></td>
                        <td class="border border-slate-300 px-2 py-1.5 text-right font-bold"><?= number_format($dn['liter'], 2, ',', '.'); ?> L</td>
                        <td class="border border-slate-300 px-2 py-1.5 text-right">Rp <?= number_format($dn['margin'], 0, ',', '.'); ?></td>
                        <td class="border border-slate-300 px-2 py-1.5 text-right font-black">Rp <?= number_format($dn['laba'], 0, ',', '.'); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot class="bg-slate-50 font-black">
                    <tr>
                        <td colspan="4" class="border border-slate-300 px-2 py-2">TOTAL LABA KOTOR</td>
                        <td class="border border-slate-300 px-2 py-2 text-right"><?= number_format($r['terjual_pertamax'] + $r['terjual_dex'], 2, ',', '.'); ?> L</td>
                        <td class="border border-slate-300 px-2 py-2"></td>
                        <td class="border border-slate-300 px-2 py-2 text-right">Rp <?= number_format($r['total_laba_kotor'], 0, ',', '.'); ?></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- SECTION B: Detail Pengeluaran & Beban -->
        <div class="mb-8">
            <h3 class="text-xs font-black uppercase tracking-widest text-slate-700 mb-3 border-l-4 border-slate-900 pl-3">B. Beban Pengeluaran Operasional & Losis</h3>
            <table class="w-full text-left text-[11px] border-collapse border border-slate-300">
                <thead>
                    <tr class="bg-slate-100 font-bold uppercase text-[10px]">
                        <th class="border border-slate-300 px-3 py-2 w-2/3">Keterangan Beban</th>
                        <th class="border border-slate-300 px-3 py-2 text-right">Jumlah (Rp)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="border border-slate-300 px-3 py-2">1. Gaji Karyawan (Otomatis)</td>
                        <td class="border border-slate-300 px-3 py-2 text-right font-bold">Rp <?= number_format($r['biaya_gaji'], 0, ',', '.'); ?></td>
                    </tr>
                    <tr>
                        <td class="border border-slate-300 px-3 py-2">2. Pengeluaran Kas Operasional (Otomatis)</td>
                        <td class="border border-slate-300 px-3 py-2 text-right font-bold">Rp <?= number_format($r['biaya_kas'], 0, ',', '.'); ?></td>
                    </tr>
                    <tr>
                        <td class="border border-slate-300 px-3 py-2">3. PPH (Pajak)</td>
                        <td class="border border-slate-300 px-3 py-2 text-right font-bold">Rp <?= number_format($r['biaya_pph'], 0, ',', '.'); ?></td>
                    </tr>
                    <tr>
                        <td class="border border-slate-300 px-3 py-2">4. Admin EDC</td>
                        <td class="border border-slate-300 px-3 py-2 text-right font-bold">Rp <?= number_format($r['biaya_admin_edc'], 0, ',', '.'); ?></td>
                    </tr>
                    <tr>
                        <td class="border border-slate-300 px-3 py-2">5. Losis Pertamax</td>
                        <td class="border border-slate-300 px-3 py-2 text-right font-bold">Rp <?= number_format($r['losis_pertamax'], 0, ',', '.'); ?></td>
                    </tr>
                    <tr>
                        <td class="border border-slate-300 px-3 py-2">6. Losis Dex</td>
                        <td class="border border-slate-300 px-3 py-2 text-right font-bold">Rp <?= number_format($r['losis_dex'], 0, ',', '.'); ?></td>
                    </tr>
                </tbody>
                <tfoot class="bg-slate-900 text-white font-black uppercase text-xs tracking-tighter">
                    <tr>
                        <td class="border border-slate-800 px-3 py-3">Total Beban Operasional & Losis</td>
                        <td class="border border-slate-800 px-3 py-3 text-right">Rp <?= number_format($r['total_pengeluaran'], 0, ',', '.'); ?></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="mt-8 pt-4 border-t border-slate-100 text-[10px] text-slate-400 flex justify-between">
            <p>Laporan TOT Akhir Laba Rugi - SPBU COCO</p>
            <p>Halaman 1 dari 1</p>
        </div>
    </div>

    <!-- Floating Action Button -->
    <div class="fixed bottom-8 right-8 no-print">
        <button onclick="window.print()" class="px-6 py-3 bg-slate-900 text-white rounded-2xl font-bold shadow-2xl hover:bg-slate-800 transition-all flex items-center gap-2">
            <i class="fas fa-print"></i> Print Laporan
        </button>
    </div>
</body>
</html>
