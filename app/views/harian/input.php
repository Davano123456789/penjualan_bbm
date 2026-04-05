<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Input Laporan Harian</h2>
        <a href="<?= BASEURL; ?>/harian" class="px-4 py-2 bg-slate-100 text-slate-600 rounded-xl text-sm font-bold hover:bg-slate-200 transition-all">
            <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar
        </a>
    </div>

    <!-- Main Entry Form -->
    <form action="<?= BASEURL; ?>/harian/tambah" method="POST" class="space-y-8 pb-20">
        <!-- Date & Time & Operators Section -->
        <?php 
            date_default_timezone_set('Asia/Jakarta');
            $currentDate = date('Y-m-d');
            $currentTime = date('H:i');
        ?>
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Tanggal Laporan</label>
                <input type="date" name="tanggal" value="<?= $currentDate; ?>" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 transition-all">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Operator 1 (Shift)</label>
                <select name="operator1_id" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
                    <option value="">- Pilih Operator 1 -</option>
                    <?php foreach($data['operators'] as $op) : ?>
                    <option value="<?= $op['id']; ?>"><?= $op['nama']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Operator 2 (Shift)</label>
                <select name="operator2_id" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
                    <option value="">- Pilih Operator 2 -</option>
                    <?php foreach($data['operators'] as $op) : ?>
                    <option value="<?= $op['id']; ?>"><?= $op['nama']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2 text-center">Masuk</label>
                    <input type="text" name="jam_masuk" id="jam_masuk" value="<?= $currentTime; ?>" placeholder="21.00" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm text-center font-bold focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2 text-center">Keluar</label>
                    <input type="text" name="jam_keluar" id="jam_keluar" value="<?= $currentTime; ?>" placeholder="06.00" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm text-center font-bold focus:ring-2 focus:ring-blue-500">
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
                        $machines = [
                            ['name' => 'MESIN 1', 'nozzles' => [1, 2, 3, 4]]
                        ];
                        
                        foreach ($machines as $mIdx => $m) : 
                            for ($i = 0; $i < count($m['nozzles']); $i += 2) :
                                $n1 = $m['nozzles'][$i];
                                $n2 = $m['nozzles'][$i+1];
                                $groupId = $n1 . "_" . $n2;
                        ?>
                        <!-- Group Header/Rows for Nozzle <?= $n1; ?> & <?= $n2; ?> -->
                        <tr class="row-reading group" data-group="<?= $groupId; ?>">
                            <?php if ($i == 0) : ?>
                            <td rowspan="4" class="px-8 py-4 bg-slate-100/50 text-center border-r border-slate-100">
                                <span class="text-xs font-black text-slate-400 rotate-180 [writing-mode:vertical-lr] tracking-[0.5em]"><?= $m['name']; ?></span>
                            </td>
                            <?php endif; ?>
                            
                            <!-- Nozzle 1 in Group -->
                            <td class="px-6 py-3 font-bold text-slate-400 text-xs">#<?= $n1; ?></td>
                            <td class="px-6 py-3">
                                <select name="penjualan[<?= $n1; ?>][produk_id]" class="produk-select w-40 px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm transition-all focus:border-blue-500">
                                    <option value="">- Produk -</option>
                                    <?php foreach ($data['produk'] as $p) : ?>
                                    <option value="<?= $p['id']; ?>" data-harga="<?= $p['harga_jual']; ?>"><?= $p['nama']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td class="px-4 py-3">
                                <input type="number" step="0.01" name="penjualan[<?= $n1; ?>][awal]" class="awal w-24 px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm text-right focus:border-blue-500">
                            </td>
                            <td class="px-4 py-3">
                                <input type="number" step="0.01" name="penjualan[<?= $n1; ?>][akhir]" class="akhir w-24 px-3 py-2 bg-white border border-slate-300 rounded-lg text-sm text-right font-bold focus:ring-2 focus:ring-blue-500">
                            </td>
                            <td class="px-6 py-3">
                                <input type="number" step="0.01" readonly name="penjualan[<?= $n1; ?>][liter_terjual]" class="liter-individual w-20 px-3 py-2 bg-slate-100 border border-transparent rounded-lg text-sm text-right font-bold text-slate-500" value="0">
                            </td>
                            
                            <!-- Spanning Cells for Group Totals -->
                            <td rowspan="2" class="px-6 py-3 bg-blue-50/10 border-l border-blue-50">
                                <input type="number" step="0.01" readonly class="group-liter-total w-20 px-3 py-3 bg-white border-2 border-blue-200 rounded-xl text-center text-sm font-black text-blue-700" value="0">
                            </td>
                            <td rowspan="2" class="px-6 py-3 bg-blue-50/10">
                                <input type="number" name="group_data[<?= $groupId; ?>][harga]" class="group-harga w-24 px-3 py-3 bg-slate-50 border border-slate-200 rounded-xl text-center text-sm font-bold text-emerald-600 focus:ring-2 focus:ring-emerald-500">
                            </td>
                            <td rowspan="2" class="px-6 py-3 bg-blue-50/10 text-right">
                                <input type="number" readonly class="group-rupiah-total w-32 px-3 py-3 bg-white border-2 border-blue-600 rounded-xl text-right text-sm font-black text-blue-800" value="0">
                                <!-- Hidden field for nozzle 1 & 2 share of the amount for DB persistence -->
                                <input type="hidden" name="penjualan[<?= $n1; ?>][total_rupiah]" class="nozzle-rupiah-share">
                                <input type="hidden" name="penjualan[<?= $n2; ?>][total_rupiah]" class="nozzle-rupiah-share">
                                <input type="hidden" name="penjualan[<?= $n1; ?>][nozzle]" value="<?= $n1; ?>">
                                <input type="hidden" name="penjualan[<?= $n2; ?>][nozzle]" value="<?= $n2; ?>">
                            </td>
                        </tr>
                        <tr class="row-reading group border-b border-slate-100" data-group="<?= $groupId; ?>">
                            <!-- Nozzle 2 in Group -->
                            <td class="px-6 py-3 font-bold text-slate-400 text-xs">#<?= $n2; ?></td>
                            <td class="px-6 py-3">
                                <select name="penjualan[<?= $n2; ?>][produk_id]" class="produk-select w-40 px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm transition-all focus:border-blue-500">
                                    <option value="">- Produk -</option>
                                    <?php foreach ($data['produk'] as $p) : ?>
                                    <option value="<?= $p['id']; ?>" data-harga="<?= $p['harga_jual']; ?>"><?= $p['nama']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td class="px-4 py-3">
                                <input type="number" step="0.01" name="penjualan[<?= $n2; ?>][awal]" class="awal w-24 px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm text-right focus:border-blue-500">
                            </td>
                            <td class="px-4 py-3">
                                <input type="number" step="0.01" name="penjualan[<?= $n2; ?>][akhir]" class="akhir w-24 px-3 py-2 bg-white border border-slate-300 rounded-lg text-sm text-right font-bold focus:ring-2 focus:ring-blue-500">
                            </td>
                            <td class="px-6 py-3">
                                <input type="number" step="0.01" readonly name="penjualan[<?= $n2; ?>][liter_terjual]" class="liter-individual w-20 px-3 py-2 bg-slate-100 border border-transparent rounded-lg text-sm text-right font-bold text-slate-500" value="0">
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
            <!-- BOX 1: PENYEIMBANGAN (SESUAI TABEL ATAS EXCEL) -->
            <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm relative overflow-hidden group">
                <div class="absolute top-0 right-0 w-32 h-32 bg-blue-50/50 rounded-full -mr-16 -mt-16 group-hover:scale-110 transition-all"></div>
                <div class="flex items-center gap-3 mb-8 relative z-10">
                    <div class="w-8 h-8 bg-blue-600 text-white rounded-lg flex items-center justify-center shadow-lg shadow-blue-200">
                        <i class="fas fa-balance-scale text-xs"></i>
                    </div>
                    <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest">Penyeimbangan (Tabel Atas Excel)</h3>
                </div>
                
                <div class="space-y-5 relative z-10">
                    <div class="flex items-center justify-between border-b border-slate-50 pb-3">
                        <span class="text-sm font-bold text-slate-500 uppercase">1. Penjualan</span>
                        <span id="summary_penjualan" class="text-lg font-black text-slate-800 tracking-tighter">Rp 0</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-bold text-slate-500 uppercase">2. Titipan</span>
                        <input type="number" name="total_titipan" id="titipan" value="0" class="w-44 px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm text-right font-bold focus:ring-2 focus:ring-blue-500 transition-all">
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-bold text-rose-500 uppercase">3. Pengeluaran</span>
                        <input type="number" name="total_pengeluaran" id="pengeluaran" value="0" class="w-44 px-4 py-3 bg-rose-50 border border-rose-100 rounded-xl text-sm text-right font-bold text-rose-600 focus:ring-2 focus:ring-rose-500 transition-all">
                    </div>
                    <div class="mt-8 pt-8 border-t-2 border-dashed border-slate-100 flex items-center justify-between bg-blue-50/30 -mx-8 px-8 py-5">
                        <div class="flex flex-col">
                            <span class="text-sm font-black text-blue-800 tracking-widest uppercase italic">SISA</span>
                            <span class="text-[9px] text-blue-500 font-bold uppercase tracking-tighter">(Uang yang harus disetor)</span>
                        </div>
                        <input type="number" readonly id="sisa_top" class="w-44 px-4 py-4 bg-white border-2 border-blue-500 rounded-2xl text-xl font-black text-blue-600 text-right shadow-sm">
                    </div>
                </div>
            </div>

            <!-- BOX 2: HITUNG LEMBARAN (SESUAI TABEL BAWAH EXCEL) -->
            <div class="bg-amber-50/50 p-8 rounded-3xl border-2 border-dashed border-amber-200 relative overflow-hidden group">
                <div class="absolute top-0 right-0 w-32 h-32 bg-amber-400/10 rounded-full -mr-16 -mt-16 group-hover:scale-110 transition-all"></div>
                <div class="flex items-center gap-3 mb-8 relative z-10">
                    <div class="w-8 h-8 bg-amber-500 text-white rounded-lg flex items-center justify-center shadow-lg shadow-amber-200">
                        <i class="fas fa-calculator text-xs"></i>
                    </div>
                    <h3 class="text-xs font-black text-amber-700/60 uppercase tracking-widest italic underline">Target Uang Kertas (Tabel Bawah Excel)</h3>
                </div>

                <div class="space-y-5 relative z-10">
                    <div class="flex items-center justify-between border-b border-amber-100 pb-3">
                        <span class="text-sm font-bold text-amber-800/60 uppercase italic underline">Pendapatan</span>
                        <span id="summary_pendapatan" class="text-lg font-black text-slate-800 tracking-tighter italic underline">Rp 0</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-bold text-rose-600/60 uppercase italic underline">Pengeluaran</span>
                        <span id="display_pengeluaran" class="text-md font-bold text-rose-500 italic underline">Rp 0</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-bold text-amber-800/60 uppercase italic underline">Koin (Logam)</span>
                        <input type="number" id="koin" value="0" class="w-44 px-4 py-3 bg-white border border-amber-200 rounded-xl text-sm text-right font-bold focus:ring-2 focus:ring-amber-500 transition-all">
                    </div>
                    <div class="mt-8 pt-8 border-t-2 border-amber-200 flex items-center justify-between bg-amber-100 -mx-8 px-8 py-5">
                        <div class="flex flex-col">
                            <span class="text-sm font-black text-amber-900 tracking-widest uppercase italic underline">Target Lembaran</span>
                            <span class="text-[9px] text-amber-600 font-bold uppercase tracking-tighter">(Harus ada uang kertas)</span>
                        </div>
                        <input type="number" readonly id="target_uang_kertas" class="w-44 px-4 py-4 bg-white border-2 border-amber-500 rounded-2xl text-xl font-black text-amber-700 text-right shadow-sm">
                    </div>
                </div>
            </div>
        </div>

        <!-- FINAL STATUS DRAWER -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Actual Cash Input -->
            <div class="lg:col-span-1 bg-slate-900 p-8 rounded-3xl text-white shadow-2xl relative overflow-hidden group">
                <div class="absolute bottom-0 right-0 opacity-10 -mr-4 -mb-4 group-hover:scale-110 transition-all">
                    <i class="fas fa-money-bill-wave text-8xl"></i>
                </div>
                <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-6 border-b border-slate-800 pb-4 italic underline">Input Uang Nyata di Tangan</h3>
                <div class="space-y-4 relative z-10">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Kertas (Lembar)</label>
                            <input type="number" id="actual_kertas" placeholder="Rp..." class="w-full px-4 py-3 bg-slate-800 border-none rounded-xl text-md font-bold text-emerald-400 focus:ring-2 focus:ring-emerald-500 transition-all shadow-inner">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Koin (Logam)</label>
                            <input type="number" id="actual_koin_fisik" placeholder="Rp..." class="w-full px-4 py-3 bg-slate-800 border-none rounded-xl text-md font-bold text-emerald-400 focus:ring-2 focus:ring-emerald-500 transition-all shadow-inner">
                        </div>
                    </div>
                    <div class="pt-4 border-t border-slate-800">
                        <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Total Kas Fisik (Di Tangan)</label>
                        <input type="number" readonly name="total_penerimaan_kas" id="actual_cash_total" class="w-full px-5 py-4 bg-slate-700/50 border-none rounded-2xl text-2xl font-black text-white shadow-inner">
                    </div>
                </div>
            </div>

            <!-- Comparison Status -->
            <div id="status_container" class="lg:col-span-2 p-8 rounded-3xl border-4 border-slate-100 shadow-sm flex flex-col justify-center items-center text-center transition-all duration-500 bg-slate-50">
                <div id="status_icon" class="w-20 h-20 rounded-2xl bg-slate-200 text-white flex items-center justify-center text-3xl mb-5 transition-all transform -rotate-6">
                    <i class="fas fa-spinner fa-spin"></i>
                </div>
                <h3 id="status_title" class="text-2xl font-black text-slate-300 uppercase tracking-[0.2em] mb-2">MENUNGGU DATA...</h3>
                <div class="space-y-2">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Selisih Akhir (Purity Audit)</p>
                    <span id="display_selisih" class="text-4xl font-black text-slate-800 tracking-tighter italic underline">Rp 0</span>
                </div>
            </div>
        </div>

        <!-- PRODUCT SUMMARY TABLE (YELLOW BOX IN EXCEL) -->
        <div class="bg-yellow-100/50 rounded-3xl border-2 border-dashed border-yellow-300 p-8">
            <div class="flex items-center gap-4 mb-6">
                <div class="w-10 h-10 bg-yellow-400 text-white rounded-xl flex items-center justify-center shadow-lg shadow-yellow-200">
                    <i class="fas fa-chart-pie"></i>
                </div>
                <div>
                    <h3 class="text-lg font-black text-yellow-800">Ringkasan Penjualan per Produk</h3>
                    <p class="text-xs font-bold text-yellow-600/70 uppercase tracking-widest">Dihitung otomatis berdasarkan gabungan nozzle</p>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4" id="product_summary_container">
                <!-- Content will be auto-generated by JS -->
                <div class="col-span-full py-10 text-center text-yellow-600/40 font-bold italic">
                    Pilih produk di tabel atas untuk melihat ringkasan...
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="fixed bottom-0 left-64 right-0 bg-white/80 backdrop-blur-md p-4 border-t border-slate-100 shadow-xl flex justify-end px-10">
            <input type="hidden" name="total_sisa" id="hidden_sisa_output">
            <button type="submit" class="px-8 py-3 bg-blue-600 text-white rounded-xl font-black text-md hover:bg-blue-700 shadow-lg shadow-blue-500/30 transition-all flex items-center gap-3">
                <i class="fas fa-save"></i> SIMPAN LAPORAN HARIAN
            </button>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Collect all unique group IDs from the table (e.g. 1_2, 3_4)
        const groupIds = [...new Set([...document.querySelectorAll('tr[data-group]')].map(el => el.dataset.group))];
        
        const summaryPenjualan = document.getElementById('summary_penjualan');
        const summaryPendapatan = document.getElementById('summary_pendapatan');
        const displayPengeluaran = document.getElementById('display_pengeluaran');
        const productSummaryContainer = document.getElementById('product_summary_container');
        
        const koinInput = document.getElementById('koin');
        const titipanInput = document.getElementById('titipan');
        const pengeluaranInput = document.getElementById('pengeluaran');
        const actualKertasInput = document.getElementById('actual_kertas');
        const actualKoinInput = document.getElementById('actual_koin_fisik');
        
        const sisaTopInput = document.getElementById('sisa_top');
        const targetUangKertasInput = document.getElementById('target_uang_kertas');
        const actualCashTotalInput = document.getElementById('actual_cash_total');
        const displaySelisih = document.getElementById('display_selisih');
        const hiddenSisaOutput = document.getElementById('hidden_sisa_output');

        const formatter = new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        });

        function calculate() {
            let grandTotal = 0;
            let productSummaryData = {};

            groupIds.forEach(groupId => {
                const groupRows = document.querySelectorAll(`tr[data-group="${groupId}"]`);
                let groupLiterTotal = 0;
                
                // Group-level inputs are in the first row of the group
                const groupHargaInput = groupRows[0].querySelector('.group-harga');
                const groupLiterDisplay = groupRows[0].querySelector('.group-liter-total');
                const groupRupiahDisplay = groupRows[0].querySelector('.group-rupiah-total');
                const rupiahShareInputs = groupRows[0].querySelectorAll('.nozzle-rupiah-share');
                
                const harga = parseFloat(groupHargaInput.value) || 0;

                // 1. Calculate Liters for each nozzle in group and sum them
                groupRows.forEach(row => {
                    const awal = parseFloat(row.querySelector('.awal').value) || 0;
                    const akhir = parseFloat(row.querySelector('.akhir').value) || 0;
                    const literIndividualDisplay = row.querySelector('.liter-individual');
                    
                    const liter = Math.max(0, akhir - awal);
                    literIndividualDisplay.value = liter.toFixed(2);
                    groupLiterTotal += liter;
                });

                // 2. Set Grouped Displays
                groupLiterDisplay.value = groupLiterTotal.toFixed(2);
                const groupRupiahTotal = Math.round(groupLiterTotal * harga);
                groupRupiahDisplay.value = groupRupiahTotal;

                // 3. Update Product Summary (Yellow Box) and Rupiah Shares (for Database)
                groupRows.forEach((row, idx) => {
                    const select = row.querySelector('.produk-select');
                    const productId = select.value;
                    const productName = select.options[select.selectedIndex].text;
                    const liter = parseFloat(row.querySelector('.liter-individual').value) || 0;
                    
                    // Proportional share calculation
                    const share = groupLiterTotal > 0 ? (liter / groupLiterTotal) * groupRupiahTotal : 0;
                    rupiahShareInputs[idx].value = Math.round(share);

                    if (productId && productId !== "") {
                        // Group by fuel type: 'Pertamax 1' & 'Pertamax 2' → 'PERTAMAX', 'Dex 1' & 'Dex 2' → 'DEX'
                        let groupKey = 'LAINNYA';
                        let groupLabel = productName;
                        if (productName.toLowerCase().includes('pertamax')) { groupKey = 'PERTAMAX'; groupLabel = 'Pertamax'; }
                        else if (productName.toLowerCase().includes('dex')) { groupKey = 'DEX'; groupLabel = 'Dex'; }

                        if (!productSummaryData[groupKey]) {
                            productSummaryData[groupKey] = { name: groupLabel, liter: 0, total: 0 };
                        }
                        productSummaryData[groupKey].liter += liter;
                        productSummaryData[groupKey].total += Math.round(share);
                    }
                });

                grandTotal += groupRupiahTotal;
            });

            // Update Global Summary
            summaryPenjualan.innerText = formatter.format(grandTotal);
            summaryPendapatan.innerText = formatter.format(grandTotal);
            displayPengeluaran.innerText = formatter.format(parseFloat(pengeluaranInput.value) || 0);

            updateProductSummaryView(productSummaryData);

            const targetSetoran = grandTotal - (parseFloat(titipanInput.value) || 0) - (parseFloat(pengeluaranInput.value) || 0);
            sisaTopInput.value = targetSetoran;

            const targetKertas = grandTotal - (parseFloat(pengeluaranInput.value) || 0) - (parseFloat(koinInput.value) || 0);
            targetUangKertasInput.value = targetKertas;

            const actualCashTotal = (parseFloat(actualKertasInput.value) || 0) + (parseFloat(actualKoinInput.value) || 0);
            actualCashTotalInput.value = actualCashTotal;

            const finalGap = actualCashTotal - targetSetoran;
            displaySelisih.innerText = formatter.format(finalGap);
            hiddenSisaOutput.value = finalGap;

            // Show status as soon as there's grand total, not waiting for actual cash input
            updateStatusUI(finalGap, grandTotal === 0);
        }

        function updateStatusUI(gap, isInitial) {
            if (isInitial) {
                statusTitle.innerText = "MENUNGGU DATA...";
                return;
            }
            if (gap === 0) {
                statusContainer.className = 'lg:col-span-2 p-8 rounded-3xl border-4 border-emerald-100 shadow-xl flex flex-col justify-center items-center text-center bg-emerald-50 scale-[1.02] transition-all';
                statusIcon.innerHTML = `<i class="fas fa-check-circle"></i>`;
                statusTitle.innerText = 'FIX / MATCH! ✅';
            } else if (gap < 0) {
                statusContainer.className = 'lg:col-span-2 p-8 rounded-3xl border-4 border-rose-100 shadow-xl flex flex-col justify-center items-center text-center bg-rose-50 transition-all';
                statusIcon.innerHTML = `<i class="fas fa-exclamation-triangle"></i>`;
                statusTitle.innerText = 'MINUS / LOSIS ⚠️';
            } else {
                statusContainer.className = 'lg:col-span-2 p-8 rounded-3xl border-4 border-blue-100 shadow-xl flex flex-col justify-center items-center text-center bg-blue-50 transition-all';
                statusIcon.innerHTML = `<i class="fas fa-plus-circle"></i>`;
                statusTitle.innerText = 'SURPLUS / LEBIH 💰';
            }
        }

        function updateProductSummaryView(data) {
            const keys = Object.keys(data);
            if (keys.length === 0) {
                productSummaryContainer.innerHTML = '<div class="col-span-full py-10 text-center text-yellow-600/40 font-bold italic">Menunggu input nozzle...</div>';
                return;
            }
            let html = '';
            keys.forEach(key => {
                const item = data[key];
                html += `
                    <div class="bg-white p-5 rounded-2xl border border-yellow-200 shadow-sm flex flex-col justify-between hover:translate-y-[-4px] transition-all">
                        <div class="flex justify-between items-start mb-3">
                            <span class="text-[10px] font-black text-yellow-600 uppercase tracking-widest">${item.name}</span>
                            <div class="w-6 h-6 bg-yellow-50 rounded-lg flex items-center justify-center text-yellow-500 text-[10px]"><i class="fas fa-gas-pump"></i></div>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-2xl font-black text-slate-800 tracking-tighter">${item.liter.toFixed(2)} L</span>
                            <span class="text-xs font-bold text-emerald-600 mt-1">${formatter.format(item.total)}</span>
                        </div>
                    </div>`;
            });
            productSummaryContainer.innerHTML = html;
        }

        // Auto-fill price logic for groups
        document.querySelectorAll('.produk-select').forEach(select => {
            select.addEventListener('change', function() {
                const groupRow = this.closest('tr[data-group]');
                const groupRows = document.querySelectorAll(`tr[data-group="${groupRow.dataset.group}"]`);
                const groupHargaInput = groupRows[0].querySelector('.group-harga');
                
                const productName = this.options[this.selectedIndex].text;
                let harga = this.options[this.selectedIndex].dataset.harga || 0;
                
                if (productName.toLowerCase().includes('pertamax') && (harga == 0 || harga == "")) {
                    harga = 12200;
                }
                
                if (groupHargaInput.value == "" || groupHargaInput.value == "0") {
                    groupHargaInput.value = harga;
                }
                calculate();
            });
        });

        document.querySelectorAll('input').forEach(el => {
            el.addEventListener('input', calculate);
        });

        // Helper to convert 21.00 into 21:00 for DB compatibility
        const timeInputs = [document.getElementById('jam_masuk'), document.getElementById('jam_keluar')];
        timeInputs.forEach(input => {
            input.addEventListener('blur', function() {
                this.value = this.value.replace('.', ':');
            });
        });

        // Auto-fetch latest readings on load
        fetch('<?= BASEURL; ?>/harian/get_latest_readings')
            .then(response => response.json())
            .then(data => {
                if (data && data.length > 0) {
                    data.forEach(item => {
                        // Find matching nozzle input
                        const awalInput = document.querySelector(`input[name="penjualan[${item.nozzle}][awal]"]`);
                        if (awalInput) {
                            awalInput.value = item.totalisator_akhir;
                            
                            // Also auto-select product if not selected
                            const row = awalInput.closest('tr');
                            const select = row.querySelector('.produk-select');
                            if (select && (select.value === "" || select.value == "0")) {
                                select.value = item.produk_id;
                                
                                // Trigger price update
                                const groupRows = document.querySelectorAll(`tr[data-group="${row.dataset.group}"]`);
                                const groupHargaInput = groupRows[0].querySelector('.group-harga');
                                if (groupHargaInput && (groupHargaInput.value === "" || groupHargaInput.value == "0")) {
                                    groupHargaInput.value = item.harga_jual || 0;
                                }
                            }
                        }
                    });
                    // Recalculate everything after auto-fill
                    calculate();
                }
            })
            .catch(error => console.error('Error fetching latest readings:', error));

        // Initial calculation
        calculate();
    });
</script>
