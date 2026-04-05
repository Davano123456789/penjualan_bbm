<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between flex-wrap gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Laporan Stok BBM</h2>
            <p class="text-sm text-slate-500">Rekap stok harian Pertamax & Dex – otomatis dari data Harian.</p>
        </div>
        <div class="flex items-center gap-3">
            <!-- Month/Year Filter -->
            <form method="GET" class="flex items-center gap-2">
                <select name="bulan" class="px-3 py-2 bg-white border border-slate-200 rounded-xl text-sm font-bold text-slate-600 focus:ring-2 focus:ring-blue-500">
                    <option value="all" <?= $data['bulan'] == 'all' ? 'selected' : ''; ?>>Semua Bulan</option>
                    <?php for ($m = 1; $m <= 12; $m++): ?>
                    <option value="<?= str_pad($m, 2, '0', STR_PAD_LEFT); ?>" <?= $data['bulan'] == str_pad($m, 2, '0', STR_PAD_LEFT) ? 'selected' : ''; ?>>
                        <?= date('F', mktime(0,0,0,$m,1)); ?>
                    </option>
                    <?php endfor; ?>
                </select>
                <input type="number" name="tahun" value="<?= $data['tahun']; ?>" min="2020" max="2099" class="w-24 px-3 py-2 bg-white border border-slate-200 rounded-xl text-sm font-bold text-slate-600 focus:ring-2 focus:ring-blue-500">
                <button type="submit" class="px-4 py-2 bg-slate-700 text-white rounded-xl text-sm font-bold hover:bg-slate-800 transition-all">
                    <i class="fas fa-filter mr-1"></i> Filter
                </button>
            </form>
            <a href="<?= BASEURL; ?>/stok/input" class="px-5 py-2 bg-blue-600 text-white rounded-xl font-bold hover:bg-blue-700 shadow-lg shadow-blue-500/30 transition-all flex items-center gap-2">
                <i class="fas fa-plus"></i> Input Stok
            </a>
        </div>
    </div>

    <?php
    // Helper function to render a stok table
    function renderStokTable($rows, $produkLabel, $color = 'blue') {
        $colorMap = [
            'blue'   => ['header' => 'bg-blue-600',  'badge' => 'bg-blue-100 text-blue-800',  'border' => 'border-blue-200'],
            'emerald'=> ['header' => 'bg-emerald-600','badge' => 'bg-emerald-100 text-emerald-800','border' => 'border-emerald-200'],
        ];
        $c = $colorMap[$color] ?? $colorMap['blue'];

        $totalKiriman  = array_sum(array_column($rows, 'kiriman_masuk'));
        $totalTerjual  = array_sum(array_column($rows, 'terjual'));
        $totalSelisih  = array_sum(array_column($rows, 'selisih'));
    ?>
    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
        <!-- Table Header -->
        <div class="px-8 py-4 <?= $c['header']; ?> flex items-center justify-between">
            <div class="flex items-center gap-3">
                <i class="fas fa-layer-group text-white/80"></i>
                <h3 class="text-sm font-black text-white uppercase tracking-widest"><?= $produkLabel; ?></h3>
            </div>
            <div class="flex items-center gap-4 text-white/80 text-xs font-bold">
                <span>Kiriman: <?= number_format($totalKiriman, 0, ',', '.'); ?> L</span>
                <span>|</span>
                <span>Terjual: <?= number_format($totalTerjual, 0, ',', '.'); ?> L</span>
                <span>|</span>
                <span class="<?= $totalSelisih < 0 ? 'text-rose-300' : 'text-emerald-300'; ?>">
                    Selisih: <?= ($totalSelisih >= 0 ? '+' : ''); ?><?= number_format($totalSelisih, 2, ',', '.'); ?> L
                </span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-slate-400 text-[10px] uppercase font-black tracking-widest border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-3">Tanggal</th>
                        <th class="px-4 py-3 text-right">Stok Awal (L)</th>
                        <th class="px-4 py-3 text-right bg-blue-50/50">Kiriman Masuk (L)</th>
                        <th class="px-4 py-3 text-right">Total Tersedia (L)</th>
                        <th class="px-4 py-3 text-right bg-amber-50/50">Terjual (L)</th>
                        <th class="px-4 py-3 text-right">Stok Teori (L)</th>
                        <th class="px-4 py-3 text-right">Stok Fisik (L)</th>
                        <th class="px-4 py-3 text-center">Selisih</th>
                        <th class="px-4 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <?php if (empty($rows)): ?>
                    <tr>
                        <td colspan="9" class="px-8 py-16 text-center text-slate-400">
                            <i class="fas fa-box-open text-4xl opacity-20 mb-3 block"></i>
                            Belum ada data stok untuk bulan ini.
                        </td>
                    </tr>
                    <?php else: ?>
                    <?php foreach ($rows as $r): ?>
                    <?php $selisih = floatval($r['selisih']); ?>
                    <tr class="hover:bg-slate-50/70 transition-all">
                        <td class="px-6 py-3">
                            <div class="font-bold text-slate-700"><?= date('d M Y', strtotime($r['tanggal'])); ?></div>
                            <?php if ($r['jadwal'] || $r['nama_supir']): ?>
                                <div class="text-[9px] text-slate-400 flex items-center gap-1 mt-0.5" title="Supir: <?= $r['nama_supir']; ?> | Jadwal: <?= $r['jadwal']; ?>">
                                    <i class="fas fa-truck-moving text-[8px]"></i>
                                    <span><?= $r['nama_supir'] ?: '?'; ?> (<?= $r['jadwal'] ?: '-'; ?>)</span>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td class="px-4 py-3 text-right text-slate-600 font-medium"><?= number_format($r['stok_awal'], 2, ',', '.'); ?></td>
                        <td class="px-4 py-3 text-right bg-blue-50/30 font-bold text-blue-700">
                            <?= $r['kiriman_masuk'] > 0 ? '<span class="px-2 py-0.5 bg-blue-100 rounded-lg">' . number_format($r['kiriman_masuk'], 0, ',', '.') . '</span>' : '<span class="text-slate-300">—</span>'; ?>
                        </td>
                        <td class="px-4 py-3 text-right text-slate-600 font-medium"><?= number_format($r['total_tersedia'], 2, ',', '.'); ?></td>
                        <td class="px-4 py-3 text-right bg-amber-50/30 font-bold text-amber-700"><?= number_format($r['terjual'], 2, ',', '.'); ?></td>
                        <td class="px-4 py-3 text-right text-slate-600"><?= number_format($r['stok_akhir_teori'], 2, ',', '.'); ?></td>
                        <td class="px-4 py-3 text-right font-black text-slate-800"><?= number_format($r['stok_akhir_fisik'], 2, ',', '.'); ?></td>
                        <td class="px-4 py-3 text-center">
                            <?php if ($selisih == 0): ?>
                                <span class="px-2 py-1 bg-emerald-100 text-emerald-700 rounded-lg text-xs font-black">± 0</span>
                            <?php elseif ($selisih < 0): ?>
                                <span class="px-2 py-1 bg-rose-100 text-rose-700 rounded-lg text-xs font-black"><?= number_format($selisih, 2, ',', '.'); ?> L</span>
                            <?php else: ?>
                                <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded-lg text-xs font-black">+<?= number_format($selisih, 2, ',', '.'); ?> L</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-4 py-3 text-center flex items-center justify-center gap-2">
                            <a href="<?= BASEURL; ?>/stok/detail/<?= $r['id']; ?>" class="w-7 h-7 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600 hover:bg-blue-600 hover:text-white transition-all shadow-sm">
                                <i class="fas fa-eye text-[10px]"></i>
                            </a>
                            <a href="<?= BASEURL; ?>/stok/edit/<?= $r['id']; ?>" class="w-7 h-7 rounded-lg bg-amber-50 flex items-center justify-center text-amber-600 hover:bg-amber-600 hover:text-white transition-all shadow-sm">
                                <i class="fas fa-pen text-[10px]"></i>
                            </a>
                            <form action="<?= BASEURL; ?>/stok/hapus/<?= $r['id']; ?>" method="POST" class="form-hapus-stok inline-block">
                                <button type="button" class="btn-hapus-stok w-7 h-7 rounded-lg bg-slate-50 flex items-center justify-center text-slate-400 hover:bg-rose-500 hover:text-white transition-all shadow-sm">
                                    <i class="fas fa-trash text-[10px]"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
                <?php if (!empty($rows)): ?>
                <tfoot class="bg-slate-50/50 border-t-2 border-slate-100 font-black text-slate-800">
                    <tr>
                        <td class="px-6 py-4">TOTAL</td>
                        <td class="px-4 py-4"></td>
                        <td class="px-4 py-4 text-right text-blue-700"><?= number_format($totalKiriman, 0, ',', '.'); ?> L</td>
                        <td class="px-4 py-4"></td>
                        <td class="px-4 py-4 text-right text-amber-700"><?= number_format($totalTerjual, 2, ',', '.'); ?> L</td>
                        <td class="px-4 py-4"></td>
                        <td class="px-4 py-4"></td>
                        <td class="px-4 py-4 text-center">
                            <?php if ($totalSelisih == 0): ?>
                                <span class="text-emerald-600">± 0</span>
                            <?php elseif ($totalSelisih < 0): ?>
                                <span class="text-rose-600"><?= number_format($totalSelisih, 2, ',', '.'); ?> L</span>
                            <?php else: ?>
                                <span class="text-blue-600">+<?= number_format($totalSelisih, 2, ',', '.'); ?> L</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-4 py-4"></td>
                    </tr>
                </tfoot>
                <?php endif; ?>
            </table>
        </div>
    </div>
    <?php } ?>

    <!-- PERTAMAX TABLE -->
    <?php renderStokTable($data['stok_pertamax'], '⛽ Pertamax', 'blue'); ?>

    <!-- DEX TABLE -->
    <?php renderStokTable($data['stok_dex'], '⛽ Dex', 'emerald'); ?>
</div>

<script>
document.querySelectorAll('.btn-hapus-stok').forEach(btn => {
    btn.addEventListener('click', function() {
        const form = this.closest('.form-hapus-stok');
        Swal.fire({
            title: 'Hapus Data Stok?',
            text: 'Data stok harian ini akan dihapus permanen!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#94a3b8',
            confirmButtonText: '<i class="fas fa-trash"></i> Hapus!',
            cancelButtonText: 'Batal'
        }).then(result => {
            if (result.isConfirmed) form.submit();
        });
    });
});
</script>
