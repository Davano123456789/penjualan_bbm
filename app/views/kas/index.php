<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Buku Kas (General Ledger)</h2>
            <p class="text-sm text-slate-500 font-medium">Monitoring arus kas fisik (Lemari) dan arus kas total (Digital + Tunai)</p>
        </div>
        
        <div class="flex items-center gap-3">
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

            <button onclick="document.getElementById('modalTambahKas').classList.remove('hidden')" 
                    class="px-5 py-2.5 bg-blue-600 text-white rounded-xl text-sm font-bold shadow-lg shadow-blue-200 hover:bg-blue-700 transition-all flex items-center gap-2">
                <i class="fas fa-plus"></i> Manual Entry
            </button>
        </div>
    </div>

    <!-- Dual Ledger Layout -->
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
        
        <!-- SECTION 1: KAS LEMARI -->
        <div class="space-y-4">
            <div class="flex items-center gap-3 mb-2 px-4 py-2 bg-blue-50 rounded-2xl w-fit">
                <div class="w-3 h-3 bg-blue-500 rounded-full animate-pulse"></div>
                <h3 class="text-sm font-black text-blue-700 uppercase tracking-widest">KAS LEMARI (FISIK)</h3>
            </div>
            
            <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs border-collapse">
                        <thead>
                            <tr class="bg-slate-50 text-slate-400 font-black uppercase tracking-wider border-b border-slate-100">
                                <th class="px-4 py-3">Tanggal</th>
                                <th class="px-4 py-3">Keterangan</th>
                                <th class="px-4 py-3 text-right">Debit</th>
                                <th class="px-4 py-3 text-right">Kredit</th>
                                <th class="px-4 py-3 text-right bg-blue-50/50">Jumlah</th>
                                <th class="px-2 py-3 text-center"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            <!-- Saldo Awal Row -->
                            <tr class="bg-blue-50/20 italic font-bold">
                                <td colspan="2" class="px-4 py-3 text-slate-400 uppercase">SALDO AWAL</td>
                                <td class="px-4 py-3 text-right"></td>
                                <td class="px-4 py-3 text-right"></td>
                                <td class="px-4 py-3 text-right text-blue-600"><?= number_format($data['saldo_awal_lemari'], 0, ',', '.'); ?></td>
                                <td class="px-2 py-3"></td>
                            </tr>
                            
                             <?php 
                             $curr_bal = $data['saldo_awal_lemari'];
                             foreach ($data['lemari'] as $r): 
                                 $debit = ($r['tipe'] == 'debit') ? $r['jumlah'] : 0;
                                 $kredit = ($r['tipe'] == 'kredit') ? $r['jumlah'] : 0;
                                 $curr_bal += $debit - $kredit;
                             ?>
                             <tr class="hover:bg-slate-50/50 transition-all group">
                                 <td class="px-4 py-3 text-slate-400 lowercase"><?= date('d/m', strtotime($r['tanggal'])); ?></td>
                                 <td class="px-4 py-3">
                                     <div class="flex flex-col">
                                         <span class="font-medium text-slate-600 truncate max-w-[140px]" title="<?= $r['keterangan']; ?>"><?= $r['keterangan']; ?></span>
                                         <?php if($r['kategori_biaya']): ?>
                                             <span class="text-[8px] font-black uppercase text-blue-500 tracking-tighter"><?= $r['kategori_biaya']; ?></span>
                                         <?php endif; ?>
                                     </div>
                                 </td>
                                 <td class="px-4 py-3 text-right text-emerald-600 font-bold"><?= $debit > 0 ? number_format($debit, 0, ',', '.') : '-'; ?></td>
                                 <td class="px-4 py-3 text-right text-rose-500 font-bold"><?= $kredit > 0 ? number_format($kredit, 0, ',', '.') : '-'; ?></td>
                                 <td class="px-4 py-3 text-right font-black text-slate-700 bg-blue-50/20"><?= number_format($curr_bal, 0, ',', '.'); ?></td>
                                 <td class="px-2 py-3 text-center">
                                     <?php if ($r['harian_id'] === NULL): ?>
                                     <a href="<?= BASEURL; ?>/kas/hapus/<?= $r['id']; ?>" onclick="return confirm('Hapus transaksi manual ini?')" 
                                        class="text-slate-200 hover:text-rose-600 transition-colors opacity-0 group-hover:opacity-100">
                                         <i class="fas fa-trash-alt text-[10px]"></i>
                                     </a>
                                     <?php else: ?>
                                     <i class="fas fa-link text-[8px] text-slate-200" title="Sinkron otomatis dengan Harian"></i>
                                     <?php endif; ?>
                                 </td>
                             </tr>
                             <?php endforeach; ?>
                         </tbody>
                     </table>
                 </div>
             </div>
         </div>

         <!-- SECTION 2: ARUS KAS (OVERALL) -->
         <div class="space-y-4">
             <div class="flex items-center gap-3 mb-2 px-4 py-2 bg-emerald-50 rounded-2xl w-fit">
                 <div class="w-3 h-3 bg-emerald-500 rounded-full animate-pulse"></div>
                 <h3 class="text-sm font-black text-emerald-700 uppercase tracking-widest">ARUS KAS (TOTAL)</h3>
             </div>

             <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden">
                 <div class="overflow-x-auto">
                     <table class="w-full text-left text-xs border-collapse">
                         <thead>
                             <tr class="bg-slate-50 text-slate-400 font-black uppercase tracking-wider border-b border-slate-100">
                                 <th class="px-4 py-3">Tanggal</th>
                                 <th class="px-4 py-3">Keterangan</th>
                                 <th class="px-4 py-3 text-right">Debit</th>
                                 <th class="px-4 py-3 text-right">Kredit</th>
                                 <th class="px-4 py-3 text-right bg-emerald-50/50">Jumlah</th>
                                 <th class="px-2 py-3 text-center"></th>
                             </tr>
                         </thead>
                         <tbody class="divide-y divide-slate-50">
                             <!-- Saldo Awal Row -->
                             <tr class="bg-emerald-50/20 italic font-bold">
                                 <td colspan="2" class="px-4 py-3 text-slate-400 uppercase">SALDO AWAL</td>
                                 <td class="px-4 py-3 text-right"></td>
                                 <td class="px-4 py-3 text-right"></td>
                                 <td class="px-4 py-3 text-right text-emerald-600"><?= number_format($data['saldo_awal_arus'], 0, ',', '.'); ?></td>
                                 <td class="px-2 py-3"></td>
                             </tr>
                             
                             <?php 
                             $curr_bal_arus = $data['saldo_awal_arus'];
                             foreach ($data['arus'] as $r): 
                                 $debit = ($r['tipe'] == 'debit') ? $r['jumlah'] : 0;
                                 $kredit = ($r['tipe'] == 'kredit') ? $r['jumlah'] : 0;
                                 $curr_bal_arus += $debit - $kredit;
                             ?>
                             <tr class="hover:bg-slate-50/50 transition-all group">
                                 <td class="px-4 py-3 text-slate-400 lowercase"><?= date('d/m', strtotime($r['tanggal'])); ?></td>
                                 <td class="px-4 py-3">
                                     <div class="flex flex-col">
                                         <span class="font-medium text-slate-600 truncate max-w-[140px]" title="<?= $r['keterangan']; ?>"><?= $r['keterangan']; ?></span>
                                         <?php if($r['kategori_biaya']): ?>
                                             <span class="text-[8px] font-black uppercase text-blue-500 tracking-tighter"><?= $r['kategori_biaya']; ?></span>
                                         <?php endif; ?>
                                     </div>
                                 </td>
                                 <td class="px-4 py-3 text-right text-emerald-600 font-bold"><?= $debit > 0 ? number_format($debit, 0, ',', '.') : '-'; ?></td>
                                 <td class="px-4 py-3 text-right text-rose-500 font-bold"><?= $kredit > 0 ? number_format($kredit, 0, ',', '.') : '-'; ?></td>
                                 <td class="px-4 py-3 text-right font-black text-slate-700 bg-emerald-50/20"><?= number_format($curr_bal_arus, 0, ',', '.'); ?></td>
                                 <td class="px-2 py-3 text-center">
                                     <?php if ($r['harian_id'] === NULL): ?>
                                     <a href="<?= BASEURL; ?>/kas/hapus/<?= $r['id']; ?>" onclick="return confirm('Hapus transaksi manual ini?')" 
                                        class="text-slate-200 hover:text-rose-600 transition-colors opacity-0 group-hover:opacity-100">
                                         <i class="fas fa-trash-alt text-[10px]"></i>
                                     </a>
                                     <?php else: ?>
                                     <i class="fas fa-link text-[8px] text-slate-200" title="Sinkron otomatis dengan Harian"></i>
                                     <?php endif; ?>
                                 </td>
                             </tr>
                             <?php endforeach; ?>
                         </tbody>
                     </table>
                 </div>
             </div>
         </div>
     </div>
 </div>
 
 <!-- Modal Tambah Kas (Manual Entry) -->
 <div id="modalTambahKas" class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
     <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden transform transition-all">
         <div class="bg-blue-600 p-6 text-white flex justify-between items-center">
             <h3 class="font-black text-lg uppercase tracking-tight">Tambah Manual (Kas)</h3>
             <button onclick="document.getElementById('modalTambahKas').classList.add('hidden')" class="text-white/70 hover:text-white transition-colors">
                 <i class="fas fa-times text-xl"></i>
             </button>
         </div>
         
         <form action="<?= BASEURL; ?>/kas/tambah" method="POST" class="p-8 space-y-5">
             <div>
                 <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">📅 Tanggal</label>
                 <input type="date" name="tanggal" value="<?= date('Y-m-d'); ?>" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold">
             </div>
             <div class="grid grid-cols-2 gap-4">
                 <div>
                     <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">🏠 Sumber Uang</label>
                     <select name="kategori" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold">
                         <option value="keduanya">🚀 Tunai (Lemari & Arus)</option>
                         <option value="arus">💳 Transfer (Arus Saja)</option>
                         <option value="lemari">📂 Kas Lemari Saja</option>
                     </select>
                 </div>
                 <div>
                     <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">🔄 Tipe</label>
                     <select name="tipe" id="tipe_transaksi" onchange="toggleBiaya(this.value)" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold">
                         <option value="debit">🟢 Uang Masuk (Debit)</option>
                         <option value="kredit">🔴 Uang Keluar (Kredit)</option>
                     </select>
                 </div>
             </div>
             
             <!-- Kategori Biaya (Hidden by default, shown for Kredit) -->
             <div id="section_biaya" class="hidden animate-fade-in">
                 <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">🏷️ Kategori Pengeluaran</label>
                 <select name="kategori_biaya" class="w-full px-4 py-3 bg-blue-50 border border-blue-200 rounded-xl text-sm font-bold text-blue-600">
                     <option value="">-- Hanya Transaksi Biasa --</option>
                     <option value="Operasional">🛠️ Operasional</option>
                     <option value="Curah">🚚 Curah / Suplier</option>
                     <option value="Lainnya">📦 Lain-lain</option>
                 </select>
             </div>
 
             <div>
                 <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">✏️ Keterangan</label>
                 <input type="text" name="keterangan" placeholder="Masuk/Keluar apa?" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm">
             </div>
 
             <div>
                 <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">💰 Jumlah Nominal</label>
                 <input type="number" name="jumlah" placeholder="0" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-lg font-black">
             </div>
             
             <div class="pt-4">
                 <button type="submit" class="w-full py-4 bg-blue-600 text-white rounded-2xl font-black hover:bg-blue-700 shadow-xl shadow-blue-200 transition-all">
                     SIMPAN TRANSAKSI
                 </button>
             </div>
         </form>
     </div>
 </div>
 
 <script>
     function toggleBiaya(val) {
         const section = document.getElementById('section_biaya');
         if(val === 'kredit') {
             section.classList.remove('hidden');
         } else {
             section.classList.add('hidden');
         }
     }
 </script>
