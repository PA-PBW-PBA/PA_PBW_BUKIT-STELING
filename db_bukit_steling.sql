-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 30, 2026 at 11:30 AM
-- Server version: 8.4.3
-- PHP Version: 8.3.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_bukit_steling`
--

-- --------------------------------------------------------

--
-- Table structure for table `tb_admin`
--

CREATE TABLE `tb_admin` (
  `id_admin` int NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tb_admin`
--

INSERT INTO `tb_admin` (`id_admin`, `nama_lengkap`, `email`, `password`, `created_at`) VALUES
(1, 'Admin Puncak Steling', 'admin@bukitstelling.com', 'admin123', '2026-03-29 07:55:41');

-- --------------------------------------------------------

--
-- Table structure for table `tb_fasilitas`
--

CREATE TABLE `tb_fasilitas` (
  `id_fasilitas` int NOT NULL,
  `nama_fasilitas` varchar(100) NOT NULL,
  `icon` varchar(50) DEFAULT NULL,
  `file_gambar` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tb_fasilitas`
--

INSERT INTO `tb_fasilitas` (`id_fasilitas`, `nama_fasilitas`, `icon`, `file_gambar`) VALUES
(1, 'Area Parkir', 'car-front', 'parkir.jpg'),
(2, 'Toilet Umum', 'house-door', 'toilet.jpg'),
(3, 'Warung Makan', 'shop', 'warung.jpg'),
(4, 'Spot Foto', 'camera', 'spot_foto.jpg'),
(5, 'Musholla', 'moon-stars', 'musholla.jpg'),
(6, 'Area Santai', 'tree', 'area_santai.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `tb_galeri`
--

CREATE TABLE `tb_galeri` (
  `id_galeri` int NOT NULL,
  `id_pengunjung` int DEFAULT NULL,
  `kategori` varchar(50) DEFAULT NULL,
  `caption` text,
  `file_foto` varchar(255) NOT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `tanggal_upload` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tb_galeri`
--

INSERT INTO `tb_galeri` (`id_galeri`, `id_pengunjung`, `kategori`, `caption`, `file_foto`, `status`, `tanggal_upload`) VALUES
(1, 1, 'Pagi Hari', '123', '1774776448_1.jpg', 'approved', '2026-03-29 09:27:28'),
(2, 1, 'Pagi Hari', '-', '1774842788_1.jpg', 'approved', '2026-03-30 03:53:08');

-- --------------------------------------------------------

--
-- Table structure for table `tb_informasi`
--

CREATE TABLE `tb_informasi` (
  `id_info` int NOT NULL,
  `harga_tiket` int NOT NULL,
  `jam_buka` time NOT NULL,
  `jam_tutup` time NOT NULL,
  `deskripsi` text,
  `tata_tertib` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tb_informasi`
--

INSERT INTO `tb_informasi` (`id_info`, `harga_tiket`, `jam_buka`, `jam_tutup`, `deskripsi`, `tata_tertib`) VALUES
(1, 15000, '10:00:00', '03:00:00', 'Puncak Steling Samarinda merupakan destinasi wisata alam yang menawarkan pemandangan Kota Samarinda dari ketinggian.', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tb_pengunjung`
--

CREATE TABLE `tb_pengunjung` (
  `id_pengunjung` int NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tb_pengunjung`
--

INSERT INTO `tb_pengunjung` (`id_pengunjung`, `nama_lengkap`, `email`, `password`, `created_at`) VALUES
(1, 'Rizky Pratama', 'rizky@gmail.com', '123', '2026-03-29 08:40:04'),
(2, 'Anisa Bahar', 'anisa@gmail.com', '123', '2026-03-29 08:40:04'),
(3, 'Prabowo', 'sawit@gmail.com', '$2y$10$/nRsMnBeNtCKobYOxvO0t.R5cjZlyZng66ch21mNllJmF5dr5An5a', '2026-03-29 09:53:32'),
(4, 'Gibran', 'fufufafa@gmail.com', '123', '2026-03-29 09:56:55');

-- --------------------------------------------------------

--
-- Table structure for table `tb_ulasan`
--

CREATE TABLE `tb_ulasan` (
  `id_ulasan` int NOT NULL,
  `id_pengunjung` int DEFAULT NULL,
  `rating` int NOT NULL,
  `komentar` text NOT NULL,
  `tanggal_ulasan` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ;

--
-- Dumping data for table `tb_ulasan`
--

INSERT INTO `tb_ulasan` (`id_ulasan`, `id_pengunjung`, `rating`, `komentar`, `tanggal_ulasan`) VALUES
(2, 2, 4, 'Tempatnya asik buat nyantai, jalannya lumayan nanjak tapi worth it banget pas sampai atas.', '2026-03-29 08:40:04'),
(4, 1, 5, 'gacor gacor', '2026-03-29 16:00:00'),
(6, 1, 4, 'tes', '2026-03-29 16:00:00'),
(7, 1, 5, 'hdue', '2026-03-29 16:00:00'),
(8, 1, 5, 'dea', '2026-03-29 16:00:00'),
(9, 1, 5, 'dsfcksefjklesjafkjwediekwjckejcnjdcj;EIHashfjlsllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllll', '2026-03-29 16:00:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tb_admin`
--
ALTER TABLE `tb_admin`
  ADD PRIMARY KEY (`id_admin`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `tb_fasilitas`
--
ALTER TABLE `tb_fasilitas`
  ADD PRIMARY KEY (`id_fasilitas`);

--
-- Indexes for table `tb_galeri`
--
ALTER TABLE `tb_galeri`
  ADD PRIMARY KEY (`id_galeri`),
  ADD KEY `id_pengunjung` (`id_pengunjung`);

--
-- Indexes for table `tb_informasi`
--
ALTER TABLE `tb_informasi`
  ADD PRIMARY KEY (`id_info`);

--
-- Indexes for table `tb_pengunjung`
--
ALTER TABLE `tb_pengunjung`
  ADD PRIMARY KEY (`id_pengunjung`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `tb_ulasan`
--
ALTER TABLE `tb_ulasan`
  ADD PRIMARY KEY (`id_ulasan`),
  ADD KEY `id_pengunjung` (`id_pengunjung`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tb_admin`
--
ALTER TABLE `tb_admin`
  MODIFY `id_admin` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tb_fasilitas`
--
ALTER TABLE `tb_fasilitas`
  MODIFY `id_fasilitas` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tb_galeri`
--
ALTER TABLE `tb_galeri`
  MODIFY `id_galeri` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tb_informasi`
--
ALTER TABLE `tb_informasi`
  MODIFY `id_info` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tb_pengunjung`
--
ALTER TABLE `tb_pengunjung`
  MODIFY `id_pengunjung` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tb_ulasan`
--
ALTER TABLE `tb_ulasan`
  MODIFY `id_ulasan` int NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tb_galeri`
--
ALTER TABLE `tb_galeri`
  ADD CONSTRAINT `tb_galeri_ibfk_1` FOREIGN KEY (`id_pengunjung`) REFERENCES `tb_pengunjung` (`id_pengunjung`) ON DELETE SET NULL;

--
-- Constraints for table `tb_ulasan`
--
ALTER TABLE `tb_ulasan`
  ADD CONSTRAINT `tb_ulasan_ibfk_1` FOREIGN KEY (`id_pengunjung`) REFERENCES `tb_pengunjung` (`id_pengunjung`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
