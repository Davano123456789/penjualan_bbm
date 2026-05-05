<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak - <?= $data['judul']; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: white; }
        @media print {
            .no-print { display: none; }
            body { padding: 0; margin: 1cm; }
            .print-container { width: 100%; max-width: none; border: none; shadow: none; }
        }
        @page {
            size: auto;
            margin: 0;
        }
    </style>
</head>
<body class="p-8">
    <?php 
    $r = $data['report']; 
    
    // Calculate Totals (Replicating JS logic)
    $totalAktiva = (float)$r['kas_spbu'] + (float)$r['koin'] + (float)$r['tabanas_bank'] + (float)$r['inventaris'] + 
                   (float)$r['stok_pertamax_nilai'] + (float)$r['stok_dex_nilai'] + 
                   (float)$r['do_pertamax_nilai'] + (float)$r['do_dex_nilai'] + 
                   (float)$r['modal_oli'] + (float)$r['modal_gas'];

    $totalPasiva = (float)$r['utang_jangka_pendek'] + (float)$r['utang_jangka_panjang'] + 
                   (float)$r['laba_berjalan'] + (float)$r['modal_oli'] + (float)$r['modal_gas'] + 
                   (float)$r['catatan_modal_1'] + (float)$r['catatan_modal_2'];
    ?>

    <div class="print-container max-w-5xl mx-auto">
        <!-- Header -->
        <div class="flex items-center justify-between border-b-4 border-slate-900 pb-4 mb-6">
            <div>
                <h1 class="text-2xl font-black uppercase tracking-tighter text-slate-900">Neraca Perdagangan</h1>
                <p class="text-sm font-medium text-slate-500">SPBU COCO - Laporan Posisi Keuangan</p>
            </div>
            <div class="text-right">
                <p class="text-sm font-black uppercase"><?= date('F', mktime(0,0,0,(int)$data['bulan'],1)); ?> <?= $data['tahun']; ?></p>
                <p class="text-[10px] text-slate-400">Dicetak pada: <?= date('d/m/Y H:i'); ?></p>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-8">
            <!-- AKTIVA -->
            <div class="space-y-6">
                <div class="bg-slate-100 px-4 py-2 border-l-4 border-blue-600">
                    <h2 class="text-sm font-black uppercase tracking-widest text-slate-800">A. AKTIVA (HARTA)</h2>
                </div>

                <div class="space-y-4">
                    <!-- Kas -->
                    <div class="space-y-1">
                        <h3 class="text-[10px] font-black text-slate-400 uppercase">I. Arus Kas</h3>
                        <table class="w-full text-xs">
                            <tr class="border-b border-slate-100">
                                <td class="py-2 text-slate-600">Kas SPBU (Lembaran)</td>
                                <td class="py-2 text-right font-bold text-slate-800">Rp <?= number_format($r['kas_spbu'], 0, ',', '.'); ?></td>
                            </tr>
                            <tr class="border-b border-slate-100">
                                <td class="py-2 text-slate-600">Koin</td>
                                <td class="py-2 text-right font-bold text-slate-800">Rp <?= number_format($r['koin'], 0, ',', '.'); ?></td>
                            </tr>
                            <tr class="border-b border-slate-100">
                                <td class="py-2 text-slate-600">Tabanas Bank</td>
                                <td class="py-2 text-right font-bold text-slate-800">Rp <?= number_format($r['tabanas_bank'], 0, ',', '.'); ?></td>
                            </tr>
                            <tr class="border-b border-slate-100">
                                <td class="py-2 text-slate-600">Inventaris</td>
                                <td class="py-2 text-right font-bold text-slate-800">Rp <?= number_format($r['inventaris'], 0, ',', '.'); ?></td>
                            </tr>
                        </table>
                    </div>

                    <!-- Stok -->
                    <div class="space-y-1">
                        <h3 class="text-[10px] font-black text-slate-400 uppercase">II. Stok Barang Dagangan</h3>
                        <table class="w-full text-xs">
                            <tr class="border-b border-slate-100">
                                <td class="py-2 text-slate-600">
                                    Pertamax 
                                    <span class="text-[9px] text-slate-400 ml-1">(<?= number_format($r['stok_pertamax_liter'], 2, ',', '.'); ?> L)</span>
                                </td>
                                <td class="py-2 text-right font-bold text-slate-800">Rp <?= number_format($r['stok_pertamax_nilai'], 0, ',', '.'); ?></td>
                            </tr>
                            <tr class="border-b border-slate-100">
                                <td class="py-2 text-slate-600">
                                    Dex 
                                    <span class="text-[9px] text-slate-400 ml-1">(<?= number_format($r['stok_dex_liter'], 2, ',', '.'); ?> L)</span>
                                </td>
                                <td class="py-2 text-right font-bold text-slate-800">Rp <?= number_format($r['stok_dex_nilai'], 0, ',', '.'); ?></td>
                            </tr>
                        </table>
                    </div>

                    <!-- DO -->
                    <div class="space-y-1">
                        <h3 class="text-[10px] font-black text-slate-400 uppercase">III. DO BBM (Pesanan)</h3>
                        <table class="w-full text-xs">
                            <tr class="border-b border-slate-100">
                                <td class="py-2 text-slate-600">DO Pertamax</td>
                                <td class="py-2 text-right font-bold text-slate-800">Rp <?= number_format($r['do_pertamax_nilai'], 0, ',', '.'); ?></td>
                            </tr>
                            <tr class="border-b border-slate-100">
                                <td class="py-2 text-slate-600">DO Dex</td>
                                <td class="py-2 text-right font-bold text-slate-800">Rp <?= number_format($r['do_dex_nilai'], 0, ',', '.'); ?></td>
                            </tr>
                        </table>
                    </div>

                    <!-- Lainnya -->
                    <div class="space-y-1">
                        <h3 class="text-[10px] font-black text-slate-400 uppercase">IV. Persediaan Lain</h3>
                        <table class="w-full text-xs">
                            <tr class="border-b border-slate-100">
                                <td class="py-2 text-slate-600">Stok Oli</td>
                                <td class="py-2 text-right font-bold text-slate-800">Rp <?= number_format($r['modal_oli'], 0, ',', '.'); ?></td>
                            </tr>
                            <tr class="border-b border-slate-100">
                                <td class="py-2 text-slate-600">Stok Gas (LPG)</td>
                                <td class="py-2 text-right font-bold text-slate-800">Rp <?= number_format($r['modal_gas'], 0, ',', '.'); ?></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="bg-blue-600 text-white p-4 flex justify-between items-center">
                    <span class="text-[10px] font-black uppercase">Total Aktiva</span>
                    <span class="text-lg font-black">Rp <?= number_format($totalAktiva, 0, ',', '.'); ?></span>
                </div>
            </div>

            <!-- PASIVA -->
            <div class="space-y-6">
                <div class="bg-slate-100 px-4 py-2 border-l-4 border-slate-800">
                    <h2 class="text-sm font-black uppercase tracking-widest text-slate-800">B. PASIVA (UTANG & MODAL)</h2>
                </div>

                <div class="space-y-4">
                    <!-- Utang -->
                    <div class="space-y-1">
                        <h3 class="text-[10px] font-black text-slate-400 uppercase">I. Utang</h3>
                        <table class="w-full text-xs">
                            <tr class="border-b border-slate-100">
                                <td class="py-2 text-slate-600">Utang Jangka Pendek</td>
                                <td class="py-2 text-right font-bold text-slate-800">Rp <?= number_format($r['utang_jangka_pendek'], 0, ',', '.'); ?></td>
                            </tr>
                            <tr class="border-b border-slate-100">
                                <td class="py-2 text-slate-600">Utang Jangka Panjang</td>
                                <td class="py-2 text-right font-bold text-slate-800">Rp <?= number_format($r['utang_jangka_panjang'], 0, ',', '.'); ?></td>
                            </tr>
                        </table>
                    </div>

                    <!-- Modal -->
                    <div class="space-y-1">
                        <h3 class="text-[10px] font-black text-slate-400 uppercase">II. Modal & Laba</h3>
                        <table class="w-full text-xs">
                            <tr class="border-b border-slate-100">
                                <td class="py-2 text-slate-600">Laba Bulan Ini</td>
                                <td class="py-2 text-right font-bold text-emerald-600">Rp <?= number_format($r['laba_berjalan'], 0, ',', '.'); ?></td>
                            </tr>
                            <tr class="border-b border-slate-100">
                                <td class="py-2 text-slate-600">Modal Oli</td>
                                <td class="py-2 text-right font-bold text-slate-800">Rp <?= number_format($r['modal_oli'], 0, ',', '.'); ?></td>
                            </tr>
                            <tr class="border-b border-slate-100">
                                <td class="py-2 text-slate-600">Modal Gas (LPG)</td>
                                <td class="py-2 text-right font-bold text-slate-800">Rp <?= number_format($r['modal_gas'], 0, ',', '.'); ?></td>
                            </tr>
                            <tr class="border-b border-slate-100">
                                <td class="py-2 text-slate-400 italic">Arus Modal 1</td>
                                <td class="py-2 text-right font-bold text-slate-400">Rp <?= number_format($r['catatan_modal_1'], 0, ',', '.'); ?></td>
                            </tr>
                            <tr class="border-b border-slate-100">
                                <td class="py-2 text-slate-400 italic">Arus Modal 2</td>
                                <td class="py-2 text-right font-bold text-slate-400">Rp <?= number_format($r['catatan_modal_2'], 0, ',', '.'); ?></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="bg-slate-800 text-white p-4 flex justify-between items-center">
                    <span class="text-[10px] font-black uppercase">Total Pasiva</span>
                    <span class="text-lg font-black">Rp <?= number_format($totalPasiva, 0, ',', '.'); ?></span>
                </div>
            </div>
        </div>

        <!-- Validation Footer -->
        <div class="mt-12 p-6 border-2 border-slate-100 rounded-2xl flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center text-xl">
                    <i class="fas fa-balance-scale"></i>
                </div>
                <div>
                    <h4 class="text-sm font-black text-slate-800 uppercase italic">Status Keseimbangan</h4>
                    <p class="text-[10px] font-bold text-slate-500">
                        <?php if (abs($totalAktiva - $totalPasiva) < 100): ?>
                            NERACA SEIMBANG (BALANCE)
                        <?php else: ?>
                            BELUM SEIMBANG (SELISIH: Rp <?= number_format(abs($totalAktiva - $totalPasiva), 0, ',', '.'); ?>)
                        <?php endif; ?>
                    </p>
                </div>
            </div>
            <div class="text-right">
                <p class="text-[10px] text-slate-400">Pengesahan,</p>
                <div class="h-16"></div>
                <p class="text-xs font-black text-slate-800 underline">Kepala SPBU COCO</p>
            </div>
        </div>
    </div>

    <!-- Floating Action Button -->
    <div class="fixed bottom-8 right-8 no-print">
        <button onclick="window.print()" class="px-6 py-3 bg-slate-900 text-white rounded-2xl font-bold shadow-2xl hover:bg-slate-800 transition-all flex items-center gap-2">
            <i class="fas fa-print"></i> Print Laporan
        </button>
    </div>
</body>
</html>
