<!-- Sidebar -->
<aside id="sidebar" class="fixed left-0 top-0 h-full w-64 bg-slate-900 text-slate-300 transition-all duration-300 z-50 overflow-y-auto">
    <div class="flex items-center gap-3 px-6 py-10 border-b border-slate-800">
        <div class="bg-blue-600 w-10 h-10 rounded-lg flex items-center justify-center text-white">
            <i class="fas fa-gas-pump text-xl text-white"></i>
        </div>
        <h2 class="text-xl font-bold text-white tracking-tight">BBM Sales</h2>
    </div>

    <nav class="mt-8 px-4 pb-10">
        <ul class="space-y-1">
            <li>
                <a href="<?= BASEURL; ?>" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 <?= $data['judul'] == 'Dashboard' ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/50' : 'hover:bg-slate-800 hover:text-white' ?>">
                    <i class="fas fa-house w-5 text-center"></i>
                    <span class="font-medium text-sm">Dashboard</span>
                </a>
            </li>

            <!-- Section: Laporan -->
            <li class="pt-4 pb-2">
                <span class="px-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Menu Laporan</span>
            </li>

            <li>
                <a href="<?= BASEURL; ?>/harian" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 <?= $data['judul'] == 'Laporan Harian' ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/50' : 'hover:bg-slate-800 hover:text-white' ?> group">
                    <i class="fas fa-calendar-day w-5 text-center group-hover:text-blue-500"></i>
                    <span class="font-medium text-sm <?= $data['judul'] == 'Laporan Harian' ? 'text-white' : 'text-slate-400' ?> group-hover:text-white">Harian</span>
                </a>
            </li>
            <li>
                <a href="<?= BASEURL; ?>/stok" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 <?= $data['judul'] == 'Laporan Stok BBM' || $data['judul'] == 'Input Stok BBM' ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/50' : 'hover:bg-slate-800 hover:text-white' ?> group">
                    <i class="fas fa-layer-group w-5 text-center group-hover:text-blue-500"></i>
                    <span class="font-medium text-sm <?= $data['judul'] == 'Laporan Stok BBM' || $data['judul'] == 'Input Stok BBM' ? 'text-white' : 'text-slate-400' ?> group-hover:text-white">Stok</span>
                </a>
            </li>
            <li>
                <a href="<?= BASEURL; ?>/kas" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 <?= $data['judul'] == 'Laporan Buku Kas' ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/50' : 'hover:bg-slate-800 hover:text-white' ?> group">
                    <i class="fas fa-cash-register w-5 text-center group-hover:text-blue-500"></i>
                    <span class="font-medium text-sm <?= $data['judul'] == 'Laporan Buku Kas' ? 'text-white' : 'text-slate-400' ?> group-hover:text-white">Kas</span>
                </a>
            </li>
            <li>
                <a href="<?= BASEURL; ?>/pengeluaran" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 <?= $data['judul'] == 'Daftar Pengeluaran Operasional' ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/50' : 'hover:bg-slate-800 hover:text-white' ?> group">
                    <i class="fas fa-money-bill-trend-up w-5 text-center group-hover:text-blue-500"></i>
                    <span class="font-medium text-sm <?= $data['judul'] == 'Daftar Pengeluaran Operasional' ? 'text-white' : 'text-slate-400' ?> group-hover:text-white">Pengeluaran</span>
                </a>
            </li>
            <li>
                <a href="<?= BASEURL; ?>/losis" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 <?= $data['judul'] == 'Laporan Losis Stok BBM' ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/50' : 'hover:bg-slate-800 hover:text-white' ?> group">
                    <i class="fas fa-droplet-slash w-5 text-center group-hover:text-blue-500"></i>
                    <span class="font-medium text-sm <?= $data['judul'] == 'Laporan Losis Stok BBM' ? 'text-white' : 'text-slate-400' ?> group-hover:text-white">Losis</span>
                </a>
            </li>
            <li>
                <a href="<?= BASEURL; ?>/totakhir" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 <?= $data['judul'] == 'Laporan Laba Rugi Bulanan' ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/50' : 'hover:bg-slate-800 hover:text-white' ?> group">
                    <i class="fas fa-calculator w-5 text-center group-hover:text-blue-500"></i>
                    <span class="font-medium text-sm <?= $data['judul'] == 'Laporan Laba Rugi Bulanan' ? 'text-white' : 'text-slate-400' ?> group-hover:text-white">TOT Akhir</span>
                </a>
            </li>
            <li>
                <a href="<?= BASEURL; ?>/edc" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 <?= $data['judul'] == 'Laporan EDC (QRIS & Debit)' ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/50' : 'hover:bg-slate-800 hover:text-white' ?> group">
                    <i class="fas fa-credit-card w-5 text-center group-hover:text-blue-500"></i>
                    <span class="font-medium text-sm <?= $data['judul'] == 'Laporan EDC (QRIS & Debit)' ? 'text-white' : 'text-slate-400' ?> group-hover:text-white">EDC</span>
                </a>
            </li>
            <li>
                <a href="<?= BASEURL; ?>/kesimpulan" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 <?= $data['judul'] == 'Laporan Kesimpulan Bulanan' ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/50' : 'hover:bg-slate-800 hover:text-white' ?> group">
                    <i class="fas fa-file-contract w-5 text-center group-hover:text-blue-500"></i>
                    <span class="font-medium text-sm <?= $data['judul'] == 'Laporan Kesimpulan Bulanan' ? 'text-white' : 'text-slate-400' ?> group-hover:text-white">Kesimpulan</span>
                </a>
            </li>
            <li>
                <a href="<?= BASEURL; ?>/neraca" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 <?= $data['judul'] == 'Laporan Neraca Bulanan' ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/50' : 'hover:bg-slate-800 hover:text-white' ?> group">
                    <i class="fas fa-balance-scale w-5 text-center group-hover:text-blue-500"></i>
                    <span class="font-medium text-sm <?= $data['judul'] == 'Laporan Neraca Bulanan' ? 'text-white' : 'text-slate-400' ?> group-hover:text-white">Neraca</span>
                </a>
            </li>
            <li>
                <a href="<?= BASEURL; ?>/gaji" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 <?= $data['judul'] == 'Laporan Gaji Karyawan' ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/50' : 'hover:bg-slate-800 hover:text-white' ?> group">
                    <i class="fas fa-money-check-dollar w-5 text-center group-hover:text-blue-500"></i>
                    <span class="font-medium text-sm <?= $data['judul'] == 'Laporan Gaji Karyawan' ? 'text-white' : 'text-slate-400' ?> group-hover:text-white">Gaji</span>
                </a>
            </li>
            <!-- <li>
                <a href="<?= BASEURL; ?>/voucher" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 <?= $data['judul'] == 'Laporan Voucher BBM' ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/50' : 'hover:bg-slate-800 hover:text-white' ?> group">
                    <i class="fas fa-ticket w-5 text-center group-hover:text-blue-500"></i>
                    <span class="font-medium text-sm <?= $data['judul'] == 'Laporan Voucher BBM' ? 'text-white' : 'text-slate-400' ?> group-hover:text-white">Voucher</span>
                </a>
            </li> -->
        </ul>

        <div class="mt-10 pt-8 border-t border-slate-800">
            <h3 class="px-4 text-xs font-semibold text-slate-500 uppercase tracking-wider mb-4">Akun Saya</h3>
            
            <!-- User Info Card -->
            <?php if(isset($_SESSION['user'])): ?>
            <div class="px-4 mb-6">
                <div class="bg-slate-800/50 rounded-2xl p-4 border border-slate-700/50 backdrop-blur-sm">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-black text-sm shadow-lg shadow-blue-500/20">
                            <?= strtoupper(substr($_SESSION['user']['nama'], 0, 1)); ?>
                        </div>
                        <div class="flex flex-col overflow-hidden">
                            <span class="text-xs font-black text-white truncate"><?= $_SESSION['user']['nama']; ?></span>
                            <span class="text-[10px] font-bold text-slate-500 uppercase tracking-tighter"><?= $_SESSION['user']['role']; ?></span>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <ul class="space-y-1">
                <li>
                    <a href="<?= BASEURL; ?>/operator" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 <?= $data['judul'] == 'Daftar Operator' ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/50' : 'hover:bg-slate-800 hover:text-white' ?> group">
                        <i class="fas fa-users-gear w-5 text-center group-hover:text-blue-500"></i>
                        <span class="font-medium text-sm <?= $data['judul'] == 'Daftar Operator' ? 'text-white' : 'text-slate-400' ?> group-hover:text-white">Data Operator</span>
                    </a>
                </li>
                <li>
                    <a href="<?= BASEURL; ?>/login/logout" onclick="return confirm('Apakah Anda yakin ingin keluar?')" class="flex items-center gap-3 px-4 py-3 rounded-lg text-rose-400 hover:bg-rose-500/10 hover:text-rose-500 transition-all duration-200 mt-2">
                        <i class="fas fa-right-from-bracket w-5 text-center"></i>
                        <span class="font-medium text-sm">Keluar Sistem</span>
                    </a>
                </li>
            </ul>
        </div>
    </nav>
</aside>