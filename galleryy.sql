-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 25, 2025 at 09:24 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `galleryy`
--

-- --------------------------------------------------------

--
-- Table structure for table `gallery_album`
--

CREATE TABLE `gallery_album` (
  `AlbumID` int(11) NOT NULL,
  `NamaAlbum` varchar(255) NOT NULL,
  `Deskripsi` text DEFAULT NULL,
  `TanggalDibuat` date NOT NULL,
  `UserID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `gallery_album`
--

INSERT INTO `gallery_album` (`AlbumID`, `NamaAlbum`, `Deskripsi`, `TanggalDibuat`, `UserID`) VALUES
(1, 'Album Gunung', 'foto gunung', '2025-01-01', 1),
(2, 'Album Pantai', 'Foto-foto Pantai ', '2025-02-14', 2),
(3, 'Album Langit', 'Foto Langit', '2025-03-10', 3),
(5, 'Album Laut', 'Foto Laut', '2025-03-10', 4);

-- --------------------------------------------------------

--
-- Table structure for table `gallery_foto`
--

CREATE TABLE `gallery_foto` (
  `FotoID` int(11) NOT NULL,
  `JudulFoto` varchar(255) DEFAULT NULL,
  `DeskripsiFoto` text DEFAULT NULL,
  `TanggalUnggah` date NOT NULL,
  `LokasiFile` varchar(255) NOT NULL,
  `AlbumID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `gallery_foto`
--

INSERT INTO `gallery_foto` (`FotoID`, `JudulFoto`, `DeskripsiFoto`, `TanggalUnggah`, `LokasiFile`, `AlbumID`) VALUES
(8, 'gunung', '\"Gunung mengajarkan bahwa tak ada batasan untuk bermimpi setinggi mungkin. Biru langit siang memberi ketenangan, dan gemerlap bintang malam memberi harapan.\"', '2025-02-03', 'assets/post/gunung 2.jpg', 3),
(11, 'Langit gelap', '\"Langit gelap bukanlah tanda bahwa dunia telah kehilangan cahaya, tetapi hanya menunggu bintang-bintang untuk bersinar.\"', '2025-02-10', 'assets/post/gunung5.jpg', 3),
(18, 'Gunung', 'Sangat Idah', '2025-02-10', 'assets/post/gunung4.jpg', 2),
(27, 'Gunung', 'Gunung adalah tempat yang tepat untuk mencari petualangan dan pengalaman baru.', '2025-02-19', 'assets/post/gunung1.jpeg', 1),
(43, 'gunung rinjani', 'pemandangan alam', '2025-02-24', 'assets/post/gunungg.jpg', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `gallery_komentarfoto`
--

CREATE TABLE `gallery_komentarfoto` (
  `KomentarID` int(11) NOT NULL,
  `FotoID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `IsiKomentar` text NOT NULL,
  `TanggalKomentar` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `gallery_komentarfoto`
--

INSERT INTO `gallery_komentarfoto` (`KomentarID`, `FotoID`, `UserID`, `IsiKomentar`, `TanggalKomentar`) VALUES
(9, 11, 3, 'bagus dan indah sekali', '2025-02-12');

-- --------------------------------------------------------

--
-- Table structure for table `gallery_likefoto`
--

CREATE TABLE `gallery_likefoto` (
  `LikeID` int(11) NOT NULL,
  `FotoID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `TanggalLike` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gallery_user`
--

CREATE TABLE `gallery_user` (
  `UserID` int(11) NOT NULL,
  `Username` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `NamaLengkap` varchar(255) DEFAULT NULL,
  `Alamat` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `gallery_user`
--

INSERT INTO `gallery_user` (`UserID`, `Username`, `Password`, `Email`, `NamaLengkap`, `Alamat`) VALUES
(1, 'Sani', 'password123', 'sani@example.com', 'Sani Dwi', 'Jl. Contoh No. 123, Jakarta'),
(2, 'Tini', 'password456', 'tini@example.com', 'Tini Maharani', 'Jl. Contoh No. 456, Jakarta'),
(3, 'Sintya', 'password789', 'sin11@example.com', 'Sintya Prasiska', 'Jl. Alam Raya No. 789, Jakarta'),
(4, 'Khasanah', 'password33', 'khasanah2@example.com', 'Khasanah Dewi', 'Jl.Nanas No. 02. semarang'),
(5, 'selvi', '12345678', 'selvi2@gmail.com', 'selviii', 'mambe'),
(8, 'admin', '123', 'admin@gmail.com', 'administrasi', 'sentono'),
(9, 'sasa', '$2y$10$2dMWxj5hi9xsc/75mwnHAuRMPMRWAPpM6zNxLDAzeM7ub3DMOxHqG', 'sisi@gmail.com', 'sdas', 'pws');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `gallery_album`
--
ALTER TABLE `gallery_album`
  ADD PRIMARY KEY (`AlbumID`),
  ADD KEY `UserID` (`UserID`);

--
-- Indexes for table `gallery_foto`
--
ALTER TABLE `gallery_foto`
  ADD PRIMARY KEY (`FotoID`),
  ADD KEY `AlbumID` (`AlbumID`);

--
-- Indexes for table `gallery_komentarfoto`
--
ALTER TABLE `gallery_komentarfoto`
  ADD PRIMARY KEY (`KomentarID`),
  ADD KEY `FotoID` (`FotoID`),
  ADD KEY `UserID` (`UserID`);

--
-- Indexes for table `gallery_likefoto`
--
ALTER TABLE `gallery_likefoto`
  ADD PRIMARY KEY (`LikeID`),
  ADD KEY `FotoID` (`FotoID`),
  ADD KEY `UserID` (`UserID`);

--
-- Indexes for table `gallery_user`
--
ALTER TABLE `gallery_user`
  ADD PRIMARY KEY (`UserID`),
  ADD UNIQUE KEY `Username` (`Username`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `gallery_album`
--
ALTER TABLE `gallery_album`
  MODIFY `AlbumID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `gallery_foto`
--
ALTER TABLE `gallery_foto`
  MODIFY `FotoID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `gallery_komentarfoto`
--
ALTER TABLE `gallery_komentarfoto`
  MODIFY `KomentarID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `gallery_likefoto`
--
ALTER TABLE `gallery_likefoto`
  MODIFY `LikeID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `gallery_user`
--
ALTER TABLE `gallery_user`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `gallery_album`
--
ALTER TABLE `gallery_album`
  ADD CONSTRAINT `gallery_album_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `gallery_user` (`UserID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `gallery_foto`
--
ALTER TABLE `gallery_foto`
  ADD CONSTRAINT `gallery_foto_ibfk_1` FOREIGN KEY (`AlbumID`) REFERENCES `gallery_album` (`AlbumID`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `gallery_komentarfoto`
--
ALTER TABLE `gallery_komentarfoto`
  ADD CONSTRAINT `gallery_komentarfoto_ibfk_1` FOREIGN KEY (`FotoID`) REFERENCES `gallery_foto` (`FotoID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `gallery_komentarfoto_ibfk_2` FOREIGN KEY (`UserID`) REFERENCES `gallery_user` (`UserID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `gallery_likefoto`
--
ALTER TABLE `gallery_likefoto`
  ADD CONSTRAINT `gallery_likefoto_ibfk_1` FOREIGN KEY (`FotoID`) REFERENCES `gallery_foto` (`FotoID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `gallery_likefoto_ibfk_2` FOREIGN KEY (`UserID`) REFERENCES `gallery_user` (`UserID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
