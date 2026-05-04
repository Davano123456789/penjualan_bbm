<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $data['judul']; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        @media print {
            .no-print { display: none; }
            @page { margin: 0; }
            body { 
                margin: 1.6cm; 
                font-size: 10pt; 
            }
            .print-table { width: 100%; border-collapse: collapse; }
            .print-table th, .print-table td { border: 1px solid #ddd; padding: 4px 8px; }
            .print-table th { background-color: #f8fafc !important; -webkit-print-color-adjust: exact; }
        }
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-white p-4">

    <!-- Print Header -->
    <div class="flex items-center justify-between mb-8 border-b-2 border-slate-200 pb-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 uppercase tracking-tight">Laporan Pengeluaran</h1>
            <p class="text-sm text-slate-500">
                Periode: <?= $data['bulan'] == 'all' ? 'Semua Bulan' : date('F', mktime(0,0,0,(int)$data['bulan'],1)); ?> <?= $data['tahun']; ?>
            </p>
        </div>
        <div class="text-right">
            <p class="text-sm font-bold text-slate-700">Dicetak pada:</p>
            <p class="text-xs text-slate-500"><?= date('d F Y H:i'); ?></p>
        </div>
    </div>

    <div class="no-print mb-6">
        <button onclick="window.print()" class="px-6 py-2 bg-blue-600 text-white rounded-lg font-bold hover:bg-blue-700 transition-all shadow-lg">
            <i class="fas fa-print mr-2"></i> Print Sekarang
        </button>
        <button onclick="window.close()" class="px-6 py-2 bg-slate-200 text-slate-700 rounded-lg font-bold hover:bg-slate-300 transition-all ml-2">
            Tutup
        </button>
    </div>

    <!-- Summary Section -->
    <div class="grid grid-cols-3 gap-4 mb-8">
        <div class="p-4 border border-slate-200 rounded-xl">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total Pengeluaran</p>
            <p class="text-lg font-black text-slate-800">Rp <?= number_format($data['total'], 0, ',', '.'); ?></p>
        </div>
        <?php 
        $cat_totals = [];
        foreach($data['summary_kategori'] as $sk) $cat_totals[$sk['kategori']] = $sk['total'];
        ?>
        <div class="p-4 border border-slate-200 rounded-xl">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Operasional</p>
            <p class="text-lg font-black text-slate-800">Rp <?= number_format($cat_totals['Operasional'] ?? 0, 0, ',', '.'); ?></p>
        </div>
        <div class="p-4 border border-slate-200 rounded-xl">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Curah / Suplier</p>
            <p class="text-lg font-black text-slate-800">Rp <?= number_format($cat_totals['Curah'] ?? 0, 0, ',', '.'); ?></p>
        </div>
    </div>

    <!-- Main Table -->
    <table class="w-full text-left text-xs border-collapse border border-slate-200 print-table">
        <thead>
            <tr class="bg-slate-50 border-b border-slate-200 uppercase font-bold text-slate-600">
                <th class="p-2 border border-slate-200 w-32">Tanggal</th>
                <th class="p-2 border border-slate-200 w-32">Kategori</th>
                <th class="p-2 border border-slate-200">Keterangan</th>
                <th class="p-2 border border-slate-200 text-right w-40">Jumlah (Rp)</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($data['pengeluaran'])): ?>
            <tr>
                <td colspan="4" class="p-4 text-center text-slate-400 italic border border-slate-200">Belum ada data pengeluaran.</td>
            </tr>
            <?php else: ?>
            <?php foreach ($data['pengeluaran'] as $r): ?>
            <tr>
                <td class="p-2 border border-slate-200"><?= date('d/m/Y', strtotime($r['tanggal'])); ?></td>
                <td class="p-2 border border-slate-200 font-bold"><?= $r['kategori']; ?></td>
                <td class="p-2 border border-slate-200 text-slate-600"><?= $r['keterangan']; ?></td>
                <td class="p-2 border border-slate-200 text-right font-bold"><?= number_format($r['jumlah'], 0, ',', '.'); ?></td>
            </tr>
            <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
        <tfoot class="bg-slate-50 font-bold text-slate-800">
            <tr>
                <td colspan="3" class="p-2 border border-slate-200 text-right">TOTAL PENGELUARAN</td>
                <td class="p-2 border border-slate-200 text-right">Rp <?= number_format($data['total'], 0, ',', '.'); ?></td>
            </tr>
        </tfoot>
    </table>

    <script>
        // window.onload = () => { window.print(); }
    </script>
</body>
</html>
