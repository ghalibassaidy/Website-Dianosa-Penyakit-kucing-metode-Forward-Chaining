-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 25 Sep 2022 pada 03.17
-- Versi server: 10.4.22-MariaDB
-- Versi PHP: 8.1.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dempster_shafer`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `diagnosa`
--

CREATE TABLE `diagnosa` (
  `kode_diagnosa` varchar(11) NOT NULL,
  `user` int(11) NOT NULL,
  `penyakit` varchar(100) NOT NULL,
  `gejala` varchar(200) NOT NULL,
  `persentase` float NOT NULL,
  `tanggal_diagnosa` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `diagnosa`
--

INSERT INTO `diagnosa` (`kode_diagnosa`, `user`, `penyakit`, `gejala`, `persentase`, `tanggal_diagnosa`) VALUES
('D10D16', 10, 'P02,P04', 'G01,G03,G05', 0.4, '2022-09-21'),
('D10D17', 10, 'P04', 'G01,G03,G04,G05', 0.344, '2022-09-21'),
('D3D1', 3, 'P01,P03,P04', 'G02,G03', 0.4, '2022-07-31'),
('D3D2', 3, 'P02', 'G03,G04', 0.45, '2022-07-31'),
('D8D10', 8, 'P01', 'G01,G02,G05', 0.44, '2022-09-06'),
('D8D11', 8, 'P01,P02', 'G01,G02,G06', 0.288, '2022-09-06'),
('D8D12', 8, 'P01,P02', 'G01,G02,G06', 0.288, '2022-09-06'),
('D8D13', 8, 'P04', 'G01,G02,G04,G05', 0.06, '2022-09-06'),
('D8D14', 8, 'P04', 'G02,G04', 0.4, '2022-09-06'),
('D8D15', 8, 'P01,P02', 'G03,G05,G06', 0.06, '2022-09-07'),
('D8D18', 8, 'P04', 'G02,G04,G05', 0.2, '2022-09-24'),
('D8D19', 8, 'P01,P02,P03,P04,P05', 'G02,G04,G06,G16', 0.006, '2022-09-25'),
('D8D20', 8, 'P04', 'G04,G05,G06', 0.32, '2022-09-25'),
('D8D21', 8, 'P04', 'G03,G04,G05', 0.16, '2022-09-25'),
('D8D22', 8, 'P05', 'G01,G05,G07', 0.12, '2022-09-25'),
('D8D23', 8, 'P04', 'G03,G04,G05', 0.16, '2022-09-25'),
('D8D24', 8, 'P05', 'G03,G04,G07', 0.192, '2022-09-25'),
('D8D4', 8, 'P02', 'G02,G04', 0.75, '2022-09-03'),
('D8D5', 8, 'P02', 'G03,G04', 0.45, '2022-09-03'),
('D8D6', 8, 'P02', 'G03,G04', 0.45, '2022-09-03'),
('D8D7', 8, 'P01,P03', 'G03,G05', 0.53, '2022-09-03'),
('D8D8', 8, 'P02', 'G02,G03,G04', 0.36, '2022-09-06'),
('D8D9', 8, 'P02', 'G02,G03,G04', 0.36, '2022-09-06'),
('D9D3', 9, 'P03,P01', 'G01,G03,G05', 0.43778, '2022-09-03');

-- --------------------------------------------------------

--
-- Struktur dari tabel `gejala`
--

CREATE TABLE `gejala` (
  `kode_gejala` varchar(3) NOT NULL,
  `nama_gejala` varchar(255) NOT NULL,
  `bobot_gejala` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `gejala`
--

INSERT INTO `gejala` (`kode_gejala`, `nama_gejala`, `bobot_gejala`) VALUES
('G01', 'Demam suhu kucing diatas 39 derajat celcius. ', 0.7),
('G02', 'Infeksi kulit, dan mulut.', 0.5),
('G03', 'Mata berair', 0.8),
('G04', 'Diare kronis', 0.8),
('G05', 'Muntah', 0.5),
('G06', 'Pilek/ Flu', 0.6),
('G07', 'Sariawan dangusi memerah.', 0.8),
('G08', 'Hilang nafsu makan', 0.2),
('G09', 'Berat badan berkurang drastis', 0.6),
('G10', 'Lemah lesu', 0.4),
('G11', 'Batuk ditandai dengan masalah peradangan', 0.6),
('G12', 'Dehidrasi mata', 0.6),
('G13', 'Diare', 0.8),
('G14', 'Kejang - kejang', 0.6),
('G15', 'Melebarnya pupil', 0.4),
('G16', 'Bulu rontok adanya jamur, kurap, parasite, tungau,dan kutu', 0.6);

-- --------------------------------------------------------

--
-- Struktur dari tabel `penyakit`
--

CREATE TABLE `penyakit` (
  `kode_penyakit` varchar(3) NOT NULL,
  `nama_penyakit` varchar(255) NOT NULL,
  `keterangan_penyakit` mediumtext NOT NULL,
  `solusi_penyakit` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `penyakit`
--

INSERT INTO `penyakit` (`kode_penyakit`, `nama_penyakit`, `keterangan_penyakit`, `solusi_penyakit`) VALUES
('P01', 'Feline Immunodeficiency Virus (FIV)', 'Umumnya dikenal sebagai feline AIDS.yang.menyebabkan penurunan.sitem.imun pada kucing.', 'Pemberian vaksinasi, berikan vitamin untuk daya tahan tubuh kucing dengan memeberikan secara teratur selama 7-14 hari dengan pemberian 2 kali dalam satu hari, dapat mengunakan obat antibiotik yang di berikan selama 7-14 hari dengan 2 kali dalam satu hari pemebrian  lewat air minum atau suntikan, dapat diusahakan untuk memberikan makanan dan minuman secara teratur, pisahkan dari kucing lain, biarkan istirahat lebih banyak dengan memberikannya tempat tidur yang nyaman dan bersih, menjaga kebersihan kandang dan mengganti alas kandang'),
('P02', 'Feline Herpes (FVR)', 'Merupakan penyakit yang menyerang.saluran pernafasan.atau.paru-paru kucing.', 'Pemberian vaksinasi, berikan vitamin untuk daya tahan tubuh kucing dengan memeberikan secara teratur selama 7-14 hari dengan pemberian 2 kali dalam satu hari, dapat mengunakan obat antibiotik yang di berikan selama 7-14 hari dengan 2 kali dalam satu hari pemebrian  lewat air minum atau suntikan, dapat diusahakan untuk memberikan makanan dan minuman secara teratur, pisahkan dari kucing lain, biarkan istirahat lebih banyak dengan memberikannya tempat tidur yang nyaman dan bersih, menjaga kebersihan kandang dan mengganti alas kandang, bersikan bagian mata dan badan kucing setidaknya satu kali seminggu'),
('P03', 'Feline Leukemia Virus (FeLV)', 'Virus yang menginfeksi kucing dapat ditularkan dari kucingtang terinfeksi melalui air liur atau cairan hidung yang mengandung virus.', 'Pemberian vaksinasi, berikan vitamin untuk daya tahan tubuh kucing dengan memeberikan secara teratur selama 7-14 hari dengan pemberian 2 kali dalam satu hari, dapat mengunakan obat antibiotik yang di berikan selama 7-14 hari dengan 2 kali dalam satu hari pemebrian  lewat air minum atau suntikan, dapat diusahakan untuk memberikan makanan dan minuman secara teratur, pisahkan dari kucing lain, biarkan istirahat lebih banyak dengan memberikannya tempat tidur yang nyaman dan bersih, menjaga kebersihan kandang dan mengganti alas kandang'),
('P04', 'Feline Panleukopenia (FPV)', 'Virus yang menyerang saluran pernafasan kucing.', 'Pemberian vaksinasi, berikan vitamin untuk daya tahan tubuh kucing dengan memeberikan secara teratur selama 7-14 hari dengan pemberian 2 kali dalam satu hari, dapat mengunakan obat antibiotik yang di berikan selama 7-14 hari dengan 2 kali dalam satu hari pemebrian  lewat air minum atau suntikan, dapat diusahakan untuk memberikan makanan dan minuman secara teratur, pisahkan dari kucing lain, biarkan istirahat lebih banyak dengan memberikannya tempat tidur yang nyaman dan bersih, menjaga kebersihan kandang dan mengganti alas kandang'),
('P05', 'Feline Calicivirus  (FCV)', 'virus dari famili caliciviridae yang menyebabkan infeksi aliran pernafasan pada kucing.', 'Pemberian vaksinasi, berikan vitamin untuk daya tahan tubuh kucing dengan memeberikan secara teratur selama 7-14 hari dengan pemberian 2 kali dalam satu hari, dapat mengunakan obat antibiotik yang di berikan selama 7-14 hari dengan 2 kali dalam satu hari pemebrian  lewat air minum atau suntikan, dapat diusahakan untuk memberikan makanan dan minuman secara teratur, pisahkan dari kucing lain, biarkan istirahat lebih banyak dengan memberikannya tempat tidur yang nyaman dan bersih, menjaga kebersihan kandang dan mengganti alas kandang, dapat diberikan antiperadangan, bersikan bagian mata dan badan kucing setidaknya satu kali seminggu.');

-- --------------------------------------------------------

--
-- Struktur dari tabel `relasi_gejala`
--

CREATE TABLE `relasi_gejala` (
  `id` int(11) NOT NULL,
  `gejala` varchar(3) NOT NULL,
  `penyakit` varchar(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `relasi_gejala`
--

INSERT INTO `relasi_gejala` (`id`, `gejala`, `penyakit`) VALUES
(19, 'G01', 'P01'),
(20, 'G02', 'P01'),
(21, 'G05', 'P01'),
(22, 'G06', 'P01'),
(23, 'G08', 'P01'),
(24, 'G09', 'P01'),
(25, 'G10', 'P01'),
(26, 'G11', 'P01'),
(27, 'G12', 'P01'),
(28, 'G15', 'P01'),
(29, 'G16', 'P01'),
(30, 'G01', 'P02'),
(31, 'G14', 'P01'),
(32, 'G03', 'P02'),
(33, 'G06', 'P02'),
(34, 'G08', 'P02'),
(35, 'G09', 'P02'),
(36, 'G10', 'P02'),
(37, 'G12', 'P02'),
(38, 'G15', 'P02'),
(39, 'G16', 'P02'),
(40, 'G01', 'P03'),
(41, 'G02', 'P03'),
(42, 'G08', 'P03'),
(43, 'G09', 'P03'),
(44, 'G10', 'P03'),
(45, 'G11', 'P03'),
(46, 'G12', 'P03'),
(47, 'G15', 'P03'),
(48, 'G16', 'P03'),
(49, 'G01', 'P04'),
(50, 'G04', 'P04'),
(51, 'G05', 'P04'),
(52, 'G08', 'P04'),
(53, 'G09', 'P04'),
(54, 'G10', 'P04'),
(55, 'G12', 'P04'),
(56, 'G13', 'P04'),
(57, 'G14', 'P04'),
(58, 'G15', 'P04'),
(59, 'G16', 'P04'),
(60, 'G01', 'P05'),
(61, 'G03', 'P05'),
(62, 'G06', 'P05'),
(63, 'G07', 'P05'),
(64, 'G08', 'P05'),
(65, 'G09', 'P05'),
(66, 'G10', 'P05'),
(67, 'G11', 'P05'),
(68, 'G12', 'P05'),
(69, 'G15', 'P05'),
(70, 'G16', 'P05');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('pengguna','admin') NOT NULL,
  `created_at` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`user_id`, `nama`, `username`, `password`, `role`, `created_at`) VALUES
(2, 'admin', 'admin', '$2y$10$OhbS2fV0TFcAKzx0Iw/LZ.OiMKqGbFZfpNjh8pPAjAL3VGQgxf9uy', 'admin', '2022-04-15'),
(3, 'test user', 'test', '$2y$10$xrpRzCA0ASWJQapVcXkXae5fITmXEpsCWd4v5o3.saFv6//fGW04K', 'pengguna', '2022-04-15'),
(7, 'test 2', 'test2', '$2y$10$8YOLjl/IwoZYDCOy6DDzP.ws8dDyUMjleegW9Ki5z8Ky3rA27A4GW', 'pengguna', '2022-04-19'),
(8, 'budi', 'budi', '$2y$10$VwwWfRV1fNCOORvtlHICKu4MPZOWvxEWmS1dCuLvdikrFErl3/4fS', 'pengguna', '2022-09-03'),
(9, 'Figita', 'figita1', '$2y$10$VzkSxj.8VIP02av9/Yj.gObFwA.BlWn6lxlWKF.jCxfpmZJM4yGQW', 'pengguna', '2022-09-03'),
(10, 'Alfi', 'Aldi123', '$2y$10$ud39cWSSg36ssZE5dFazo./Vlyb8vOoCFJluR0X5wJoMBYHkevrB6', 'pengguna', '2022-09-21');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `diagnosa`
--
ALTER TABLE `diagnosa`
  ADD PRIMARY KEY (`kode_diagnosa`),
  ADD KEY `kode_user` (`user`),
  ADD KEY `penyakit` (`penyakit`);

--
-- Indeks untuk tabel `gejala`
--
ALTER TABLE `gejala`
  ADD PRIMARY KEY (`kode_gejala`);

--
-- Indeks untuk tabel `penyakit`
--
ALTER TABLE `penyakit`
  ADD PRIMARY KEY (`kode_penyakit`);

--
-- Indeks untuk tabel `relasi_gejala`
--
ALTER TABLE `relasi_gejala`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kode_gejala` (`gejala`),
  ADD KEY `kode_penyakit` (`penyakit`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `relasi_gejala`
--
ALTER TABLE `relasi_gejala`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `diagnosa`
--
ALTER TABLE `diagnosa`
  ADD CONSTRAINT `f_kode_user` FOREIGN KEY (`user`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `relasi_gejala`
--
ALTER TABLE `relasi_gejala`
  ADD CONSTRAINT `f_kode_gejala` FOREIGN KEY (`gejala`) REFERENCES `gejala` (`kode_gejala`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `f_kode_penyakit` FOREIGN KEY (`penyakit`) REFERENCES `penyakit` (`kode_penyakit`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
