-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Dec 17, 2023 at 07:21 AM
-- Server version: 10.4.21-MariaDB
-- PHP Version: 8.0.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bismillah`
--

-- --------------------------------------------------------

--
-- Table structure for table `check_out`
--

CREATE TABLE `check_out` (
  `id_checkout` int(11) NOT NULL,
  `id_penghuni` int(11) NOT NULL,
  `id_kamar` int(11) NOT NULL,
  `tanggal_checkout` date DEFAULT NULL,
  `keterangan_checkout` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `check_out`
--

INSERT INTO `check_out` (`id_checkout`, `id_penghuni`, `id_kamar`, `tanggal_checkout`, `keterangan_checkout`) VALUES
(1, 66, 5, '2023-08-31', 'catatan 5'),
(4, 79, 23, '2090-08-05', ''),
(5, 84, 3, '2023-09-06', ''),
(6, 86, 17, '2023-09-11', ''),
(7, 87, 3, '2023-09-12', '');

-- --------------------------------------------------------

--
-- Table structure for table `kamar`
--

CREATE TABLE `kamar` (
  `id_kamar` int(11) NOT NULL,
  `id_lokasi` int(11) DEFAULT NULL,
  `nomor_kamar` varchar(10) DEFAULT NULL,
  `harga_kamar` int(11) DEFAULT NULL,
  `status_kamar` enum('Tersedia','Terisi','Renovasi','Perbaikan') NOT NULL DEFAULT 'Tersedia'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `kamar`
--

INSERT INTO `kamar` (`id_kamar`, `id_lokasi`, `nomor_kamar`, `harga_kamar`, `status_kamar`) VALUES
(3, 2, '001', 1700000, 'Tersedia'),
(4, 2, '101', 1500000, 'Tersedia'),
(5, 2, '002', 1600000, 'Tersedia'),
(6, 2, '003', 2000000, 'Tersedia'),
(7, 2, '004', 1500000, 'Tersedia'),
(8, 4, '102', 1600000, 'Tersedia'),
(9, 4, '103', 1600000, 'Tersedia'),
(10, 4, '104', 1600000, 'Tersedia'),
(11, 4, '105', 1600000, 'Tersedia'),
(12, 4, '106', 1400000, 'Tersedia'),
(13, 4, '107', 1600000, 'Tersedia'),
(14, 4, '108', 1600000, 'Tersedia'),
(15, 4, '109', 1600000, 'Tersedia'),
(16, 4, '110', 1600000, 'Tersedia'),
(17, 4, '111', 1600000, 'Tersedia'),
(18, 5, '201', 1600000, 'Tersedia'),
(19, 5, '202', 1600000, 'Tersedia'),
(20, 5, '203', 1600000, 'Tersedia'),
(21, 5, '204', 1600000, 'Tersedia'),
(22, 5, '205', 1600000, 'Tersedia'),
(23, 5, '206', 1400000, 'Tersedia'),
(24, 5, '207', 1400000, 'Tersedia'),
(25, 5, '208', 1500000, 'Tersedia'),
(26, 5, '209', 1500000, 'Tersedia'),
(27, 5, '210', 1500000, 'Tersedia'),
(28, 5, '211', 1500000, 'Tersedia'),
(29, 5, '212', 1500000, 'Tersedia');

-- --------------------------------------------------------

--
-- Table structure for table `keluhan`
--

CREATE TABLE `keluhan` (
  `id_keluhan` int(11) NOT NULL,
  `id_penghuni` int(11) DEFAULT NULL,
  `tanggal_keluhan` datetime DEFAULT NULL,
  `gambar_keluhan` varchar(255) DEFAULT NULL,
  `isi_keluhan` text DEFAULT NULL,
  `status_keluhan` enum('Belum Ditanggapi','Sedang Ditanggapi','Sudah Ditanggapi') NOT NULL DEFAULT 'Belum Ditanggapi'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `keluhan`
--

INSERT INTO `keluhan` (`id_keluhan`, `id_penghuni`, `tanggal_keluhan`, `gambar_keluhan`, `isi_keluhan`, `status_keluhan`) VALUES
(2, 79, '2023-08-05 17:24:56', 'imageupload/1691231435.jpg', 'Keluhan ', 'Belum Ditanggapi'),
(3, 84, '2023-08-06 00:56:16', 'imageupload/16rwxYhTD3ly..jpg', '1', 'Sedang Ditanggapi'),
(6, 86, '2023-08-11 20:57:23', 'imageupload/1691762243.jpeg', 'Lampu lantai 2 sebagian mati', 'Belum Ditanggapi');

-- --------------------------------------------------------

--
-- Table structure for table `kendaraan`
--

CREATE TABLE `kendaraan` (
  `id_kendaraan` int(11) NOT NULL,
  `id_penghuni` int(11) NOT NULL,
  `nomor_kendaraan` varchar(20) NOT NULL,
  `jenis_kendaraan` varchar(50) NOT NULL,
  `model_kendaraan` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `kendaraan`
--

INSERT INTO `kendaraan` (`id_kendaraan`, `id_penghuni`, `nomor_kendaraan`, `jenis_kendaraan`, `model_kendaraan`) VALUES
(5, 86, 'AB 111 SHN', 'Mobil', 'Honda Civic');

-- --------------------------------------------------------

--
-- Table structure for table `lokasi_kamar`
--

CREATE TABLE `lokasi_kamar` (
  `id_lokasi` int(11) NOT NULL,
  `nama_lokasi` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `lokasi_kamar`
--

INSERT INTO `lokasi_kamar` (`id_lokasi`, `nama_lokasi`) VALUES
(2, 'Rumah Induk'),
(4, 'Lantai 1'),
(5, 'Lantai 2'),
(7, 'Blackbox');

-- --------------------------------------------------------

--
-- Table structure for table `pembayaran`
--

CREATE TABLE `pembayaran` (
  `id_pembayaran` int(11) NOT NULL,
  `id_penghuni` int(11) NOT NULL,
  `tanggal_pembayaran` datetime DEFAULT NULL,
  `jumlah_pembayaran` int(11) NOT NULL,
  `keterangan_pembayaran` varchar(255) DEFAULT NULL,
  `bukti_pembayaran` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pembayaran`
--

INSERT INTO `pembayaran` (`id_pembayaran`, `id_penghuni`, `tanggal_pembayaran`, `jumlah_pembayaran`, `keterangan_pembayaran`, `bukti_pembayaran`) VALUES
(7, 86, '2023-01-11 00:00:00', 1600000, 'Bank Central Asia', 'imageupload/bayar.JPG');

-- --------------------------------------------------------

--
-- Table structure for table `pembayaran_tagihan`
--

CREATE TABLE `pembayaran_tagihan` (
  `id_pembayaran_tagihan` int(11) NOT NULL,
  `id_pembayaran` int(11) DEFAULT NULL,
  `id_tagihan` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pembayaran_tagihan`
--

INSERT INTO `pembayaran_tagihan` (`id_pembayaran_tagihan`, `id_pembayaran`, `id_tagihan`) VALUES
(16, 7, 85);

-- --------------------------------------------------------

--
-- Table structure for table `pengeluaran`
--

CREATE TABLE `pengeluaran` (
  `id_pengeluaran` int(11) NOT NULL,
  `keterangan_pengeluaran` varchar(100) NOT NULL,
  `nominal_pengeluaran` int(11) NOT NULL,
  `tanggal_pengeluaran` date NOT NULL,
  `bukti_bayar` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pengeluaran`
--

INSERT INTO `pengeluaran` (`id_pengeluaran`, `keterangan_pengeluaran`, `nominal_pengeluaran`, `tanggal_pengeluaran`, `bukti_bayar`) VALUES
(2, 'Stock Teh', 12800, '2023-08-03', 'imageupload/1691752441.jpeg'),
(3, 'Belanja Perbaikan Kos', 1246000, '2023-08-03', 'imageupload/1691752468.jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `penghuni`
--

CREATE TABLE `penghuni` (
  `id_penghuni` int(11) NOT NULL,
  `id_users` int(11) NOT NULL,
  `id_kamar` int(11) NOT NULL,
  `tanggal_registrasi_penghuni` date DEFAULT NULL,
  `tanggal_masuk_penghuni` date DEFAULT NULL,
  `tanggal_keluar_penghuni` date DEFAULT NULL,
  `identitas_penghuni` varchar(255) DEFAULT NULL,
  `keterangan_penghuni` text DEFAULT NULL,
  `status_penghuni` enum('aktif','tidak aktif') DEFAULT 'aktif'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `penghuni`
--

INSERT INTO `penghuni` (`id_penghuni`, `id_users`, `id_kamar`, `tanggal_registrasi_penghuni`, `tanggal_masuk_penghuni`, `tanggal_keluar_penghuni`, `identitas_penghuni`, `keterangan_penghuni`, `status_penghuni`) VALUES
(86, 130, 17, '2023-08-06', '2023-08-11', '2023-11-11', 'imageupload/1691746085.webp', '', 'tidak aktif'),
(87, 131, 3, '2023-08-12', '2023-08-12', '2023-09-12', 'img/nodata.jpg', '', 'tidak aktif');

-- --------------------------------------------------------

--
-- Table structure for table `penghuni_in_house`
--

CREATE TABLE `penghuni_in_house` (
  `id_pih` int(11) NOT NULL,
  `id_kamar` int(11) DEFAULT NULL,
  `id_penghuni` int(11) DEFAULT NULL,
  `tanggal_masuk_pih` date DEFAULT NULL,
  `tanggal_keluar_pih` date DEFAULT NULL,
  `durasi_sewa_pih` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `penghuni_in_house`
--

INSERT INTO `penghuni_in_house` (`id_pih`, `id_kamar`, `id_penghuni`, `tanggal_masuk_pih`, `tanggal_keluar_pih`, `durasi_sewa_pih`) VALUES
(8, 4, 81, '2023-08-05', '2023-09-05', '1');

-- --------------------------------------------------------

--
-- Table structure for table `pindah_kamar`
--

CREATE TABLE `pindah_kamar` (
  `id_pindah_kamar` int(11) NOT NULL,
  `id_penghuni` int(11) DEFAULT NULL,
  `kamar_sebelum` int(11) DEFAULT NULL,
  `kamar_sesudah` int(11) DEFAULT NULL,
  `waktu_pindah` datetime DEFAULT NULL,
  `alasan_pindah` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pindah_kamar`
--

INSERT INTO `pindah_kamar` (`id_pindah_kamar`, `id_penghuni`, `kamar_sebelum`, `kamar_sesudah`, `waktu_pindah`, `alasan_pindah`) VALUES
(2, 86, 23, 17, '2023-08-06 01:14:59', '');

-- --------------------------------------------------------

--
-- Table structure for table `rekening`
--

CREATE TABLE `rekening` (
  `id_rekening` int(11) NOT NULL,
  `jenis_pembayaran` varchar(255) DEFAULT NULL,
  `nomor_rekening` varchar(255) DEFAULT NULL,
  `pemilik_rekening` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `rekening`
--

INSERT INTO `rekening` (`id_rekening`, `jenis_pembayaran`, `nomor_rekening`, `pemilik_rekening`) VALUES
(2, 'Bank Central Asia', '1020535071', 'Riska Rahmasari Octavian'),
(3, 'Bank Central Asia', '8610475354', 'Muchammad Soim');

-- --------------------------------------------------------

--
-- Table structure for table `tagihan`
--

CREATE TABLE `tagihan` (
  `id_tagihan` int(11) NOT NULL,
  `id_penghuni` int(11) DEFAULT NULL,
  `no_tagihan` varchar(50) DEFAULT NULL,
  `kategori_tagihan` varchar(255) DEFAULT NULL,
  `deskripsi_tagihan` varchar(255) DEFAULT NULL,
  `jumlah_tagihan` int(255) DEFAULT NULL,
  `jumlah_bayar_tagihan` int(11) DEFAULT NULL,
  `jumlah_sisa_tagihan` int(11) DEFAULT NULL,
  `tanggal_tagihan` date DEFAULT NULL,
  `status_tagihan` enum('Lunas','Belum Lunas','Belum Bayar') DEFAULT 'Belum Bayar',
  `keterangan_tagihan` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tagihan`
--

INSERT INTO `tagihan` (`id_tagihan`, `id_penghuni`, `no_tagihan`, `kategori_tagihan`, `deskripsi_tagihan`, `jumlah_tagihan`, `jumlah_bayar_tagihan`, `jumlah_sisa_tagihan`, `tanggal_tagihan`, `status_tagihan`, `keterangan_tagihan`) VALUES
(81, 85, '05001', 'Tanda Jadi', '', 500000, NULL, 500000, '2023-08-06', 'Belum Bayar', ''),
(82, 86, '02002', 'sewa_tamu', 'Tamu', 250000, 150000, 100000, '2023-08-06', 'Belum Lunas', ''),
(83, 86, '05002', 'Tanda Jadi', '', 50000, 0, 0, '2023-08-06', 'Belum Bayar', ''),
(84, 86, '03001', 'sewa_kendaraan', '', 500000, 0, 0, '2023-08-06', 'Belum Bayar', ''),
(85, 86, '01001', 'sewa_kamar', 'Tagihan Sewa Kamar Juli 2023', 1600000, 1600000, 0, '2023-07-06', 'Lunas', ''),
(86, 86, '04001', 'sewa_lainnya', '', 250000, 0, 0, '2023-08-06', 'Belum Bayar', '');

-- --------------------------------------------------------

--
-- Table structure for table `tamu`
--

CREATE TABLE `tamu` (
  `id_tamu` int(11) NOT NULL,
  `id_penghuni` int(11) DEFAULT NULL,
  `id_tagihan` int(11) DEFAULT NULL,
  `tanggal_tamu` datetime DEFAULT NULL,
  `nama_tamu` varchar(255) DEFAULT NULL,
  `identitas_tamu` varchar(255) DEFAULT NULL,
  `waktu_menginap_tamu` int(255) DEFAULT NULL,
  `tarif_tamu` int(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tamu`
--

INSERT INTO `tamu` (`id_tamu`, `id_penghuni`, `id_tagihan`, `tanggal_tamu`, `nama_tamu`, `identitas_tamu`, `waktu_menginap_tamu`, `tarif_tamu`) VALUES
(8, 86, 82, '2023-08-06 01:15:00', 'Spiva', 'imageupload/1691750723.jpeg', 10, 25000);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id_users` int(11) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `kontak` varchar(20) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `alamat` varchar(255) DEFAULT NULL,
  `tipe` enum('pemilik','penghuni','staff') NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_token_expiry` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_users`, `nama`, `kontak`, `email`, `alamat`, `tipe`, `username`, `password`, `reset_token`, `reset_token_expiry`) VALUES
(1, 'admin', '123', 'admin@gmail.com', 'Yogyakarta', 'pemilik', 'admin', '$2y$10$naopT4h0AVIFAQkneHQhWe0ShC2fz45hKJLzWPBgCSCWndccR9G.G', NULL, NULL),
(2, 'penjaga', '456', 'penjaga@gmail.com', 'Yogyakarta', 'staff', 'penjaga', '$2y$10$QTvRV2Q2SsZiWK3hIyJKmOpWHxtgc0LJXwLtkOyi6Dzes571svB56', NULL, NULL),
(130, 'Muhammad Arigie Rizky Fadillah', '083891733783', 'agiwwo@gmail.com', 'Jl. Sungai Andai Komp. Kesehatan Blok A No. 10', 'penghuni', 'arigie', '$2y$10$YpwpNG1uG6yuC339s81tauU1P5u9jW8IzC8gYJDE7P16s9DHqIlkG', NULL, NULL),
(131, 'Spiva', '111', '', 'Spiva Regency', 'penghuni', 'spiva', '$2y$10$1l2I28GOkcst5w3Z.0Fq/.pRfbUkv7V.MHqqFjhtMEFZ11dI6L3qq', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `check_out`
--
ALTER TABLE `check_out`
  ADD PRIMARY KEY (`id_checkout`),
  ADD KEY `id_penghuni` (`id_penghuni`),
  ADD KEY `id_kamar` (`id_kamar`);

--
-- Indexes for table `kamar`
--
ALTER TABLE `kamar`
  ADD PRIMARY KEY (`id_kamar`),
  ADD KEY `id_lokasi` (`id_lokasi`);

--
-- Indexes for table `keluhan`
--
ALTER TABLE `keluhan`
  ADD PRIMARY KEY (`id_keluhan`),
  ADD KEY `id_penghuni` (`id_penghuni`);

--
-- Indexes for table `kendaraan`
--
ALTER TABLE `kendaraan`
  ADD PRIMARY KEY (`id_kendaraan`),
  ADD KEY `id_penghuni` (`id_penghuni`);

--
-- Indexes for table `lokasi_kamar`
--
ALTER TABLE `lokasi_kamar`
  ADD PRIMARY KEY (`id_lokasi`);

--
-- Indexes for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD PRIMARY KEY (`id_pembayaran`),
  ADD KEY `id_penghuni` (`id_penghuni`);

--
-- Indexes for table `pembayaran_tagihan`
--
ALTER TABLE `pembayaran_tagihan`
  ADD PRIMARY KEY (`id_pembayaran_tagihan`),
  ADD KEY `id_pembayaran` (`id_pembayaran`),
  ADD KEY `id_tagihan` (`id_tagihan`);

--
-- Indexes for table `pengeluaran`
--
ALTER TABLE `pengeluaran`
  ADD PRIMARY KEY (`id_pengeluaran`);

--
-- Indexes for table `penghuni`
--
ALTER TABLE `penghuni`
  ADD PRIMARY KEY (`id_penghuni`),
  ADD KEY `id_users` (`id_users`),
  ADD KEY `id_kamar` (`id_kamar`);

--
-- Indexes for table `penghuni_in_house`
--
ALTER TABLE `penghuni_in_house`
  ADD PRIMARY KEY (`id_pih`),
  ADD KEY `id_penghuni` (`id_penghuni`),
  ADD KEY `id_kamar` (`id_kamar`);

--
-- Indexes for table `pindah_kamar`
--
ALTER TABLE `pindah_kamar`
  ADD PRIMARY KEY (`id_pindah_kamar`),
  ADD KEY `id_penghuni` (`id_penghuni`),
  ADD KEY `kamar_sebelum` (`kamar_sebelum`),
  ADD KEY `kamar_sesudah` (`kamar_sesudah`);

--
-- Indexes for table `rekening`
--
ALTER TABLE `rekening`
  ADD PRIMARY KEY (`id_rekening`);

--
-- Indexes for table `tagihan`
--
ALTER TABLE `tagihan`
  ADD PRIMARY KEY (`id_tagihan`),
  ADD KEY `id_penghuni` (`id_penghuni`);

--
-- Indexes for table `tamu`
--
ALTER TABLE `tamu`
  ADD PRIMARY KEY (`id_tamu`),
  ADD KEY `id_penghuni` (`id_penghuni`),
  ADD KEY `fk_tamu_tagihan` (`id_tagihan`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_users`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `check_out`
--
ALTER TABLE `check_out`
  MODIFY `id_checkout` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `kamar`
--
ALTER TABLE `kamar`
  MODIFY `id_kamar` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `keluhan`
--
ALTER TABLE `keluhan`
  MODIFY `id_keluhan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `kendaraan`
--
ALTER TABLE `kendaraan`
  MODIFY `id_kendaraan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `lokasi_kamar`
--
ALTER TABLE `lokasi_kamar`
  MODIFY `id_lokasi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `pembayaran`
--
ALTER TABLE `pembayaran`
  MODIFY `id_pembayaran` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `pembayaran_tagihan`
--
ALTER TABLE `pembayaran_tagihan`
  MODIFY `id_pembayaran_tagihan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `pengeluaran`
--
ALTER TABLE `pengeluaran`
  MODIFY `id_pengeluaran` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `penghuni`
--
ALTER TABLE `penghuni`
  MODIFY `id_penghuni` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=88;

--
-- AUTO_INCREMENT for table `penghuni_in_house`
--
ALTER TABLE `penghuni_in_house`
  MODIFY `id_pih` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `pindah_kamar`
--
ALTER TABLE `pindah_kamar`
  MODIFY `id_pindah_kamar` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `rekening`
--
ALTER TABLE `rekening`
  MODIFY `id_rekening` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tagihan`
--
ALTER TABLE `tagihan`
  MODIFY `id_tagihan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=87;

--
-- AUTO_INCREMENT for table `tamu`
--
ALTER TABLE `tamu`
  MODIFY `id_tamu` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_users` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=132;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `check_out`
--
ALTER TABLE `check_out`
  ADD CONSTRAINT `check_out_ibfk_1` FOREIGN KEY (`id_penghuni`) REFERENCES `penghuni` (`id_penghuni`),
  ADD CONSTRAINT `check_out_ibfk_2` FOREIGN KEY (`id_kamar`) REFERENCES `kamar` (`id_kamar`);

--
-- Constraints for table `kamar`
--
ALTER TABLE `kamar`
  ADD CONSTRAINT `kamar_ibfk_1` FOREIGN KEY (`id_lokasi`) REFERENCES `lokasi_kamar` (`id_lokasi`) ON DELETE CASCADE;

--
-- Constraints for table `keluhan`
--
ALTER TABLE `keluhan`
  ADD CONSTRAINT `keluhan_ibfk_1` FOREIGN KEY (`id_penghuni`) REFERENCES `penghuni` (`id_penghuni`);

--
-- Constraints for table `kendaraan`
--
ALTER TABLE `kendaraan`
  ADD CONSTRAINT `kendaraan_ibfk_1` FOREIGN KEY (`id_penghuni`) REFERENCES `penghuni` (`id_penghuni`);

--
-- Constraints for table `penghuni`
--
ALTER TABLE `penghuni`
  ADD CONSTRAINT `penghuni_ibfk_1` FOREIGN KEY (`id_users`) REFERENCES `users` (`id_users`),
  ADD CONSTRAINT `penghuni_ibfk_2` FOREIGN KEY (`id_kamar`) REFERENCES `kamar` (`id_kamar`);

--
-- Constraints for table `penghuni_in_house`
--
ALTER TABLE `penghuni_in_house`
  ADD CONSTRAINT `penghuni_in_house_ibfk_1` FOREIGN KEY (`id_penghuni`) REFERENCES `penghuni` (`id_penghuni`),
  ADD CONSTRAINT `penghuni_in_house_ibfk_2` FOREIGN KEY (`id_kamar`) REFERENCES `kamar` (`id_kamar`);

--
-- Constraints for table `pindah_kamar`
--
ALTER TABLE `pindah_kamar`
  ADD CONSTRAINT `pindah_kamar_ibfk_1` FOREIGN KEY (`id_penghuni`) REFERENCES `penghuni` (`id_penghuni`),
  ADD CONSTRAINT `pindah_kamar_ibfk_2` FOREIGN KEY (`kamar_sebelum`) REFERENCES `kamar` (`id_kamar`),
  ADD CONSTRAINT `pindah_kamar_ibfk_3` FOREIGN KEY (`kamar_sesudah`) REFERENCES `kamar` (`id_kamar`);

--
-- Constraints for table `tamu`
--
ALTER TABLE `tamu`
  ADD CONSTRAINT `fk_tamu_tagihan` FOREIGN KEY (`id_tagihan`) REFERENCES `tagihan` (`id_tagihan`) ON DELETE CASCADE,
  ADD CONSTRAINT `tamu_ibfk_1` FOREIGN KEY (`id_penghuni`) REFERENCES `penghuni` (`id_penghuni`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
