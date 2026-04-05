<div class="space-y-6 pb-20">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Edit Stok BBM</h2>
            <p class="text-sm text-slate-500">Mengubah rekapan stok hari <span class="font-bold text-blue-600"><?= date('d F Y', strtotime($data['tanggal'])); ?></span>.</p>
        </div>
        <a href="<?= BASEURL; ?>/stok" class="px-4 py-2 bg-slate-100 text-slate-600 rounded-xl text-sm font-bold hover:bg-slate-200 transition-all">
            <i class="fas fa-arrow-left mr-2"></i> Batal
        </a>
    </div>

    <form action="<?= BASEURL; ?>/stok/simpan" method="POST" class="space-y-6">
        <!-- Tanggal (Read Only when editing for integrity) -->
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
            <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Tanggal</label>
            <input type="date" name="tanggal" value="<?= $data['tanggal']; ?>" readonly
                class="w-60 px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold opacity-70 cursor-not-allowed">
            <p class="text-xs text-slate-400 mt-2"><i class="fas fa-info-circle mr-1"></i> Tanggal tidak dapat diubah saat mode edit.</p>
        </div>

        <?php
        $groups = [
            [
                'label'     => 'Pertamax',
                'prefix'    => 'pertamax',
                'icon_color'=> 'bg-blue-600',
                'border'    => 'border-blue-100',
                'row'       => $data['stok_p']
            ],
            [
                'label'     => 'Dex',
                'prefix'    => 'dex',
                'icon_color'=> 'bg-emerald-600',
                'border'    => 'border-emerald-100',
                'row'       => $data['stok_d']
            ],
        ];

        foreach ($groups as $g):
            $prefix    = $g['prefix'];
            $row       = $g['row'];
            $stokAwal  = $row ? floatval($row['stok_awal']) : 0;
            $kiriman   = $row ? floatval($row['kiriman_masuk']) : 0;
            $terjual   = $row ? floatval($row['terjual']) : 0;
            $stokFisik = $row ? floatval($row['stok_akhir_fisik']) : 0;
        ?>
        <!-- CARD: <?= $g['label']; ?> -->
        <div class="bg-white rounded-3xl border <?= $g['border']; ?> shadow-sm overflow-hidden">
            <!-- Header -->
            <div class="px-8 py-4 <?= $g['icon_color']; ?> flex items-center gap-3">
                <i class="fas fa-gas-pump text-white/80"></i>
                <h3 class="text-sm font-black text-white uppercase tracking-widest">⛽ <?= $g['label']; ?></h3>
            </div>

            <div class="p-8">
                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-4 items-end">

                    <!-- 1. Stok Awal -->
                    <div class="lg:col-span-1">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Stok Awal (L)</label>
                        <input type="number" step="0.01" name="<?= $prefix; ?>_stok_awal" id="<?= $prefix; ?>_stok_awal" value="<?= $stokAwal; ?>"
                            class="w-full px-3 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm text-right font-bold focus:ring-2 focus:ring-blue-500">
                    </div>

                    <!-- 2. Kiriman Masuk -->
                    <div class="lg:col-span-1">
                        <label class="block text-[10px] font-black text-blue-600 uppercase tracking-widest mb-2">✏️ Kiriman (L)</label>
                        <input type="number" step="0.01" name="<?= $prefix; ?>_kiriman" id="<?= $prefix; ?>_kiriman" value="<?= $kiriman; ?>"
                            class="w-full px-3 py-3 bg-white border-2 border-blue-400 rounded-xl text-sm text-right font-black text-blue-700 focus:ring-2 focus:ring-blue-500 shadow-sm">
                    </div>

                    <!-- 3. Total Tersedia -->
                    <div class="lg:col-span-1">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Total Tersedia (L)</label>
                        <input type="number" step="0.01" id="<?= $prefix; ?>_total" readonly
                            class="w-full px-3 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm text-right font-black text-slate-600 cursor-not-allowed">
                    </div>

                    <!-- 4. Terjual -->
                    <div class="lg:col-span-1">
                        <label class="block text-[10px] font-black text-amber-600 uppercase tracking-widest mb-2">Terjual (L)</label>
                        <input type="number" step="0.01" name="<?= $prefix; ?>_terjual" id="<?= $prefix; ?>_terjual" value="<?= $terjual; ?>"
                            class="w-full px-3 py-3 bg-white border-2 border-amber-400 rounded-xl text-sm text-right font-black text-amber-700 focus:ring-2 focus:ring-amber-500 shadow-sm">
                    </div>

                    <!-- 5. Stok Akhir Teori -->
                    <div class="lg:col-span-1">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Stok Teori (L)</label>
                        <input type="number" step="0.01" id="<?= $prefix; ?>_teori" readonly
                            class="w-full px-3 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm text-right font-black text-slate-600 cursor-not-allowed">
                    </div>

                    <!-- 6. Stok Akhir Fisik -->
                    <div class="lg:col-span-1">
                        <label class="block text-[10px] font-black text-emerald-600 uppercase tracking-widest mb-2">✏️ Stok Fisik (L)</label>
                        <input type="number" step="0.01" name="<?= $prefix; ?>_stok_fisik" id="<?= $prefix; ?>_fisik" value="<?= $stokFisik; ?>"
                            class="w-full px-3 py-3 bg-white border-2 border-emerald-400 rounded-xl text-sm text-right font-black text-emerald-700 focus:ring-2 focus:ring-emerald-500 shadow-sm">
                    </div>

                    <!-- 7. Selisih -->
                    <div class="lg:col-span-1">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Selisih (L)</label>
                        <input type="number" step="0.01" id="<?= $prefix; ?>_selisih" readonly
                            class="w-full px-3 py-3 bg-white border-2 border-slate-200 rounded-xl text-sm text-right font-black transition-all cursor-not-allowed">
                    </div>
                </div>

                <!-- Catatan & Delivery Details -->
                <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6 pt-6 border-t border-slate-50">
                    <div class="md:col-span-1">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">⌚ Jam Kiriman</label>
                        <input type="time" name="<?= $prefix; ?>_jadwal" value="<?= $row ? $row['jadwal'] : ''; ?>" 
                            class="w-full px-4 py-2 bg-white border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 shadow-sm font-bold text-slate-700">
                    </div>
                    <div class="md:col-span-1">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">✏️ Nama Supir</label>
                        <input type="text" name="<?= $prefix; ?>_nama_supir" value="<?= $row ? $row['nama_supir'] : ''; ?>" 
                            class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 shadow-sm">
                    </div>
                    <div class="md:col-span-1">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Catatan</label>
                        <input type="text" name="<?= $prefix; ?>_catatan" value="<?= $row ? $row['catatan'] : ''; ?>"
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-slate-400">
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>

        <!-- Update Button -->
        <div class="fixed bottom-0 left-64 right-0 bg-white/90 backdrop-blur-md p-4 border-t border-slate-100 shadow-xl flex justify-end px-10">
            <button type="submit" class="px-8 py-3 bg-blue-600 text-white rounded-xl font-black text-md hover:bg-blue-700 shadow-lg shadow-blue-500/30 transition-all flex items-center gap-3">
                <i class="fas fa-save-as"></i> SIMPAN PERUBAHAN
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
});
</script>
