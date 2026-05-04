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
    <div class="print-container max-w-6xl mx-auto">
        <div class="flex items-center justify-between border-b-2 border-slate-900 pb-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold uppercase tracking-tight text-slate-900">Kesimpulan Laporan Bulanan</h1>
                <p class="text-sm text-slate-500">SPBU COCO - Rekapitulasi Penerimaan & Penjualan BBM</p>
            </div>
            <div class="text-right">
                <p class="text-sm font-bold uppercase"><?= date('F', mktime(0, 0, 0, (int)$data['bulan'], 1)); ?> <?= $data['tahun']; ?></p>
                <p class="text-[10px] text-slate-400">Dicetak pada: <?= date('d/m/Y H:i'); ?></p>
            </div>
        </div>

        <table class="w-full text-left text-[10px] border-collapse border border-slate-300">
            <thead>
                <tr class="bg-slate-100 font-bold uppercase">
                    <th class="border border-slate-300 px-3 py-2">Produk</th>
                    <th class="border border-slate-300 px-3 py-2 text-center">Stok Awal (L)</th>
                    <th class="border border-slate-300 px-3 py-2 text-center">Penerimaan (L)</th>
                    <th class="border border-slate-300 px-3 py-2">Nozzle</th>
                    <th class="border border-slate-300 px-3 py-2 text-right">Tot Awal</th>
                    <th class="border border-slate-300 px-3 py-2 text-right">Tot Akhir</th>
                    <th class="border border-slate-300 px-3 py-2 text-center">Jual (L)</th>
                    <th class="border border-slate-300 px-3 py-2 text-center">Stok Akhir (L)</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data['kesimpulan'] as $k): ?>
                    <tr>
                        <td rowspan="<?= count($k['nozzles']) + 1; ?>" class="border border-slate-300 px-3 py-2 font-black uppercase"><?= $k['produk']; ?></td>
                        <td rowspan="<?= count($k['nozzles']) + 1; ?>" class="border border-slate-300 px-3 py-2 text-center font-bold"><?= number_format($k['stok_awal'], 0, ',', '.'); ?></td>
                        <td rowspan="<?= count($k['nozzles']) + 1; ?>" class="border border-slate-300 px-3 py-2 text-center font-bold text-emerald-600">+ <?= number_format($k['penerimaan_bbm'], 0, ',', '.'); ?></td>
                        
                        <td class="border border-slate-300 px-3 py-1.5 italic"><?= $k['nozzles'][0]['label']; ?></td>
                        <td class="border border-slate-300 px-3 py-1.5 text-right"><?= number_format($k['nozzles'][0]['totalisator_awal'], 0, ',', '.'); ?></td>
                        <td class="border border-slate-300 px-3 py-1.5 text-right"><?= number_format($k['nozzles'][0]['totalisator_akhir'], 0, ',', '.'); ?></td>
                        <td class="border border-slate-300 px-3 py-1.5 text-center font-bold"><?= number_format($k['nozzles'][0]['total_penjualan'], 0, ',', '.'); ?></td>
                        
                        <td rowspan="<?= count($k['nozzles']) + 1; ?>" class="border border-slate-300 px-3 py-2 text-center font-black text-blue-700"><?= number_format($k['stok_akhir'], 0, ',', '.'); ?></td>
                    </tr>
                    <?php for ($i = 1; $i < count($k['nozzles']); $i++): ?>
                    <tr>
                        <td class="border border-slate-300 px-3 py-1.5 italic"><?= $k['nozzles'][$i]['label']; ?></td>
                        <td class="border border-slate-300 px-3 py-1.5 text-right"><?= number_format($k['nozzles'][$i]['totalisator_awal'], 0, ',', '.'); ?></td>
                        <td class="border border-slate-300 px-3 py-1.5 text-right"><?= number_format($k['nozzles'][$i]['totalisator_akhir'], 0, ',', '.'); ?></td>
                        <td class="border border-slate-300 px-3 py-1.5 text-center font-bold"><?= number_format($k['nozzles'][$i]['total_penjualan'], 0, ',', '.'); ?></td>
                    </tr>
                    <?php endfor; ?>
                    <tr class="bg-slate-50 font-black">
                        <td colspan="3" class="border border-slate-300 px-3 py-2 text-right uppercase text-[8px] tracking-widest text-slate-400">Total Penjualan <?= $k['produk']; ?></td>
                        <td class="border border-slate-300 px-3 py-2 text-center" colspan="1">
                            <?php
                            $total_jual = 0;
                            foreach ($k['nozzles'] as $n) $total_jual += $n['total_penjualan'];
                            echo number_format($total_jual, 0, ',', '.');
                            ?> L
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>



        <div class="mt-8 pt-4 border-t border-slate-100 text-[10px] text-slate-400 flex justify-between">
            <p>Laporan Kesimpulan Bulanan - SPBU COCO</p>
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
