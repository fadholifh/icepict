DROP TABLE IF EXISTS `admin`;

CREATE TABLE `admin` (
  `id_admin` int(8) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  PRIMARY KEY (`id_admin`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

INSERT INTO `admin` VALUES('1','alpin','alpin');



DROP TABLE IF EXISTS `download`;

CREATE TABLE `download` (
  `id_download` int(50) NOT NULL AUTO_INCREMENT,
  `photo_id` int(50) NOT NULL,
  `status` varchar(10) NOT NULL,
  `dari` int(50) NOT NULL,
  PRIMARY KEY (`id_download`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;




DROP TABLE IF EXISTS `komentar`;

CREATE TABLE `komentar` (
  `id_komentar` int(50) NOT NULL AUTO_INCREMENT,
  `id_photo` int(50) NOT NULL,
  `komentar` text NOT NULL,
  `tanggal_komentar` date NOT NULL,
  `id_user` int(50) NOT NULL,
  PRIMARY KEY (`id_komentar`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=latin1;

INSERT INTO `komentar` VALUES('28','66','film !!!','2015-01-23','28');
INSERT INTO `komentar` VALUES('29','66','good !!','2015-01-23','29');
INSERT INTO `komentar` VALUES('30','69','keren !!','2015-01-23','28');



DROP TABLE IF EXISTS `like`;

CREATE TABLE `like` (
  `id_like` int(8) NOT NULL AUTO_INCREMENT,
  `id_user` int(8) NOT NULL,
  `id_photo` int(8) NOT NULL,
  PRIMARY KEY (`id_like`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=latin1;




DROP TABLE IF EXISTS `notifikasi`;

CREATE TABLE `notifikasi` (
  `id_notif` int(6) NOT NULL AUTO_INCREMENT,
  `judul` varchar(32) NOT NULL,
  `date` date NOT NULL,
  `status` varchar(32) NOT NULL,
  `isi` text NOT NULL,
  `id_awal` int(10) NOT NULL,
  `id_akhir` int(10) NOT NULL,
  `id` int(10) DEFAULT NULL,
  PRIMARY KEY (`id_notif`)
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=latin1;

INSERT INTO `notifikasi` VALUES('36','like','2015-01-23','read','memberi ice foto anda','28','28','67');
INSERT INTO `notifikasi` VALUES('37','komentar','2015-01-23','read','mengomentari foto anda','28','28','66');
INSERT INTO `notifikasi` VALUES('38','follow','2015-01-23','unread','mengikuti anda','29','0','28');
INSERT INTO `notifikasi` VALUES('39','like','2015-01-23','read','memberi ice foto anda','29','28','66');
INSERT INTO `notifikasi` VALUES('40','komentar','2015-01-23','read','mengomentari foto anda','29','28','66');
INSERT INTO `notifikasi` VALUES('41','follow','2015-01-23','unread','mengikuti anda','28','0','29');
INSERT INTO `notifikasi` VALUES('42','follow','2015-01-23','unread','mengikuti anda','28','0','0');
INSERT INTO `notifikasi` VALUES('43','follow','2015-01-23','read','mengikuti anda','28','29','0');
INSERT INTO `notifikasi` VALUES('44','komentar','2015-01-23','read','mengomentari foto anda','28','29','69');



DROP TABLE IF EXISTS `photo`;

CREATE TABLE `photo` (
  `id_photo` int(50) NOT NULL AUTO_INCREMENT,
  `url` varchar(100) NOT NULL,
  `tanggal` date NOT NULL,
  `id_user` int(50) NOT NULL,
  `caption` text,
  `filter` varchar(50) NOT NULL DEFAULT 'c',
  PRIMARY KEY (`id_photo`)
) ENGINE=InnoDB AUTO_INCREMENT=70 DEFAULT CHARSET=latin1;

INSERT INTO `photo` VALUES('66','95844_MG_0661_1.jpg','2015-01-23','28','mari menonton','c');
INSERT INTO `photo` VALUES('67','42483IMG_20141222_065623.jpg','2015-01-23','28','together','a');
INSERT INTO `photo` VALUES('68','44409IMG_20141222_151127_1.jpg','2015-01-23','28','negate','b');
INSERT INTO `photo` VALUES('69','88305IMG_20141014_154418.jpg','2015-01-23','29','win !!','a');



DROP TABLE IF EXISTS `point`;

CREATE TABLE `point` (
  `id_point` int(50) NOT NULL AUTO_INCREMENT,
  `predikat` varchar(50) NOT NULL,
  `dari` varchar(32) NOT NULL,
  `ke` varchar(32) NOT NULL,
  PRIMARY KEY (`id_point`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

INSERT INTO `point` VALUES('1','Es Lilin','0','50');
INSERT INTO `point` VALUES('2','Es Dung Dung','51','300');
INSERT INTO `point` VALUES('5','Es Cincau','301','1000');
INSERT INTO `point` VALUES('6','Es Teler','1001','3000');
INSERT INTO `point` VALUES('8','Es Dawet Ayu','3001','10000000');



DROP TABLE IF EXISTS `relationship`;

CREATE TABLE `relationship` (
  `id_rel` int(50) NOT NULL AUTO_INCREMENT,
  `id_user_awal` int(50) NOT NULL,
  `id_user_akhir` int(50) NOT NULL,
  PRIMARY KEY (`id_rel`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=latin1;

INSERT INTO `relationship` VALUES('21','29','28');
INSERT INTO `relationship` VALUES('24','28','29');



DROP TABLE IF EXISTS `user`;

CREATE TABLE `user` (
  `id_user` int(50) NOT NULL AUTO_INCREMENT,
  `email` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `username` varchar(32) NOT NULL,
  `fullname` varchar(50) NOT NULL,
  `bio` text NOT NULL,
  `profilpic` varchar(50) NOT NULL DEFAULT 'default.jpg',
  `point` int(8) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_user`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=latin1;

INSERT INTO `user` VALUES('28','hafidalpin.al@gmail.com','f2621da6b3d4f712bf5e29861f186c7c','hafidalpin','hafid alpin al gazni','icepict founder','IMG_0236_1.jpg','1000');
INSERT INTO `user` VALUES('29','hamas182@gmail.com','359294456580c6702a35376d8b3c70b4','khakimassidiqi','Khakim Assidiqi','master !!','IMG_20141103_215126.jpg','0');



