-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 10, 2026 at 05:59 PM
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
(1, 'Area Parkir', 'car-front', '1775321745_69d142911f969.webp'),
(2, 'Toilet Umum', 'house-door', '1775321672_69d142481b402.webp'),
(3, 'Warung', 'shop', '1775321642_69d1422a66b8c.webp'),
(4, 'Spot Foto', 'camera', '1775321683_69d142530f56c.webp'),
(5, 'Musholla', 'moon-stars', '1775321727_69d1427f60a02.webp'),
(6, 'Area Santai', 'tree', '1775321718_69d142765a583.webp'),
(7, 'Tempat Charge Handphone', NULL, '1775321761_69d142a17c641.webp');

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
(2, 1, 'Pagi Hari', '-', '1774842788_1.jpg', 'approved', '2026-03-30 03:53:08'),
(5, 2, 'Sore & Sunset', 's', '1775060548_2.png', 'approved', '2026-04-01 16:22:28'),
(7, 2, 'Malam Hari', 's', '1775061048_2.png', 'approved', '2026-04-01 16:30:48'),
(8, 2, 'Sore & Sunset', 'sda', '1775063067_2.png', 'approved', '2026-04-01 17:04:27'),
(9, 5, 'Malam Hari', 'ya\r\n', '1775100224_5.jpg', 'approved', '2026-04-02 03:23:44'),
(10, 2, 'Pagi Hari', '=', '1775320282_2.webp', 'approved', '2026-04-04 16:31:22'),
(11, 1, 'Sore & Sunset', 's', '1775320665_1.webp', 'approved', '2026-04-04 16:37:45'),
(12, 7, 'Sore & Sunset', 'z', '1775322232_7.webp', 'approved', '2026-04-04 17:03:52'),
(13, 1, 'Sore & Sunset', 'uSDhOHDy9q3487n837-483C4U092802-938N0C384989C90842', '1775812044_1.webp', 'approved', '2026-04-10 09:07:24'),
(14, 1, 'Malam Hari', 'S', '1775843176_1.webp', 'approved', '2026-04-10 17:46:17');

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
(1, 10000, '14:22:00', '03:00:00', 'Puncak Steling Samarinda merupakan destinasi wisata alam yang menawarkan pemandangan Kota Samarinda dari ketinggian.', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tb_like`
--

CREATE TABLE `tb_like` (
  `id_like` int NOT NULL,
  `id_galeri` int NOT NULL,
  `id_pengunjung` int NOT NULL,
  `tanggal_like` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tb_like`
--

INSERT INTO `tb_like` (`id_like`, `id_galeri`, `id_pengunjung`, `tanggal_like`) VALUES
(2, 13, 1, '2026-04-10 12:15:45'),
(6, 12, 1, '2026-04-10 12:15:48'),
(7, 12, 2, '2026-04-10 12:16:05'),
(8, 11, 1, '2026-04-10 14:58:54'),
(9, 9, 1, '2026-04-10 15:13:51'),
(10, 13, 2, '2026-04-10 17:35:31');

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
(4, 'Gibran', 'fufufafa@gmail.com', '123', '2026-03-29 09:56:55'),
(5, 'repi', 'repigay@outlook.com', '123', '2026-04-02 03:23:11'),
(6, 'jokowi', 'jokowi@gmail.com', '123', '2026-04-02 04:27:47'),
(7, 'lau sape', '123@gmail.com', '123', '2026-04-04 17:00:25'),
(8, 'r', '111@gmail.com', '1', '2026-04-04 17:09:30'),
(9, '121222', '1@r.com', 'wwwwwwwwwww', '2026-04-04 17:16:11'),
(10, 'BigMO', 'mo@outlook.com', '123', '2026-04-08 14:04:51'),
(11, 'eko wijaya', 'eko@gmail.com', '12345678', '2026-04-10 08:23:05');

-- --------------------------------------------------------

--
-- Table structure for table `tb_ulasan`
--

CREATE TABLE `tb_ulasan` (
  `id_ulasan` int NOT NULL,
  `id_pengunjung` int DEFAULT NULL,
  `rating` int NOT NULL,
  `komentar` text NOT NULL,
  `balasan_admin` text,
  `tanggal_ulasan` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tb_ulasan`
--

INSERT INTO `tb_ulasan` (`id_ulasan`, `id_pengunjung`, `rating`, `komentar`, `balasan_admin`, `tanggal_ulasan`) VALUES
(2, 2, 4, 'Tempatnya asik buat nyantai, jalannya lumayan nanjak tapi worth it banget pas sampai atas.', NULL, '2026-03-29 08:40:04'),
(4, 1, 5, 'gacor gacor', NULL, '2026-03-29 16:00:00'),
(6, 1, 4, 'tes', NULL, '2026-03-29 16:00:00'),
(7, 1, 5, 'hdue', NULL, '2026-03-29 16:00:00'),
(11, 2, 5, 'yo', 'jhuhuhuihlijoljoljj', '2026-04-01 16:22:03'),
(12, 4, 1, 'jelek jir', 'haters', '2026-04-01 23:37:19'),
(16, 1, 5, 'ASDASDAD', NULL, '2026-04-10 10:13:38'),
(17, 1, 5, 'ADADADADADASD', NULL, '2026-04-10 10:13:44'),
(18, 1, 5, 'ADADADADADADA', NULL, '2026-04-10 10:13:50'),
(19, 1, 5, 'ASDADADADADDA', NULL, '2026-04-10 10:13:56');

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
-- Indexes for table `tb_like`
--
ALTER TABLE `tb_like`
  ADD PRIMARY KEY (`id_like`),
  ADD KEY `id_galeri` (`id_galeri`),
  ADD KEY `id_pengunjung` (`id_pengunjung`);

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
  MODIFY `id_fasilitas` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tb_galeri`
--
ALTER TABLE `tb_galeri`
  MODIFY `id_galeri` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `tb_informasi`
--
ALTER TABLE `tb_informasi`
  MODIFY `id_info` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tb_like`
--
ALTER TABLE `tb_like`
  MODIFY `id_like` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tb_pengunjung`
--
ALTER TABLE `tb_pengunjung`
  MODIFY `id_pengunjung` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `tb_ulasan`
--
ALTER TABLE `tb_ulasan`
  MODIFY `id_ulasan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tb_galeri`
--
ALTER TABLE `tb_galeri`
  ADD CONSTRAINT `tb_galeri_ibfk_1` FOREIGN KEY (`id_pengunjung`) REFERENCES `tb_pengunjung` (`id_pengunjung`) ON DELETE SET NULL;

--
-- Constraints for table `tb_like`
--
ALTER TABLE `tb_like`
  ADD CONSTRAINT `tb_like_ibfk_1` FOREIGN KEY (`id_galeri`) REFERENCES `tb_galeri` (`id_galeri`) ON DELETE CASCADE,
  ADD CONSTRAINT `tb_like_ibfk_2` FOREIGN KEY (`id_pengunjung`) REFERENCES `tb_pengunjung` (`id_pengunjung`) ON DELETE CASCADE;

--
-- Constraints for table `tb_ulasan`
--
ALTER TABLE `tb_ulasan`
  ADD CONSTRAINT `tb_ulasan_ibfk_1` FOREIGN KEY (`id_pengunjung`) REFERENCES `tb_pengunjung` (`id_pengunjung`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
