<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Kesimpulan Laporan Bulanan</h2>
            <p class="text-sm text-slate-500 font-medium">Laporan Penerimaan dan Penjualan BBM (Stok vs Nozzle)</p>
        </div>

        <div class="flex items-center gap-3">
            <form method="GET" class="flex items-center gap-2">
                <select name="bulan" class="px-3 py-2 bg-white border border-slate-200 rounded-xl text-sm font-bold text-slate-600 focus:ring-2 focus:ring-blue-500 outline-none">
                    <?php for ($m = 1; $m <= 12; $m++): ?>
                        <option value="<?= str_pad($m, 2, '0', STR_PAD_LEFT); ?>" <?= $data['bulan'] == str_pad($m, 2, '0', STR_PAD_LEFT) ? 'selected' : ''; ?>>
                            <?= date('F', mktime(0, 0, 0, $m, 1)); ?>
                        </option>
                    <?php endfor; ?>
                </select>
                <input type="number" name="tahun" value="<?= $data['tahun']; ?>" class="w-24 px-3 py-2 bg-white border border-slate-200 rounded-xl text-sm font-bold text-slate-600 focus:ring-2 focus:ring-blue-500 outline-none">
                <button type="submit" class="px-4 py-2 bg-slate-800 text-white rounded-xl text-sm font-bold hover:bg-slate-700 transition-all shadow-lg shadow-slate-200">
                    <i class="fas fa-filter"></i>
                </button>
            </form>

            <a href="<?= BASEURL; ?>/kesimpulan/cetak?bulan=<?= $data['bulan']; ?>&tahun=<?= $data['tahun']; ?>" target="_blank" class="px-5 py-2.5 bg-emerald-600 text-white rounded-xl text-sm font-bold shadow-lg shadow-emerald-200 hover:bg-emerald-700 transition-all flex items-center gap-2">
                <i class="fas fa-print"></i> Cetak PDF
            </a>
        </div>
    </div>

    <!-- Summary Section -->
    <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-50 bg-slate-50/30">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-blue-200">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-slate-800">Ringkasan Performa</h3>
                    <p class="text-xs text-slate-400 font-bold uppercase tracking-widest">Periode: <?= date('F', mktime(0, 0, 0, $data['bulan'], 1)); ?> <?= $data['tahun']; ?></p>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse border-slate-100">
                <thead>
                    <tr class="bg-slate-50/50 text-slate-400 text-[10px] uppercase font-black tracking-widest border-b border-slate-100">
                        <th class="px-6 py-4 border-r border-slate-100">Produk</th>
                        <th class="px-6 py-4 text-center border-r border-slate-100">Stok Awal (L)</th>
                        <th class="px-6 py-4 text-center border-r border-slate-100">Penerimaan BBM (L)</th>
                        <th class="px-6 py-4 border-r border-slate-100">Dispenser</th>
                        <th class="px-6 py-4 text-right border-r border-slate-100">Totalisator Awal</th>
                        <th class="px-6 py-4 text-right border-r border-slate-100">Totalisator Akhir</th>
                        <th class="px-6 py-4 text-center border-r border-slate-100">Total Penjualan (L)</th>
                        <th class="px-6 py-4 text-center">Stok Akhir (L)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <?php if (empty($data['kesimpulan'])): ?>
                        <tr>
                            <td colspan="8" class="px-6 py-20 text-center text-slate-300 font-bold italic">Belum ada data untuk periode ini...</td>
                        </tr>
                    <?php endif; ?>

                    <?php foreach ($data['kesimpulan'] as $k): ?>
                        <!-- Group Row 1 -->
                        <tr class="hover:bg-slate-50/30 transition-all">
                            <td rowspan="<?= count($k['nozzles']) + 1; ?>" class="px-6 py-6 border-r border-slate-100 bg-white align-top">
                                <span class="px-3 py-1 bg-blue-50 text-blue-700 rounded-lg text-[10px] font-black uppercase tracking-wider block text-center mb-1"><?= $k['produk']; ?></span>
                                <div class="text-[10px] font-bold text-slate-400 text-center italic">Fuel Inventory</div>
                            </td>
                            <td rowspan="<?= count($k['nozzles']) + 1; ?>" class="px-6 py-6 text-center border-r border-slate-100 font-black text-slate-700 bg-white align-middle">
                                <?= number_format($k['stok_awal'], 0, ',', '.'); ?>
                            </td>
                            <td rowspan="<?= count($k['nozzles']) + 1; ?>" class="px-6 py-6 text-center border-r border-slate-100 font-black text-emerald-600 bg-white align-middle">
                                <span class="text-[10px] opacity-50 mr-1">+</span><?= number_format($k['penerimaan_bbm'], 0, ',', '.'); ?>
                            </td>

                            <!-- First Nozzle -->
                            <td class="px-6 py-4 border-r border-slate-100 text-sm font-bold text-slate-600 italic">
                                <?= $k['nozzles'][0]['label']; ?>
                            </td>
                            <td class="px-6 py-4 border-r border-slate-100 text-right font-bold text-slate-500">
                                <?= number_format($k['nozzles'][0]['totalisator_awal'], 0, ',', '.'); ?>
                            </td>
                            <td class="px-6 py-4 border-r border-slate-100 text-right font-bold text-slate-700">
                                <?= number_format($k['nozzles'][0]['totalisator_akhir'], 0, ',', '.'); ?>
                            </td>
                            <td class="px-6 py-4 border-r border-slate-100 text-center font-black text-slate-800">
                                <?= number_format($k['nozzles'][0]['total_penjualan'], 0, ',', '.'); ?>
                            </td>

                            <td rowspan="<?= count($k['nozzles']) + 1; ?>" class="px-6 py-6 text-center font-black text-blue-600 bg-white align-middle">
                                <?= number_format($k['stok_akhir'], 0, ',', '.'); ?>
                            </td>
                        </tr>

                        <!-- Subsequent Nozzles -->
                        <?php for ($i = 1; $i < count($k['nozzles']); $i++): ?>
                            <tr class="hover:bg-slate-50/30 transition-all border-b border-slate-50">
                                <td class="px-6 py-4 border-r border-slate-100 text-sm font-bold text-slate-600 italic">
                                    <?= $k['nozzles'][$i]['label']; ?>
                                </td>
                                <td class="px-6 py-4 border-r border-slate-100 text-right font-bold text-slate-500">
                                    <?= number_format($k['nozzles'][$i]['totalisator_awal'], 0, ',', '.'); ?>
                                </td>
                                <td class="px-6 py-4 border-r border-slate-100 text-right font-bold text-slate-700">
                                    <?= number_format($k['nozzles'][$i]['totalisator_akhir'], 0, ',', '.'); ?>
                                </td>
                                <td class="px-6 py-4 border-r border-slate-100 text-center font-black text-slate-800">
                                    <?= number_format($k['nozzles'][$i]['total_penjualan'], 0, ',', '.'); ?>
                                </td>
                            </tr>
                        <?php endfor; ?>

                        <!-- Group Total Row -->
                        <tr class="bg-slate-50/40 text-slate-800 font-black">
                            <td colspan="3" class="px-6 py-3 border-r border-slate-100 text-right uppercase text-[9px] tracking-widest text-slate-400">Total Penjualan <?= $k['produk']; ?></td>
                            <td class="px-6 py-3 border-r border-slate-100 text-center bg-slate-800 text-white rounded-sm border-2 border-white shadow-sm" colspan="1">
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
        </div>
    </div>

    <!-- Legend/Notes -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-blue-50/50 rounded-2xl p-6 border border-blue-100">
            <h4 class="text-blue-800 font-bold mb-2 flex items-center gap-2">
                <i class="fas fa-info-circle"></i> Catatan Laporan
            </h4>
            <ul class="text-xs text-blue-700/80 space-y-1 list-disc ml-4">
                <li>Angka Totalisator diambil dari pembukaan meteran hari pertama dan penutupan hari terakhir bulan tersebut.</li>
                <li>Stok Awal & Akhir merujuk pada data stock fisik tangki yang diinput secara manual.</li>
                <li>Penerimaan BBM adalah total kuantitas DO Pertamina yang diverifikasi masuk ke tangki.</li>
            </ul>
        </div>
    </div>
</div>

</div>