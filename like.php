<?php require_once('Connections/konek.php'); ?>
<?php require_once('engine/boe-function.php'); ?>
<?php
// get values
$id_photo = sql($_GET['id']);

// ambil user dari sesion
  $q = mysql_query("SELECT * FROM `user` WHERE `username` = '".$_SESSION['MM_Username']."'") or die(mysql_error());
  $f = mysql_fetch_array($q);
  
  
// new like
mysql_query("INSERT INTO `like` (`id_user`, `id_photo`) VALUES ('".$f['id_user']."', '".$id_photo."')");


?>
  
  <?php 

  // ambil user dari id photo
  $qq = mysql_query("SELECT * FROM `photo` WHERE `id_photo` = '".$id_photo."'");
  $ff = mysql_fetch_array($qq);
  //tambah point\
  mysql_query("UPDATE `user` SET `point`= `point`+1 WHERE `id_user` = '".$ff['id_user']."'") or die(mysql_error());
  ?>
  
 <?php
 //notifikasi
 // ambil user dari sesion
  $q = mysql_query("SELECT * FROM `user` WHERE `username` = '".$_SESSION['MM_Username']."'") or die(mysql_error());
  $f = mysql_fetch_array($q);
 
 $id_awal = $f['id_user'];
 $tgl = date("Y-m-d");
 $id=$_GET['id'];
 $q = mysql_query("SELECT* FROM `photo` WHERE `id_photo`=$id_photo");
 $f = mysql_fetch_array($q);
 $id_akhir=$f['id_user'];
  
// masukan notifikasi
mysql_query("INSERT INTO `notifikasi` (`judul`,`date`, `status`,`isi`,`id_awal`,`id_akhir`,`id`) VALUES ('like','$tgl'
,'unread','memberi ice foto anda','$id_awal','$id_akhir','$id_photo')") or die(mysql_error());
 ?> 
<?php

  $updateGoTo = "timeline.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
?>
