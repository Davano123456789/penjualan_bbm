<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Laporan Voucher (Kupon BBM)</h2>
            <p class="text-sm text-slate-500 font-medium">Monitoring pengisian BBM non-tunai (piutang/jatah).</p>
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
                <button type="submit" class="px-4 py-2 bg-slate-800 text-white rounded-xl text-sm font-bold hover:bg-slate-700 transition-all shadow-lg shadow-slate-200">
                    <i class="fas fa-filter"></i>
                </button>
            </form>
            <button onclick="openModal()" class="px-5 py-2.5 bg-blue-600 text-white rounded-xl text-sm font-bold hover:bg-blue-700 transition-all shadow-lg shadow-blue-200 flex items-center gap-2">
                <i class="fas fa-plus-circle"></i>
                <span>Catat Voucher</span>
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm flex items-center gap-4">
            <div class="w-14 h-14 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center text-2xl shadow-sm">
                <i class="fas fa-receipt"></i>
            </div>
            <div>
                <p class="text-[10px] text-slate-400 font-black uppercase tracking-widest">Total Voucher (Rp)</p>
                <?php 
                    $total_rp = array_sum(array_column($data['vouchers'], 'jumlah_rupiah'));
                ?>
                <h4 class="text-2xl font-black text-slate-800">Rp <?= number_format($total_rp, 0, ',', '.'); ?></h4>
            </div>
        </div>
        
        <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm flex items-center gap-4">
            <div class="w-14 h-14 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center text-2xl shadow-sm">
                <i class="fas fa-gas-pump"></i>
            </div>
            <div>
                <p class="text-[10px] text-slate-400 font-black uppercase tracking-widest">Volume (Estimasi)</p>
                <?php 
                    $total_liter = 0;
                    foreach($data['vouchers'] as $v) { $total_liter += ($v['jumlah_rupiah'] / ($v['harga_jual'] ?: 1)); }
                ?>
                <h4 class="text-2xl font-black text-slate-800"><?= number_format($total_liter, 2, ',', '.'); ?> <span class="text-xs font-bold text-slate-400">Liter</span></h4>
            </div>
        </div>

        <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm flex items-center gap-4">
            <div class="w-14 h-14 bg-amber-50 text-amber-600 rounded-2xl flex items-center justify-center text-2xl shadow-sm">
                <i class="fas fa-users"></i>
            </div>
            <div>
                <p class="text-[10px] text-slate-400 font-black uppercase tracking-widest">Jumlah Penerima</p>
                <h4 class="text-2xl font-black text-slate-800"><?= count($data['rekap']); ?> <span class="text-xs font-bold text-slate-400">Orang</span></h4>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
        <!-- Main Table -->
        <div class="lg:col-span-2 bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-50 flex items-center justify-between">
                <h3 class="text-lg font-bold text-slate-800 uppercase tracking-tight">Rincian Voucher Harian</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50 text-slate-400 text-[10px] uppercase font-black tracking-widest border-b border-slate-100">
                            <th class="px-6 py-4">Tgl</th>
                            <th class="px-6 py-4">Penerima</th>
                            <th class="px-6 py-4">Produk</th>
                            <th class="px-6 py-4 text-right">Jumlah (Rp)</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        <?php if (empty($data['vouchers'])): ?>
                        <tr>
                            <td colspan="5" class="px-6 py-20 text-center text-slate-300 font-bold italic">Belum ada pengisian voucher bulan ini...</td>
                        </tr>
                        <?php endif; ?>
                        
                        <?php foreach ($data['vouchers'] as $v): ?>
                        <tr class="hover:bg-slate-50/50 transition-all">
                            <td class="px-6 py-4 text-slate-400 font-mono text-xs"><?= date('d/m', strtotime($v['periode'])); ?></td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-700"><?= $v['penerima']; ?></div>
                                <div class="text-[10px] text-slate-400 italic"><?= $v['keterangan']; ?></div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 bg-blue-50 text-blue-600 rounded-lg text-[10px] font-black uppercase"><?= $v['nama_produk']; ?></span>
                            </td>
                            <td class="px-6 py-4 text-right font-black text-slate-700">
                                Rp <?= number_format($v['jumlah_rupiah'], 0, ',', '.'); ?>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <a href="<?= BASEURL; ?>/voucher/hapus/<?= $v['id']; ?>" onclick="return confirm('Hapus catatan voucher ini?')" 
                                   class="w-8 h-8 flex items-center justify-center rounded-lg bg-rose-50 text-rose-500 hover:bg-rose-500 hover:text-white transition-all mx-auto">
                                   <i class="fas fa-trash-alt text-xs"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- summary Side Card -->
        <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-50 bg-slate-50/30 flex items-center gap-3">
                <i class="fas fa-chart-pie text-blue-600"></i>
                <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest">Rekap Per Nama</h3>
            </div>
            <div class="divide-y divide-slate-50 max-h-[500px] overflow-y-auto">
                <?php foreach ($data['rekap'] as $r): ?>
                <div class="p-5 hover:bg-slate-50 transition-all group">
                    <div class="flex items-center justify-between mb-1">
                        <span class="font-bold text-slate-700 group-hover:text-blue-600 transition-all"><?= $r['penerima']; ?></span>
                        <span class="text-[10px] font-black text-slate-400 uppercase"><?= $r['jumlah_transaksi']; ?>x Isi</span>
                    </div>
                    <div class="text-lg font-black text-slate-800">Rp <?= number_format($r['total_rupiah'], 0, ',', '.'); ?></div>
                </div>
                <?php endforeach; ?>
                
                <?php if (empty($data['rekap'])): ?>
                <div class="p-10 text-center text-slate-300 italic font-bold">Kosong</div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Modal Input Voucher -->
    <div id="modalVoucher" class="hidden fixed inset-0 z-50 overflow-auto bg-slate-900/60 flex items-center justify-center backdrop-blur-md">
        <div class="bg-white rounded-[2.5rem] shadow-2xl p-8 w-full max-w-md m-4 border border-slate-100 animate__animated animate__fadeInUp animate__faster">
            <div class="flex items-center justify-between mb-8">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-blue-600 rounded-2xl flex items-center justify-center text-white shadow-xl shadow-blue-200">
                        <i class="fas fa-ticket-alt text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-black text-slate-800">Catat Voucher</h3>
                        <p class="text-xs text-slate-400 font-bold uppercase tracking-widest">Input Penjualan Non-Tunai</p>
                    </div>
                </div>
                <button onclick="closeModal()" class="w-10 h-10 flex items-center justify-center rounded-2xl bg-slate-50 text-slate-400 hover:bg-rose-50 hover:text-rose-500 transition-all">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form action="<?= BASEURL; ?>/voucher/tambah" method="POST" class="space-y-6">
                <div class="space-y-4">
                    <div class="group">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1 ml-1">Tanggal</label>
                        <input type="date" name="periode" value="<?= date('Y-m-d'); ?>" required
                            class="w-full px-5 py-4 bg-slate-50 border-2 border-slate-50 rounded-2xl text-slate-800 font-bold focus:bg-white focus:border-blue-500 transition-all outline-none">
                    </div>

                    <div class="group">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1 ml-1">Nama Penerima / Orang</label>
                        <input type="text" name="penerima" placeholder="Contoh: Yuyun PLN, Pa Atang..." required
                            class="w-full px-5 py-4 bg-slate-50 border-2 border-slate-50 rounded-2xl text-slate-800 font-bold focus:bg-white focus:border-blue-500 transition-all outline-none placeholder:text-slate-300">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="group">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1 ml-1">Produk BBM</label>
                            <select name="produk_id" id="produk_select" required onchange="calculateLiter()"
                                class="w-full px-5 py-4 bg-slate-50 border-2 border-slate-50 rounded-2xl text-slate-800 font-bold focus:bg-white focus:border-blue-500 transition-all outline-none appearance-none">
                                <?php foreach($data['produk'] as $p): ?>
                                <option value="<?= $p['id']; ?>" data-harga="<?= $p['harga_jual']; ?>"><?= $p['nama']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="group">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1 ml-1">Nominal (Rp)</label>
                            <input type="number" name="jumlah_rupiah" id="rupiah_input" placeholder="0" required oninput="calculateLiter()"
                                class="w-full px-5 py-4 bg-slate-50 border-2 border-slate-50 rounded-2xl text-blue-600 font-black focus:bg-white focus:border-blue-500 transition-all outline-none placeholder:text-slate-200">
                        </div>
                    </div>

                    <!-- Liter Result Badge -->
                    <div id="liter_badge" class="bg-blue-50 border border-blue-100 p-4 rounded-2xl hidden animate__animated animate__fadeIn">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-blue-400 uppercase tracking-widest">Estimasi Liter:</span>
                            <span class="text-xl font-black text-blue-600 italic" id="liter_result">0 Liter</span>
                        </div>
                    </div>

                    <div class="group">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1 ml-1">Keterangan (Opsional)</label>
                        <input type="text" name="keterangan" placeholder="Catatan tambahan..."
                            class="w-full px-5 py-4 bg-slate-50 border-2 border-slate-50 rounded-2xl text-slate-800 font-medium focus:bg-white focus:border-blue-500 transition-all outline-none placeholder:text-slate-300">
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full py-5 bg-slate-800 text-white rounded-[1.5rem] font-black text-sm uppercase tracking-widest shadow-xl shadow-slate-200 hover:bg-slate-700 hover:-translate-y-1 transition-all active:translate-y-0">
                        <i class="fas fa-check-circle mr-2"></i> Simpan Voucher
                    </button>
                    <p class="text-center text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-4">Data ini akan terekap sebagai piutang.</p>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const modal = document.getElementById('modalVoucher');

    function openModal() {
        modal.classList.remove('hidden');
    }

    function closeModal() {
        modal.classList.add('hidden');
    }

    function calculateLiter() {
        const rupiah = document.getElementById('rupiah_input').value;
        const select = document.getElementById('produk_select');
        const harga = select.options[select.selectedIndex].getAttribute('data-harga');
        const badge = document.getElementById('liter_badge');
        const result = document.getElementById('liter_result');

        if(rupiah > 0) {
            const liter = (rupiah / harga).toFixed(2);
            result.innerText = liter + ' Liter';
            badge.classList.remove('hidden');
        } else {
            badge.classList.add('hidden');
        }
    }

    window.onclick = function(event) {
        if (event.target == modal) {
            closeModal();
        }
    }
</script>
