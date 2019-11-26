-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Feb 21, 2015 at 08:45 AM
-- Server version: 5.5.27
-- PHP Version: 5.4.7

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `social_photo`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE IF NOT EXISTS `admin` (
  `id_admin` int(8) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  PRIMARY KEY (`id_admin`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id_admin`, `username`, `password`) VALUES
(1, 'alpin', 'alpin');

-- --------------------------------------------------------

--
-- Table structure for table `download`
--

CREATE TABLE IF NOT EXISTS `download` (
  `id_download` int(50) NOT NULL AUTO_INCREMENT,
  `photo_id` int(50) NOT NULL,
  `status` varchar(10) NOT NULL,
  `dari` int(50) NOT NULL,
  PRIMARY KEY (`id_download`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `komentar`
--

CREATE TABLE IF NOT EXISTS `komentar` (
  `id_komentar` int(50) NOT NULL AUTO_INCREMENT,
  `id_photo` int(50) NOT NULL,
  `komentar` text NOT NULL,
  `tanggal_komentar` date NOT NULL,
  `id_user` int(50) NOT NULL,
  PRIMARY KEY (`id_komentar`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=38 ;

--
-- Dumping data for table `komentar`
--

INSERT INTO `komentar` (`id_komentar`, `id_photo`, `komentar`, `tanggal_komentar`, `id_user`) VALUES
(28, 66, 'film !!!', '2015-01-23', 28),
(29, 66, 'good !!', '2015-01-23', 29),
(30, 69, 'keren !!', '2015-01-23', 28),
(31, 70, 'test', '2015-01-23', 30),
(35, 92, 'as', '2015-02-19', 29),
(37, 92, 'test', '2015-02-20', 28);

-- --------------------------------------------------------

--
-- Table structure for table `like`
--

CREATE TABLE IF NOT EXISTS `like` (
  `id_like` int(8) NOT NULL AUTO_INCREMENT,
  `id_user` int(8) NOT NULL,
  `id_photo` int(8) NOT NULL,
  PRIMARY KEY (`id_like`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=40 ;

--
-- Dumping data for table `like`
--

INSERT INTO `like` (`id_like`, `id_user`, `id_photo`) VALUES
(34, 30, 70),
(35, 30, 67),
(36, 30, 66),
(37, 30, 69),
(38, 34, 71),
(39, 29, 92);

-- --------------------------------------------------------

--
-- Table structure for table `notifikasi`
--

CREATE TABLE IF NOT EXISTS `notifikasi` (
  `id_notif` int(6) NOT NULL AUTO_INCREMENT,
  `judul` varchar(32) NOT NULL,
  `date` date NOT NULL,
  `status` varchar(32) NOT NULL,
  `isi` text NOT NULL,
  `id_awal` int(10) NOT NULL,
  `id_akhir` int(10) NOT NULL,
  `id` int(10) DEFAULT NULL,
  PRIMARY KEY (`id_notif`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=88 ;

--
-- Dumping data for table `notifikasi`
--

INSERT INTO `notifikasi` (`id_notif`, `judul`, `date`, `status`, `isi`, `id_awal`, `id_akhir`, `id`) VALUES
(80, 'komentar', '2015-02-20', 'read', 'mengomentari foto anda', 29, 29, 92),
(81, 'komentar', '2015-02-20', 'read', 'mengomentari foto anda', 28, 29, 92),
(82, 'follow', '2015-02-20', 'unread', 'mengikuti anda', 29, 28, 0),
(83, 'like', '2015-02-20', 'read', 'memberi ice foto anda', 29, 29, 92),
(84, 'like', '2015-02-20', 'read', 'memberi ice foto anda', 29, 29, 92),
(85, 'komentar', '2015-02-20', 'read', 'mengomentari foto anda', 29, 29, 92),
(86, 'komentar', '2015-02-20', 'read', 'mengomentari foto anda', 29, 29, 92),
(87, 'like', '2015-02-20', 'read', 'memberi ice foto anda', 29, 29, 92);

-- --------------------------------------------------------

--
-- Table structure for table `photo`
--

CREATE TABLE IF NOT EXISTS `photo` (
  `id_photo` int(50) NOT NULL AUTO_INCREMENT,
  `url` varchar(100) NOT NULL,
  `tanggal` date NOT NULL,
  `id_user` int(50) NOT NULL,
  `caption` text,
  `filter` varchar(50) NOT NULL DEFAULT 'c',
  PRIMARY KEY (`id_photo`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=99 ;

--
-- Dumping data for table `photo`
--

INSERT INTO `photo` (`id_photo`, `url`, `tanggal`, `id_user`, `caption`, `filter`) VALUES
(66, '95844_MG_0661_1.jpg', '2015-01-23', 28, 'mari menonton', 'c'),
(67, '42483IMG_20141222_065623.jpg', '2015-01-23', 28, 'together', 'a'),
(68, '44409IMG_20141222_151127_1.jpg', '2015-01-23', 28, 'negate', 'b'),
(70, '31966IMG_3571.jpg', '2015-01-23', 30, 'asdasd', 'a'),
(71, '40955575311_394434923930710_2079734226_n.jpg', '2015-02-18', 34, NULL, 'c'),
(91, '72732C360_2014-08-31-12-12-11-129.jpg', '2015-02-19', 42, NULL, 'c'),
(95, '80569IMG_2358.JPG', '2015-02-20', 29, 'none\r\n', 'c'),
(96, '32321IMG_2358.JPG', '2015-02-20', 29, 'alpha', 'alpha'),
(97, '52433IMG_2358.JPG', '2015-02-20', 29, 'negate', 'b'),
(98, '28057IMG_2358.JPG', '2015-02-20', 29, 'gray', 'a');

-- --------------------------------------------------------

--
-- Table structure for table `point`
--

CREATE TABLE IF NOT EXISTS `point` (
  `id_point` int(50) NOT NULL AUTO_INCREMENT,
  `predikat` varchar(50) NOT NULL,
  `dari` varchar(32) NOT NULL,
  `ke` varchar(32) NOT NULL,
  PRIMARY KEY (`id_point`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `point`
--

INSERT INTO `point` (`id_point`, `predikat`, `dari`, `ke`) VALUES
(1, 'Es Lilin', '0', '50'),
(2, 'Es Dung Dung', '51', '300'),
(5, 'Es Cincau', '301', '1000'),
(6, 'Es Teler', '1001', '3000'),
(8, 'Es Dawet Ayu', '3001', '10000000');

-- --------------------------------------------------------

--
-- Table structure for table `relationship`
--

CREATE TABLE IF NOT EXISTS `relationship` (
  `id_rel` int(50) NOT NULL AUTO_INCREMENT,
  `id_user_awal` int(50) NOT NULL,
  `id_user_akhir` int(50) NOT NULL,
  PRIMARY KEY (`id_rel`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id_user` int(50) NOT NULL AUTO_INCREMENT,
  `email` varchar(50) NOT NULL,
  `email_status` varchar(10) NOT NULL DEFAULT 'unverified',
  `password` varchar(50) NOT NULL,
  `username` varchar(32) NOT NULL,
  `fullname` varchar(50) NOT NULL,
  `bio` text NOT NULL,
  `profilpic` varchar(50) NOT NULL DEFAULT 'default.jpg',
  `reg_date` datetime NOT NULL,
  `ver_code` varchar(40) DEFAULT NULL,
  `forgot_key` varchar(40) DEFAULT NULL,
  `setting` varchar(1000) NOT NULL DEFAULT '{"private_account":true, "premium_account":false, "official_account":false}',
  `status` varchar(10) NOT NULL,
  `point` int(8) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_user`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=43 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id_user`, `email`, `email_status`, `password`, `username`, `fullname`, `bio`, `profilpic`, `reg_date`, `ver_code`, `forgot_key`, `setting`, `status`, `point`) VALUES
(28, 'hafidalpin.al@gmail.com', 'unverified', 'f2621da6b3d4f712bf5e29861f186c7c', 'hafidalpin', 'hafid alpin al gazni', 'icepict founder', 'IMG_0236_1.jpg', '0000-00-00 00:00:00', NULL, NULL, '{"private_account":true, "premium_account":false, "official_account":false}', 'verified', 1002),
(29, 'hamas182@gmail.com', 'verified', '359294456580c6702a35376d8b3c70b4', 'khakimassidiqi', 'KHAKIM ASSIDIQI', '18yo!', 'pp_6ea9ab1baa0efb9e19094440c317e21b.jpg', '0000-00-00 00:00:00', NULL, NULL, '{"private_account":true, "premium_account":false, "official_account":false}', 'verified', 2),
(42, 'root@asdasd.asd', 'verified', '359294456580c6702a35376d8b3c70b4', 'sdadasdas', 'FRANKLIN BJ.', 'E=mc2', 'pp_a1d0c6e83f027327d8461063f4ac58a6.jpg', '2015-02-19 11:06:01', NULL, NULL, '{"private_account":true,"premium_account":false,"official_account":false}', 'verified', 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
