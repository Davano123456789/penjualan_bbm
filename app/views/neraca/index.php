<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Neraca Perdagangan</h2>
            <p class="text-sm text-slate-500 font-medium tracking-tight">Laporan Posisi Keuangan Bulanan — Aktiva vs Pasiva.</p>
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
            <?php if ($data['report']['is_saved']): ?>
                <span class="px-3 py-1.5 bg-emerald-50 text-emerald-600 rounded-lg text-[10px] font-black uppercase tracking-widest border border-emerald-100 flex items-center gap-2">
                    <i class="fas fa-check-circle"></i> Tersimpan
                </span>
            <?php endif; ?>
        </div>
    </div>

    <!-- Flash Message -->
    <div><?= Flasher::flash(); ?></div>

    <?php $r = $data['report']; ?>

    <form action="<?= BASEURL; ?>/neraca/simpan" method="POST" id="formNeraca">
        <input type="hidden" name="bulan" value="<?= $data['bulan']; ?>">
        <input type="hidden" name="tahun" value="<?= $data['tahun']; ?>">

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-start">
            
            <!-- AKTIVA (SISI KIRI) -->
            <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden">
                <div class="bg-blue-600 p-5 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center text-white">
                            <i class="fas fa-vault"></i>
                        </div>
                        <h3 class="text-white font-black uppercase tracking-widest text-sm">A. AKTIVA (HARTA)</h3>
                    </div>
                </div>

                <div class="p-6 space-y-6">
                    <!-- ARUS KAS -->
                    <div class="space-y-3">
                        <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest flex items-center gap-2">
                            <span class="w-1.5 h-1.5 bg-blue-500 rounded-full"></span> I. ARUS KAS
                        </h4>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between group">
                                <span class="text-sm font-bold text-slate-600">Kas SPBU (Lembaran)</span>
                                <div class="flex items-center gap-2 border-b-2 border-slate-100 group-focus-within:border-blue-500 transition-all pb-1">
                                    <span class="text-xs text-slate-400 font-bold">Rp</span>
                                    <input type="number" name="kas_spbu" value="<?= $r['kas_spbu']; ?>" class="w-36 text-right bg-transparent font-black text-slate-800 outline-none js-aktiva" data-label="Kas SPBU">
                                </div>
                            </div>
                            <div class="flex items-center justify-between group">
                                <span class="text-sm font-bold text-slate-600">Koin</span>
                                <div class="flex items-center gap-2 border-b-2 border-slate-100 group-focus-within:border-blue-500 transition-all pb-1">
                                    <span class="text-xs text-slate-400 font-bold">Rp</span>
                                    <input type="number" name="koin" value="<?= $r['koin']; ?>" class="w-36 text-right bg-transparent font-black text-slate-800 outline-none js-aktiva" data-label="Koin">
                                </div>
                            </div>
                            <div class="flex items-center justify-between group">
                                <span class="text-sm font-bold text-slate-600">Tabanas Bank</span>
                                <div class="flex items-center gap-2 border-b-2 border-slate-100 group-focus-within:border-blue-500 transition-all pb-1">
                                    <span class="text-xs text-slate-400 font-bold">Rp</span>
                                    <input type="number" name="tabanas_bank" value="<?= $r['tabanas_bank']; ?>" class="w-36 text-right bg-transparent font-black text-slate-800 outline-none js-aktiva" placeholder="Input Manual">
                                </div>
                            </div>
                            <div class="flex items-center justify-between group">
                                <span class="text-sm font-bold text-slate-600">Inventaris</span>
                                <div class="flex items-center gap-2 border-b-2 border-slate-100 group-focus-within:border-blue-500 transition-all pb-1">
                                    <span class="text-xs text-slate-400 font-bold">Rp</span>
                                    <input type="number" name="inventaris" value="<?= $r['inventaris']; ?>" class="w-36 text-right bg-transparent font-black text-slate-800 outline-none js-aktiva" placeholder="Input Manual">
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="border-slate-50">

                    <!-- STOK BARANG DAGANGAN -->
                    <div class="space-y-3">
                        <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest flex items-center gap-2">
                            <span class="w-1.5 h-1.5 bg-blue-500 rounded-full"></span> II. STOK BARANG
                        </h4>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between group">
                                <div>
                                    <span class="text-sm font-bold text-slate-600 block">Pertamax</span>
                                    <span class="text-[10px] text-slate-400 font-bold"><?= number_format($r['stok_pertamax_liter'], 2, ',', '.'); ?> L</span>
                                    <input type="hidden" name="stok_px_l" value="<?= $r['stok_pertamax_liter']; ?>">
                                    <input type="hidden" name="stok_px_n" value="<?= $r['stok_pertamax_nilai']; ?>">
                                </div>
                                <span class="font-black text-slate-800 text-sm">Rp <?= number_format($r['stok_pertamax_nilai'], 0, ',', '.'); ?></span>
                                <input type="hidden" class="js-aktiva-fixed" value="<?= $r['stok_pertamax_nilai']; ?>">
                            </div>
                            <div class="flex items-center justify-between group">
                                <div>
                                    <span class="text-sm font-bold text-slate-600 block">Dex</span>
                                    <span class="text-[10px] text-slate-400 font-bold"><?= number_format($r['stok_dex_liter'], 2, ',', '.'); ?> L</span>
                                    <input type="hidden" name="stok_dx_l" value="<?= $r['stok_dex_liter']; ?>">
                                    <input type="hidden" name="stok_dx_n" value="<?= $r['stok_dex_nilai']; ?>">
                                </div>
                                <span class="font-black text-slate-800 text-sm">Rp <?= number_format($r['stok_dex_nilai'], 0, ',', '.'); ?></span>
                                <input type="hidden" class="js-aktiva-fixed" value="<?= $r['stok_dex_nilai']; ?>">
                            </div>
                        </div>
                    </div>

                    <hr class="border-slate-50">

                    <!-- DO BBM -->
                    <div class="space-y-3">
                        <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest flex items-center gap-2">
                            <span class="w-1.5 h-1.5 bg-blue-500 rounded-full"></span> III. DO BBM (PESANAN)
                        </h4>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-1">
                                <label class="text-[10px] text-slate-400 font-black">LITER PX</label>
                                <input type="number" step="0.01" name="do_px_l" value="<?= $r['do_pertamax_liter']; ?>" class="w-full px-3 py-2 bg-slate-50 border-2 border-slate-100 rounded-xl font-bold text-slate-700 outline-none focus:border-blue-500 text-xs">
                            </div>
                            <div class="space-y-1">
                                <label class="text-[10px] text-slate-400 font-black">NILAI RP PX</label>
                                <input type="number" name="do_px_n" value="<?= $r['do_pertamax_nilai']; ?>" class="w-full px-3 py-2 bg-slate-50 border-2 border-slate-100 rounded-xl font-bold text-slate-700 outline-none focus:border-blue-500 text-xs js-aktiva">
                            </div>
                            <div class="space-y-1">
                                <label class="text-[10px] text-slate-400 font-black">LITER DEX</label>
                                <input type="number" step="0.01" name="do_dx_l" value="<?= $r['do_dex_liter']; ?>" class="w-full px-3 py-2 bg-slate-50 border-2 border-slate-100 rounded-xl font-bold text-slate-700 outline-none focus:border-blue-500 text-xs">
                            </div>
                            <div class="space-y-1">
                                <label class="text-[10px] text-slate-400 font-black">NILAI RP DEX</label>
                                <input type="number" name="do_dx_n" value="<?= $r['do_dex_nilai']; ?>" class="w-full px-3 py-2 bg-slate-50 border-2 border-slate-100 rounded-xl font-bold text-slate-700 outline-none focus:border-blue-500 text-xs js-aktiva">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- TOTAL AKTIVA -->
                <div class="bg-blue-600 p-6 flex justify-between items-center mt-auto">
                    <span class="text-white text-sm font-black uppercase tracking-widest">TOTAL AKTIVA</span>
                    <span class="text-white text-xl font-black" id="total_aktiva_display">Rp 0</span>
                </div>
            </div>

            <!-- PASIVA (SISI KANAN) -->
            <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden lg:mt-12">
                <div class="bg-slate-800 p-5 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center text-white">
                            <i class="fas fa-balance-scale"></i>
                        </div>
                        <h3 class="text-white font-black uppercase tracking-widest text-sm">B. PASIVA (UTANG & MODAL)</h3>
                    </div>
                </div>

                <div class="p-6 space-y-6">
                    <!-- UTANG -->
                    <div class="space-y-3">
                        <h4 class="text-[10px] font-black text-rose-400 uppercase tracking-widest flex items-center gap-2">
                            <span class="w-1.5 h-1.5 bg-rose-500 rounded-full"></span> I. UTANG
                        </h4>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between group">
                                <span class="text-sm font-bold text-slate-600">Utang Jangka Pendek</span>
                                <div class="flex items-center gap-2 border-b-2 border-slate-100 group-focus-within:border-rose-500 transition-all pb-1">
                                    <span class="text-xs text-slate-400 font-bold">Rp</span>
                                    <input type="number" name="utang_pndk" value="<?= $r['utang_jangka_pendek']; ?>" class="w-36 text-right bg-transparent font-black text-slate-800 outline-none js-pasiva">
                                </div>
                            </div>
                            <div class="flex items-center justify-between group">
                                <span class="text-sm font-bold text-slate-600">Utang Jangka Panjang</span>
                                <div class="flex items-center gap-2 border-b-2 border-slate-100 group-focus-within:border-rose-500 transition-all pb-1">
                                    <span class="text-xs text-slate-400 font-bold">Rp</span>
                                    <input type="number" name="utang_pjng" value="<?= $r['utang_jangka_panjang']; ?>" class="w-36 text-right bg-transparent font-black text-slate-800 outline-none js-pasiva">
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="border-slate-50">

                    <!-- MODAL -->
                    <div class="space-y-3">
                        <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest flex items-center gap-2">
                            <span class="w-1.5 h-1.5 bg-slate-800 rounded-full"></span> II. MODAL & LABA
                        </h4>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between group">
                                <span class="text-sm font-bold text-slate-600">Laba Bulan Ini</span>
                                <span class="font-black <?= $r['laba_berjalan'] >= 0 ? 'text-emerald-600' : 'text-rose-600'; ?> text-sm">
                                    Rp <?= number_format($r['laba_berjalan'], 0, ',', '.'); ?>
                                </span>
                                <input type="hidden" class="js-pasiva-fixed" value="<?= $r['laba_berjalan']; ?>">
                            </div>
                            <div class="flex items-center justify-between group">
                                <span class="text-sm font-bold text-slate-600">Modal Oli</span>
                                <div class="flex items-center gap-2 border-b-2 border-slate-100 group-focus-within:border-slate-800 transition-all pb-1">
                                    <span class="text-xs text-slate-400 font-bold">Rp</span>
                                    <input type="number" name="modal_oli" value="<?= $r['modal_oli']; ?>" class="w-36 text-right bg-transparent font-black text-slate-800 outline-none js-pasiva">
                                </div>
                            </div>
                            <div class="flex items-center justify-between group">
                                <span class="text-sm font-bold text-slate-600">Modal Gas</span>
                                <div class="flex items-center gap-2 border-b-2 border-slate-100 group-focus-within:border-slate-800 transition-all pb-1">
                                    <span class="text-xs text-slate-400 font-bold">Rp</span>
                                    <input type="number" name="modal_gas" value="<?= $r['modal_gas']; ?>" class="w-36 text-right bg-transparent font-black text-slate-800 outline-none js-pasiva">
                                </div>
                            </div>
                            <div class="flex items-center justify-between group">
                                <span class="text-sm font-bold text-slate-600 text-slate-400">Arus Modal 1 (Cadangan)</span>
                                <div class="flex items-center gap-2 border-b-2 border-slate-100 group-focus-within:border-slate-800 transition-all pb-1">
                                    <input type="number" name="catatan_1" value="<?= $r['catatan_modal_1']; ?>" class="w-36 text-right bg-transparent font-black text-slate-500 outline-none js-pasiva text-xs">
                                </div>
                            </div>
                            <div class="flex items-center justify-between group">
                                <span class="text-sm font-bold text-slate-600 text-slate-400">Arus Modal 2 (Lain-lain)</span>
                                <div class="flex items-center gap-2 border-b-2 border-slate-100 group-focus-within:border-slate-800 transition-all pb-1">
                                    <input type="number" name="catatan_2" value="<?= $r['catatan_modal_2']; ?>" class="w-36 text-right bg-transparent font-black text-slate-500 outline-none js-pasiva text-xs">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- TOTAL PASIVA -->
                <div class="bg-slate-800 p-6 flex justify-between items-center mt-auto">
                    <span class="text-white text-sm font-black uppercase tracking-widest">TOTAL PASIVA</span>
                    <span class="text-white text-xl font-black" id="total_pasiva_display">Rp 0</span>
                </div>
            </div>
        </div>

        <!-- FOOTER STATUS & SAVE -->
        <div class="mt-8 flex flex-col md:flex-row items-center justify-between bg-white p-6 rounded-[2rem] border-4 border-slate-100 shadow-xl gap-6">
            <div class="flex items-center gap-4">
                <div id="status_icon" class="w-16 h-16 rounded-2xl flex items-center justify-center text-3xl">
                    <i class="fas fa-balance-scale"></i>
                </div>
                <div>
                    <h3 id="status_text" class="text-xl font-black text-slate-800">Mengecek Keseimbangan...</h3>
                    <p id="selisih_text" class="text-sm font-bold text-slate-500">Selisih: Rp 0</p>
                </div>
            </div>
            <button type="submit" class="w-full md:w-auto px-12 py-5 bg-slate-800 text-white rounded-2xl font-black text-sm uppercase tracking-widest shadow-lg hover:bg-slate-700 hover:-translate-y-1 transition-all">
                <i class="fas fa-save mr-2"></i> Kunci & Simpan Neraca
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const formatRp = (val) => "Rp " + new Intl.NumberFormat('id-ID').format(val);
    
    function calculate() {
        let totalAktiva = 0;
        let totalPasiva = 0;

        // Sum Aktiva (Cash + Stock + DO + Modal Oli/Gas)
        document.querySelectorAll('.js-aktiva').forEach(el => totalAktiva += (parseFloat(el.value) || 0));
        document.querySelectorAll('.js-aktiva-fixed').forEach(el => totalAktiva += (parseFloat(el.value) || 0));
        // Add Modal Oli/Gas from the Pasiva section because they also count as Asset Value (Inventory)
        totalAktiva += (parseFloat(document.querySelector('input[name="modal_oli"]').value) || 0);
        totalAktiva += (parseFloat(document.querySelector('input[name="modal_gas"]').value) || 0);

        // Sum Pasiva
        document.querySelectorAll('.js-pasiva').forEach(el => totalPasiva += (parseFloat(el.value) || 0));
        document.querySelectorAll('.js-pasiva-fixed').forEach(el => totalPasiva += (parseFloat(el.value) || 0));

        // Display
        document.getElementById('total_aktiva_display').innerText = formatRp(totalAktiva);
        document.getElementById('total_pasiva_display').innerText = formatRp(totalPasiva);

        const selisih = Math.abs(totalAktiva - totalPasiva);
        const isBalance = selisih < 10; // Tolerance for decimals

        const statusIcon = document.getElementById('status_icon');
        const statusText = document.getElementById('status_text');
        const selisihText = document.getElementById('selisih_text');

        if (isBalance) {
            statusIcon.className = "w-16 h-16 bg-emerald-100 text-emerald-600 rounded-2xl flex items-center justify-center text-3xl animate-bounce";
            statusText.innerText = "NERACA SEIMBANG (BALANCE)";
            statusText.className = "text-xl font-black text-emerald-600";
            selisihText.innerText = "Kerja Bagus! Total Aset sama dengan Total Modal.";
        } else {
            statusIcon.className = "w-16 h-16 bg-rose-100 text-rose-600 rounded-2xl flex items-center justify-center text-3xl";
            statusText.innerText = "BELUM SEIMBANG";
            statusText.className = "text-xl font-black text-rose-600";
            selisihText.innerText = "Selisih: " + formatRp(totalAktiva - totalPasiva);
        }
    }

    document.querySelectorAll('input').forEach(input => {
        input.addEventListener('input', calculate);
    });

    calculate(); // Initial call
});
</script>
