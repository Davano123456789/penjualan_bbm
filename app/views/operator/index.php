<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Manajemen Operator</h2>
            <p class="text-sm text-slate-500">Kelola daftar karyawan/operator yang bertugas di SPBU.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Add Operator Form (Left) -->
        <div class="lg:col-span-1">
            <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm sticky top-6">
                <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-6 border-b border-slate-50 pb-4">Tambah Karyawan Baru</h3>
                <form action="<?= BASEURL; ?>/operator/tambah" method="POST" class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-2">Nama Lengkap</label>
                        <input type="text" name="nama" placeholder="Masukkan nama operator..." required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 transition-all font-bold">
                    </div>
                    <button type="submit" class="w-full py-4 bg-blue-600 text-white rounded-xl font-black text-sm hover:bg-blue-700 shadow-lg shadow-blue-500/20 transition-all flex items-center justify-center gap-2">
                        <i class="fas fa-plus-circle"></i> DAFTARKAN OPERATOR
                    </button>
                </form>

                <div class="mt-8 p-4 bg-amber-50 rounded-xl border border-amber-100 flex gap-3 text-amber-800 text-xs font-medium leading-relaxed">
                    <i class="fas fa-circle-info mt-1 opacity-50"></i>
                    <span>Nama yang didaftarkan di sini akan muncul sebagai pilihan di laporan harian. Pastikan penulisan nama sudah benar.</span>
                </div>
            </div>
        </div>

        <!-- List Operators (Right) -->
        <div class="lg:col-span-2 space-y-4">
            <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-slate-400 text-[11px] uppercase tracking-widest font-extrabold bg-slate-50/50">
                            <th class="px-8 py-5">#ID</th>
                            <th class="px-8 py-5">Nama Operator</th>
                            <th class="px-8 py-5 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        <?php if (empty($data['operator'])) : ?>
                        <tr>
                            <td colspan="3" class="px-8 py-20 text-center text-slate-400 italic">Belum ada operator yang terdaftar.</td>
                        </tr>
                        <?php else : ?>
                        <?php foreach($data['operator'] as $op) : ?>
                        <tr class="hover:bg-slate-50/50 transition-all group">
                            <td class="px-8 py-5 text-xs font-mono text-slate-400">#<?= $op['id']; ?></td>
                            <td class="px-8 py-5">
                                <span class="text-sm font-bold text-slate-800"><?= $op['nama']; ?></span>
                            </td>
                            <td class="px-8 py-5 text-center">
                                <a href="<?= BASEURL; ?>/operator/hapus/<?= $op['id']; ?>" onclick="return confirm('Hapus operator ini?');" class="px-3 py-2 bg-rose-50 text-rose-500 rounded-lg hover:bg-rose-500 hover:text-white transition-all text-xs font-bold shadow-sm">
                                    <i class="fas fa-trash-can mr-1"></i> Hapus
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <p class="text-center text-[10px] font-bold text-slate-400 uppercase tracking-widest">Total: <?= count($data['operator']); ?> Operator Terdaftar</p>
        </div>
    </div>
</div>
