<div class="space-y-6 pb-20">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Input Stok BBM</h2>
            <p class="text-sm text-slate-500">Stok Awal & Terjual <span class="font-black text-blue-600">otomatis terisi</span> — Anda hanya input Kiriman & Stok Fisik.</p>
        </div>
        <a href="<?= BASEURL; ?>/stok" class="px-4 py-2 bg-slate-100 text-slate-600 rounded-xl text-sm font-bold hover:bg-slate-200 transition-all">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>

    <form action="<?= BASEURL; ?>/stok/simpan" method="POST" class="space-y-6">
        <!-- Tanggal -->
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
            <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Tanggal</label>
            <input type="date" name="tanggal" id="tanggal" value="<?= $data['tanggal']; ?>" required
                class="w-60 px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold focus:ring-2 focus:ring-blue-500 transition-all">
            <p class="text-xs text-slate-400 mt-2"><i class="fas fa-info-circle mr-1"></i> Ubah tanggal untuk mengisi stok hari berbeda.</p>
        </div>

        <?php
        $groups = [
            [
                'label'     => 'Pertamax',
                'prefix'    => 'pertamax',
                'icon_color'=> 'bg-blue-600',
                'border'    => 'border-blue-100',
                'stok_awal' => $data['stok_awal_pertamax'],
                'terjual'   => $data['terjual_pertamax'],
            ],
            [
                'label'     => 'Dex',
                'prefix'    => 'dex',
                'icon_color'=> 'bg-emerald-600',
                'border'    => 'border-emerald-100',
                'stok_awal' => $data['stok_awal_dex'],
                'terjual'   => $data['terjual_dex'],
            ],
        ];

        foreach ($groups as $g):
            $prefix    = $g['prefix'];
            $stokAwal  = floatval($g['stok_awal']);
            $terjual   = floatval($g['terjual']);
        ?>
        <!-- CARD: <?= $g['label']; ?> -->
        <div class="bg-white rounded-3xl border <?= $g['border']; ?> shadow-sm overflow-hidden">
            <!-- Header -->
            <div class="px-8 py-4 <?= $g['icon_color']; ?> flex items-center gap-3">
                <i class="fas fa-gas-pump text-white/80"></i>
                <h3 class="text-sm font-black text-white uppercase tracking-widest">⛽ <?= $g['label']; ?></h3>
                <span class="ml-auto text-white/60 text-xs font-bold">Isi kolom yang disorot saja</span>
            </div>

            <div class="p-8">
                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-4 items-end">

                    <!-- 1. Stok Awal (AUTO) -->
                    <div class="lg:col-span-1">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">
                            Stok Awal (L) <span class="text-blue-500">⚡ Otomatis</span>
                        </label>
                        <input type="number" step="0.01"
                            name="<?= $prefix; ?>_stok_awal"
                            id="<?= $prefix; ?>_stok_awal"
                            value="<?= $stokAwal; ?>"
                            class="w-full px-3 py-3 bg-slate-100 border border-slate-200 rounded-xl text-sm text-right font-black text-slate-600 focus:ring-2 focus:ring-blue-500 transition-all">
                    </div>

                    <!-- 2. Kiriman Masuk (INPUT) -->
                    <div class="lg:col-span-1">
                        <label class="block text-[10px] font-black text-blue-600 uppercase tracking-widest mb-2">
                            ✏️ Kiriman Masuk (L)
                        </label>
                        <input type="number" step="0.01"
                            name="<?= $prefix; ?>_kiriman"
                            id="<?= $prefix; ?>_kiriman"
                            value="0" placeholder="0"
                            class="w-full px-3 py-3 bg-white border-2 border-blue-400 rounded-xl text-sm text-right font-black text-blue-700 focus:ring-2 focus:ring-blue-500 transition-all shadow-sm">
                    </div>

                    <!-- 3. Total Tersedia (CALC) -->
                    <div class="lg:col-span-1">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">
                            Total Tersedia (L)
                        </label>
                        <input type="number" step="0.01" readonly
                            id="<?= $prefix; ?>_total"
                            value="<?= $stokAwal; ?>"
                            class="w-full px-3 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm text-right font-black text-slate-600 cursor-not-allowed">
                    </div>

                    <!-- 4. Terjual (AUTO from Harian) -->
                    <div class="lg:col-span-1">
                        <label class="block text-[10px] font-black text-amber-600 uppercase tracking-widest mb-2">
                            Terjual (L) <span class="text-slate-400">⚡ dari Harian</span>
                        </label>
                        <input type="number" step="0.01"
                            name="<?= $prefix; ?>_terjual"
                            id="<?= $prefix; ?>_terjual"
                            value="<?= $terjual; ?>"
                            class="w-full px-3 py-3 bg-amber-50 border border-amber-200 rounded-xl text-sm text-right font-black text-amber-700 focus:ring-2 focus:ring-amber-400 transition-all">
                    </div>

                    <!-- 5. Stok Akhir Teori (CALC) -->
                    <div class="lg:col-span-1">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">
                            Stok Teori (L)
                        </label>
                        <input type="number" step="0.01" readonly
                            id="<?= $prefix; ?>_teori"
                            value="<?= $stokAwal - $terjual; ?>"
                            class="w-full px-3 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm text-right font-black text-slate-600 cursor-not-allowed">
                    </div>

                    <!-- 6. Stok Akhir Fisik (INPUT) -->
                    <div class="lg:col-span-1">
                        <label class="block text-[10px] font-black text-emerald-600 uppercase tracking-widest mb-2">
                            ✏️ Stok Fisik (L)
                        </label>
                        <input type="number" step="0.01"
                            name="<?= $prefix; ?>_stok_fisik"
                            id="<?= $prefix; ?>_fisik"
                            value="0"
                            class="w-full px-3 py-3 bg-white border-2 border-emerald-400 rounded-xl text-sm text-right font-black text-emerald-700 focus:ring-2 focus:ring-emerald-500 transition-all shadow-sm">
                    </div>

                    <!-- 7. Selisih (CALC) -->
                    <div class="lg:col-span-1">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">
                            Selisih (L)
                        </label>
                        <input type="number" step="0.01" readonly
                            id="<?= $prefix; ?>_selisih"
                            value="0"
                            class="w-full px-3 py-3 bg-white border-2 border-slate-200 rounded-xl text-sm text-right font-black transition-all cursor-not-allowed">
                    </div>

                </div>

                <!-- Catatan & Delivery Details -->
                <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6 pt-6 border-t border-slate-50">
                    <div class="md:col-span-1">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">⌚ Jam Kiriman</label>
                        <input type="time" name="<?= $prefix; ?>_jadwal"
                            class="w-full px-4 py-2 bg-white border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 shadow-sm font-bold text-slate-700">
                    </div>
                    <div class="md:col-span-1">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">✏️ Nama Supir</label>
                        <input type="text" name="<?= $prefix; ?>_nama_supir" placeholder="Nama pengemudi truk"
                            class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 shadow-sm">
                    </div>
                    <div class="md:col-span-1">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Catatan (opsional)</label>
                        <input type="text" name="<?= $prefix; ?>_catatan" placeholder="Misal: No.SJ-001, dll."
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-slate-400 transition-all">
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>

        <!-- Submit Button -->
        <div class="fixed bottom-0 left-64 right-0 bg-white/90 backdrop-blur-md p-4 border-t border-slate-100 shadow-xl flex justify-end px-10">
            <button type="submit" class="px-8 py-3 bg-blue-600 text-white rounded-xl font-black text-md hover:bg-blue-700 shadow-lg shadow-blue-500/30 transition-all flex items-center gap-3">
                <i class="fas fa-save"></i> SIMPAN STOK HARI INI
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const groups = ['pertamax', 'dex'];

    groups.forEach(prefix => {
        const awal   = document.getElementById(`${prefix}_stok_awal`);
        const kiriman= document.getElementById(`${prefix}_kiriman`);
        const total  = document.getElementById(`${prefix}_total`);
        const terjual= document.getElementById(`${prefix}_terjual`);
        const teori  = document.getElementById(`${prefix}_teori`);
        const fisik  = document.getElementById(`${prefix}_fisik`);
        const selisih= document.getElementById(`${prefix}_selisih`);

        function recalc() {
            const a = parseFloat(awal.value)    || 0;
            const k = parseFloat(kiriman.value) || 0;
            const t = parseFloat(terjual.value) || 0;
            const f = parseFloat(fisik.value)   || 0;

            const tot  = a + k;
            const teo  = tot - t;
            const sel  = f - teo;

            total.value   = tot.toFixed(2);
            teori.value   = teo.toFixed(2);
            selisih.value = sel.toFixed(2);

            // Color selisih
            if (sel < 0) {
                selisih.className = selisih.className.replace(/text-\w+-\d+/g, '') + ' text-rose-600';
                selisih.style.borderColor = '#f43f5e';
            } else if (sel > 0) {
                selisih.className = selisih.className.replace(/text-\w+-\d+/g, '') + ' text-blue-600';
                selisih.style.borderColor = '#3b82f6';
            } else {
                selisih.className = selisih.className.replace(/text-\w+-\d+/g, '') + ' text-emerald-600';
                selisih.style.borderColor = '#10b981';
            }
        }

        [awal, kiriman, terjual, fisik].forEach(el => el.addEventListener('input', recalc));
        recalc(); // initial
    });

    // Handle date change to reload with new stok awal/terjual info
    document.getElementById('tanggal').addEventListener('change', function() {
        window.location.href = `<?= BASEURL; ?>/stok/input?tanggal=${this.value}`;
    });
});
</script>
