<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Detail Laporan Harian</h2>
            <p class="text-sm text-slate-500">Melihat rekapan harian yang telah tersimpan. (Mode Baca Saja)</p>
        </div>
        <a href="<?= BASEURL; ?>/harian" class="px-4 py-2 bg-slate-100 text-slate-600 rounded-xl text-sm font-bold hover:bg-slate-200 transition-all">
            <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar
        </a>
    </div>

    <div class="space-y-8 pb-20">
        <?php $h = $data['harian']; ?>
        <!-- Date & Time & Operators Section -->
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Tanggal Laporan</label>
                <input type="date" value="<?= $h['tanggal']; ?>" disabled class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold opacity-70 cursor-not-allowed">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Operator 1 (Shift)</label>
                <input type="text" value="<?= $h['nama_op1'] ?: '-'; ?>" disabled class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold opacity-70 cursor-not-allowed">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Operator 2 (Shift)</label>
                <input type="text" value="<?= $h['nama_op2'] ?: '-'; ?>" disabled class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold opacity-70 cursor-not-allowed">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2 text-center">Masuk</label>
                    <input type="text" value="<?= $h['jam_masuk']; ?>" disabled class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm text-center font-bold opacity-70 cursor-not-allowed">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2 text-center">Keluar</label>
                    <input type="text" value="<?= $h['jam_keluar']; ?>" disabled class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm text-center font-bold opacity-70 cursor-not-allowed">
                </div>
            </div>
        </div>

        <!-- Meter Readings Section -->
        <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden mb-12">
            <div class="px-8 py-5 border-b border-slate-50 bg-slate-50/50 flex items-center gap-3">
                <i class="fas fa-gas-pump text-blue-500"></i>
                <h3 class="text-sm font-black text-slate-700 tracking-wide uppercase">Daftar Nozzle Berkelompok (Per Mesin)</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left" id="readingTable">
                    <thead class="bg-slate-50/50 text-slate-400 text-[10px] uppercase font-black tracking-widest border-b border-slate-100">
                        <tr>
                            <th class="px-6 py-4">Mesin</th>
                            <th class="px-6 py-4">Nozzle</th>
                            <th class="px-6 py-4">Produk</th>
                            <th class="px-6 py-4 text-center">Totalisator Awal</th>
                            <th class="px-6 py-4 text-center">Totalisator Akhir</th>
                            <th class="px-6 py-4 text-right">Terjual (L)</th>
                            <th class="px-6 py-4 text-center bg-blue-50/30">Total Liter</th>
                            <th class="px-6 py-4 text-center bg-blue-50/30">Harga</th>
                            <th class="px-6 py-4 text-right bg-blue-50/30">Jumlah Rupiah</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        <?php 
                        $penjData = [];
                        $grandTotalRupiah = 0;
                        $productSummaryData = [];

                        foreach ($data['penjualan'] as $p) {
                            $penjData[$p['nozzle']] = $p;
                            $grandTotalRupiah += $p['total_rupiah'];

                        // Group by fuel type: Pertamax 1+2 → PERTAMAX, Dex 1+2 → DEX
                            $groupKey = 'LAINNYA';
                            $groupLabel = $p['nama_produk'];
                            if (stripos($p['nama_produk'], 'pertamax') !== false) {
                                $groupKey = 'PERTAMAX';
                                $groupLabel = 'Pertamax';
                            } elseif (stripos($p['nama_produk'], 'dex') !== false) {
                                $groupKey = 'DEX';
                                $groupLabel = 'Dex';
                            }

                            if (!isset($productSummaryData[$groupKey])) {
                                $productSummaryData[$groupKey] = ['nama' => $groupLabel, 'liter' => 0, 'total' => 0];
                            }
                            $productSummaryData[$groupKey]['liter'] += $p['liter_terjual'];
                            $productSummaryData[$groupKey]['total'] += $p['total_rupiah'];
                        }

                        $machines = [
                            ['name' => 'MESIN 1', 'nozzles' => [1, 2, 3, 4]]
                        ];
                        
                        foreach ($machines as $mIdx => $m) : 
                            for ($i = 0; $i < count($m['nozzles']); $i += 2) :
                                $n1 = $m['nozzles'][$i];
                                $n2 = $m['nozzles'][$i+1];
                                $groupId = $n1 . "_" . $n2;

                                $p1 = isset($penjData[$n1]) ? $penjData[$n1] : null;
                                $p2 = isset($penjData[$n2]) ? $penjData[$n2] : null;

                                $literGroup = ($p1 ? $p1['liter_terjual'] : 0) + ($p2 ? $p2['liter_terjual'] : 0);
                                $rupiahGroup = ($p1 ? $p1['total_rupiah'] : 0) + ($p2 ? $p2['total_rupiah'] : 0);
                                $hargaGroup = $literGroup > 0 ? ($p1 ? $p1['harga_jual'] : ($p2 ? $p2['harga_jual'] : 0)) : 0;
                        ?>
                        <tr class="row-reading group" data-group="<?= $groupId; ?>">
                            <?php if ($i == 0) : ?>
                            <td rowspan="4" class="px-8 py-4 bg-slate-100/50 text-center border-r border-slate-100">
                                <span class="text-xs font-black text-slate-400 rotate-180 [writing-mode:vertical-lr] tracking-[0.5em]"><?= $m['name']; ?></span>
                            </td>
                            <?php endif; ?>
                            
                            <td class="px-6 py-3 font-bold text-slate-400 text-xs">#<?= $n1; ?></td>
                            <td class="px-6 py-3">
                                <input type="text" value="<?= $p1 ? $p1['nama_produk'] : '-'; ?>" disabled class="w-40 px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm font-bold opacity-70">
                            </td>
                            <td class="px-4 py-3">
                                <input type="text" value="<?= $p1 ? ($p1['totalisator_awal'] > 0 ? $p1['totalisator_awal'] : '-') : '-'; ?>" disabled class="w-24 px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm text-center font-bold opacity-70 cursor-not-allowed">
                            </td>
                            <td class="px-4 py-3">
                                <input type="text" value="<?= $p1 ? ($p1['totalisator_akhir'] > 0 ? $p1['totalisator_akhir'] : '-') : '-'; ?>" disabled class="w-24 px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm text-center font-bold opacity-70 cursor-not-allowed">
                            </td>
                            <td class="px-6 py-3">
                                <input type="text" disabled class="w-20 px-3 py-2 bg-slate-100 border border-transparent rounded-lg text-sm text-right font-bold text-slate-500" value="<?= $p1 ? $p1['liter_terjual'] : '0'; ?>">
                            </td>
                            
                            <!-- Spanning Cells -->
                            <td rowspan="2" class="px-6 py-3 bg-blue-50/10 border-l border-blue-50">
                                <input type="text" disabled class="w-20 px-3 py-3 bg-slate-50 border-2 border-slate-200 rounded-xl text-center text-sm font-black text-slate-500 cursor-not-allowed" value="<?= number_format($literGroup, 2); ?>">
                            </td>
                            <td rowspan="2" class="px-6 py-3 bg-blue-50/10">
                                <input type="text" disabled class="w-24 px-3 py-3 bg-slate-50 border border-slate-200 rounded-xl text-center text-sm font-bold text-slate-500 cursor-not-allowed" value="<?= number_format($hargaGroup, 0, ',', '.'); ?>">
                            </td>
                            <td rowspan="2" class="px-6 py-3 bg-blue-50/10 text-right">
                                <input type="text" disabled class="w-32 px-3 py-3 bg-slate-50 border-2 border-slate-200 rounded-xl text-right text-sm font-black text-slate-500 cursor-not-allowed" value="<?= number_format($rupiahGroup, 0, ',', '.'); ?>">
                            </td>
                        </tr>
                        <tr class="row-reading group border-b border-slate-100" data-group="<?= $groupId; ?>">
                            <td class="px-6 py-3 font-bold text-slate-400 text-xs">#<?= $n2; ?></td>
                            <td class="px-6 py-3">
                                <input type="text" value="<?= $p2 ? $p2['nama_produk'] : '-'; ?>" disabled class="w-40 px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm font-bold opacity-70">
                            </td>
                            <td class="px-4 py-3">
                                <input type="text" value="<?= $p2 ? ($p2['totalisator_awal'] > 0 ? $p2['totalisator_awal'] : '-') : '-'; ?>" disabled class="w-24 px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm text-center font-bold opacity-70 cursor-not-allowed">
                            </td>
                            <td class="px-4 py-3">
                                <input type="text" value="<?= $p2 ? ($p2['totalisator_akhir'] > 0 ? $p2['totalisator_akhir'] : '-') : '-'; ?>" disabled class="w-24 px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm text-center font-bold opacity-70 cursor-not-allowed">
                            </td>
                            <td class="px-6 py-3">
                                <input type="text" disabled class="w-20 px-3 py-2 bg-slate-100 border border-transparent rounded-lg text-sm text-right font-bold text-slate-500" value="<?= $p2 ? $p2['liter_terjual'] : '0'; ?>">
                            </td>
                        </tr>
                        <?php 
                            endfor;
                        endforeach; 
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Financial Summary Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- BOX 1: PENYEIMBANGAN -->
            <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm relative overflow-hidden group">
                <div class="absolute top-0 right-0 w-32 h-32 bg-blue-50/50 rounded-full -mr-16 -mt-16 group-hover:scale-110 transition-all"></div>
                <div class="flex items-center gap-3 mb-8 relative z-10">
                    <div class="w-8 h-8 bg-blue-600 text-white rounded-lg flex items-center justify-center shadow-lg shadow-blue-200">
                        <i class="fas fa-balance-scale text-xs"></i>
                    </div>
                    <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest">Penyeimbangan</h3>
                </div>
                
                <div class="space-y-5 relative z-10">
                    <div class="flex items-center justify-between border-b border-slate-50 pb-3">
                        <span class="text-sm font-bold text-slate-500 uppercase">1. Penjualan</span>
                        <span class="text-lg font-black text-slate-800 tracking-tighter">Rp <?= number_format($grandTotalRupiah, 0, ',', '.'); ?></span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-bold text-slate-500 uppercase">2. Titipan</span>
                        <input type="text" value="<?= number_format($h['total_titipan'], 0, ',', '.'); ?>" disabled class="w-44 px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm text-right font-bold opacity-70 cursor-not-allowed">
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-bold text-rose-500 uppercase">3. Pengeluaran</span>
                        <input type="text" value="<?= number_format($h['total_pengeluaran'], 0, ',', '.'); ?>" disabled class="w-44 px-4 py-3 bg-rose-50 border border-rose-100 rounded-xl text-sm text-right font-bold text-rose-600 opacity-70 cursor-not-allowed">
                    </div>
                    <div class="mt-8 pt-8 border-t-2 border-dashed border-slate-100 flex items-center justify-between bg-blue-50/30 -mx-8 px-8 py-5">
                        <div class="flex flex-col">
                            <span class="text-sm font-black text-blue-800 tracking-widest uppercase italic">SISA</span>
                            <span class="text-[9px] text-blue-500 font-bold uppercase tracking-tighter">(Target Setoran)</span>
                        </div>
                        <?php $targetSisa = $grandTotalRupiah - $h['total_titipan'] - $h['total_pengeluaran']; ?>
                        <input type="text" value="<?= number_format($targetSisa, 0, ',', '.'); ?>" disabled class="w-44 px-4 py-4 bg-white border-2 border-blue-500 rounded-2xl text-xl font-black text-blue-600 text-right shadow-sm cursor-not-allowed">
                    </div>
                </div>
            </div>

            <!-- BOX 2: STATUS AUDIT -->
            <?php 
                $actualCash = $h['total_penerimaan_kas'];
                $gap = $h['total_sisa']; // Gap is saved as total_sisa in DB based on input calculation logic
            ?>
            <div id="status_container" class="<?= $gap == 0 ? 'bg-emerald-50 border-emerald-100' : ($gap < 0 ? 'bg-rose-50 border-rose-100' : 'bg-blue-50 border-blue-100'); ?> p-8 rounded-3xl border-4 shadow-sm flex flex-col justify-center items-center text-center transition-all duration-500">
                <div class="w-20 h-20 rounded-2xl <?= $gap == 0 ? 'bg-emerald-500 shadow-emerald-200' : ($gap < 0 ? 'bg-rose-500 shadow-rose-200 rotate-6' : 'bg-blue-500 shadow-blue-200 -rotate-6'); ?> text-white flex items-center justify-center text-3xl mb-5 shadow-lg">
                    <i class="fas <?= $gap == 0 ? 'fa-check-circle' : ($gap < 0 ? 'fa-exclamation-triangle' : 'fa-plus-circle'); ?>"></i>
                </div>
                <h3 class="text-2xl font-black uppercase tracking-[0.2em] mb-2 <?= $gap == 0 ? 'text-emerald-800' : ($gap < 0 ? 'text-rose-800' : 'text-blue-800'); ?>">
                    <?= $gap == 0 ? 'FIX / MATCH! ✅' : ($gap < 0 ? 'MINUS / LOSIS ⚠️' : 'SURPLUS / LEBIH 💰'); ?>
                </h3>
                <div class="space-y-2">
                    <p class="text-[10px] font-black <?= $gap == 0 ? 'text-emerald-600/70' : ($gap < 0 ? 'text-rose-600/70' : 'text-blue-600/70'); ?> uppercase tracking-widest">Selisih Akhir (Purity Audit)</p>
                    <span class="text-4xl font-black <?= $gap == 0 ? 'text-emerald-800' : ($gap < 0 ? 'text-rose-800' : 'text-blue-800'); ?> tracking-tighter italic underline">Rp <?= number_format($gap, 0, ',', '.'); ?></span>
                </div>
                <div class="mt-6 pt-4 border-t <?= $gap == 0 ? 'border-emerald-200/50' : ($gap < 0 ? 'border-rose-200/50' : 'border-blue-200/50'); ?> w-full text-center">
                    <p class="text-xs font-bold text-slate-500">Total Kas Fisik Dilaporkan: <span class="text-slate-800">Rp <?= number_format($actualCash, 0, ',', '.'); ?></span></p>
                </div>
            </div>
        </div>

        <!-- PRODUCT SUMMARY TABLE -->
        <div class="bg-yellow-100/50 rounded-3xl border-2 border-dashed border-yellow-300 p-8">
            <div class="flex items-center gap-4 mb-6">
                <div class="w-10 h-10 bg-yellow-400 text-white rounded-xl flex items-center justify-center shadow-lg shadow-yellow-200">
                    <i class="fas fa-chart-pie"></i>
                </div>
                <div>
                    <h3 class="text-lg font-black text-yellow-800">Ringkasan Penjualan per Produk</h3>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <?php if(empty($productSummaryData)): ?>
                <div class="col-span-full py-10 text-center text-yellow-600/40 font-bold italic">
                    Tidak ada data penjualan produk.
                </div>
                <?php else: ?>
                    <?php foreach($productSummaryData as $key => $pd) : ?>
                    <div class="bg-white p-5 rounded-2xl border border-yellow-200 shadow-sm flex flex-col justify-between">
                        <div class="flex justify-between items-start mb-3">
                            <span class="text-[10px] font-black text-yellow-600 uppercase tracking-widest"><?= $pd['nama']; ?></span>
                            <div class="w-6 h-6 bg-yellow-50 rounded-lg flex items-center justify-center text-yellow-500 text-[10px]"><i class="fas fa-gas-pump"></i></div>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-2xl font-black text-slate-800 tracking-tighter"><?= number_format($pd['liter'], 2); ?> L</span>
                            <span class="text-xs font-bold text-emerald-600 mt-1">Rp <?= number_format($pd['total'], 0, ',', '.'); ?></span>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
