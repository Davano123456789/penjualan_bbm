-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 12 Apr 2026 pada 12.22
-- Versi server: 10.4.24-MariaDB
-- Versi PHP: 8.2.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `laporan_bbm`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `edc`
--

CREATE TABLE `edc` (
  `id` int(11) NOT NULL,
  `kas_transaksi_id` int(10) UNSIGNED DEFAULT NULL,
  `kas_id` int(10) UNSIGNED DEFAULT NULL,
  `konfigurasi_edc_id` int(10) UNSIGNED NOT NULL,
  `tanggal` date NOT NULL,
  `metode` enum('qris','debit') NOT NULL,
  `nominal` decimal(14,2) NOT NULL DEFAULT 0.00,
  `persen_potongan` decimal(5,3) NOT NULL,
  `jumlah_masuk` decimal(14,2) NOT NULL DEFAULT 0.00,
  `jumlah_potongan` decimal(14,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `edc`
--

INSERT INTO `edc` (`id`, `kas_transaksi_id`, `kas_id`, `konfigurasi_edc_id`, `tanggal`, `metode`, `nominal`, `persen_potongan`, `jumlah_masuk`, `jumlah_potongan`) VALUES
(1, NULL, 11, 1, '2026-03-01', 'qris', '107000.00', '0.300', '106679.00', '321.00'),
(2, NULL, 13, 2, '2026-03-01', 'debit', '300000.00', '0.500', '298500.00', '1500.00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `gaji`
--

CREATE TABLE `gaji` (
  `id` int(11) NOT NULL,
  `operator_id` int(10) UNSIGNED NOT NULL,
  `periode` date NOT NULL,
  `gaji_pokok` decimal(12,2) NOT NULL DEFAULT 0.00,
  `lembur` decimal(12,2) NOT NULL DEFAULT 0.00,
  `kas_bon` decimal(12,2) NOT NULL DEFAULT 0.00,
  `total_diterima` decimal(12,2) GENERATED ALWAYS AS (`gaji_pokok` + `lembur` - `kas_bon`) STORED
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `gaji`
--

INSERT INTO `gaji` (`id`, `operator_id`, `periode`, `gaji_pokok`, `lembur`, `kas_bon`) VALUES
(1, 5, '2026-03-01', '2000000.00', '0.00', '0.00'),
(2, 5, '2026-04-01', '2000000.00', '0.00', '0.00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `gaji_komponen`
--

CREATE TABLE `gaji_komponen` (
  `id` int(11) NOT NULL,
  `operator_id` int(10) UNSIGNED NOT NULL,
  `gaji_pokok_standar` decimal(12,2) NOT NULL DEFAULT 0.00,
  `berlaku_mulai` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `harian`
--

CREATE TABLE `harian` (
  `id` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `operator1_id` int(10) UNSIGNED DEFAULT NULL,
  `operator2_id` int(10) UNSIGNED DEFAULT NULL,
  `jam_masuk` time DEFAULT NULL,
  `jam_keluar` time DEFAULT NULL,
  `total_penerimaan_kas` decimal(14,2) DEFAULT 0.00,
  `total_pengeluaran` decimal(14,2) DEFAULT 0.00,
  `total_titipan` decimal(14,2) DEFAULT 0.00,
  `total_sisa` decimal(14,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `harian`
--

INSERT INTO `harian` (`id`, `tanggal`, `operator1_id`, `operator2_id`, `jam_masuk`, `jam_keluar`, `total_penerimaan_kas`, `total_pengeluaran`, `total_titipan`, `total_sisa`) VALUES
(4, '2026-03-01', 5, 6, '05:00:00', '21:00:00', '10718000.00', '407000.00', '10000000.00', '10000000.00'),
(5, '2026-03-02', 5, 6, '05:00:00', '21:00:00', '12725000.00', '573000.00', '12000000.00', '12000000.00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kas`
--

CREATE TABLE `kas` (
  `id` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `keterangan` varchar(255) NOT NULL,
  `tipe` enum('debit','kredit') NOT NULL,
  `jumlah` decimal(15,2) NOT NULL,
  `kategori_biaya` enum('Operasional','Curah','Lainnya') DEFAULT NULL,
  `transaksi_id` varchar(50) DEFAULT NULL,
  `kategori` enum('lemari','arus') NOT NULL DEFAULT 'lemari',
  `harian_id` int(10) UNSIGNED DEFAULT NULL,
  `pengeluaran_id` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `kas`
--

INSERT INTO `kas` (`id`, `tanggal`, `keterangan`, `tipe`, `jumlah`, `kategori_biaya`, `transaksi_id`, `kategori`, `harian_id`, `pengeluaran_id`, `created_at`) VALUES
(4, '2026-03-01', 'Penjualan (Penjualan Harian)', 'debit', '11125000.00', NULL, NULL, 'lemari', 4, NULL, '2026-04-12 07:59:03'),
(5, '2026-03-01', 'Penjualan (Penjualan Harian)', 'debit', '11125000.00', NULL, NULL, 'arus', 4, NULL, '2026-04-12 07:59:03'),
(6, '2026-03-02', 'Penjualan (Penjualan Harian)', 'debit', '13298000.00', NULL, NULL, 'lemari', 5, NULL, '2026-04-12 08:06:47'),
(7, '2026-03-02', 'Penjualan (Penjualan Harian)', 'debit', '13298000.00', NULL, NULL, 'arus', 5, NULL, '2026-04-12 08:06:47'),
(8, '2026-03-01', 'Sisa kas', 'debit', '3905000.00', NULL, NULL, 'lemari', NULL, NULL, '2026-04-12 08:16:48'),
(9, '2026-03-01', 'Sisa kas', 'debit', '14305000.00', NULL, NULL, 'arus', NULL, NULL, '2026-04-12 08:17:55'),
(10, '2026-03-01', 'Qris', 'kredit', '107000.00', NULL, 'TRX-1775981991-ba0a534b', 'lemari', NULL, NULL, '2026-04-12 08:19:51'),
(11, '2026-03-01', 'Qris', 'kredit', '107000.00', NULL, 'TRX-1775981991-ba0a534b', 'arus', NULL, NULL, '2026-04-12 08:19:51'),
(12, '2026-03-01', 'Debit', 'kredit', '300000.00', NULL, 'TRX-1775982050-d3d9eb95', 'lemari', NULL, NULL, '2026-04-12 08:20:50'),
(13, '2026-03-01', 'Debit', 'kredit', '300000.00', NULL, 'TRX-1775982050-d3d9eb95', 'arus', NULL, NULL, '2026-04-12 08:20:50'),
(14, '2026-03-12', 'Setoran', 'kredit', '10000000.00', NULL, NULL, 'lemari', NULL, NULL, '2026-04-12 08:21:46');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kas_saldo`
--

CREATE TABLE `kas_saldo` (
  `id` int(11) NOT NULL,
  `harian_id` int(10) UNSIGNED NOT NULL,
  `tanggal` date NOT NULL,
  `jenis_kas` enum('kas_lemari','arus_kas') NOT NULL,
  `saldo_awal` decimal(14,2) NOT NULL DEFAULT 0.00,
  `saldo_akhir` decimal(14,2) NOT NULL DEFAULT 0.00,
  `uang_lembar` decimal(14,2) NOT NULL DEFAULT 0.00,
  `uang_koin` decimal(14,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `kas_transaksi`
--

CREATE TABLE `kas_transaksi` (
  `id` int(11) NOT NULL,
  `harian_id` int(10) UNSIGNED NOT NULL,
  `tanggal` date NOT NULL,
  `keterangan` varchar(150) NOT NULL,
  `jenis_kas` enum('kas_lemari','arus_kas') NOT NULL,
  `kategori` enum('penjualan','qris','debit','setoran','stor_bank','pengeluaran','voucher','lainnya') NOT NULL,
  `debit` decimal(14,2) NOT NULL DEFAULT 0.00,
  `kredit` decimal(14,2) NOT NULL DEFAULT 0.00,
  `saldo_berjalan` decimal(14,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `kiriman_bbm`
--

CREATE TABLE `kiriman_bbm` (
  `id` int(11) NOT NULL,
  `produk_id` int(10) UNSIGNED NOT NULL,
  `tanggal_kiriman` date NOT NULL,
  `jumlah_liter` decimal(10,2) NOT NULL DEFAULT 0.00,
  `nama_supir` varchar(100) DEFAULT NULL,
  `no_do` varchar(50) DEFAULT NULL,
  `keterangan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `konfigurasi_edc`
--

CREATE TABLE `konfigurasi_edc` (
  `id` int(11) NOT NULL,
  `metode` enum('qris','debit') NOT NULL,
  `persen_potongan` decimal(5,3) NOT NULL,
  `berlaku_mulai` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `konfigurasi_edc`
--

INSERT INTO `konfigurasi_edc` (`id`, `metode`, `persen_potongan`, `berlaku_mulai`) VALUES
(1, 'qris', '0.300', '2026-01-01'),
(2, 'debit', '0.500', '2026-01-01');

-- --------------------------------------------------------

--
-- Struktur dari tabel `laporan_bulanan`
--

CREATE TABLE `laporan_bulanan` (
  `id` int(11) NOT NULL,
  `periode` date NOT NULL,
  `total_pendapatan_kotor` decimal(16,2) NOT NULL DEFAULT 0.00,
  `laba_pertamax` decimal(16,2) NOT NULL DEFAULT 0.00,
  `laba_dex` decimal(16,2) NOT NULL DEFAULT 0.00,
  `total_laba_kotor` decimal(16,2) NOT NULL DEFAULT 0.00,
  `biaya_gaji` decimal(16,2) NOT NULL DEFAULT 0.00,
  `biaya_kas` decimal(16,2) NOT NULL DEFAULT 0.00,
  `biaya_pph` decimal(16,2) NOT NULL DEFAULT 0.00,
  `biaya_admin_edc` decimal(16,2) NOT NULL DEFAULT 0.00,
  `total_pengeluaran` decimal(16,2) NOT NULL DEFAULT 0.00,
  `total_losis_rupiah` decimal(16,2) NOT NULL DEFAULT 0.00,
  `laba_bersih` decimal(16,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `laporan_bulanan`
--

INSERT INTO `laporan_bulanan` (`id`, `periode`, `total_pendapatan_kotor`, `laba_pertamax`, `laba_dex`, `total_laba_kotor`, `biaya_gaji`, `biaya_kas`, `biaya_pph`, `biaya_admin_edc`, `total_pengeluaran`, `total_losis_rupiah`, `laba_bersih`) VALUES
(1, '2026-03-01', '0.00', '540000.00', '6000.00', '546000.00', '2000000.00', '0.00', '0.00', '0.00', '2000000.00', '0.00', '-1454000.00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `losis`
--

CREATE TABLE `losis` (
  `id` int(11) NOT NULL,
  `produk_id` int(10) UNSIGNED NOT NULL,
  `periode` date NOT NULL,
  `stok_awal` decimal(10,2) NOT NULL DEFAULT 0.00,
  `do_penebusan` decimal(10,2) NOT NULL DEFAULT 0.00,
  `jumlah_stok` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_penjualan` decimal(10,2) NOT NULL DEFAULT 0.00,
  `stok_akhir` decimal(10,2) NOT NULL DEFAULT 0.00,
  `jumlah_terjual_akhir` decimal(10,2) NOT NULL DEFAULT 0.00,
  `losis` decimal(10,2) NOT NULL DEFAULT 0.00,
  `persentase_losis` decimal(7,4) NOT NULL DEFAULT 0.0000
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `neraca`
--

CREATE TABLE `neraca` (
  `id` int(11) NOT NULL,
  `periode` date NOT NULL,
  `kas_spbu` decimal(16,2) NOT NULL DEFAULT 0.00,
  `koin` decimal(16,2) NOT NULL DEFAULT 0.00,
  `tabanas_bank` decimal(16,2) NOT NULL DEFAULT 0.00,
  `inventaris` decimal(16,2) NOT NULL DEFAULT 0.00,
  `total_arus_kas` decimal(16,2) NOT NULL DEFAULT 0.00,
  `stok_pertamax_liter` decimal(10,2) NOT NULL DEFAULT 0.00,
  `stok_pertamax_nilai` decimal(16,2) NOT NULL DEFAULT 0.00,
  `stok_dex_liter` decimal(10,2) NOT NULL DEFAULT 0.00,
  `stok_dex_nilai` decimal(16,2) NOT NULL DEFAULT 0.00,
  `total_stok_nilai` decimal(16,2) NOT NULL DEFAULT 0.00,
  `do_pertamax_liter` decimal(10,2) NOT NULL DEFAULT 0.00,
  `do_pertamax_nilai` decimal(16,2) NOT NULL DEFAULT 0.00,
  `do_dex_liter` decimal(10,2) NOT NULL DEFAULT 0.00,
  `do_dex_nilai` decimal(16,2) NOT NULL DEFAULT 0.00,
  `total_do_nilai` decimal(16,2) NOT NULL DEFAULT 0.00,
  `utang_jangka_pendek` decimal(16,2) NOT NULL DEFAULT 0.00,
  `utang_jangka_panjang` decimal(16,2) NOT NULL DEFAULT 0.00,
  `modal_oli` decimal(16,2) NOT NULL DEFAULT 0.00,
  `modal_gas` decimal(16,2) NOT NULL DEFAULT 0.00,
  `jumlah_harta_lancar` decimal(16,2) NOT NULL DEFAULT 0.00,
  `catatan_modal_1` decimal(16,2) NOT NULL DEFAULT 0.00,
  `catatan_modal_2` decimal(16,2) NOT NULL DEFAULT 0.00,
  `total_modal` decimal(16,2) NOT NULL DEFAULT 0.00,
  `perputaran_modal` decimal(16,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `operators`
--

CREATE TABLE `operators` (
  `id` int(11) NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `nama` varchar(100) NOT NULL,
  `tanggal_masuk` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `operators`
--

INSERT INTO `operators` (`id`, `user_id`, `nama`, `tanggal_masuk`) VALUES
(5, 1, 'Asep Supriadi', NULL),
(6, 1, 'Budi Rahardjo', NULL),
(7, 1, 'Susi Susanti', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `penerima_voucher`
--

CREATE TABLE `penerima_voucher` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `jenis` enum('internal','eksternal') NOT NULL DEFAULT 'eksternal',
  `operator_id` int(10) UNSIGNED DEFAULT NULL,
  `keterangan` varchar(150) DEFAULT NULL,
  `aktif` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengeluaran`
--

CREATE TABLE `pengeluaran` (
  `id` int(11) NOT NULL,
  `kas_transaksi_id` int(10) UNSIGNED DEFAULT NULL,
  `harian_id` int(10) UNSIGNED NOT NULL,
  `tanggal` date NOT NULL,
  `keterangan` varchar(150) NOT NULL,
  `kategori` enum('operasional','curah','lainnya') NOT NULL DEFAULT 'operasional',
  `nominal` decimal(14,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `penjualan_harian`
--

CREATE TABLE `penjualan_harian` (
  `id` int(11) NOT NULL,
  `harian_id` int(10) UNSIGNED NOT NULL,
  `produk_id` int(10) UNSIGNED NOT NULL,
  `nozzle` tinyint(3) UNSIGNED NOT NULL,
  `totalisator_awal` decimal(10,2) DEFAULT 0.00,
  `totalisator_akhir` decimal(10,2) DEFAULT 0.00,
  `liter_terjual` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_rupiah` decimal(14,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `penjualan_harian`
--

INSERT INTO `penjualan_harian` (`id`, `harian_id`, `produk_id`, `nozzle`, `totalisator_awal`, `totalisator_akhir`, `liter_terjual`, `total_rupiah`) VALUES
(9, 4, 1, 1, '30685.00', '31100.00', '415.00', '5063000.00'),
(10, 4, 2, 2, '31872.00', '32357.00', '485.00', '5917000.00'),
(11, 4, 3, 3, '6601.00', '6601.00', '0.00', '0.00'),
(12, 4, 4, 4, '6609.00', '6619.00', '10.00', '145000.00'),
(13, 5, 1, 1, '31100.00', '31607.00', '507.00', '6185400.00'),
(14, 5, 2, 2, '32357.00', '32940.00', '583.00', '7112600.00'),
(15, 5, 3, 3, '6601.00', '6601.00', '0.00', '0.00'),
(16, 5, 4, 4, '6619.00', '6619.00', '0.00', '0.00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `produk_bbm`
--

CREATE TABLE `produk_bbm` (
  `id` int(11) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `harga_jual` decimal(12,2) NOT NULL DEFAULT 0.00,
  `margin_liter` decimal(12,2) NOT NULL DEFAULT 0.00,
  `satuan` varchar(10) NOT NULL DEFAULT 'liter'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `produk_bbm`
--

INSERT INTO `produk_bbm` (`id`, `nama`, `harga_jual`, `margin_liter`, `satuan`) VALUES
(1, 'Pertamax 1', '12200.00', '0.00', 'liter'),
(2, 'Pertamax 2', '12200.00', '0.00', 'liter'),
(3, 'Dex 1', '14500.00', '0.00', 'liter'),
(4, 'Dex 2', '14500.00', '0.00', 'liter');

-- --------------------------------------------------------

--
-- Struktur dari tabel `stok`
--

CREATE TABLE `stok` (
  `id` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `produk_id` int(10) UNSIGNED NOT NULL,
  `stok_awal` decimal(10,2) DEFAULT 0.00,
  `kiriman_masuk` decimal(10,2) DEFAULT 0.00,
  `total_tersedia` decimal(10,2) DEFAULT 0.00,
  `terjual` decimal(10,2) DEFAULT 0.00,
  `stok_akhir_teori` decimal(10,2) DEFAULT 0.00,
  `stok_akhir_fisik` decimal(10,2) DEFAULT 0.00,
  `selisih` decimal(10,2) DEFAULT 0.00,
  `jadwal` varchar(50) DEFAULT NULL,
  `nama_supir` varchar(100) DEFAULT NULL,
  `catatan` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `stok`
--

INSERT INTO `stok` (`id`, `tanggal`, `produk_id`, `stok_awal`, `kiriman_masuk`, `total_tersedia`, `terjual`, `stok_akhir_teori`, `stok_akhir_fisik`, `selisih`, `jadwal`, `nama_supir`, `catatan`, `created_at`) VALUES
(1, '2026-03-01', 1, '6646.00', '0.00', '6646.00', '900.00', '5746.00', '5744.00', '-2.00', '', '', '', '2026-04-12 08:13:01'),
(2, '2026-03-01', 3, '6158.00', '0.00', '6158.00', '10.00', '6148.00', '6147.92', '-0.08', '', '', '', '2026-04-12 08:13:01');

-- --------------------------------------------------------

--
-- Struktur dari tabel `totalisator`
--

CREATE TABLE `totalisator` (
  `id` int(11) NOT NULL,
  `harian_id` int(10) UNSIGNED NOT NULL,
  `produk_id` int(10) UNSIGNED NOT NULL,
  `nozzle` tinyint(3) UNSIGNED NOT NULL,
  `tot_awal` decimal(14,2) NOT NULL DEFAULT 0.00,
  `tot_akhir` decimal(14,2) NOT NULL DEFAULT 0.00,
  `terjual_liter` decimal(10,2) GENERATED ALWAYS AS (`tot_akhir` - `tot_awal`) STORED,
  `tera_bbm` decimal(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `totalisator_akhir`
--

CREATE TABLE `totalisator_akhir` (
  `id` int(11) NOT NULL,
  `produk_id` int(10) UNSIGNED NOT NULL,
  `nozzle` tinyint(3) UNSIGNED NOT NULL,
  `periode` date NOT NULL,
  `tot_awal_bulan` decimal(14,2) NOT NULL DEFAULT 0.00,
  `tot_akhir_bulan` decimal(14,2) NOT NULL DEFAULT 0.00,
  `terjual_liter` decimal(10,2) NOT NULL DEFAULT 0.00,
  `tera_bbm` decimal(10,2) NOT NULL DEFAULT 0.00,
  `liter_bersih` decimal(10,2) NOT NULL DEFAULT 0.00,
  `harga` decimal(12,2) NOT NULL DEFAULT 0.00,
  `jumlah_rupiah` decimal(16,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `role` enum('admin','manajer','operator') NOT NULL DEFAULT 'operator',
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `nama`, `role`, `password`, `created_at`) VALUES
(1, 'admin', 'admin', 'admin123', '2026-03-30 03:53:31');

-- --------------------------------------------------------

--
-- Struktur dari tabel `voucher`
--

CREATE TABLE `voucher` (
  `id` int(11) NOT NULL,
  `harian_id` int(10) UNSIGNED DEFAULT NULL,
  `kas_transaksi_id` int(10) UNSIGNED DEFAULT NULL,
  `penerima_voucher_id` int(10) UNSIGNED DEFAULT NULL,
  `periode` date NOT NULL,
  `jenis` enum('internal','eksternal') NOT NULL DEFAULT 'eksternal',
  `penerima` varchar(100) NOT NULL,
  `produk_id` int(10) UNSIGNED NOT NULL,
  `jumlah_rupiah` decimal(12,2) NOT NULL DEFAULT 0.00,
  `keterangan` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `edc`
--
ALTER TABLE `edc`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_edc_kas_trx` (`kas_transaksi_id`),
  ADD KEY `fk_edc_konfigurasi` (`konfigurasi_edc_id`);

--
-- Indeks untuk tabel `gaji`
--
ALTER TABLE `gaji`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_gaji` (`operator_id`,`periode`);

--
-- Indeks untuk tabel `gaji_komponen`
--
ALTER TABLE `gaji_komponen`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_gaji_komponen` (`operator_id`,`berlaku_mulai`);

--
-- Indeks untuk tabel `harian`
--
ALTER TABLE `harian`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tanggal` (`tanggal`),
  ADD KEY `fk_harian_op1` (`operator1_id`),
  ADD KEY `fk_harian_op2` (`operator2_id`);

--
-- Indeks untuk tabel `kas`
--
ALTER TABLE `kas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_transaksi_id` (`transaksi_id`);

--
-- Indeks untuk tabel `kas_saldo`
--
ALTER TABLE `kas_saldo`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_kas_saldo` (`harian_id`,`jenis_kas`);

--
-- Indeks untuk tabel `kas_transaksi`
--
ALTER TABLE `kas_transaksi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_kas_trx_harian` (`harian_id`);

--
-- Indeks untuk tabel `kiriman_bbm`
--
ALTER TABLE `kiriman_bbm`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_kiriman_produk` (`produk_id`);

--
-- Indeks untuk tabel `konfigurasi_edc`
--
ALTER TABLE `konfigurasi_edc`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_konfigurasi_edc` (`metode`,`berlaku_mulai`);

--
-- Indeks untuk tabel `laporan_bulanan`
--
ALTER TABLE `laporan_bulanan`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `periode` (`periode`);

--
-- Indeks untuk tabel `losis`
--
ALTER TABLE `losis`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `neraca`
--
ALTER TABLE `neraca`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `operators`
--
ALTER TABLE `operators`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `penerima_voucher`
--
ALTER TABLE `penerima_voucher`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `pengeluaran`
--
ALTER TABLE `pengeluaran`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `penjualan_harian`
--
ALTER TABLE `penjualan_harian`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `produk_bbm`
--
ALTER TABLE `produk_bbm`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `stok`
--
ALTER TABLE `stok`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `totalisator`
--
ALTER TABLE `totalisator`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `totalisator_akhir`
--
ALTER TABLE `totalisator_akhir`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `voucher`
--
ALTER TABLE `voucher`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `edc`
--
ALTER TABLE `edc`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `gaji`
--
ALTER TABLE `gaji`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `gaji_komponen`
--
ALTER TABLE `gaji_komponen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `harian`
--
ALTER TABLE `harian`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `kas`
--
ALTER TABLE `kas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT untuk tabel `kas_saldo`
--
ALTER TABLE `kas_saldo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `kas_transaksi`
--
ALTER TABLE `kas_transaksi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `kiriman_bbm`
--
ALTER TABLE `kiriman_bbm`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `konfigurasi_edc`
--
ALTER TABLE `konfigurasi_edc`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `laporan_bulanan`
--
ALTER TABLE `laporan_bulanan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `losis`
--
ALTER TABLE `losis`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `neraca`
--
ALTER TABLE `neraca`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `operators`
--
ALTER TABLE `operators`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `penerima_voucher`
--
ALTER TABLE `penerima_voucher`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `pengeluaran`
--
ALTER TABLE `pengeluaran`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `penjualan_harian`
--
ALTER TABLE `penjualan_harian`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT untuk tabel `produk_bbm`
--
ALTER TABLE `produk_bbm`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `stok`
--
ALTER TABLE `stok`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `totalisator`
--
ALTER TABLE `totalisator`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `totalisator_akhir`
--
ALTER TABLE `totalisator_akhir`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `voucher`
--
ALTER TABLE `voucher`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
