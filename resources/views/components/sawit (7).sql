-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Nov 30, 2025 at 08:44 AM
-- Server version: 8.4.3
-- PHP Version: 8.3.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sawit`
--

-- --------------------------------------------------------

--
-- Table structure for table `absensi`
--

CREATE TABLE `absensi` (
  `id_absensi` bigint UNSIGNED NOT NULL,
  `id_user` bigint UNSIGNED NOT NULL,
  `tanggal` date NOT NULL,
  `status_kehadiran` enum('Hadir','Izin','Sakit','Alpha','Libur_Agama') COLLATE utf8mb4_unicode_ci NOT NULL,
  `jam_masuk` time DEFAULT NULL,
  `jam_pulang` time DEFAULT NULL,
  `keterangan` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `blok_ladang`
--

CREATE TABLE `blok_ladang` (
  `id_blok` bigint UNSIGNED NOT NULL,
  `nama_blok` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kategori` enum('dekat','jauh') COLLATE utf8mb4_unicode_ci NOT NULL,
  `luas_hektar` decimal(8,2) DEFAULT NULL,
  `harga_upah_per_kg` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `blok_ladang`
--

INSERT INTO `blok_ladang` (`id_blok`, `nama_blok`, `kategori`, `luas_hektar`, `harga_upah_per_kg`, `created_at`, `updated_at`) VALUES
(1, 'A', 'dekat', 0.00, 200.00, '2025-11-25 10:32:15', '2025-11-25 10:32:15'),
(2, 'B', 'dekat', 0.00, 200.00, '2025-11-25 10:32:15', '2025-11-25 10:32:15'),
(3, 'C', 'jauh', 0.00, 220.00, '2025-11-25 10:32:15', '2025-11-25 10:32:15'),
(4, 'D', 'jauh', 0.00, 220.00, '2025-11-25 10:32:15', '2025-11-25 10:32:15');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `detail_penjualan`
--

CREATE TABLE `detail_penjualan` (
  `id_detail_penjualan` bigint UNSIGNED NOT NULL,
  `id_penjualan` bigint UNSIGNED NOT NULL,
  `jenis_buah` enum('buah_segar','buah_gugur') COLLATE utf8mb4_unicode_ci NOT NULL,
  `jumlah_kg` decimal(10,2) NOT NULL,
  `harga_jual_kg` decimal(10,2) NOT NULL,
  `subtotal` decimal(12,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `laporan_masalah`
--

CREATE TABLE `laporan_masalah` (
  `id_masalah` bigint UNSIGNED NOT NULL,
  `id_user` bigint UNSIGNED NOT NULL,
  `id_blok` bigint UNSIGNED DEFAULT NULL,
  `tanggal` date NOT NULL,
  `jenis_masalah` enum('Cuaca Buruk','Kemalingan','Serangan Hama','Kerusakan Alat','Lainnya') COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `tindakan` text COLLATE utf8mb4_unicode_ci,
  `status_masalah` enum('dilaporkan','dalam_penanganan','selesai') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'dilaporkan',
  `ditangani_oleh` bigint UNSIGNED DEFAULT NULL,
  `tanggal_selesai` datetime DEFAULT NULL,
  `tingkat_keparahan` enum('ringan','sedang','berat') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ringan',
  `diteruskan_ke_owner` tinyint(1) NOT NULL DEFAULT '0',
  `ditandai_oleh` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '2025_11_19_172519_create_blok_ladang_table', 1),
(3, '2025_11_19_172520_create_absensi_table', 1),
(4, '2025_11_19_172521_create_panen_harian_table', 1),
(5, '2025_11_19_172522_create_penjualan_table', 1),
(6, '2025_11_19_172523_create_detail_penjualan_table', 1),
(7, '2025_11_19_172524_create_pemasukan_table', 1),
(8, '2025_11_19_172525_create_pengeluaran_table', 1),
(9, '2025_11_19_172526_create_pengeluaran_pupuk_table', 1),
(10, '2025_11_19_172527_create_pengeluaran_transportasi_table', 1),
(11, '2025_11_19_172528_create_pengeluaran_perawatan_table', 1),
(12, '2025_11_19_172529_create_pengeluaran_gaji_table', 1),
(13, '2025_11_19_172530_create_laporan_masalah_table', 1),
(14, '2025_11_19_172531_create_rekap_keuangan_table', 1),
(15, '2025_11_19_173522_create_sessions_table', 1),
(16, '2025_11_19_173730_create_cache_table', 1),
(17, '2025_11_19_173955_create_jobs_table', 1),
(18, '2025_11_20_154549_create_password_reset_tokens_table', 1),
(19, '2025_11_25_095909_add_id_blok_to_users_table', 2);

-- --------------------------------------------------------

--
-- Table structure for table `panen_harian`
--

CREATE TABLE `panen_harian` (
  `id_panen` bigint UNSIGNED NOT NULL,
  `id_user` bigint UNSIGNED NOT NULL,
  `id_blok` bigint UNSIGNED NOT NULL,
  `tanggal` date NOT NULL,
  `jumlah_kg` decimal(10,2) NOT NULL,
  `jenis_buah` enum('buah_segar','buah_gugur') COLLATE utf8mb4_unicode_ci NOT NULL,
  `harga_upah_per_kg` decimal(10,2) NOT NULL,
  `total_upah` decimal(12,2) NOT NULL,
  `status_panen` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT 'selesai',
  `diverifikasi_oleh` bigint UNSIGNED DEFAULT NULL,
  `keterangan` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `panen_harian`
--

INSERT INTO `panen_harian` (`id_panen`, `id_user`, `id_blok`, `tanggal`, `jumlah_kg`, `jenis_buah`, `harga_upah_per_kg`, `total_upah`, `status_panen`, `diverifikasi_oleh`, `keterangan`, `created_at`, `updated_at`) VALUES
(6, 8, 2, '2025-11-30', 1.00, 'buah_gugur', 100.00, 100.00, 'selesai', NULL, 'p', '2025-11-30 01:14:45', '2025-11-30 01:14:45'),
(7, 8, 2, '2025-11-30', 1.00, 'buah_gugur', 100.00, 100.00, 'selesai', NULL, 'p', '2025-11-30 01:14:45', '2025-11-30 01:14:45'),
(8, 8, 3, '2025-11-30', 1.00, 'buah_segar', 220.00, 220.00, 'selesai', NULL, 'segar', '2025-11-30 01:15:11', '2025-11-30 01:15:11'),
(9, 8, 1, '2025-11-30', 20.00, 'buah_segar', 200.00, 4000.00, 'selesai', NULL, NULL, '2025-11-30 01:15:28', '2025-11-30 01:15:28');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pemasukan`
--

CREATE TABLE `pemasukan` (
  `id_pemasukan` bigint UNSIGNED NOT NULL,
  `id_penjualan` bigint UNSIGNED DEFAULT NULL,
  `tanggal` date NOT NULL,
  `sumber_pemasukan` enum('penjualan_buah','lainnya') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'penjualan_buah',
  `total_pemasukan` decimal(12,2) NOT NULL,
  `keterangan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_user_pencatat` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pengeluaran`
--

CREATE TABLE `pengeluaran` (
  `id_pengeluaran` bigint UNSIGNED NOT NULL,
  `tanggal` date NOT NULL,
  `jenis_pengeluaran` enum('pupuk','transportasi','perawatan','gaji','lainnya') COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_biaya` decimal(12,2) NOT NULL,
  `keterangan` text COLLATE utf8mb4_unicode_ci,
  `id_user_pencatat` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pengeluaran`
--

INSERT INTO `pengeluaran` (`id_pengeluaran`, `tanggal`, `jenis_pengeluaran`, `total_biaya`, `keterangan`, `id_user_pencatat`, `created_at`, `updated_at`) VALUES
(1, '2025-11-30', 'pupuk', 1100.00, 'tes', 8, '2025-11-29 23:06:33', '2025-11-29 23:06:33');

-- --------------------------------------------------------

--
-- Table structure for table `pengeluaran_gaji`
--

CREATE TABLE `pengeluaran_gaji` (
  `id_gaji` bigint UNSIGNED NOT NULL,
  `id_pengeluaran` bigint UNSIGNED NOT NULL,
  `id_user` bigint UNSIGNED NOT NULL,
  `periode` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_gaji` decimal(12,2) NOT NULL,
  `tanggal_generate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pengeluaran_perawatan`
--

CREATE TABLE `pengeluaran_perawatan` (
  `id_perawatan` bigint UNSIGNED NOT NULL,
  `id_pengeluaran` bigint UNSIGNED NOT NULL,
  `jenis_perawatan` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `biaya` decimal(12,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pengeluaran_pupuk`
--

CREATE TABLE `pengeluaran_pupuk` (
  `id_pupuk` bigint UNSIGNED NOT NULL,
  `id_pengeluaran` bigint UNSIGNED NOT NULL,
  `jenis_pupuk` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jumlah` decimal(10,2) NOT NULL,
  `harga_satuan` decimal(12,2) NOT NULL,
  `total_harga` decimal(12,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pengeluaran_pupuk`
--

INSERT INTO `pengeluaran_pupuk` (`id_pupuk`, `id_pengeluaran`, `jenis_pupuk`, `jumlah`, `harga_satuan`, `total_harga`, `created_at`, `updated_at`) VALUES
(1, 1, 'urea', 11.00, 100.00, 1100.00, '2025-11-29 23:06:33', '2025-11-29 23:06:33');

-- --------------------------------------------------------

--
-- Table structure for table `pengeluaran_transportasi`
--

CREATE TABLE `pengeluaran_transportasi` (
  `id_transport` bigint UNSIGNED NOT NULL,
  `id_pengeluaran` bigint UNSIGNED NOT NULL,
  `tujuan` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `biaya` decimal(12,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `penjualan`
--

CREATE TABLE `penjualan` (
  `id_penjualan` bigint UNSIGNED NOT NULL,
  `tanggal` date NOT NULL,
  `tujuan_jual` enum('ram_family','pemilik_setempat','pabrik','lainnya') COLLATE utf8mb4_unicode_ci NOT NULL,
  `pembeli` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_berat_kg` decimal(10,2) NOT NULL DEFAULT '0.00',
  `total_pemasukan` decimal(12,2) NOT NULL DEFAULT '0.00',
  `id_user_pencatat` bigint UNSIGNED NOT NULL,
  `keterangan` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rekap_keuangan`
--

CREATE TABLE `rekap_keuangan` (
  `id_rekap` bigint UNSIGNED NOT NULL,
  `tanggal_generate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `periode` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipe_periode` enum('harian','10_harian','bulanan','tahunan') COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_pemasukan` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_pengeluaran_pupuk` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_pengeluaran_transport` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_pengeluaran_perawatan` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_pengeluaran_gaji` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_pengeluaran_lainnya` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_pengeluaran_all` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_kehadiran` int NOT NULL DEFAULT '0',
  `total_izin` int NOT NULL DEFAULT '0',
  `total_sakit` int NOT NULL DEFAULT '0',
  `total_alpha` int NOT NULL DEFAULT '0',
  `total_karyawan_aktif` int NOT NULL DEFAULT '0',
  `total_laporan_masalah` int NOT NULL DEFAULT '0',
  `total_masalah_selesai` int NOT NULL DEFAULT '0',
  `laba_bersih` decimal(12,2) NOT NULL DEFAULT '0.00',
  `margin_keuntungan` decimal(5,2) NOT NULL DEFAULT '0.00',
  `efisiensi_kehadiran` decimal(5,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id_user` bigint UNSIGNED NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_lengkap` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('owner','admin','karyawan') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'karyawan',
  `status_aktif` tinyint(1) NOT NULL DEFAULT '1',
  `no_telepon` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alamat` text COLLATE utf8mb4_unicode_ci,
  `id_blok` bigint UNSIGNED DEFAULT NULL,
  `tanggal_bergabung` date DEFAULT NULL,
  `bisa_input_panen` tinyint(1) NOT NULL DEFAULT '1',
  `bisa_input_absen` tinyint(1) NOT NULL DEFAULT '1',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_user`, `username`, `email`, `password`, `nama_lengkap`, `role`, `status_aktif`, `no_telepon`, `alamat`, `id_blok`, `tanggal_bergabung`, `bisa_input_panen`, `bisa_input_absen`, `remember_token`, `created_at`, `updated_at`) VALUES
(8, 'dapa', 'rajamuhammaddaffa12@gmail.com', '$2y$12$ABOP2CZiVwvwBdDCVMH3ZuKJICBH0g0pbpUVlsDXb0MmF53ykSrWa', 'raja muhammd daffa', 'admin', 1, '082367481498', 'usu', 1, '2025-11-25', 1, 1, NULL, '2025-11-25 04:15:51', '2025-11-25 04:15:51'),
(9, 'ubuai', 'ubai@gmail.com', '$2y$12$dvwL4mi2sixa5rqDUj4uOex14OM3Nq/oMfD3GkdWobypDKP9RJKBe', 'ubaijelek', 'karyawan', 1, '081237361161', 'usu jauh', 3, '2025-11-25', 1, 1, NULL, '2025-11-25 04:16:57', '2025-11-25 04:16:57');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `absensi`
--
ALTER TABLE `absensi`
  ADD PRIMARY KEY (`id_absensi`),
  ADD KEY `absensi_id_user_foreign` (`id_user`);

--
-- Indexes for table `blok_ladang`
--
ALTER TABLE `blok_ladang`
  ADD PRIMARY KEY (`id_blok`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `detail_penjualan`
--
ALTER TABLE `detail_penjualan`
  ADD PRIMARY KEY (`id_detail_penjualan`),
  ADD KEY `detail_penjualan_id_penjualan_foreign` (`id_penjualan`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `laporan_masalah`
--
ALTER TABLE `laporan_masalah`
  ADD PRIMARY KEY (`id_masalah`),
  ADD KEY `laporan_masalah_id_user_foreign` (`id_user`),
  ADD KEY `laporan_masalah_ditangani_oleh_foreign` (`ditangani_oleh`),
  ADD KEY `laporan_masalah_ditandai_oleh_foreign` (`ditandai_oleh`),
  ADD KEY `laporan_masalah_id_blok_foreign` (`id_blok`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `panen_harian`
--
ALTER TABLE `panen_harian`
  ADD PRIMARY KEY (`id_panen`),
  ADD KEY `panen_harian_id_user_foreign` (`id_user`),
  ADD KEY `panen_harian_id_blok_foreign` (`id_blok`),
  ADD KEY `panen_harian_diverifikasi_oleh_foreign` (`diverifikasi_oleh`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD KEY `password_reset_tokens_email_index` (`email`);

--
-- Indexes for table `pemasukan`
--
ALTER TABLE `pemasukan`
  ADD PRIMARY KEY (`id_pemasukan`),
  ADD KEY `pemasukan_id_penjualan_foreign` (`id_penjualan`),
  ADD KEY `pemasukan_id_user_pencatat_foreign` (`id_user_pencatat`);

--
-- Indexes for table `pengeluaran`
--
ALTER TABLE `pengeluaran`
  ADD PRIMARY KEY (`id_pengeluaran`),
  ADD KEY `pengeluaran_id_user_pencatat_foreign` (`id_user_pencatat`);

--
-- Indexes for table `pengeluaran_gaji`
--
ALTER TABLE `pengeluaran_gaji`
  ADD PRIMARY KEY (`id_gaji`),
  ADD KEY `pengeluaran_gaji_id_pengeluaran_foreign` (`id_pengeluaran`),
  ADD KEY `pengeluaran_gaji_id_user_foreign` (`id_user`);

--
-- Indexes for table `pengeluaran_perawatan`
--
ALTER TABLE `pengeluaran_perawatan`
  ADD PRIMARY KEY (`id_perawatan`),
  ADD KEY `pengeluaran_perawatan_id_pengeluaran_foreign` (`id_pengeluaran`);

--
-- Indexes for table `pengeluaran_pupuk`
--
ALTER TABLE `pengeluaran_pupuk`
  ADD PRIMARY KEY (`id_pupuk`),
  ADD KEY `pengeluaran_pupuk_id_pengeluaran_foreign` (`id_pengeluaran`);

--
-- Indexes for table `pengeluaran_transportasi`
--
ALTER TABLE `pengeluaran_transportasi`
  ADD PRIMARY KEY (`id_transport`),
  ADD KEY `pengeluaran_transportasi_id_pengeluaran_foreign` (`id_pengeluaran`);

--
-- Indexes for table `penjualan`
--
ALTER TABLE `penjualan`
  ADD PRIMARY KEY (`id_penjualan`),
  ADD KEY `penjualan_id_user_pencatat_foreign` (`id_user_pencatat`);

--
-- Indexes for table `rekap_keuangan`
--
ALTER TABLE `rekap_keuangan`
  ADD PRIMARY KEY (`id_rekap`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `users_username_unique` (`username`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_id_blok_foreign` (`id_blok`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `absensi`
--
ALTER TABLE `absensi`
  MODIFY `id_absensi` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `blok_ladang`
--
ALTER TABLE `blok_ladang`
  MODIFY `id_blok` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `detail_penjualan`
--
ALTER TABLE `detail_penjualan`
  MODIFY `id_detail_penjualan` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `laporan_masalah`
--
ALTER TABLE `laporan_masalah`
  MODIFY `id_masalah` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `panen_harian`
--
ALTER TABLE `panen_harian`
  MODIFY `id_panen` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `pemasukan`
--
ALTER TABLE `pemasukan`
  MODIFY `id_pemasukan` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pengeluaran`
--
ALTER TABLE `pengeluaran`
  MODIFY `id_pengeluaran` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `pengeluaran_gaji`
--
ALTER TABLE `pengeluaran_gaji`
  MODIFY `id_gaji` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pengeluaran_perawatan`
--
ALTER TABLE `pengeluaran_perawatan`
  MODIFY `id_perawatan` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pengeluaran_pupuk`
--
ALTER TABLE `pengeluaran_pupuk`
  MODIFY `id_pupuk` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `pengeluaran_transportasi`
--
ALTER TABLE `pengeluaran_transportasi`
  MODIFY `id_transport` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `penjualan`
--
ALTER TABLE `penjualan`
  MODIFY `id_penjualan` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rekap_keuangan`
--
ALTER TABLE `rekap_keuangan`
  MODIFY `id_rekap` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `absensi`
--
ALTER TABLE `absensi`
  ADD CONSTRAINT `absensi_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`);

--
-- Constraints for table `detail_penjualan`
--
ALTER TABLE `detail_penjualan`
  ADD CONSTRAINT `detail_penjualan_id_penjualan_foreign` FOREIGN KEY (`id_penjualan`) REFERENCES `penjualan` (`id_penjualan`) ON DELETE CASCADE;

--
-- Constraints for table `laporan_masalah`
--
ALTER TABLE `laporan_masalah`
  ADD CONSTRAINT `laporan_masalah_ditandai_oleh_foreign` FOREIGN KEY (`ditandai_oleh`) REFERENCES `users` (`id_user`),
  ADD CONSTRAINT `laporan_masalah_ditangani_oleh_foreign` FOREIGN KEY (`ditangani_oleh`) REFERENCES `users` (`id_user`),
  ADD CONSTRAINT `laporan_masalah_id_blok_foreign` FOREIGN KEY (`id_blok`) REFERENCES `blok_ladang` (`id_blok`) ON DELETE SET NULL,
  ADD CONSTRAINT `laporan_masalah_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`);

--
-- Constraints for table `panen_harian`
--
ALTER TABLE `panen_harian`
  ADD CONSTRAINT `panen_harian_diverifikasi_oleh_foreign` FOREIGN KEY (`diverifikasi_oleh`) REFERENCES `users` (`id_user`),
  ADD CONSTRAINT `panen_harian_id_blok_foreign` FOREIGN KEY (`id_blok`) REFERENCES `blok_ladang` (`id_blok`),
  ADD CONSTRAINT `panen_harian_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`);

--
-- Constraints for table `pemasukan`
--
ALTER TABLE `pemasukan`
  ADD CONSTRAINT `pemasukan_id_penjualan_foreign` FOREIGN KEY (`id_penjualan`) REFERENCES `penjualan` (`id_penjualan`),
  ADD CONSTRAINT `pemasukan_id_user_pencatat_foreign` FOREIGN KEY (`id_user_pencatat`) REFERENCES `users` (`id_user`);

--
-- Constraints for table `pengeluaran`
--
ALTER TABLE `pengeluaran`
  ADD CONSTRAINT `pengeluaran_id_user_pencatat_foreign` FOREIGN KEY (`id_user_pencatat`) REFERENCES `users` (`id_user`);

--
-- Constraints for table `pengeluaran_gaji`
--
ALTER TABLE `pengeluaran_gaji`
  ADD CONSTRAINT `pengeluaran_gaji_id_pengeluaran_foreign` FOREIGN KEY (`id_pengeluaran`) REFERENCES `pengeluaran` (`id_pengeluaran`) ON DELETE CASCADE,
  ADD CONSTRAINT `pengeluaran_gaji_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`);

--
-- Constraints for table `pengeluaran_perawatan`
--
ALTER TABLE `pengeluaran_perawatan`
  ADD CONSTRAINT `pengeluaran_perawatan_id_pengeluaran_foreign` FOREIGN KEY (`id_pengeluaran`) REFERENCES `pengeluaran` (`id_pengeluaran`) ON DELETE CASCADE;

--
-- Constraints for table `pengeluaran_pupuk`
--
ALTER TABLE `pengeluaran_pupuk`
  ADD CONSTRAINT `pengeluaran_pupuk_id_pengeluaran_foreign` FOREIGN KEY (`id_pengeluaran`) REFERENCES `pengeluaran` (`id_pengeluaran`) ON DELETE CASCADE;

--
-- Constraints for table `pengeluaran_transportasi`
--
ALTER TABLE `pengeluaran_transportasi`
  ADD CONSTRAINT `pengeluaran_transportasi_id_pengeluaran_foreign` FOREIGN KEY (`id_pengeluaran`) REFERENCES `pengeluaran` (`id_pengeluaran`) ON DELETE CASCADE;

--
-- Constraints for table `penjualan`
--
ALTER TABLE `penjualan`
  ADD CONSTRAINT `penjualan_id_user_pencatat_foreign` FOREIGN KEY (`id_user_pencatat`) REFERENCES `users` (`id_user`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_id_blok_foreign` FOREIGN KEY (`id_blok`) REFERENCES `blok_ladang` (`id_blok`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
