<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Laporan Losis Stok BBM</h2>
            <p class="text-sm text-slate-500 font-medium">Rekapitulasi selisih buku vs fisik tangki (Bulanan)</p>
        </div>

        <div class="flex items-center gap-3">
            <form method="GET" class="flex items-center gap-2">
                <select name="bulan" class="px-3 py-2 bg-white border border-slate-200 rounded-xl text-sm font-bold text-slate-600">
                    <?php for ($m = 1; $m <= 12; $m++): ?>
                        <option value="<?= str_pad($m, 2, '0', STR_PAD_LEFT); ?>" <?= $data['bulan'] == str_pad($m, 2, '0', STR_PAD_LEFT) ? 'selected' : ''; ?>>
                            <?= date('F', mktime(0, 0, 0, $m, 1)); ?>
                        </option>
                    <?php endfor; ?>
                </select>
                <input type="number" name="tahun" value="<?= $data['tahun']; ?>" class="w-24 px-3 py-2 bg-white border border-slate-200 rounded-xl text-sm font-bold text-slate-600">
                <button type="submit" class="px-4 py-2 bg-slate-800 text-white rounded-xl text-sm font-bold hover:bg-slate-700 transition-all">
                    <i class="fas fa-filter"></i>
                </button>
            </form>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <?php foreach ($data['results'] as $r): ?>
            <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden relative group">
                <div class="p-6 flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-black text-slate-800 mb-1"><?= $r['nama_produk']; ?></h3>
                        <div class="flex items-center gap-2">
                            <span class="text-[10px] font-black uppercase px-2 py-0.5 <?= ($r['losis'] >= 0) ? 'bg-rose-50 text-rose-600' : 'bg-emerald-50 text-emerald-600'; ?> rounded-lg">
                                <?= ($r['losis'] >= 0) ? 'Loss' : 'Gain'; ?>
                            </span>
                            <span class="text-sm font-bold <?= ($r['losis'] >= 0) ? 'text-rose-500' : 'text-emerald-500'; ?>">
                                <?= number_format(abs($r['losis']), 2, ',', '.'); ?> L
                            </span>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Persentase</p>
                        <h3 class="text-2xl font-black <?= ($r['losis'] >= 0) ? 'text-rose-600' : 'text-emerald-600'; ?>">
                            <?= number_format($r['persentase'], 2, ',', '.'); ?> %
                        </h3>
                    </div>
                </div>

                <div class="px-6 pb-6 pt-2 border-t border-slate-50 grid grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Total Stok Buku</p>
                        <p class="text-xs font-bold text-slate-700"><?= number_format($r['jumlah_stok'], 0, ',', '.'); ?> L</p>
                    </div>
                    <div class="space-y-1 text-right">
                        <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Total Stok Riil</p>
                        <p class="text-xs font-bold text-slate-700"><?= number_format($r['penjualan_plus_akhir'], 0, ',', '.'); ?> L</p>
                    </div>
                </div>

                <!-- Progress Bar -->
                <div class="h-1.5 w-full bg-slate-50 overflow-hidden">
                    <?php
                    $limit = 0.5; // Normal SPBU limit is 0.5%
                    $abs_perc = abs($r['persentase']);
                    $bar_color = ($abs_perc > $limit) ? 'bg-rose-500' : 'bg-emerald-500';
                    ?>
                    <div class="<?= $bar_color; ?> h-full" style="width: <?= min(100, ($abs_perc / 1) * 100); ?>%"></div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Tables for Detailed View -->
    <?php foreach ($data['results'] as $r): ?>
        <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 bg-slate-50 border-b border-slate-100 flex justify-between items-center">
                <h3 class="text-sm font-black text-slate-700 uppercase tracking-widest">Detail Perhitungan <?= $r['nama_produk']; ?></h3>
                <span class="text-[10px] font-bold text-slate-400"><?= date('F', mktime(0, 0, 0, (int)$data['bulan'], 1)); ?> <?= $data['tahun']; ?></span>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    <!-- Inputs (Buku) -->
                    <div class="space-y-4">
                        <div class="flex flex-col p-4 bg-slate-50 rounded-2xl">
                            <span class="text-[9px] font-black text-slate-400 uppercase mb-2">Stok Awal (Awal Bulan)</span>
                            <span class="text-lg font-black text-slate-800"><?= number_format($r['stok_awal'], 0, ',', '.'); ?> L</span>
                        </div>
                        <div class="flex flex-col p-4 bg-slate-50 rounded-2xl">
                            <span class="text-[9px] font-black text-slate-400 uppercase mb-2">Total DO (Penembusan)</span>
                            <span class="text-lg font-black text-slate-800">+ <?= number_format($r['do_penebusan'], 0, ',', '.'); ?> L</span>
                        </div>
                        <div class="flex flex-col p-4 bg-blue-50 border border-blue-100 rounded-2xl relative">
                            <div class="absolute -top-2 left-4 px-2 py-0.5 bg-blue-600 text-white text-[8px] font-black rounded-lg uppercase">Total Stok DO (Penembusan)</div>
                            <span class="text-lg font-black text-blue-700"><?= number_format($r['jumlah_stok'], 0, ',', '.'); ?> L</span>
                        </div>
                    </div>

                    <!-- Sales & Physical (Riil) -->
                    <div class="space-y-4">
                        <div class="flex flex-col p-4 bg-slate-50 rounded-2xl">
                            <span class="text-[9px] font-black text-slate-400 uppercase mb-2">Total Penjualan</span>
                            <span class="text-lg font-black text-slate-800"><?= number_format($r['total_penjualan'], 0, ',', '.'); ?> L</span>
                        </div>
                        <div class="flex flex-col p-4 bg-slate-50 rounded-2xl">
                            <span class="text-[9px] font-black text-slate-400 uppercase mb-2">Stok Akhir Fisik (Terakhir)</span>
                            <span class="text-lg font-black text-slate-800">+ <?= number_format($r['stok_akhir'], 0, ',', '.'); ?> L</span>
                        </div>
                        <div class="flex flex-col p-4 bg-emerald-50 border border-emerald-100 rounded-2xl relative">
                            <div class="absolute -top-2 left-4 px-2 py-0.5 bg-emerald-600 text-white text-[8px] font-black rounded-lg uppercase">Total Stok Riil</div>
                            <span class="text-lg font-black text-emerald-700"><?= number_format($r['penjualan_plus_akhir'], 0, ',', '.'); ?> L</span>
                        </div>
                    </div>

                    <!-- Losis Summary -->
                    <div class="lg:col-span-2 flex flex-col justify-center bg-slate-900 rounded-[2rem] p-8 text-white relative overflow-hidden group">
                        <div class="absolute right-0 bottom-0 opacity-10 -mr-10 -mb-10 rotate-12 group-hover:scale-110 transition-all">
                            <i class="fas <?= ($r['losis'] >= 0) ? 'fa-chart-line' : 'fa-chart-area'; ?> text-[10rem]"></i>
                        </div>

                        <div class="relative z-10">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Ringkasan Losis</p>
                            <div class="space-y-6">
                                <div>
                                    <h4 class="text-slate-500 text-xs font-bold mb-1">Selisih Liter</h4>
                                    <h3 class="text-4xl font-black <?= ($r['losis'] >= 0) ? 'text-rose-400' : 'text-emerald-400'; ?>">
                                        <?= number_format($r['losis'], 2, ',', '.'); ?> L
                                    </h3>
                                    <p class="text-[10px] italic text-slate-500 mt-1">
                                        <?= ($r['losis'] >= 0) ? '* Stok fisik lebih kecil dari buku' : '* Stok fisik lebih besar dari buku'; ?>
                                    </p>
                                </div>

                                <div>
                                    <h4 class="text-slate-500 text-xs font-bold mb-1">Rasio Losis</h4>
                                    <h3 class="text-3xl font-black text-white">
                                        <?= number_format($r['persentase'], 2, ',', '.'); ?> %
                                    </h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>