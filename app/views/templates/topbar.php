<!-- Top Header -->
<header class="flex flex-col md:flex-row items-center justify-between gap-6 mb-10 px-6 py-4 bg-white rounded-2xl shadow-sm border border-slate-100">
    <div class="flex items-center gap-6 w-full md:w-auto">
        <div class="flex flex-col">
            <h1 class="text-2xl font-bold text-slate-800 tracking-tight leading-tight">Halo, <?= $_SESSION['user']['nama'] ?? 'User'; ?>!</h1>
            <p class="text-sm font-medium text-slate-500">Laporan penjualan BBM hari ini terlihat stabil.</p>
        </div>
    </div>

    <!-- Search Bar & Tools -->
    <div class="flex items-center gap-6 w-full md:w-auto justify-end">
        <div class="relative hidden sm:block">
            <i class="fas fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
            <input type="text" placeholder="Cari laporan..." class="pl-10 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all w-64">
        </div>
        
        <!-- Notifications -->
        <button class="relative w-10 h-10 flex items-center justify-center bg-slate-50 text-slate-500 rounded-xl hover:bg-slate-100 hover:text-slate-800 transition-all border border-slate-200">
            <i class="fas fa-bell text-lg"></i>
            <span class="absolute top-2 right-2 w-2 h-2 bg-rose-500 rounded-full border-2 border-white"></span>
        </button>

        <!-- User Profile -->
        <div class="flex items-center gap-4 group cursor-pointer pl-4 border-l border-slate-200">
            <div class="flex flex-col items-end">
                <span class="text-sm font-bold text-slate-800"><?= $_SESSION['user']['nama'] ?? 'User'; ?></span>
                <span class="text-xs font-semibold text-blue-600 uppercase tracking-wider"><?= $_SESSION['user']['role'] ?? 'System'; ?></span>
            </div>
            <div class="relative">
                <img class="w-12 h-12 rounded-xl object-cover border-2 border-blue-100 group-hover:border-blue-500 transition-all shadow-sm" src="https://ui-avatars.com/api/?name=<?= $_SESSION['user']['nama'] ?? 'User'; ?>&background=2563eb&color=fff&bold=true" alt="Profile">
                <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-emerald-500 rounded-full border-2 border-white shadow-sm"></div>
            </div>
        </div>
    </div>
</header>
