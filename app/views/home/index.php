<!-- Dashboard Stats Grid -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
    <!-- Card: Total Users (DB Test) -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-all group overflow-hidden relative">
        <div class="absolute -right-4 -top-4 w-20 h-20 bg-blue-50 rounded-full group-hover:scale-110 transition-all opacity-50"></div>
        <div class="flex items-center gap-4 relative z-10">
            <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center text-xl group-hover:bg-blue-600 group-hover:text-white transition-all">
                <i class="fas fa-users"></i>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total User Sistem</p>
                <p class="text-xl font-bold text-slate-800"><?= $data['total_users']; ?> User</p>
            </div>
        </div>
        <div class="mt-4 flex items-center gap-2 text-xs font-bold text-emerald-600">
            <i class="fas fa-check-circle"></i>
            <span>Database Terhubung</span>
        </div>
    </div>

    <!-- Card: Pertalite -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-all group overflow-hidden relative">
        <div class="absolute -right-4 -top-4 w-20 h-20 bg-amber-50 rounded-full group-hover:scale-110 transition-all opacity-50"></div>
        <div class="flex items-center gap-4 relative z-10">
            <div class="w-12 h-12 bg-amber-100 text-amber-600 rounded-xl flex items-center justify-center text-xl group-hover:bg-amber-600 group-hover:text-white transition-all">
                <i class="fas fa-gas-pump"></i>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Stok Pertalite</p>
                <p class="text-xl font-bold text-slate-800">4.250 L</p>
            </div>
        </div>
        <div class="mt-4 w-full bg-slate-100 h-1.5 rounded-full overflow-hidden">
            <div class="bg-amber-500 h-full w-[65%] rounded-full"></div>
        </div>
    </div>

    <!-- Card: Pertamax -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-all group overflow-hidden relative">
        <div class="absolute -right-4 -top-4 w-20 h-20 bg-emerald-50 rounded-full group-hover:scale-110 transition-all opacity-50"></div>
        <div class="flex items-center gap-4 relative z-10">
            <div class="w-12 h-12 bg-emerald-100 text-emerald-600 rounded-xl flex items-center justify-center text-xl group-hover:bg-emerald-600 group-hover:text-white transition-all">
                <i class="fas fa-bolt"></i>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Stok Pertamax</p>
                <p class="text-xl font-bold text-slate-800">2.100 L</p>
            </div>
        </div>
        <div class="mt-4 w-full bg-slate-100 h-1.5 rounded-full overflow-hidden">
            <div class="bg-emerald-500 h-full w-[40%] rounded-full"></div>
        </div>
    </div>

    <!-- Card: Revenue -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-all group overflow-hidden relative">
        <div class="absolute -right-4 -top-4 w-20 h-20 bg-rose-50 rounded-full group-hover:scale-110 transition-all opacity-50"></div>
        <div class="flex items-center gap-4 relative z-10">
            <div class="w-12 h-12 bg-rose-100 text-rose-600 rounded-xl flex items-center justify-center text-xl group-hover:bg-rose-600 group-hover:text-white transition-all">
                <i class="fas fa-chart-line"></i>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Omzet Bulanan</p>
                <p class="text-xl font-bold text-slate-800">Rp 128.5M</p>
            </div>
        </div>
        <div class="mt-4 flex items-center gap-2 text-xs font-bold text-slate-500">
            <i class="fas fa-clock"></i>
            <span>Update 5m yang lalu</span>
        </div>
    </div>
</div>

<!-- Recent Transactions Table -->
<div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
    <div class="px-8 py-6 border-b border-slate-50 flex items-center justify-between bg-slate-50/50">
        <div>
            <h2 class="text-lg font-bold text-slate-800">Transaksi Penjualan Terbaru</h2>
            <p class="text-xs font-medium text-slate-500">Data real-time dari terminal pengisian.</p>
        </div>
        <button class="px-4 py-2 bg-white border border-slate-200 rounded-xl text-sm font-bold text-slate-600 hover:bg-slate-50 transition-all shadow-sm">
            Lihat Semua <i class="fas fa-arrow-right ml-2 text-[10px]"></i>
        </button>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="text-slate-400 text-[11px] uppercase tracking-widest font-extrabold bg-slate-50/30">
                    <th class="px-8 py-4">Waktu</th>
                    <th class="px-8 py-4">Jenis BBM</th>
                    <th class="px-8 py-4">Jumlah</th>
                    <th class="px-8 py-4">Total Harga</th>
                    <th class="px-8 py-4 text-center">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                <tr class="hover:bg-slate-50/80 transition-all group">
                    <td class="px-8 py-4 text-sm font-bold text-slate-600 italic">09:15:22</td>
                    <td class="px-8 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-amber-100 text-amber-600 flex items-center justify-center text-xs font-bold">PL</div>
                            <span class="text-sm font-bold text-slate-700">Pertalite</span>
                        </div>
                    </td>
                    <td class="px-8 py-4 text-sm font-extrabold text-slate-700 font-mono">15.5 L</td>
                    <td class="px-8 py-4 text-sm font-extrabold text-emerald-600">Rp 155.000</td>
                    <td class="px-8 py-4 text-center">
                        <span class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-[10px] font-black uppercase tracking-tighter shadow-sm border border-emerald-200/50">Berhasil</span>
                    </td>
                </tr>
                <tr class="hover:bg-slate-50/80 transition-all group">
                    <td class="px-8 py-4 text-sm font-bold text-slate-600 italic">09:02:10</td>
                    <td class="px-8 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center text-xs font-bold">PM</div>
                            <span class="text-sm font-bold text-slate-700">Pertamax</span>
                        </div>
                    </td>
                    <td class="px-8 py-4 text-sm font-extrabold text-slate-700 font-mono">20.0 L</td>
                    <td class="px-8 py-4 text-sm font-extrabold text-emerald-600">Rp 260.000</td>
                    <td class="px-8 py-4 text-center">
                        <span class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-[10px] font-black uppercase tracking-tighter shadow-sm border border-emerald-200/50">Berhasil</span>
                    </td>
                </tr>
                <tr class="hover:bg-slate-50/80 transition-all group">
                    <td class="px-8 py-4 text-sm font-bold text-slate-600 italic">08:55:04</td>
                    <td class="px-8 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center text-xs font-bold">DX</div>
                            <span class="text-sm font-bold text-slate-700">Dexlite</span>
                        </div>
                    </td>
                    <td class="px-8 py-4 text-sm font-extrabold text-slate-700 font-mono">50.0 L</td>
                    <td class="px-8 py-4 text-sm font-extrabold text-emerald-600">Rp 750.000</td>
                    <td class="px-8 py-4 text-center">
                        <span class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-[10px] font-black uppercase tracking-tighter shadow-sm border border-emerald-200/50">Berhasil</span>
                    </td>
                </tr>
                <tr class="hover:bg-slate-50/80 transition-all group">
                    <td class="px-8 py-4 text-sm font-bold text-slate-600 italic">08:42:15</td>
                    <td class="px-8 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-amber-100 text-amber-600 flex items-center justify-center text-xs font-bold">PL</div>
                            <span class="text-sm font-bold text-slate-700">Pertalite</span>
                        </div>
                    </td>
                    <td class="px-8 py-4 text-sm font-extrabold text-slate-700 font-mono">5.2 L</td>
                    <td class="px-8 py-4 text-sm font-extrabold text-slate-700/50">Rp 52.000</td>
                    <td class="px-8 py-4 text-center">
                        <span class="px-3 py-1 bg-amber-100 text-amber-700 rounded-full text-[10px] font-black uppercase tracking-tighter shadow-sm border border-amber-200/50">Pending</span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
