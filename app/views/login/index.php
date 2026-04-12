<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $data['judul']; ?> — Sistem Penjualan BBM</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .glass-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        .bg-pattern {
            background-image: radial-gradient(#3b82f6 0.5px, transparent 0.5px), radial-gradient(#3b82f6 0.5px, #f8fafc 0.5px);
            background-size: 20px 20px;
            background-position: 0 0, 10px 10px;
            opacity: 0.1;
        }
    </style>
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center p-6 relative overflow-hidden">
    
    <!-- Decorative background elements -->
    <div class="absolute inset-0 bg-pattern"></div>
    <div class="absolute -top-24 -left-24 w-96 h-96 bg-blue-100 rounded-full blur-[100px] opacity-50"></div>
    <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-purple-100 rounded-full blur-[100px] opacity-50"></div>

    <div class="w-full max-w-md relative">
        <!-- Logo & Branding -->
        <div class="text-center mb-10">
            <div class="w-20 h-20 bg-blue-600 rounded-3xl shadow-xl shadow-blue-200 flex items-center justify-center mx-auto mb-6 transform rotate-3 hover:rotate-0 transition-all duration-500">
                <i class="fas fa-gas-pump text-3xl text-white"></i>
            </div>
            <h1 class="text-3xl font-extrabold text-slate-800 tracking-tight mb-2">Penyimpanan BBM</h1>
            <p class="text-slate-500 font-medium">Sistem Informasi Pengelolaan & Penjualan</p>
        </div>

        <!-- Login Card -->
        <div class="glass-card rounded-[2.5rem] shadow-2xl shadow-slate-200 border border-white overflow-hidden p-10">
            
            <div class="mb-8">
                <h2 class="text-xl font-bold text-slate-800">Selamat Datang 👋</h2>
                <p class="text-sm text-slate-400 font-medium mt-1">Silakan masuk untuk melanjutkan ke dashboard.</p>
            </div>

            <!-- Flash Message -->
            <div class="mb-6">
                <?php 
                $flash = Flasher::flash(); 
                if ($flash) echo $flash;
                ?>
            </div>

            <form action="<?= BASEURL; ?>/login/process" method="POST" class="space-y-6">
                <div class="space-y-2">
                    <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Username</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-blue-500 transition-colors">
                            <i class="fas fa-user-circle"></i>
                        </div>
                        <input type="text" name="nama" required 
                            class="w-full pl-11 pr-4 py-4 bg-white border-2 border-slate-100 rounded-2xl outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/5 transition-all font-bold text-slate-700"
                            placeholder="Masukkan username">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Password</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-blue-500 transition-colors">
                            <i class="fas fa-lock"></i>
                        </div>
                        <input type="password" name="password" required 
                            class="w-full pl-11 pr-4 py-4 bg-white border-2 border-slate-100 rounded-2xl outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/5 transition-all font-bold text-slate-700"
                            placeholder="********">
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit" 
                        class="w-full py-4 bg-slate-800 text-white rounded-2xl font-black text-sm uppercase tracking-widest shadow-xl shadow-slate-200 hover:bg-slate-700 hover:-translate-y-1 active:translate-y-0 transition-all duration-300 flex items-center justify-center gap-3">
                        Masuk Ke Akun
                        <i class="fas fa-arrow-right text-xs opacity-50"></i>
                    </button>
                </div>
            </form>

            <div class="mt-8 pt-8 border-t border-slate-100 text-center">
                <p class="text-xs text-slate-400 font-bold uppercase tracking-tighter">
                    Lupa password? Hubungi Administrator TI
                </p>
            </div>
        </div>

        <p class="text-center mt-10 text-[10px] font-black text-slate-300 uppercase tracking-widest">
            © 2026 PT Penjualan BBM Maju Jaya — Versi 2.1.0
        </p>
    </div>
</body>
</html>
