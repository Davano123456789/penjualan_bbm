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
    <div class="print-container max-w-5xl mx-auto">
        <div class="flex items-center justify-between border-b-2 border-slate-900 pb-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold uppercase tracking-tight text-slate-900">Laporan EDC (QRIS & Debit)</h1>
                <p class="text-sm text-slate-500">SPBU COCO - Rekapitulasi Transaksi Non-Tunai & MDR</p>
            </div>
            <div class="text-right">
                <p class="text-sm font-bold uppercase"><?= $data['bulan'] == 'all' ? 'Semua Bulan' : date('F', mktime(0,0,0,(int)$data['bulan'],1)); ?> <?= $data['tahun']; ?></p>
                <p class="text-[10px] text-slate-400">Dicetak pada: <?= date('d/m/Y H:i'); ?></p>
            </div>
        </div>

        <?php
        $total_nominal = array_sum(array_column($data['edc'], 'nominal'));
        $total_potongan = array_sum(array_column($data['edc'], 'jumlah_potongan'));
        $total_masuk = array_sum(array_column($data['edc'], 'jumlah_masuk'));
        ?>

        <div class="grid grid-cols-3 gap-4 mb-8">
            <div class="border border-slate-200 p-4 rounded-xl">
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Gross</p>
                <p class="text-lg font-black text-slate-800">Rp <?= number_format($total_nominal, 0, ',', '.'); ?></p>
            </div>
            <div class="border border-slate-200 p-4 rounded-xl">
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Total MDR (Potongan)</p>
                <p class="text-lg font-black text-rose-600">Rp <?= number_format($total_potongan, 0, ',', '.'); ?></p>
            </div>
            <div class="border border-slate-900 p-4 rounded-xl bg-slate-900 text-white">
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Net (Masuk)</p>
                <p class="text-lg font-black text-emerald-400">Rp <?= number_format($total_masuk, 0, ',', '.'); ?></p>
            </div>
        </div>

        <table class="w-full text-left text-[10px] border-collapse border border-slate-300">
            <thead>
                <tr class="bg-slate-100 font-bold uppercase">
                    <th class="border border-slate-300 px-3 py-2">Tanggal</th>
                    <th class="border border-slate-300 px-3 py-2">Metode</th>
                    <th class="border border-slate-300 px-3 py-2">Keterangan</th>
                    <th class="border border-slate-300 px-3 py-2 text-right">Nominal</th>
                    <th class="border border-slate-300 px-3 py-2 text-center">%</th>
                    <th class="border border-slate-300 px-3 py-2 text-right text-rose-600">Potongan</th>
                    <th class="border border-slate-300 px-3 py-2 text-right text-emerald-600">Masuk</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data['edc'] as $e): ?>
                <tr>
                    <td class="border border-slate-300 px-3 py-1.5 font-bold"><?= date('d/m/y', strtotime($e['tanggal'])); ?></td>
                    <td class="border border-slate-300 px-3 py-1.5 uppercase"><?= $e['metode']; ?></td>
                    <td class="border border-slate-300 px-3 py-1.5 text-slate-500"><?= $e['keterangan']; ?></td>
                    <td class="border border-slate-300 px-3 py-1.5 text-right font-bold">Rp <?= number_format($e['nominal'], 0, ',', '.'); ?></td>
                    <td class="border border-slate-300 px-3 py-1.5 text-center"><?= number_format($e['persen_potongan'], 1); ?>%</td>
                    <td class="border border-slate-300 px-3 py-1.5 text-right text-rose-600 font-bold">Rp <?= number_format($e['jumlah_potongan'], 0, ',', '.'); ?></td>
                    <td class="border border-slate-300 px-3 py-1.5 text-right text-emerald-700 font-black">Rp <?= number_format($e['jumlah_masuk'], 0, ',', '.'); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot class="bg-slate-50 font-black">
                <tr>
                    <td colspan="3" class="border border-slate-300 px-3 py-2 uppercase tracking-tighter">Total Keseluruhan</td>
                    <td class="border border-slate-300 px-3 py-2 text-right">Rp <?= number_format($total_nominal, 0, ',', '.'); ?></td>
                    <td class="border border-slate-300 px-3 py-2"></td>
                    <td class="border border-slate-300 px-3 py-2 text-right text-rose-600">Rp <?= number_format($total_potongan, 0, ',', '.'); ?></td>
                    <td class="border border-slate-300 px-3 py-2 text-right text-emerald-700">Rp <?= number_format($total_masuk, 0, ',', '.'); ?></td>
                </tr>
            </tfoot>
        </table>

        <div class="mt-8 pt-4 border-t border-slate-100 text-[10px] text-slate-400 flex justify-between">
            <p>Laporan Rekapitulasi EDC - SPBU COCO</p>
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
