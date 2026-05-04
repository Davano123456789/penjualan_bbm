<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-800 tracking-tight">TOT Akhir</h2>
            <p class="text-sm text-slate-500 font-medium">Laporan Laba Rugi Bulanan — Totalisator Nozzle & Pendapatan Bersih.</p>
        </div>
        <div class="flex items-center gap-3">
            <form method="GET" class="flex items-center gap-2">
                <select name="bulan" class="px-3 py-2 bg-white border border-slate-200 rounded-xl text-sm font-bold text-slate-600 focus:ring-2 focus:ring-blue-500 outline-none">
                    <?php for ($m = 1; $m <= 12; $m++): ?>
                    <option value="<?= str_pad($m, 2, '0', STR_PAD_LEFT); ?>" <?= $data['bulan'] == str_pad($m, 2, '0', STR_PAD_LEFT) ? 'selected' : ''; ?>>
                        <?= date('F', mktime(0,0,0,$m,1)); ?>
                    </option>
                    <?php endfor; ?>
                </select>
                <input type="number" name="tahun" value="<?= $data['tahun']; ?>" class="w-24 px-3 py-2 bg-white border border-slate-200 rounded-xl text-sm font-bold text-slate-600 focus:ring-2 focus:ring-blue-500 outline-none">
                <button type="submit" class="px-4 py-2 bg-slate-800 text-white rounded-xl text-sm font-bold hover:bg-slate-700 transition-all">
                    <i class="fas fa-filter"></i>
                </button>
            </form>
            <a href="<?= BASEURL; ?>/totakhir/cetak?bulan=<?= $data['bulan']; ?>&tahun=<?= $data['tahun']; ?>" target="_blank" class="px-5 py-2 bg-emerald-600 text-white rounded-xl font-bold hover:bg-emerald-700 shadow-lg shadow-emerald-500/30 transition-all flex items-center gap-2">
                <i class="fas fa-print"></i> Cetak PDF
            </a>
            <?php if (isset($data['report']['is_saved']) && $data['report']['is_saved']): ?>
                <span class="px-4 py-2 bg-emerald-50 text-emerald-600 rounded-xl text-xs font-black uppercase tracking-widest border border-emerald-100 flex items-center gap-2">
                    <i class="fas fa-lock"></i> Terkunci
                </span>
            <?php endif; ?>
        </div>
    </div>

    <!-- Flash -->
    <div><?= Flasher::flash(); ?></div>

    <?php $r = $data['report']; ?>
    <?php $bulan_nama = date('F Y', mktime(0,0,0,$data['bulan'],1,$data['tahun'])); ?>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm flex items-center gap-4">
            <div class="w-14 h-14 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center text-2xl">
                <i class="fas fa-coins"></i>
            </div>
            <div>
                <p class="text-[10px] text-slate-400 font-black uppercase tracking-widest">Total Laba Kotor</p>
                <h4 class="text-xl font-black text-slate-800">Rp <?= number_format($r['total_laba_kotor'], 0, ',', '.'); ?></h4>
            </div>
        </div>
        <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm flex items-center gap-4">
            <div class="w-14 h-14 bg-rose-50 text-rose-500 rounded-2xl flex items-center justify-center text-2xl">
                <i class="fas fa-arrow-trend-down"></i>
            </div>
            <div>
                <p class="text-[10px] text-slate-400 font-black uppercase tracking-widest">Total Beban & Losis</p>
                <h4 class="text-xl font-black text-slate-800">Rp <?= number_format($r['total_pengeluaran'], 0, ',', '.'); ?></h4>
            </div>
        </div>
        <div class="<?= $r['laba_bersih'] >= 0 ? 'bg-emerald-600' : 'bg-rose-600'; ?> p-6 rounded-[2rem] shadow-xl flex items-center gap-4">
            <div class="w-14 h-14 bg-white/20 rounded-2xl flex items-center justify-center text-2xl text-white">
                <i class="fas <?= $r['laba_bersih'] >= 0 ? 'fa-chart-line' : 'fa-chart-line-down'; ?>"></i>
            </div>
            <div>
                <p class="text-[10px] text-white/70 font-black uppercase tracking-widest">Laba Bersih <?= $bulan_nama; ?></p>
                <h4 class="text-xl font-black text-white">Rp <?= number_format(abs($r['laba_bersih']), 0, ',', '.'); ?></h4>
                <?php if($r['laba_bersih'] < 0): ?><p class="text-[10px] text-white/70 font-bold mt-0.5">* Rugi</p><?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Main P&L Report -->
    <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-50 bg-slate-50/30 flex items-center gap-3">
            <div class="w-10 h-10 bg-slate-800 rounded-xl flex items-center justify-center text-white">
                <i class="fas fa-file-invoice"></i>
            </div>
            <div>
                <h3 class="text-lg font-bold text-slate-800">Laporan Laba Rugi (TOT Akhir)</h3>
                <p class="text-[10px] text-slate-400 font-black uppercase tracking-widest">Periode: <?= $bulan_nama; ?></p>
            </div>
        </div>

        <div class="p-6 space-y-6">

            <!-- SECTION 1: Pendapatan / Laba Kotor -->
            <div>
                <div class="flex items-center gap-2 mb-3">
                    <div class="w-1 h-5 bg-blue-600 rounded-full"></div>
                    <h4 class="text-xs font-black text-slate-500 uppercase tracking-widest">A. Pendapatan Laba Kotor (Margin/Liter)</h4>
                </div>
                <div class="bg-slate-50 rounded-2xl overflow-x-auto">
                    <table class="w-full text-left min-w-[800px]">
                        <thead>
                            <tr class="text-[10px] text-slate-400 font-black uppercase tracking-widest border-b border-slate-100">
                                <th class="px-5 py-4 border-r border-slate-100">Nozzel</th>
                                <th class="px-5 py-4 border-r border-slate-100">Produk</th>
                                <th class="px-5 py-4 text-right border-r border-slate-100">Tot Awal</th>
                                <th class="px-5 py-4 text-right border-r border-slate-100">Tot Akhir</th>
                                <th class="px-5 py-4 text-right border-r border-slate-100">Tera</th>
                                <th class="px-5 py-4 text-right border-r border-slate-100 bg-blue-50/50">Liter</th>
                                <th class="px-5 py-4 text-right border-r border-slate-100">Harga</th>
                                <th class="px-5 py-4 text-right border-r border-slate-100">Jumlah</th>
                                <th class="px-5 py-4 text-right border-r border-slate-100">Margin</th>
                                <th class="px-5 py-4 text-right bg-emerald-50/50">Laba Kotor</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <?php foreach ($r['detail_nozzle'] as $dn): ?>
                            <tr class="hover:bg-slate-100/50 transition-all font-mono text-[11px]">
                                <td class="px-5 py-3 font-bold text-slate-700 border-r border-slate-100"><?= $dn['nozzle']; ?></td>
                                <td class="px-5 py-3 text-slate-600 border-r border-slate-100"><?= $dn['produk']; ?></td>
                                <td class="px-5 py-3 text-right text-slate-500 border-r border-slate-100"><?= number_format($dn['tot_awal'], 2, ',', '.'); ?></td>
                                <td class="px-5 py-3 text-right text-slate-700 border-r border-slate-100"><?= number_format($dn['tot_akhir'], 2, ',', '.'); ?></td>
                                <td class="px-5 py-3 text-right text-slate-400 border-r border-slate-100"><?= number_format($dn['tera'], 2, ',', '.'); ?></td>
                                <td class="px-5 py-3 text-right font-black text-blue-700 border-r border-slate-100 bg-blue-50/20"><?= number_format($dn['liter'], 2, ',', '.'); ?> L</td>
                                <td class="px-5 py-3 text-right text-slate-600 border-r border-slate-100">Rp <?= number_format($dn['harga'], 0, ',', '.'); ?></td>
                                <td class="px-5 py-3 text-right font-bold text-slate-800 border-r border-slate-100">Rp <?= number_format($dn['jumlah'], 0, ',', '.'); ?></td>
                                <td class="px-5 py-3 text-right text-slate-500 border-r border-slate-100">Rp <?= number_format($dn['margin'], 0, ',', '.'); ?></td>
                                <td class="px-5 py-3 text-right font-black text-emerald-600 bg-emerald-50/20">Rp <?= number_format($dn['laba'], 0, ',', '.'); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr class="bg-blue-600 text-white shadow-xl">
                                <td colspan="5" class="px-5 py-4 font-black text-xs uppercase tracking-widest text-white/80">TOTAL PENDAPATAN</td>
                                <td class="px-5 py-4 text-right font-black text-sm"><?= number_format($r['terjual_pertamax'] + $r['terjual_dex'], 2, ',', '.'); ?> L</td>
                                <td colspan="2" class="px-5 py-4 text-right font-black text-sm">Rp <?= number_format($r['total_laba_kotor'] / 600 * 12200, 0, ',', '.'); // Rough estimate of gross revenue ?></td>
                                <td colspan="2" class="px-5 py-4 text-right font-black text-sm bg-blue-700/50">Rp <?= number_format($r['total_laba_kotor'], 0, ',', '.'); ?></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!-- SECTION 2: Beban / Pengeluaran -->
            <div>
                <div class="flex items-center gap-2 mb-3">
                    <div class="w-1 h-5 bg-rose-500 rounded-full"></div>
                    <h4 class="text-xs font-black text-slate-500 uppercase tracking-widest">B. Beban Pengeluaran Operasional</h4>
                </div>

                <form action="<?= BASEURL; ?>/totakhir/simpan" method="POST">
                    <input type="hidden" name="bulan" value="<?= $data['bulan']; ?>">
                    <input type="hidden" name="tahun" value="<?= $data['tahun']; ?>">
                    <div class="bg-slate-50 rounded-2xl overflow-hidden">
                        <table class="w-full text-left">
                            <tbody class="divide-y divide-slate-100">
                                <tr>
                                    <td class="px-5 py-4 font-bold text-slate-700 w-1/2">1. Gaji Karyawan</td>
                                    <td class="px-5 py-4 text-right font-black text-slate-800 text-sm">
                                        Rp <?= number_format($r['biaya_gaji'], 0, ',', '.'); ?>
                                        <span class="block text-[9px] text-slate-400 font-bold">Otomatis dari modul Gaji</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-5 py-4 font-bold text-slate-700">2. Pengeluaran Kas Operasional</td>
                                    <td class="px-5 py-4 text-right font-black text-slate-800 text-sm">
                                        Rp <?= number_format($r['biaya_kas'], 0, ',', '.'); ?>
                                        <span class="block text-[9px] text-slate-400 font-bold">Otomatis dari modul Pengeluaran</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-5 py-4 font-bold text-slate-700">3. PPH (Pajak)</td>
                                    <td class="px-5 py-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <span class="text-slate-400 font-bold text-sm">Rp</span>
                                            <input type="number" name="biaya_pph" value="<?= $r['biaya_pph']; ?>" placeholder="0"
                                                class="w-40 px-3 py-2 bg-white border-2 border-slate-200 rounded-xl text-right font-black text-slate-700 focus:border-blue-500 outline-none text-sm">
                                        </div>
                                        <span class="block text-[9px] text-slate-400 font-bold text-right mt-1">Input Manual</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-5 py-4 font-bold text-slate-700">4. Admin EDC</td>
                                    <td class="px-5 py-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <span class="text-slate-400 font-bold text-sm">Rp</span>
                                            <input type="number" name="biaya_admin_edc" value="<?= $r['biaya_admin_edc']; ?>" placeholder="0"
                                                class="w-40 px-3 py-2 bg-white border-2 border-slate-200 rounded-xl text-right font-black text-slate-700 focus:border-blue-500 outline-none text-sm">
                                        </div>
                                        <span class="block text-[9px] text-slate-400 font-bold text-right mt-1">Input Manual</span>
                                    </td>
                                </tr>
                                <?php foreach($r['detail_losis'] as $idx => $dl): ?>
                                <tr class="bg-amber-50/50">
                                    <td class="px-5 py-4 font-bold text-amber-700">
                                        <?= ($idx + 5); ?>. Losis <?= $dl['produk']; ?> 
                                        <span class="text-[10px] font-black ml-1">(<?= number_format($dl['volume'], 2, ',', '.'); ?> L)</span>
                                    </td>
                                    <td class="px-5 py-4 text-right font-black text-amber-700 text-sm">
                                        Rp <?= number_format($dl['total_rp'], 0, ',', '.'); ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr class="bg-rose-50 border-t-2 border-rose-100">
                                    <td class="px-5 py-3 font-black text-rose-700 text-sm">Total Pengeluaran & Beban</td>
                                    <td class="px-5 py-3 text-right font-black text-rose-700 text-sm" id="total_beban_display">
                                        Rp <?= number_format($r['total_pengeluaran'], 0, ',', '.'); ?>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <!-- Laba Bersih (Final) -->
                    <div class="mt-5 <?= $r['laba_bersih'] >= 0 ? 'bg-emerald-600' : 'bg-rose-600'; ?> p-6 rounded-2xl shadow-lg flex items-center justify-between">
                        <div>
                            <p class="text-white/70 text-xs font-black uppercase tracking-widest">Laba Bersih Bulan <?= $bulan_nama; ?></p>
                            <p class="text-white text-[10px] font-bold">(Laba Kotor - Semua Beban)</p>
                        </div>
                        <div class="text-right">
                            <span class="text-white text-3xl font-black">Rp <?= number_format(abs($r['laba_bersih']), 0, ',', '.'); ?></span>
                            <?php if($r['laba_bersih'] < 0): ?>
                                <p class="text-white/70 text-xs font-bold">* Dalam Kondisi Rugi</p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Save Button -->
                    <div class="mt-4">
                        <button type="submit" class="w-full py-4 bg-slate-800 text-white rounded-2xl font-black text-sm uppercase tracking-widest shadow-lg hover:bg-slate-700 hover:-translate-y-0.5 transition-all">
                            <i class="fas fa-save mr-2"></i> Kunci & Simpan Laporan Ini
                        </button>
                        <p class="text-center text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-3">PPH & Admin EDC akan tersimpan, data lain otomatis.</p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
