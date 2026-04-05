<div class="space-y-6 pb-20">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Detail Stok BBM</h2>
            <p class="text-sm text-slate-500">Melihat rekapan stok harian yang telah tersimpan. (Mode Baca Saja)</p>
        </div>
        <a href="<?= BASEURL; ?>/stok" class="px-4 py-2 bg-slate-100 text-slate-600 rounded-xl text-sm font-bold hover:bg-slate-200 transition-all">
            <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar
        </a>
    </div>

    <!-- Tanggal -->
    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Tanggal Laporan</label>
        <input type="date" value="<?= $data['tanggal']; ?>" disabled
            class="w-60 px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold opacity-70 cursor-not-allowed">
    </div>

    <?php
    $groups = [
        [
            'label'     => 'Pertamax',
            'prefix'    => 'stok_p',
            'icon_color'=> 'bg-blue-600',
            'border'    => 'border-blue-100',
            'data'      => $data['stok_p']
        ],
        [
            'label'     => 'Dex',
            'prefix'    => 'stok_d',
            'icon_color'=> 'bg-emerald-600',
            'border'    => 'border-emerald-100',
            'data'      => $data['stok_d']
        ],
    ];

    foreach ($groups as $g):
        $row = $g['data'];
    ?>
    <!-- CARD: <?= $g['label']; ?> -->
    <div class="bg-white rounded-3xl border <?= $g['border']; ?> shadow-sm overflow-hidden">
        <!-- Header -->
        <div class="px-8 py-4 <?= $g['icon_color']; ?> flex items-center gap-3">
            <i class="fas fa-gas-pump text-white/80"></i>
            <h3 class="text-sm font-black text-white uppercase tracking-widest">⛽ <?= $g['label']; ?></h3>
        </div>

        <div class="p-8">
            <?php if (!$row): ?>
                <div class="py-4 text-center text-slate-400 italic text-sm">
                    Data stok <?= $g['label']; ?> tidak diinput untuk tanggal ini.
                </div>
            <?php else: ?>
                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-4 items-end">
                    <!-- 1. Stok Awal -->
                    <div class="lg:col-span-1">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Stok Awal (L)</label>
                        <input type="text" value="<?= number_format($row['stok_awal'], 2, ',', '.'); ?>" disabled
                            class="w-full px-3 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm text-right font-bold opacity-70 cursor-not-allowed">
                    </div>

                    <!-- 2. Kiriman Masuk -->
                    <div class="lg:col-span-1">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Kiriman Masuk (L)</label>
                        <input type="text" value="<?= number_format($row['kiriman_masuk'], 2, ',', '.'); ?>" disabled
                            class="w-full px-3 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm text-right font-bold opacity-70 cursor-not-allowed">
                    </div>

                    <!-- 3. Total Tersedia -->
                    <div class="lg:col-span-1">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Total Tersedia (L)</label>
                        <input type="text" value="<?= number_format($row['total_tersedia'], 2, ',', '.'); ?>" disabled
                            class="w-full px-3 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm text-right font-bold opacity-70 cursor-not-allowed">
                    </div>

                    <!-- 4. Terjual -->
                    <div class="lg:col-span-1">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Terjual (L)</label>
                        <input type="text" value="<?= number_format($row['terjual'], 2, ',', '.'); ?>" disabled
                            class="w-full px-3 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm text-right font-bold opacity-70 cursor-not-allowed">
                    </div>

                    <!-- 5. Stok Akhir Teori -->
                    <div class="lg:col-span-1">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Stok Teori (L)</label>
                        <input type="text" value="<?= number_format($row['stok_akhir_teori'], 2, ',', '.'); ?>" disabled
                            class="w-full px-3 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm text-right font-bold opacity-70 cursor-not-allowed">
                    </div>

                    <!-- 6. Stok Akhir Fisik -->
                    <div class="lg:col-span-1">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Stok Fisik (L)</label>
                        <input type="text" value="<?= number_format($row['stok_akhir_fisik'], 2, ',', '.'); ?>" disabled
                            class="w-full px-3 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm text-right font-black opacity-70 cursor-not-allowed">
                    </div>

                    <!-- 7. Selisih -->
                    <div class="lg:col-span-1">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Selisih (L)</label>
                        <?php $sel = $row['selisih']; ?>
                        <input type="text" value="<?= ($sel > 0 ? '+' : '') . number_format($sel, 2, ',', '.'); ?>" disabled
                            class="w-full px-3 py-3 bg-white border-2 <?= $sel < 0 ? 'border-rose-200 text-rose-600' : ($sel > 0 ? 'border-blue-200 text-blue-600' : 'border-emerald-200 text-emerald-600'); ?> rounded-xl text-sm text-right font-black opacity-70 cursor-not-allowed">
                    </div>
                </div>

                <!-- Catatan & Delivery Details -->
                <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6 pt-6 border-t border-slate-50 text-slate-600">
                    <div class="md:col-span-1">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Jam Kiriman</label>
                        <div class="px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-slate-700 italic">
                            <?= $row['jadwal'] ?: '-'; ?>
                        </div>
                    </div>
                    <div class="md:col-span-1">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Nama Supir</label>
                        <div class="px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium italic">
                            <?= $row['nama_supir'] ?: '-'; ?>
                        </div>
                    </div>
                    <div class="md:col-span-1">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Catatan</label>
                        <div class="px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm italic">
                            <?= $row['catatan'] ?: '-'; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <?php endforeach; ?>
</div>
