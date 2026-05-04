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
<body class="p-8">
    <div class="print-container max-w-5xl mx-auto">
        <div class="flex items-center justify-between border-b-2 border-slate-900 pb-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold uppercase tracking-tight text-slate-900">Laporan Losis Stok BBM</h1>
                <p class="text-sm text-slate-500">SPBU COCO - Rekapitulasi Selisih Bulanan</p>
            </div>
            <div class="text-right">
                <p class="text-sm font-bold uppercase"><?= $data['bulan'] == 'all' ? 'Semua Bulan' : date('F', mktime(0, 0, 0, (int)$data['bulan'], 1)); ?> <?= $data['tahun']; ?></p>
                <p class="text-[10px] text-slate-400">Dicetak pada: <?= date('d/m/Y H:i'); ?></p>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4 mb-8">
            <?php foreach ($data['results'] as $r): ?>
                <div class="border border-slate-300 p-4 rounded-xl">
                    <h3 class="text-sm font-black text-slate-800 uppercase mb-2"><?= $r['nama_produk']; ?></h3>
                    <div class="flex justify-between items-end">
                        <div>
                            <p class="text-[9px] font-black text-slate-400 uppercase">Losis (Liter)</p>
                            <p class="text-xl font-black <?= ($r['losis'] >= 0) ? 'text-rose-600' : 'text-emerald-600'; ?>">
                                <?= number_format($r['losis'], 2, ',', '.'); ?> L
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-[9px] font-black text-slate-400 uppercase">Persentase</p>
                            <p class="text-xl font-black <?= ($r['losis'] >= 0) ? 'text-rose-600' : 'text-emerald-600'; ?>">
                                <?= number_format($r['persentase'], 2, ',', '.'); ?> %
                            </p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <?php foreach ($data['results'] as $r): ?>
            <div class="mb-8">
                <h3 class="text-xs font-black uppercase tracking-widest text-slate-700 mb-3 border-l-4 border-slate-900 pl-3">Detail <?= $r['nama_produk']; ?></h3>
                <table class="w-full text-left text-xs border-collapse border border-slate-300">
                    <tr class="bg-slate-50">
                        <th class="border border-slate-300 px-3 py-2 w-1/2">Komponen Perhitungan</th>
                        <th class="border border-slate-300 px-3 py-2 text-right">Volume (Liter)</th>
                    </tr>
                    <tr>
                        <td class="border border-slate-300 px-3 py-2 text-slate-600">Stok Awal (Awal Bulan)</td>
                        <td class="border border-slate-300 px-3 py-2 text-right"><?= number_format($r['stok_awal'], 0, ',', '.'); ?></td>
                    </tr>
                    <tr>
                        <td class="border border-slate-300 px-3 py-2 text-slate-600">Total DO (Penembusan)</td>
                        <td class="border border-slate-300 px-3 py-2 text-right">+ <?= number_format($r['do_penebusan'], 0, ',', '.'); ?></td>
                    </tr>
                    <tr class="font-bold bg-slate-50/50">
                        <td class="border border-slate-300 px-3 py-2 uppercase tracking-tighter">Total Stok Buku</td>
                        <td class="border border-slate-300 px-3 py-2 text-right text-blue-700"><?= number_format($r['jumlah_stok'], 0, ',', '.'); ?></td>
                    </tr>
                    <tr>
                        <td class="border border-slate-300 px-3 py-2 text-slate-600">Total Penjualan</td>
                        <td class="border border-slate-300 px-3 py-2 text-right"><?= number_format($r['total_penjualan'], 0, ',', '.'); ?></td>
                    </tr>
                    <tr>
                        <td class="border border-slate-300 px-3 py-2 text-slate-600">Stok Akhir Fisik (Terakhir)</td>
                        <td class="border border-slate-300 px-3 py-2 text-right">+ <?= number_format($r['stok_akhir'], 0, ',', '.'); ?></td>
                    </tr>
                    <tr class="font-bold bg-slate-50/50 text-emerald-700">
                        <td class="border border-slate-300 px-3 py-2 uppercase tracking-tighter">Total Stok Riil</td>
                        <td class="border border-slate-300 px-3 py-2 text-right"><?= number_format($r['penjualan_plus_akhir'], 0, ',', '.'); ?></td>
                    </tr>
                    <tr class="bg-slate-900 text-white font-black">
                        <td class="border border-slate-800 px-3 py-3 uppercase tracking-widest text-sm">Selisih (Losis)</td>
                        <td class="border border-slate-800 px-3 py-3 text-right text-sm <?= ($r['losis'] >= 0) ? 'text-rose-400' : 'text-emerald-400'; ?>">
                            <?= number_format($r['losis'], 2, ',', '.'); ?> L
                        </td>
                    </tr>
                </table>
            </div>
        <?php endforeach; ?>

        <!-- Footer Info -->
        <div class="mt-8 pt-4 border-t border-slate-100 text-[10px] text-slate-400 flex justify-between">
            <p>Laporan Losis Stok BBM - SPBU COCO</p>
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
