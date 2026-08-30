-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 09, 2026 at 08:48 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dbperpustakaan`
--

-- --------------------------------------------------------

--
-- Table structure for table `buku`
--

CREATE TABLE `buku` (
  `Id_buku` int(11) NOT NULL,
  `Judul` varchar(30) NOT NULL,
  `Pengarang` varchar(225) NOT NULL,
  `Id_genre` int(11) NOT NULL,
  `Id_kategori` int(11) NOT NULL,
  `Foto_buku` text DEFAULT NULL,
  `Stok` int(225) NOT NULL,
  `Harga` decimal(15,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `buku`
--

INSERT INTO `buku` (`Id_buku`, `Judul`, `Pengarang`, `Id_genre`, `Id_kategori`, `Foto_buku`, `Stok`, `Harga`) VALUES
(11, 'Jujutsu Kaisen', 'Gege Akutami', 4, 1, '1778302954_Volume_1.webp', 46, 30000.00),
(13, 'Look Back', 'Tatsuki Fujimoto', 3, 1, '1777776782_Look_Back_volume_cover.jpg', 76, 35000.00),
(14, 'Little Prince', 'Antoine de Saint-Exupéry', 1, 2, '1777778111_Lepetitprinceindonesia.jpg', 74, 45000.00),
(16, 'One Piece', 'Eiichiro Oda', 1, 1, '1777893065_op.webp', 90, 30000.00),
(17, 'Animal farm', 'George Orwell', 3, 2, '1778038796_animal.jpg', 38, 25000.00),
(18, 'Absolute Batman', 'Scott Snyder', 4, 1, '1778291666_a_batman.jpg', 50, 65000.00);

-- --------------------------------------------------------

--
-- Table structure for table `genre`
--

CREATE TABLE `genre` (
  `Id_genre` int(11) NOT NULL,
  `genre` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `genre`
--

INSERT INTO `genre` (`Id_genre`, `genre`) VALUES
(1, 'Fiksi'),
(2, 'Non-fiksi'),
(3, 'Drama'),
(4, 'Aksi');

-- --------------------------------------------------------

--
-- Table structure for table `kategori`
--

CREATE TABLE `kategori` (
  `Id_kategori` int(11) NOT NULL,
  `kategori` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kategori`
--

INSERT INTO `kategori` (`Id_kategori`, `kategori`) VALUES
(1, 'Komik'),
(2, 'Novel');

-- --------------------------------------------------------

--
-- Table structure for table `log_stok`
--

CREATE TABLE `log_stok` (
  `id_log` int(11) NOT NULL,
  `Id_peminjaman` varchar(225) NOT NULL,
  `stok_berkurang` int(11) NOT NULL,
  `Id_buku` int(11) NOT NULL,
  `keterangan` varchar(225) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `peminjaman`
--

CREATE TABLE `peminjaman` (
  `Id_peminjaman` varchar(225) NOT NULL,
  `Nama_peminjam` varchar(255) NOT NULL,
  `Alamat` text NOT NULL,
  `No_Telp` varchar(255) NOT NULL,
  `Tgl_pinjam` date NOT NULL,
  `Id_buku` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `Status` enum('Dipinjam','Dikembalikan','Terlambat','Buku Rusak') DEFAULT 'Dipinjam',
  `Denda` decimal(15,2) DEFAULT 0.00,
  `Id_pengembalian` varchar(225) NOT NULL,
  `Tgl_kembali` date DEFAULT NULL,
  `Kondisi_kembali` varchar(10) DEFAULT 'baik',
  `Idp_pinjam` varchar(225) NOT NULL,
  `Idp_kembali` varchar(225) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `peminjaman`
--

INSERT INTO `peminjaman` (`Id_peminjaman`, `Nama_peminjam`, `Alamat`, `No_Telp`, `Tgl_pinjam`, `Id_buku`, `jumlah`, `Status`, `Denda`, `Id_pengembalian`, `Tgl_kembali`, `Kondisi_kembali`, `Idp_pinjam`, `Idp_kembali`) VALUES
('PJM001', 'Afriza Abiyu Hanif', 'Bandung', '09210832129', '2026-05-08', 16, 1, 'Dikembalikan', 0.00, 'KMB003', '2026-05-09', 'baik', 'Noor', 'Noor'),
('PJM002', 'Nurrafi Ahmad Kasyfayail', 'Bandung', '0323142342', '2026-05-10', 13, 1, 'Dikembalikan', 0.00, 'KMB002', '2026-05-08', 'baik', 'Noor', 'Connor');

-- --------------------------------------------------------

--
-- Table structure for table `petugas`
--

CREATE TABLE `petugas` (
  `Id_petugas` varchar(225) NOT NULL,
  `Username` varchar(30) NOT NULL,
  `Password` varchar(225) NOT NULL,
  `JenisKelamin` varchar(30) NOT NULL,
  `Role` enum('Admin','Petugas') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `petugas`
--

INSERT INTO `petugas` (`Id_petugas`, `Username`, `Password`, `JenisKelamin`, `Role`) VALUES
('PT01', 'Noor', '8e296a067a37563370ded05f5a3bf3ec', 'Pria', 'Admin'),
('PT02', 'Connor', '23fb1f4f40eb6d6741ff3f7670119cb5', 'Pria', 'Petugas'),
('PT03', 'Henry', 'c4ca4238a0b923820dcc509a6f75849b', 'Pria', 'Petugas');

-- --------------------------------------------------------

--
-- Table structure for table `riwayat`
--

CREATE TABLE `riwayat` (
  `Id_riwayat` int(11) NOT NULL,
  `Tgl_Peminjaman` date NOT NULL,
  `Tgl_Pengembalian` date NOT NULL,
  `Id_buku` int(11) NOT NULL,
  `Id_peminjaman` varchar(225) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `riwayat`
--

INSERT INTO `riwayat` (`Id_riwayat`, `Tgl_Peminjaman`, `Tgl_Pengembalian`, `Id_buku`, `Id_peminjaman`) VALUES
(3, '2026-05-10', '2026-05-08', 13, 'PJM002'),
(4, '2026-05-08', '2026-05-09', 16, 'PJM001');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `buku`
--
ALTER TABLE `buku`
  ADD PRIMARY KEY (`Id_buku`);

--
-- Indexes for table `genre`
--
ALTER TABLE `genre`
  ADD PRIMARY KEY (`Id_genre`);

--
-- Indexes for table `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`Id_kategori`);

--
-- Indexes for table `log_stok`
--
ALTER TABLE `log_stok`
  ADD PRIMARY KEY (`id_log`);

--
-- Indexes for table `peminjaman`
--
ALTER TABLE `peminjaman`
  ADD PRIMARY KEY (`Id_peminjaman`);

--
-- Indexes for table `petugas`
--
ALTER TABLE `petugas`
  ADD PRIMARY KEY (`Id_petugas`);

--
-- Indexes for table `riwayat`
--
ALTER TABLE `riwayat`
  ADD PRIMARY KEY (`Id_riwayat`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `buku`
--
ALTER TABLE `buku`
  MODIFY `Id_buku` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `log_stok`
--
ALTER TABLE `log_stok`
  MODIFY `id_log` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `riwayat`
--
ALTER TABLE `riwayat`
  MODIFY `Id_riwayat` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
