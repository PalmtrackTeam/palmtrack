-- --------------------------------------------------------
-- Database: `sawit_db`
-- --------------------------------------------------------

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE DATABASE IF NOT EXISTS `sawit_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `sawit_db`;

-- --------------------------------------------------------
-- Table structures
-- --------------------------------------------------------

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `jabatan` enum('mandor','asisten_mandor','anggota') NOT NULL,
  `role` enum('owner','admin','karyawan') NOT NULL,
  `status_aktif` tinyint(1) DEFAULT 1,
  `no_telepon` varchar(20) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `tanggal_bergabung` date DEFAULT NULL,
  `status_tinggal` enum('barak','keluarga_barak','luar') DEFAULT NULL,
  `bisa_input_panen` tinyint(1) DEFAULT 1,
  `bisa_input_absen` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `blok_ladang` (
  `id_blok` int(11) NOT NULL,
  `nama_blok` varchar(50) NOT NULL,
  `kategori` enum('dekat','jauh') NOT NULL,
  `luas_hektar` decimal(8,2) DEFAULT NULL,
  `harga_upah_per_kg` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `absensi` (
  `id_absensi` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `status_kehadiran` enum('Hadir','Izin','Sakit','Alpha','Libur_Agama') NOT NULL,
  `jam_masuk` time DEFAULT NULL,
  `jam_pulang` time DEFAULT NULL,
  `keterangan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `panen_harian` (
  `id_panen` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_blok` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `jumlah_kg` decimal(10,2) NOT NULL,
  `jenis_buah` enum('buah_segar','buah_gugur') NOT NULL,
  `harga_upah_per_kg` decimal(10,2) NOT NULL,
  `total_upah` decimal(12,2) NOT NULL,
  `status_panen` enum('draft','diverifikasi','dibayar') DEFAULT 'draft',
  `diverifikasi_oleh` int(11) DEFAULT NULL,
  `keterangan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `penjualan` (
  `id_penjualan` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `tujuan_jual` enum('ram_family','pemilik_setempat','pabrik','lainnya') NOT NULL,
  `pembeli` varchar(100) NOT NULL,
  `total_berat_kg` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_pemasukan` decimal(12,2) NOT NULL DEFAULT 0.00,
  `id_user_pencatat` int(11) NOT NULL,
  `keterangan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `detail_penjualan` (
  `id_detail_penjualan` int(11) NOT NULL,
  `id_penjualan` int(11) NOT NULL,
  `jenis_buah` enum('buah_segar','buah_gugur') NOT NULL,
  `jumlah_kg` decimal(10,2) NOT NULL,
  `harga_jual_kg` decimal(10,2) NOT NULL,
  `subtotal` decimal(12,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `pemasukan` (
  `id_pemasukan` int(11) NOT NULL,
  `id_penjualan` int(11) DEFAULT NULL,
  `tanggal` date NOT NULL,
  `sumber_pemasukan` enum('penjualan_buah','lainnya') DEFAULT 'penjualan_buah',
  `total_pemasukan` decimal(12,2) NOT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `id_user_pencatat` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `pengeluaran` (
  `id_pengeluaran` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `jenis_pengeluaran` enum('pupuk','transportasi','perawatan','gaji','lainnya') NOT NULL,
  `total_biaya` decimal(12,2) NOT NULL,
  `keterangan` text DEFAULT NULL,
  `id_user_pencatat` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `pengeluaran_pupuk` (
  `id_pupuk` int(11) NOT NULL,
  `id_pengeluaran` int(11) NOT NULL,
  `jenis_pupuk` varchar(100) NOT NULL,
  `jumlah` decimal(10,2) NOT NULL,
  `harga_satuan` decimal(12,2) NOT NULL,
  `total_harga` decimal(12,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `pengeluaran_transportasi` (
  `id_transport` int(11) NOT NULL,
  `id_pengeluaran` int(11) NOT NULL,
  `tujuan` varchar(100) NOT NULL,
  `biaya` decimal(12,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `pengeluaran_perawatan` (
  `id_perawatan` int(11) NOT NULL,
  `id_pengeluaran` int(11) NOT NULL,
  `jenis_perawatan` varchar(100) NOT NULL,
  `biaya` decimal(12,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `pengeluaran_gaji` (
  `id_gaji` int(11) NOT NULL,
  `id_pengeluaran` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `periode` varchar(20) NOT NULL,
  `total_gaji` decimal(12,2) NOT NULL,
  `tanggal_generate` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `laporan_masalah` (
  `id_masalah` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `jenis_masalah` enum('Cuaca Buruk','Kemalingan','Serangan Hama','Kerusakan Alat','Lainnya') NOT NULL,
  `deskripsi` text NOT NULL,
  `tindakan` text DEFAULT NULL,
  `status_masalah` enum('dilaporkan','dalam_penanganan','selesai') DEFAULT 'dilaporkan',
  `ditangani_oleh` int(11) DEFAULT NULL,
  `tanggal_selesai` datetime DEFAULT NULL,
  `tingkat_keparahan` enum('ringan', 'sedang', 'berat') DEFAULT 'ringan',
  `diteruskan_ke_owner` tinyint(1) DEFAULT 0,
  `ditandai_oleh` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `rekap_keuangan` (
  `id_rekap` int(11) NOT NULL,
  `tanggal_generate` datetime DEFAULT current_timestamp(),
  `periode` varchar(20) NOT NULL,
  `tipe_periode` enum('harian','10_harian','bulanan','tahunan') NOT NULL,
  `total_pemasukan` decimal(12,2) NOT NULL DEFAULT 0.00,
  `total_pengeluaran_pupuk` decimal(12,2) NOT NULL DEFAULT 0.00,
  `total_pengeluaran_transport` decimal(12,2) NOT NULL DEFAULT 0.00,
  `total_pengeluaran_perawatan` decimal(12,2) NOT NULL DEFAULT 0.00,
  `total_pengeluaran_gaji` decimal(12,2) NOT NULL DEFAULT 0.00,
  `total_pengeluaran_lainnya` decimal(12,2) NOT NULL DEFAULT 0.00,
  `total_pengeluaran_all` decimal(12,2) NOT NULL DEFAULT 0.00,
  `total_kehadiran` int(11) NOT NULL DEFAULT 0,
  `total_izin` int(11) NOT NULL DEFAULT 0,
  `total_sakit` int(11) NOT NULL DEFAULT 0,
  `total_alpha` int(11) NOT NULL DEFAULT 0,
  `total_karyawan_aktif` int(11) NOT NULL DEFAULT 0,
  `total_laporan_masalah` int(11) NOT NULL DEFAULT 0,
  `total_masalah_selesai` int(11) NOT NULL DEFAULT 0,
  `laba_bersih` decimal(12,2) NOT NULL DEFAULT 0.00,
  `margin_keuntungan` decimal(5,2) NOT NULL DEFAULT 0.00,
  `efisiensi_kehadiran` decimal(5,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Primary Keys and Auto Increment
-- --------------------------------------------------------

ALTER TABLE `users` ADD PRIMARY KEY (`id_user`);
ALTER TABLE `blok_ladang` ADD PRIMARY KEY (`id_blok`);
ALTER TABLE `absensi` ADD PRIMARY KEY (`id_absensi`), ADD KEY `id_user` (`id_user`);
ALTER TABLE `panen_harian` ADD PRIMARY KEY (`id_panen`), ADD KEY `id_user` (`id_user`), ADD KEY `id_blok` (`id_blok`), ADD KEY `diverifikasi_oleh` (`diverifikasi_oleh`);
ALTER TABLE `penjualan` ADD PRIMARY KEY (`id_penjualan`), ADD KEY `id_user_pencatat` (`id_user_pencatat`);
ALTER TABLE `detail_penjualan` ADD PRIMARY KEY (`id_detail_penjualan`), ADD KEY `id_penjualan` (`id_penjualan`);
ALTER TABLE `pemasukan` ADD PRIMARY KEY (`id_pemasukan`), ADD KEY `id_penjualan` (`id_penjualan`), ADD KEY `id_user_pencatat` (`id_user_pencatat`);
ALTER TABLE `pengeluaran` ADD PRIMARY KEY (`id_pengeluaran`), ADD KEY `id_user_pencatat` (`id_user_pencatat`);
ALTER TABLE `pengeluaran_pupuk` ADD PRIMARY KEY (`id_pupuk`), ADD KEY `id_pengeluaran` (`id_pengeluaran`);
ALTER TABLE `pengeluaran_transportasi` ADD PRIMARY KEY (`id_transport`), ADD KEY `id_pengeluaran` (`id_pengeluaran`);
ALTER TABLE `pengeluaran_perawatan` ADD PRIMARY KEY (`id_perawatan`), ADD KEY `id_pengeluaran` (`id_pengeluaran`);
ALTER TABLE `pengeluaran_gaji` ADD PRIMARY KEY (`id_gaji`), ADD KEY `id_pengeluaran` (`id_pengeluaran`), ADD KEY `id_user` (`id_user`);
ALTER TABLE `laporan_masalah` ADD PRIMARY KEY (`id_masalah`), ADD KEY `id_user` (`id_user`), ADD KEY `ditangani_oleh` (`ditangani_oleh`), ADD KEY `ditandai_oleh` (`ditandai_oleh`);
ALTER TABLE `rekap_keuangan` ADD PRIMARY KEY (`id_rekap`);

ALTER TABLE `users` MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `blok_ladang` MODIFY `id_blok` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `absensi` MODIFY `id_absensi` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `panen_harian` MODIFY `id_panen` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `penjualan` MODIFY `id_penjualan` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `detail_penjualan` MODIFY `id_detail_penjualan` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `pemasukan` MODIFY `id_pemasukan` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `pengeluaran` MODIFY `id_pengeluaran` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `pengeluaran_pupuk` MODIFY `id_pupuk` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `pengeluaran_transportasi` MODIFY `id_transport` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `pengeluaran_perawatan` MODIFY `id_perawatan` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `pengeluaran_gaji` MODIFY `id_gaji` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `laporan_masalah` MODIFY `id_masalah` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `rekap_keuangan` MODIFY `id_rekap` int(11) NOT NULL AUTO_INCREMENT;

-- --------------------------------------------------------
-- Foreign Keys
-- --------------------------------------------------------

ALTER TABLE `absensi` ADD CONSTRAINT `absensi_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`);
ALTER TABLE `panen_harian` ADD CONSTRAINT `panen_harian_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`), ADD CONSTRAINT `panen_harian_ibfk_2` FOREIGN KEY (`id_blok`) REFERENCES `blok_ladang` (`id_blok`), ADD CONSTRAINT `panen_harian_ibfk_3` FOREIGN KEY (`diverifikasi_oleh`) REFERENCES `users` (`id_user`);
ALTER TABLE `penjualan` ADD CONSTRAINT `penjualan_ibfk_1` FOREIGN KEY (`id_user_pencatat`) REFERENCES `users` (`id_user`);
ALTER TABLE `detail_penjualan` ADD CONSTRAINT `detail_penjualan_ibfk_1` FOREIGN KEY (`id_penjualan`) REFERENCES `penjualan` (`id_penjualan`) ON DELETE CASCADE;
ALTER TABLE `pemasukan` ADD CONSTRAINT `pemasukan_ibfk_1` FOREIGN KEY (`id_penjualan`) REFERENCES `penjualan` (`id_penjualan`), ADD CONSTRAINT `pemasukan_ibfk_2` FOREIGN KEY (`id_user_pencatat`) REFERENCES `users` (`id_user`);
ALTER TABLE `pengeluaran` ADD CONSTRAINT `pengeluaran_ibfk_1` FOREIGN KEY (`id_user_pencatat`) REFERENCES `users` (`id_user`);
ALTER TABLE `pengeluaran_pupuk` ADD CONSTRAINT `pengeluaran_pupuk_ibfk_1` FOREIGN KEY (`id_pengeluaran`) REFERENCES `pengeluaran` (`id_pengeluaran`) ON DELETE CASCADE;
ALTER TABLE `pengeluaran_transportasi` ADD CONSTRAINT `pengeluaran_transportasi_ibfk_1` FOREIGN KEY (`id_pengeluaran`) REFERENCES `pengeluaran` (`id_pengeluaran`) ON DELETE CASCADE;
ALTER TABLE `pengeluaran_perawatan` ADD CONSTRAINT `pengeluaran_perawatan_ibfk_1` FOREIGN KEY (`id_pengeluaran`) REFERENCES `pengeluaran` (`id_pengeluaran`) ON DELETE CASCADE;
ALTER TABLE `pengeluaran_gaji` ADD CONSTRAINT `pengeluaran_gaji_ibfk_1` FOREIGN KEY (`id_pengeluaran`) REFERENCES `pengeluaran` (`id_pengeluaran`) ON DELETE CASCADE, ADD CONSTRAINT `pengeluaran_gaji_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`);
ALTER TABLE `laporan_masalah` ADD CONSTRAINT `laporan_masalah_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`), ADD CONSTRAINT `laporan_masalah_ibfk_2` FOREIGN KEY (`ditangani_oleh`) REFERENCES `users` (`id_user`), ADD CONSTRAINT `laporan_masalah_ibfk_3` FOREIGN KEY (`ditandai_oleh`) REFERENCES `users` (`id_user`);

-- --------------------------------------------------------
-- STORED FUNCTIONS
-- --------------------------------------------------------

DELIMITER $$

CREATE FUNCTION `fn_total_upah_karyawan`(
    p_id_user INT,
    p_start_date DATE,
    p_end_date DATE
) RETURNS DECIMAL(12,2)
READS SQL DATA
DETERMINISTIC
BEGIN
    DECLARE total_upah DECIMAL(12,2);
    
    SELECT COALESCE(SUM(total_upah), 0) INTO total_upah
    FROM panen_harian 
    WHERE id_user = p_id_user 
    AND tanggal BETWEEN p_start_date AND p_end_date
    AND status_panen = 'diverifikasi';
    
    RETURN total_upah;
END$$

CREATE FUNCTION `fn_rata_harga_jual`(
    p_jenis_buah ENUM('buah_segar','buah_gugur'),
    p_start_date DATE,
    p_end_date DATE
) RETURNS DECIMAL(10,2)
READS SQL DATA
DETERMINISTIC
BEGIN
    DECLARE avg_price DECIMAL(10,2);
    
    SELECT COALESCE(AVG(harga_jual_kg), 0) INTO avg_price
    FROM detail_penjualan dp
    JOIN penjualan p ON dp.id_penjualan = p.id_penjualan
    WHERE dp.jenis_buah = p_jenis_buah
    AND p.tanggal BETWEEN p_start_date AND p_end_date;
    
    RETURN avg_price;
END$$

CREATE FUNCTION `fn_efisiensi_kehadiran`(
    p_start_date DATE,
    p_end_date DATE
) RETURNS DECIMAL(5,2)
READS SQL DATA
DETERMINISTIC
BEGIN
    DECLARE total_karyawan INT;
    DECLARE total_hari_kerja INT;
    DECLARE total_kehadiran INT;
    DECLARE efisiensi DECIMAL(5,2);
    
    SELECT COUNT(*) INTO total_karyawan 
    FROM users 
    WHERE status_aktif = 1 AND role IN ('karyawan', 'admin');
    
    SET total_hari_kerja = DATEDIFF(p_end_date, p_start_date) + 1;
    
    SELECT COUNT(*) INTO total_kehadiran
    FROM absensi 
    WHERE tanggal BETWEEN p_start_date AND p_end_date
    AND status_kehadiran = 'Hadir';
    
    IF total_karyawan > 0 AND total_hari_kerja > 0 THEN
        SET efisiensi = (total_kehadiran / (total_karyawan * total_hari_kerja)) * 100;
    ELSE
        SET efisiensi = 0;
    END IF;
    
    RETURN efisiensi;
END$$

CREATE FUNCTION `fn_cek_gaji_dibayar`(
    p_id_user INT,
    p_periode VARCHAR(20)
) RETURNS TINYINT(1)
READS SQL DATA
DETERMINISTIC
BEGIN
    DECLARE sudah_dibayar TINYINT(1) DEFAULT 0;
    
    SELECT COUNT(*) INTO sudah_dibayar
    FROM pengeluaran_gaji 
    WHERE id_user = p_id_user 
    AND periode = p_periode;
    
    RETURN IF(sudah_dibayar > 0, 1, 0);
END$$

DELIMITER ;

-- --------------------------------------------------------
-- STORED PROCEDURES BARU UNTUK WORKFLOW
-- --------------------------------------------------------

DELIMITER $$

CREATE PROCEDURE `sp_dashboard_mandor`(IN p_tanggal DATE)
BEGIN
    -- Produktivitas hari ini
    SELECT 
        COUNT(DISTINCT ph.id_user) as total_karyawan_panen,
        COALESCE(SUM(ph.jumlah_kg), 0) as total_kg,
        COALESCE(AVG(ph.jumlah_kg), 0) as rata_kg,
        b.nama_blok as blok_terproduktif,
        COUNT(ph.id_panen) as total_panen_hari_ini
    FROM panen_harian ph
    LEFT JOIN blok_ladang b ON ph.id_blok = b.id_blok
    WHERE ph.tanggal = p_tanggal
    GROUP BY b.nama_blok
    ORDER BY total_kg DESC
    LIMIT 1;
    
    -- Absensi real-time
    SELECT 
        COUNT(CASE WHEN status_kehadiran = 'Hadir' THEN 1 END) as total_hadir,
        COUNT(CASE WHEN status_kehadiran = 'Izin' THEN 1 END) as total_izin,
        COUNT(CASE WHEN status_kehadiran = 'Sakit' THEN 1 END) as total_sakit,
        COUNT(CASE WHEN status_kehadiran = 'Alpha' THEN 1 END) as total_alpha,
        (SELECT COUNT(*) FROM users WHERE role = 'karyawan' AND status_aktif = 1) as total_karyawan
    FROM absensi 
    WHERE tanggal = p_tanggal;
    
    -- Karyawan yang belum absen
    SELECT u.id_user, u.nama_lengkap
    FROM users u
    WHERE u.role = 'karyawan' 
    AND u.status_aktif = 1
    AND NOT EXISTS (
        SELECT 1 FROM absensi a 
        WHERE a.id_user = u.id_user AND a.tanggal = p_tanggal
    );
END$$

CREATE PROCEDURE `sp_list_panen_perlu_verifikasi`()
BEGIN
    SELECT 
        ph.id_panen,
        u.nama_lengkap,
        b.nama_blok,
        ph.jenis_buah,
        ph.jumlah_kg,
        ph.total_upah,
        ph.tanggal
    FROM panen_harian ph
    JOIN users u ON ph.id_user = u.id_user
    JOIN blok_ladang b ON ph.id_blok = b.id_blok
    WHERE ph.status_panen = 'draft'
    ORDER BY ph.tanggal DESC;
END$$

CREATE PROCEDURE `sp_teruskan_ke_owner`(
    IN p_id_masalah INT,
    IN p_id_mandor INT
)
BEGIN
    UPDATE laporan_masalah 
    SET diteruskan_ke_owner = 1,
        ditandai_oleh = p_id_mandor,
        status_masalah = 'dalam_penanganan'
    WHERE id_masalah = p_id_masalah;
END$$

CREATE PROCEDURE `sp_masalah_diteruskan_owner`()
BEGIN
    SELECT 
        lm.id_masalah,
        u.nama_lengkap as pelapor,
        lm.jenis_masalah,
        lm.deskripsi,
        lm.tanggal,
        m.nama_lengkap as ditandai_oleh,
        lm.tingkat_keparahan
    FROM laporan_masalah lm
    JOIN users u ON lm.id_user = u.id_user
    LEFT JOIN users m ON lm.ditandai_oleh = m.id_user
    WHERE lm.diteruskan_ke_owner = 1
    AND lm.status_masalah != 'selesai'
    ORDER BY lm.tanggal DESC;
END$$

CREATE PROCEDURE `sp_input_panen_karyawan`(
    IN p_id_user INT,
    IN p_id_blok INT,
    IN p_tanggal DATE,
    IN p_jenis_buah ENUM('buah_segar','buah_gugur'),
    IN p_jumlah_kg DECIMAL(10,2)
)
BEGIN
    DECLARE v_bisa_input TINYINT(1);
    
    SELECT bisa_input_panen INTO v_bisa_input
    FROM users WHERE id_user = p_id_user;
    
    IF v_bisa_input = 1 THEN
        INSERT INTO panen_harian (id_user, id_blok, tanggal, jenis_buah, jumlah_kg)
        VALUES (p_id_user, p_id_blok, p_tanggal, p_jenis_buah, p_jumlah_kg);
    ELSE
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Tidak memiliki akses input panen';
    END IF;
END$$

CREATE PROCEDURE `sp_input_absen_karyawan`(
    IN p_id_user INT,
    IN p_tanggal DATE,
    IN p_status_kehadiran ENUM('Hadir','Izin','Sakit','Alpha','Libur_Agama'),
    IN p_jam_masuk TIME
)
BEGIN
    DECLARE v_bisa_input TINYINT(1);
    
    SELECT bisa_input_absen INTO v_bisa_input
    FROM users WHERE id_user = p_id_user;
    
    IF v_bisa_input = 1 THEN
        IF NOT EXISTS (SELECT 1 FROM absensi WHERE id_user = p_id_user AND tanggal = p_tanggal) THEN
            INSERT INTO absensi (id_user, tanggal, status_kehadiran, jam_masuk)
            VALUES (p_id_user, p_tanggal, p_status_kehadiran, p_jam_masuk);
        ELSE
            SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Sudah absen hari ini';
        END IF;
    ELSE
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Tidak memiliki akses input absen';
    END IF;
END$$

CREATE PROCEDURE `sp_generate_gaji_periode`(
    IN p_periode VARCHAR(20),
    IN p_start_date DATE,
    IN p_end_date DATE
)
BEGIN
    DECLARE done INT DEFAULT 0;
    DECLARE v_id_user INT;
    DECLARE v_total_upah DECIMAL(12,2);
    DECLARE v_id_pengeluaran INT;
    
    DECLARE cur_karyawan CURSOR FOR 
    SELECT id_user FROM users WHERE status_aktif = 1 AND role IN ('karyawan', 'admin');
    
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;
    
    OPEN cur_karyawan;
    
    read_loop: LOOP
        FETCH cur_karyawan INTO v_id_user;
        IF done = 1 THEN
            LEAVE read_loop;
        END IF;
        
        SET v_total_upah = fn_total_upah_karyawan(v_id_user, p_start_date, p_end_date);
        
        IF v_total_upah > 0 THEN
            INSERT INTO pengeluaran (tanggal, jenis_pengeluaran, total_biaya, id_user_pencatat, keterangan)
            VALUES (CURDATE(), 'gaji', v_total_upah, 1, CONCAT('Gaji periode ', p_periode));
            
            SET v_id_pengeluaran = LAST_INSERT_ID();
            
            INSERT INTO pengeluaran_gaji (id_pengeluaran, id_user, periode, total_gaji)
            VALUES (v_id_pengeluaran, v_id_user, p_periode, v_total_upah);
        END IF;
        
    END LOOP;
    
    CLOSE cur_karyawan;
END$$

CREATE PROCEDURE `sp_generate_rekap_lengkap`(
    IN p_tipe_periode ENUM('harian','10_harian','bulanan'),
    IN p_start_date DATE,
    IN p_end_date DATE
)
BEGIN
    DECLARE v_periode VARCHAR(20);
    DECLARE v_total_karyawan INT;
    DECLARE v_total_kehadiran INT;
    
    CASE p_tipe_periode
        WHEN 'harian' THEN SET v_periode = p_start_date;
        WHEN '10_harian' THEN SET v_periode = CONCAT(p_start_date, '_to_', p_end_date);
        WHEN 'bulanan' THEN SET v_periode = DATE_FORMAT(p_start_date, '%Y-%m');
    END CASE;
    
    SELECT COUNT(*) INTO v_total_karyawan 
    FROM users 
    WHERE status_aktif = 1 AND role IN ('karyawan', 'admin');
    
    SELECT COUNT(*) INTO v_total_kehadiran
    FROM absensi 
    WHERE tanggal BETWEEN p_start_date AND p_end_date
    AND status_kehadiran = 'Hadir';
    
    INSERT INTO rekap_keuangan (
        periode, tipe_periode, 
        total_pemasukan,
        total_pengeluaran_pupuk, total_pengeluaran_transport, 
        total_pengeluaran_perawatan, total_pengeluaran_gaji, total_pengeluaran_lainnya,
        total_kehadiran, total_izin, total_sakit, total_alpha, total_karyawan_aktif,
        total_laporan_masalah, total_masalah_selesai
    )
    SELECT 
        v_periode as periode,
        p_tipe_periode as tipe_periode,
        
        COALESCE(SUM(pm.total_pemasukan), 0) as total_pemasukan,
        
        COALESCE(SUM(CASE WHEN pg.jenis_pengeluaran = 'pupuk' THEN pg.total_biaya ELSE 0 END), 0) as pupuk,
        COALESCE(SUM(CASE WHEN pg.jenis_pengeluaran = 'transportasi' THEN pg.total_biaya ELSE 0 END), 0) as transport,
        COALESCE(SUM(CASE WHEN pg.jenis_pengeluaran = 'perawatan' THEN pg.total_biaya ELSE 0 END), 0) as perawatan,
        COALESCE(SUM(CASE WHEN pg.jenis_pengeluaran = 'gaji' THEN pg.total_biaya ELSE 0 END), 0) as gaji,
        COALESCE(SUM(CASE WHEN pg.jenis_pengeluaran = 'lainnya' THEN pg.total_biaya ELSE 0 END), 0) as lainnya,
        
        COALESCE(SUM(CASE WHEN a.status_kehadiran = 'Hadir' THEN 1 ELSE 0 END), 0) as kehadiran,
        COALESCE(SUM(CASE WHEN a.status_kehadiran = 'Izin' THEN 1 ELSE 0 END), 0) as izin,
        COALESCE(SUM(CASE WHEN a.status_kehadiran = 'Sakit' THEN 1 ELSE 0 END), 0) as sakit,
        COALESCE(SUM(CASE WHEN a.status_kehadiran = 'Alpha' THEN 1 ELSE 0 END), 0) as alpha,
        v_total_karyawan as total_karyawan,
        
        COALESCE(COUNT(lm.id_masalah), 0) as total_laporan,
        COALESCE(SUM(CASE WHEN lm.status_masalah = 'selesai' THEN 1 ELSE 0 END), 0) as masalah_selesai
        
    FROM pemasukan pm 
    LEFT JOIN pengeluaran pg ON pg.tanggal BETWEEN p_start_date AND p_end_date
    LEFT JOIN absensi a ON a.tanggal BETWEEN p_start_date AND p_end_date
    LEFT JOIN laporan_masalah lm ON lm.tanggal BETWEEN p_start_date AND p_end_date
    WHERE pm.tanggal BETWEEN p_start_date AND p_end_date;
    
END$$

CREATE PROCEDURE `sp_verifikasi_panen`(
    IN p_id_panen INT,
    IN p_id_verifikator INT
)
BEGIN
    UPDATE panen_harian 
    SET status_panen = 'diverifikasi',
        diverifikasi_oleh = p_id_verifikator
    WHERE id_panen = p_id_panen
    AND status_panen = 'draft';
END$$

CREATE PROCEDURE `sp_selesaikan_masalah`(
    IN p_id_masalah INT,
    IN p_id_penangan INT,
    IN p_tindakan TEXT
)
BEGIN
    UPDATE laporan_masalah 
    SET status_masalah = 'selesai',
        ditangani_oleh = p_id_penangan,
        tindakan = p_tindakan,
        tanggal_selesai = NOW()
    WHERE id_masalah = p_id_masalah;
END$$

CREATE PROCEDURE `sp_laporan_produktivitas`(
    IN p_start_date DATE,
    IN p_end_date DATE
)
BEGIN
    SELECT 
        u.id_user,
        u.nama_lengkap,
        u.jabatan,
        COUNT(DISTINCT a.tanggal) as hari_kerja,
        COALESCE(SUM(ph.jumlah_kg), 0) as total_kg,
        COALESCE(SUM(ph.total_upah), 0) as total_upah,
        CASE 
            WHEN COUNT(DISTINCT a.tanggal) > 0 THEN COALESCE(SUM(ph.jumlah_kg), 0) / COUNT(DISTINCT a.tanggal)
            ELSE 0 
        END as rata_kg_per_hari
    FROM users u
    LEFT JOIN absensi a ON u.id_user = a.id_user AND a.tanggal BETWEEN p_start_date AND p_end_date AND a.status_kehadiran = 'Hadir'
    LEFT JOIN panen_harian ph ON u.id_user = ph.id_user AND ph.tanggal BETWEEN p_start_date AND p_end_date AND ph.status_panen = 'diverifikasi'
    WHERE u.status_aktif = 1 AND u.role IN ('karyawan', 'admin')
    GROUP BY u.id_user, u.nama_lengkap, u.jabatan
    ORDER BY total_kg DESC;
END$$

DELIMITER ;

-- --------------------------------------------------------
-- TRIGGERS
-- --------------------------------------------------------

DELIMITER $$

CREATE TRIGGER `trg_panen_total_upah` BEFORE INSERT ON `panen_harian` 
FOR EACH ROW 
BEGIN
    DECLARE v_harga DECIMAL(10,2);
    SELECT harga_upah_per_kg INTO v_harga FROM blok_ladang WHERE id_blok = NEW.id_blok;
    SET NEW.harga_upah_per_kg = v_harga;
    SET NEW.total_upah = NEW.jumlah_kg * v_harga;
END$$

CREATE TRIGGER `trg_detail_penjualan_subtotal` BEFORE INSERT ON `detail_penjualan` 
FOR EACH ROW BEGIN
    SET NEW.subtotal = NEW.jumlah_kg * NEW.harga_jual_kg;
END$$

CREATE TRIGGER `trg_update_penjualan_total` AFTER INSERT ON `detail_penjualan` 
FOR EACH ROW BEGIN
    UPDATE penjualan 
    SET total_berat_kg = (SELECT SUM(jumlah_kg) FROM detail_penjualan WHERE id_penjualan = NEW.id_penjualan),
        total_pemasukan = (SELECT SUM(subtotal) FROM detail_penjualan WHERE id_penjualan = NEW.id_penjualan)
    WHERE id_penjualan = NEW.id_penjualan;
END$$

CREATE TRIGGER `trg_auto_pemasukan` AFTER UPDATE ON `penjualan` 
FOR EACH ROW BEGIN
    IF NEW.total_pemasukan > 0 AND NOT EXISTS (
        SELECT 1 FROM pemasukan WHERE id_penjualan = NEW.id_penjualan
    ) THEN
        INSERT INTO pemasukan (id_penjualan, tanggal, total_pemasukan, id_user_pencatat, keterangan)
        VALUES (NEW.id_penjualan, NEW.tanggal, NEW.total_pemasukan, NEW.id_user_pencatat, 
                CONCAT('Penjualan ke ', NEW.pembeli));
    END IF;
END$$

CREATE TRIGGER `trg_pupuk_total` BEFORE INSERT ON `pengeluaran_pupuk` 
FOR EACH ROW BEGIN
    SET NEW.total_harga = NEW.jumlah * NEW.harga_satuan;
END$$

CREATE TRIGGER `trg_pengeluaran_from_pupuk` AFTER INSERT ON `pengeluaran_pupuk` 
FOR EACH ROW BEGIN
    UPDATE pengeluaran SET total_biaya = NEW.total_harga 
    WHERE id_pengeluaran = NEW.id_pengeluaran;
END$$

CREATE TRIGGER `trg_pengeluaran_from_transport` AFTER INSERT ON `pengeluaran_transportasi` 
FOR EACH ROW BEGIN
    UPDATE pengeluaran SET total_biaya = NEW.biaya 
    WHERE id_pengeluaran = NEW.id_pengeluaran;
END$$

CREATE TRIGGER `trg_pengeluaran_from_perawatan` AFTER INSERT ON `pengeluaran_perawatan` 
FOR EACH ROW BEGIN
    UPDATE pengeluaran SET total_biaya = NEW.biaya 
    WHERE id_pengeluaran = NEW.id_pengeluaran;
END$$

CREATE TRIGGER `trg_pengeluaran_from_gaji` AFTER INSERT ON `pengeluaran_gaji` 
FOR EACH ROW BEGIN
    UPDATE pengeluaran SET total_biaya = NEW.total_gaji 
    WHERE id_pengeluaran = NEW.id_pengeluaran;
END$$

CREATE TRIGGER `trg_rekap_lengkap_calculation` BEFORE INSERT ON `rekap_keuangan` 
FOR EACH ROW BEGIN
    SET NEW.total_pengeluaran_all = NEW.total_pengeluaran_pupuk + NEW.total_pengeluaran_transport + 
                                  NEW.total_pengeluaran_perawatan + NEW.total_pengeluaran_gaji + NEW.total_pengeluaran_lainnya;
    SET NEW.laba_bersih = NEW.total_pemasukan - NEW.total_pengeluaran_all;
    SET NEW.margin_keuntungan = CASE 
        WHEN NEW.total_pemasukan > 0 THEN (NEW.laba_bersih / NEW.total_pemasukan) * 100
        ELSE 0 
    END;
    SET NEW.efisiensi_kehadiran = CASE 
        WHEN NEW.total_karyawan_aktif > 0 THEN (NEW.total_kehadiran / NEW.total_karyawan_aktif) * 100
        ELSE 0 
    END;
END$$

DELIMITER ;

-- --------------------------------------------------------
-- Insert Master Data
-- --------------------------------------------------------

INSERT INTO `blok_ladang` (`id_blok`, `nama_blok`, `kategori`, `luas_hektar`, `harga_upah_per_kg`) VALUES
(1, 'Blok A', 'dekat', 50.00, 200.00),
(2, 'Blok B', 'dekat', 50.00, 200.00),
(3, 'Blok C', 'jauh', 50.00, 220.00),
(4, 'Blok D', 'jauh', 50.00, 220.00);

INSERT INTO `users` (`id_user`, `username`, `password`, `nama_lengkap`, `jabatan`, `role`, `status_aktif`, `no_telepon`, `status_tinggal`, `bisa_input_panen`, `bisa_input_absen`) VALUES
(1, 'owner', 'owner123', 'Pemilik Kebun', 'mandor', 'owner', 1, '081234567890', 'luar', 0, 0),
(2, 'mandor1', 'mandor123', 'Mandor Utama', 'mandor', 'admin', 1, '081234567891', 'barak', 0, 0),
(3, 'asmandor1', 'asmandor123', 'Asisten Mandor', 'asisten_mandor', 'admin', 1, '081234567892', 'keluarga_barak', 0, 0),
(4, 'karyawan1', 'karyawan123', 'Budi Santoso', 'anggota', 'karyawan', 1, '081234567893', 'barak', 1, 1),
(5, 'karyawan2', 'karyawan123', 'Sari Indah', 'anggota', 'karyawan', 1, '081234567894', 'keluarga_barak', 1, 1),
(6, 'karyawan3', 'karyawan123', 'Rudi Hartono', 'anggota', 'karyawan', 1, '081234567895', 'barak', 1, 1);

COMMIT;