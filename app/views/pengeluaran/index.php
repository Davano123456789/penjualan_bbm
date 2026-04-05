<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Laporan Pengeluaran</h2>
            <p class="text-sm text-slate-500 font-medium">Rekapitulasi biaya operasional dan curah (Data dari Buku Kas)</p>
        </div>
        
        <div class="flex items-center gap-3">
            <!-- Filter -->
            <form method="GET" class="flex items-center gap-2">
                <select name="bulan" class="px-3 py-2 bg-white border border-slate-200 rounded-xl text-sm font-bold text-slate-600 focus:ring-2 focus:ring-blue-500">
                    <option value="all" <?= $data['bulan'] == 'all' ? 'selected' : ''; ?>>Semua Bulan</option>
                    <?php for ($m = 1; $m <= 12; $m++): ?>
                    <option value="<?= str_pad($m, 2, '0', STR_PAD_LEFT); ?>" <?= $data['bulan'] == str_pad($m, 2, '0', STR_PAD_LEFT) ? 'selected' : ''; ?>>
                        <?= date('F', mktime(0,0,0,$m,1)); ?>
                    </option>
                    <?php endfor; ?>
                </select>
                <input type="number" name="tahun" value="<?= $data['tahun']; ?>" class="w-24 px-3 py-2 bg-white border border-slate-200 rounded-xl text-sm font-bold text-slate-600 focus:ring-2 focus:ring-blue-500">
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
        <!-- Total Card -->
        <div class="bg-rose-600 rounded-3xl p-6 text-white shadow-xl shadow-rose-100 flex items-center justify-between overflow-hidden relative group">
            <div class="absolute right-0 top-0 opacity-10 -mr-10 -mt-10 group-hover:scale-110 transition-all">
                <i class="fas fa-wallet text-8xl"></i>
            </div>
            <div class="relative z-10">
                <p class="text-[10px] font-black text-rose-100 uppercase tracking-widest mb-1">Total Pengeluaran</p>
                <h3 class="text-2xl font-black tracking-tighter">Rp <?= number_format($data['total'], 0, ',', '.'); ?></h3>
            </div>
        </div>

        <?php 
        $cat_totals = [];
        foreach($data['summary_kategori'] as $sk) $cat_totals[$sk['kategori']] = $sk['total'];
        ?>

        <!-- Operasional Card -->
        <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm flex items-center justify-between overflow-hidden relative group">
            <div class="relative z-10">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Operasional</p>
                <h3 class="text-2xl font-black text-slate-800 tracking-tighter">
                    Rp <?= number_format($cat_totals['Operasional'] ?? 0, 0, ',', '.'); ?>
                </h3>
            </div>
            <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center">
                <i class="fas fa-tools"></i>
            </div>
        </div>

        <!-- Curah Card -->
        <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm flex items-center justify-between overflow-hidden relative group">
            <div class="relative z-10">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Curah / Suplier</p>
                <h3 class="text-2xl font-black text-slate-800 tracking-tighter">
                    Rp <?= number_format($cat_totals['Curah'] ?? 0, 0, ',', '.'); ?>
                </h3>
            </div>
            <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center">
                <i class="fas fa-truck-loading"></i>
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
                        <th class="px-6 py-4">Kategori</th>
                        <th class="px-6 py-4">Keterangan / Deskripsi</th>
                        <th class="px-6 py-4 text-right">Jumlah (Rp)</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <?php if (empty($data['pengeluaran'])): ?>
                    <tr>
                        <td colspan="5" class="px-6 py-20 text-center text-slate-300 font-bold italic">Belum ada catatan pengeluaran...</td>
                    </tr>
                    <?php endif; ?>
                    <?php foreach ($data['pengeluaran'] as $r): ?>
                    <tr class="hover:bg-slate-50/50 transition-all">
                        <td class="px-6 py-4 font-bold text-slate-700"><?= date('d M Y', strtotime($r['tanggal'])); ?></td>
                        <td class="px-6 py-4">
                            <?php 
                                $color = "bg-slate-100 text-slate-600";
                                if($r['kategori'] == 'Curah') $color = "bg-emerald-100 text-emerald-700";
                                if($r['kategori'] == 'Operasional') $color = "bg-blue-100 text-blue-700";
                            ?>
                            <span class="px-3 py-1 <?= $color; ?> rounded-lg text-[10px] font-black uppercase tracking-wider">
                                <?= $r['kategori']; ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-600"><?= $r['keterangan']; ?></td>
                        <td class="px-6 py-4 font-black text-rose-600 text-right">Rp <?= number_format($r['jumlah'], 0, ',', '.'); ?></td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-3">
                                <a href="<?= BASEURL; ?>/pengeluaran/hapus/<?= $r['id']; ?>" 
                                   onclick="return confirm('Hapus data ini dari Buku Kas?')"
                                   class="text-slate-300 hover:text-rose-600 transition-colors">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
