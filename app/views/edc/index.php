<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Rekapitulasi EDC (QRIS & Debit)</h2>
            <p class="text-sm text-slate-500 font-medium">Monitoring transaksi non-tunai dan biaya admin bank (MDR)</p>
        </div>
        
        <div class="flex items-center gap-3">
            <form method="GET" class="flex items-center gap-2">
                <select name="bulan" class="px-3 py-2 bg-white border border-slate-200 rounded-xl text-sm font-bold text-slate-600">
                    <option value="all" <?= $data['bulan'] == 'all' ? 'selected' : ''; ?>>Semua Bulan</option>
                    <?php for ($m = 1; $m <= 12; $m++): ?>
                    <option value="<?= str_pad($m, 2, '0', STR_PAD_LEFT); ?>" <?= $data['bulan'] == str_pad($m, 2, '0', STR_PAD_LEFT) ? 'selected' : ''; ?>>
                        <?= date('F', mktime(0,0,0,$m,1)); ?>
                    </option>
                    <?php endfor; ?>
                </select>
                <input type="number" name="tahun" value="<?= $data['tahun']; ?>" class="w-24 px-3 py-2 bg-white border border-slate-200 rounded-xl text-sm font-bold text-slate-600">
                <button type="submit" class="px-4 py-2 bg-slate-800 text-white rounded-xl text-sm font-bold hover:bg-slate-700 transition-all">
                    <i class="fas fa-filter"></i>
                </button>
            </form>
            
            <a href="<?= BASEURL; ?>/kas" class="px-5 py-2.5 bg-blue-600 text-white rounded-xl text-sm font-bold shadow-lg shadow-blue-200 hover:bg-blue-700 transition-all flex items-center gap-2">
                <i class="fas fa-plus"></i> Input via Kas
            </a>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <?php 
        $total_nominal = 0;
        $total_potongan = 0;
        $total_masuk = 0;
        foreach($data['edc'] as $e) {
            $total_nominal += $e['nominal'];
            $total_potongan += $e['jumlah_potongan'];
            $total_masuk += $e['jumlah_masuk'];
        }
        ?>
        <div class="bg-white rounded-[2rem] p-6 border border-slate-100 shadow-sm relative group overflow-hidden">
            <div class="relative z-10">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Transaksi (Gross)</p>
                <h3 class="text-2xl font-black text-slate-800 tracking-tighter">Rp <?= number_format($total_nominal, 0, ',', '.'); ?></h3>
            </div>
            <div class="absolute right-6 top-6 w-12 h-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform">
                <i class="fas fa-credit-card"></i>
            </div>
        </div>

        <div class="bg-white rounded-[2rem] p-6 border border-slate-100 shadow-sm relative group overflow-hidden">
            <div class="relative z-10">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Potongan (MDR)</p>
                <h3 class="text-2xl font-black text-rose-600 tracking-tighter">Rp <?= number_format($total_potongan, 0, ',', '.'); ?></h3>
            </div>
            <div class="absolute right-6 top-6 w-12 h-12 bg-rose-50 text-rose-600 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform">
                <i class="fas fa-percent"></i>
            </div>
        </div>

        <div class="bg-slate-900 rounded-[2rem] p-6 shadow-xl shadow-slate-200 relative group overflow-hidden">
            <div class="relative z-10">
                <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">Total Masuk (Net)</p>
                <h3 class="text-2xl font-black text-white tracking-tighter">Rp <?= number_format($total_masuk, 0, ',', '.'); ?></h3>
            </div>
            <div class="absolute right-6 top-6 w-12 h-12 bg-slate-800 text-blue-400 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform">
                <i class="fas fa-wallet"></i>
            </div>
            <div class="absolute -right-4 -bottom-4 opacity-5 rotate-12">
                <i class="fas fa-money-bill-wave text-8xl text-white"></i>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 text-slate-400 text-[10px] uppercase font-black tracking-widest border-b border-slate-100">
                        <th class="px-6 py-4">Tanggal</th>
                        <th class="px-6 py-4">Metode</th>
                        <th class="px-6 py-4">Keterangan Kas</th>
                        <th class="px-6 py-4 text-right">Harga</th>
                        <th class="px-6 py-4 text-center">Fee (%)</th>
                        <th class="px-6 py-4 text-right text-rose-600">Jumlah Potongan</th>
                        <th class="px-6 py-4 text-right text-emerald-600">Jumlah Masuk</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <?php if (empty($data['edc'])): ?>
                    <tr>
                        <td colspan="7" class="px-6 py-20 text-center text-slate-300 font-bold italic">Belum ada transaksi non-tunai...</td>
                    </tr>
                    <?php endif; ?>
                    <?php foreach ($data['edc'] as $e): ?>
                    <tr class="hover:bg-slate-50/50 transition-all">
                        <td class="px-6 py-4 font-bold text-slate-700"><?= date('d M Y', strtotime($e['tanggal'])); ?></td>
                        <td class="px-6 py-4">
                            <?php 
                                $color = ($e['metode'] == 'qris') ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700';
                            ?>
                            <span class="px-3 py-1 <?= $color; ?> rounded-lg text-[10px] font-black uppercase tracking-wider">
                                <?= strtoupper($e['metode']); ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-500 font-medium"><?= $e['keterangan']; ?></td>
                        <td class="px-6 py-4 font-black text-slate-700 text-right">Rp <?= number_format($e['nominal'], 0, ',', '.'); ?></td>
                        <td class="px-6 py-4 text-center font-bold text-slate-400 text-xs"><?= number_format($e['persen_potongan'], 1); ?>%</td>
                        <td class="px-6 py-4 font-bold text-rose-500 text-right">
                           <span class="text-[10px] opacity-50 mr-1">-</span>Rp <?= number_format($e['jumlah_potongan'], 0, ',', '.'); ?>
                        </td>
                        <td class="px-6 py-4 font-black text-emerald-600 text-right">Rp <?= number_format($e['jumlah_masuk'], 0, ',', '.'); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot class="bg-slate-50/50 border-t border-slate-100">
                    <tr class="font-black text-slate-800">
                        <td colspan="3" class="px-6 py-4 text-right uppercase text-[10px] tracking-widest text-slate-400">Total Keseluruhan</td>
                        <td class="px-6 py-4 text-right">Rp <?= number_format($total_nominal, 0, ',', '.'); ?></td>
                        <td></td>
                        <td class="px-6 py-4 text-right text-rose-600">Rp <?= number_format($total_potongan, 0, ',', '.'); ?></td>
                        <td class="px-6 py-4 text-right text-emerald-600">Rp <?= number_format($total_masuk, 0, ',', '.'); ?></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
