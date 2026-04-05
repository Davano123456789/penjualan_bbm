<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Riwayat Laporan Harian</h2>
            <p class="text-sm text-slate-500">Daftar rekapan harian yang telah tersimpan di sistem.</p>
        </div>
        <a href="<?= BASEURL; ?>/harian/input" class="px-6 py-3 bg-blue-600 text-white rounded-xl font-bold hover:bg-blue-700 shadow-lg shadow-blue-500/30 transition-all flex items-center gap-2">
            <i class="fas fa-plus"></i> Tambah Laporan Baru
        </a>
    </div>

    <!-- History Table -->
    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-slate-400 text-[11px] uppercase tracking-widest font-extrabold bg-slate-50/30">
                        <th class="px-8 py-4">Tanggal</th>
                        <th class="px-8 py-4">Jam Shift</th>
                        <th class="px-8 py-4 text-right">Kas Fisik</th>
                        <th class="px-8 py-4 text-right">Titipan</th>
                        <th class="px-8 py-4 text-right">Pengeluaran</th>
                        <th class="px-8 py-4 text-right">SISA</th>
                        <th class="px-8 py-4 text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <?php if (empty($data['harian'])) : ?>
                    <tr>
                        <td colspan="7" class="px-8 py-20 text-center">
                            <div class="flex flex-col items-center gap-4 text-slate-400">
                                <i class="fas fa-folder-open text-5xl opacity-20"></i>
                                <p class="font-medium">Belum ada data laporan harian.</p>
                                <a href="<?= BASEURL; ?>/harian/input" class="text-blue-600 hover:underline font-bold text-sm">Input Sekarang →</a>
                            </div>
                        </td>
                    </tr>
                    <?php else : ?>
                    <?php foreach ($data['harian'] as $h) : ?>
                    <tr class="hover:bg-slate-50/80 transition-all group">
                        <td class="px-8 py-5">
                            <div class="flex flex-col">
                                <span class="text-sm font-bold text-slate-800"><?= date('d M Y', strtotime($h['tanggal'])); ?></span>
                                <span class="text-[10px] text-slate-400 font-bold uppercase tracking-tighter">Harian</span>
                            </div>
                        </td>
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-2 text-xs font-bold text-slate-500">
                                <i class="fas fa-clock text-[10px]"></i>
                                <?= $h['jam_masuk']; ?> - <?= $h['jam_keluar']; ?>
                            </div>
                        </td>
                        <td class="px-8 py-5 text-right font-bold text-slate-700 text-sm">
                            Rp <?= number_format($h['total_penerimaan_kas'], 0, ',', '.'); ?>
                        </td>
                        <td class="px-8 py-5 text-right font-medium text-slate-500 text-sm">
                            Rp <?= number_format($h['total_titipan'], 0, ',', '.'); ?>
                        </td>
                        <td class="px-8 py-5 text-right font-medium text-rose-500 text-sm">
                            Rp <?= number_format($h['total_pengeluaran'], 0, ',', '.'); ?>
                        </td>
                        <td class="px-8 py-5 text-right">
                            <span class="px-3 py-1 bg-blue-50 text-blue-700 rounded-lg text-sm font-black italic">
                                Rp <?= number_format($h['total_sisa'], 0, ',', '.'); ?>
                            </span>
                        </td>
                        <td class="px-8 py-5 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="<?= BASEURL; ?>/harian/detail/<?= $h['id']; ?>" class="w-8 h-8 rounded-lg bg-slate-50 flex items-center justify-center text-slate-400 hover:bg-emerald-500 hover:text-white transition-all shadow-sm" title="Lihat Detail">
                                    <i class="fas fa-eye text-xs"></i>
                                </a>
                                <form action="<?= BASEURL; ?>/harian/hapus/<?= $h['id']; ?>" method="POST" class="inline-block form-hapus">
                                    <button type="button" class="btn-hapus w-8 h-8 rounded-lg bg-slate-50 flex items-center justify-center text-slate-400 hover:bg-rose-500 hover:text-white transition-all shadow-sm" title="Hapus Laporan">
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- SweetAlert Delete Confirmation Script -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const deleteButtons = document.querySelectorAll('.btn-hapus');
        
        deleteButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const form = this.closest('.form-hapus');
                
                Swal.fire({
                    title: 'Apakah Anda Yakin?',
                    text: 'Data laporan harian ini beserta rincian mesinnya akan dihapus permanen!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444', // Tailwind rose-500
                    cancelButtonColor: '#94a3b8', // Tailwind slate-400
                    confirmButtonText: '<i class="fas fa-trash"></i> Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    });
</script>
