<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Gaji Karyawan</h2>
            <p class="text-sm text-slate-500 font-medium">Rekapitulasi penggajian, lembur, dan kas bon harian.</p>
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
        </div>
    </div>

    <!-- Alert -->
    <div class="mb-4">
        <?= Flasher::flash(); ?>
    </div>

    <!-- Main Table Card -->
    <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-50 bg-slate-50/30 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-emerald-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-emerald-200">
                    <i class="fas fa-wallet text-xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-slate-800 uppercase tracking-tight">Daftar Penggajian Bulanan</h3>
                    <p class="text-[10px] text-slate-400 font-black uppercase tracking-widest">Periode: <?= date('F', mktime(0,0,0,$data['bulan'],1)); ?> <?= $data['tahun']; ?></p>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 text-slate-400 text-[10px] uppercase font-black tracking-widest border-b border-slate-100">
                        <th class="px-6 py-4">Nama Operator</th>
                        <th class="px-6 py-4 text-right">Besaran Gaji (Pokok)</th>
                        <th class="px-6 py-4 text-center">Lembur (+)</th>
                        <th class="px-6 py-4 text-right">Kas Bon (-)</th>
                        <th class="px-6 py-4 text-right text-slate-800">Sisa Gaji (Net)</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <?php 
                    $total_setoran = 0;
                    if (empty($data['gaji'])): ?>
                    <tr>
                        <td colspan="6" class="px-6 py-20 text-center text-slate-300 font-bold italic">Belum ada data operator...</td>
                    </tr>
                    <?php endif; ?>
                    
                    <?php foreach ($data['gaji'] as $g): ?>
                    <?php 
                        $is_saved = !empty($g['gaji_record_id']);
                        $default_gp = $g['gaji_pokok_prev'] ?: 0;
                        $current_gp = $is_saved ? $g['gaji_pokok'] : $default_gp;
                        $total_net = $is_saved ? $g['total_diterima'] : ($current_gp + ($g['lembur'] ?: 0) - ($g['kas_bon'] ?: 0));
                    ?>
                    <tr class="<?= $is_saved ? 'bg-white' : 'bg-slate-50/30' ?> hover:bg-slate-50/50 transition-all">
                        <td class="px-6 py-6">
                            <div class="font-bold text-slate-700"><?= $g['nama']; ?></div>
                            <?php if ($is_saved): ?>
                                <span class="text-[9px] font-black uppercase tracking-widest text-emerald-500 flex items-center gap-1 mt-1">
                                    <i class="fas fa-check-circle"></i> Tersimpan
                                </span>
                            <?php else: ?>
                                <span class="text-[9px] font-black uppercase tracking-widest text-slate-400 flex items-center gap-1 mt-1">
                                    <i class="fas fa-clock"></i> Draft / Belum Diinput
                                </span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-6 text-right font-black <?= $is_saved ? 'text-slate-600' : 'text-slate-300' ?>">
                            Rp <?= number_format($current_gp, 0, ',', '.'); ?>
                        </td>
                        <td class="px-6 py-6 text-center">
                            <?php if ($is_saved && $g['lembur'] > 0): ?>
                                <span class="px-3 py-1 bg-emerald-50 text-emerald-600 rounded-lg text-xs font-bold font-mono">
                                    +Rp <?= number_format($g['lembur'], 0, ',', '.'); ?>
                                </span>
                            <?php else: ?>
                                <span class="text-slate-300">-</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-6 text-right font-bold <?= $is_saved ? 'text-rose-500' : 'text-slate-300' ?>">
                            <?php if ($is_saved && $g['kas_bon'] > 0): ?>
                                -Rp <?= number_format($g['kas_bon'], 0, ',', '.'); ?>
                            <?php else: ?>
                                <span class="text-slate-200">Rp 0</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-6 text-right font-black text-slate-900 group">
                            <div class="px-4 py-2 <?= $is_saved ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : 'bg-slate-100 text-slate-400 opacity-50' ?> inline-block rounded-xl group-hover:bg-blue-600 group-hover:text-white group-hover:opacity-100 transition-all shadow-sm">
                                Rp <?= number_format($total_net, 0, ',', '.'); ?>
                            </div>
                        </td>
                        <td class="px-6 py-6 text-center">
                            <button onclick="editGaji(<?= htmlspecialchars(json_encode([
                                'id' => $g['operator_id'],
                                'nama' => $g['nama'],
                                'gaji_pokok' => $current_gp,
                                'lembur' => $is_saved ? $g['lembur'] : 0,
                                'kas_bon' => $is_saved ? $g['kas_bon'] : 0
                            ])); ?>)" 
                                class="w-10 h-10 <?= $is_saved ? 'bg-blue-50 text-blue-600' : 'bg-slate-100 text-slate-400' ?> rounded-xl hover:bg-blue-600 hover:text-white transition-all shadow-sm flex items-center justify-center mx-auto">
                                <i class="fas <?= $is_saved ? 'fa-edit' : 'fa-plus' ?>"></i>
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Input/Edit Gaji -->
    <div id="modalGaji" class="hidden fixed inset-0 z-50 overflow-auto bg-slate-900/60 flex items-center justify-center backdrop-blur-sm">
        <div class="bg-white rounded-[2.5rem] shadow-2xl p-8 w-full max-w-md m-4 border border-slate-100 animate__animated animate__fadeInUp animate__faster">
            <div class="flex items-center justify-between mb-8">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-blue-600 rounded-2xl flex items-center justify-center text-white shadow-xl shadow-blue-200">
                        <i class="fas fa-file-invoice-dollar text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-black text-slate-800" id="modalTitle">Atur Gaji</h3>
                        <p class="text-xs text-slate-400 font-bold uppercase tracking-widest" id="operatorName"></p>
                    </div>
                </div>
                <button onclick="closeModal()" class="w-10 h-10 flex items-center justify-center rounded-2xl bg-slate-50 text-slate-400 hover:bg-rose-50 hover:text-rose-500 transition-all">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form action="<?= BASEURL; ?>/gaji/save" method="POST" class="space-y-6">
                <input type="hidden" name="operator_id" id="form_operator_id">
                <input type="hidden" name="bulan" value="<?= $data['bulan']; ?>">
                <input type="hidden" name="tahun" value="<?= $data['tahun']; ?>">

                <div class="space-y-4">
                    <div class="group">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1 ml-1">Gaji Pokok (Rp)</label>
                        <input type="number" name="gaji_pokok" id="form_gaji_pokok" required
                            class="w-full px-5 py-4 bg-slate-50 border-2 border-slate-50 rounded-2xl text-slate-800 font-black focus:bg-white focus:border-blue-500 transition-all outline-none">
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div class="group">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1 ml-1">Lembur (+)</label>
                            <input type="number" name="lembur" id="form_lembur" value="0"
                                class="w-full px-5 py-4 bg-slate-50 border-2 border-slate-50 rounded-2xl text-emerald-600 font-black focus:bg-white focus:border-emerald-500 transition-all outline-none">
                        </div>
                        <div class="group">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1 ml-1">Kas Bon (-)</label>
                            <input type="number" name="kas_bon" id="form_kas_bon" value="0"
                                class="w-full px-5 py-4 bg-slate-50 border-2 border-slate-50 rounded-2xl text-rose-500 font-black focus:bg-white focus:border-rose-500 transition-all outline-none">
                        </div>
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full py-5 bg-slate-800 text-white rounded-[1.5rem] font-black text-sm uppercase tracking-widest shadow-xl shadow-slate-200 hover:bg-slate-700 hover:-translate-y-1 transition-all active:translate-y-0">
                        <i class="fas fa-check-circle mr-2"></i> Simpan Laporan Gaji
                    </button>
                    <p class="text-center text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-4">Angka 'Sisa Gaji' Akan Terhitung Otomatis</p>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const modal = document.getElementById('modalGaji');

    function editGaji(data) {
        document.getElementById('form_operator_id').value = data.id;
        document.getElementById('operatorName').innerText = data.nama;
        document.getElementById('form_gaji_pokok').value = data.gaji_pokok;
        document.getElementById('form_lembur').value = data.lembur;
        document.getElementById('form_kas_bon').value = data.kas_bon;
        
        modal.classList.remove('hidden');
    }

    function closeModal() {
        modal.classList.add('hidden');
    }

    window.onclick = function(event) {
        if (event.target == modal) {
            closeModal();
        }
    }
</script>
