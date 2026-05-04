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
            table { font-size: 10px; }
        }
        @page {
            size: auto;
            margin: 0;
        }
    </style>
</head>
<body class="p-8">
    <div class="print-container max-w-5xl mx-auto">
        <div class="flex items-center justify-between border-b-2 border-slate-900 pb-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold uppercase tracking-tight text-slate-900">Laporan Stok BBM</h1>
                <p class="text-sm text-slate-500">SPBU COCO - Rekapitulasi Stok Harian</p>
            </div>
            <div class="text-right">
                <p class="text-sm font-bold uppercase"><?= $data['bulan'] == 'all' ? 'Semua Bulan' : date('F', mktime(0,0,0,(int)$data['bulan'],1)); ?> <?= $data['tahun']; ?></p>
                <p class="text-[10px] text-slate-400">Dicetak pada: <?= date('d/m/Y H:i'); ?></p>
            </div>
        </div>

        <?php
        function renderPrintTable($rows, $produkLabel) {
            if (empty($rows)) return;
            $totalKiriman  = array_sum(array_column($rows, 'kiriman_masuk'));
            $totalTerjual  = array_sum(array_column($rows, 'terjual'));
            $totalSelisih  = array_sum(array_column($rows, 'selisih'));
        ?>
        <div class="mb-8">
            <h3 class="text-xs font-black uppercase tracking-widest text-slate-700 mb-3 border-l-4 border-slate-900 pl-3"><?= $produkLabel; ?></h3>
            <table class="w-full text-left text-[11px] border-collapse border border-slate-300">
                <thead>
                    <tr class="bg-slate-100 text-slate-700 uppercase font-black">
                        <th class="border border-slate-300 px-3 py-2">Tanggal</th>
                        <th class="border border-slate-300 px-2 py-2 text-right">Stok Awal</th>
                        <th class="border border-slate-300 px-2 py-2 text-right">Kiriman</th>
                        <th class="border border-slate-300 px-2 py-2 text-right">Tersedia</th>
                        <th class="border border-slate-300 px-2 py-2 text-right">Terjual</th>
                        <th class="border border-slate-300 px-2 py-2 text-right">Teori</th>
                        <th class="border border-slate-300 px-2 py-2 text-right">Fisik</th>
                        <th class="border border-slate-300 px-2 py-2 text-center">Selisih</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rows as $r): ?>
                    <tr>
                        <td class="border border-slate-300 px-3 py-1.5"><?= date('d/m/y', strtotime($r['tanggal'])); ?></td>
                        <td class="border border-slate-300 px-2 py-1.5 text-right"><?= number_format($r['stok_awal'], 0, ',', '.'); ?></td>
                        <td class="border border-slate-300 px-2 py-1.5 text-right font-bold"><?= $r['kiriman_masuk'] > 0 ? number_format($r['kiriman_masuk'], 0, ',', '.') : '—'; ?></td>
                        <td class="border border-slate-300 px-2 py-1.5 text-right"><?= number_format($r['total_tersedia'], 0, ',', '.'); ?></td>
                        <td class="border border-slate-300 px-2 py-1.5 text-right"><?= number_format($r['terjual'], 0, ',', '.'); ?></td>
                        <td class="border border-slate-300 px-2 py-1.5 text-right"><?= number_format($r['stok_akhir_teori'], 0, ',', '.'); ?></td>
                        <td class="border border-slate-300 px-2 py-1.5 text-right font-bold"><?= number_format($r['stok_akhir_fisik'], 0, ',', '.'); ?></td>
                        <td class="border border-slate-300 px-2 py-1.5 text-center font-bold">
                            <?= number_format($r['selisih'], 2, ',', '.'); ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot class="bg-slate-50 font-bold">
                    <tr>
                        <td class="border border-slate-300 px-3 py-2">TOTAL</td>
                        <td class="border border-slate-300 px-2 py-2"></td>
                        <td class="border border-slate-300 px-2 py-2 text-right"><?= number_format($totalKiriman, 0, ',', '.'); ?></td>
                        <td class="border border-slate-300 px-2 py-2"></td>
                        <td class="border border-slate-300 px-2 py-2 text-right"><?= number_format($totalTerjual, 0, ',', '.'); ?></td>
                        <td class="border border-slate-300 px-2 py-2"></td>
                        <td class="border border-slate-300 px-2 py-2"></td>
                        <td class="border border-slate-300 px-2 py-2 text-center">
                            <?= ($totalSelisih >= 0 ? '+' : ''); ?><?= number_format($totalSelisih, 2, ',', '.'); ?>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <?php } ?>

        <?php renderPrintTable($data['stok_pertamax'], 'Pertamax'); ?>
        <?php renderPrintTable($data['stok_dex'], 'Dex'); ?>

        <!-- Footer Info -->
        <div class="mt-8 pt-4 border-t border-slate-100 text-[10px] text-slate-400 flex justify-between">
            <p>Laporan Stok BBM - SPBU COCO</p>
            <p>Halaman 1 dari 1</p>
        </div>
    </div>

    <!-- Floating Action Button -->
    <div class="fixed bottom-8 right-8 no-print">
        <button onclick="window.print()" class="px-6 py-3 bg-slate-900 text-white rounded-2xl font-bold shadow-2xl hover:bg-slate-800 transition-all flex items-center gap-2">
            <i class="fas fa-print"></i> Print Laporan
        </button>
    </div>

    <script>
        // window.print();
    </script>
</body>
</html>
