<?php require_once('Connections/konek.php'); ?>
<?php require_once('engine/boe-function.php'); ?>
<?php

if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "";
$MM_donotCheckaccess = "true";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && true) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "index.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($_SERVER['QUERY_STRING']) && strlen($_SERVER['QUERY_STRING']) > 0) 
  $MM_referrer .= "?" . $_SERVER['QUERY_STRING'];
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  //header("Location: ". $MM_restrictGoTo); 
  exit;
}

if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

$colname_download = "-1";
if (isset($_GET['id'])) {
  $colname_download = $_GET['id'];
}
mysql_select_db($database_konek, $konek);
$query_download = sprintf("SELECT * FROM photo WHERE id_photo = %s", GetSQLValueString($colname_download, "int"));
$download = mysql_query($query_download, $konek) or die(mysql_error());
$row_download = mysql_fetch_assoc($download);
$totalRows_download = mysql_num_rows($download);
?>
<?php
$id_photo = sql($_GET['id']);


// ambnil jumlah point
$qp = mysql_query("SELECT * FROM user WHERE username = '".$_SESSION['MM_Username']."'");
$user_ku = mysql_fetch_array($qp);
$id_user_ku = $user_ku['id_user'];
$point_ku = $user_ku['point'];

if($point_ku < 10){

	echo "<script> alert('point tidak cukup!');window.location='timeline.php' </script>";
	exit;	
}else{
  // ambil user dari id photo
  $qq = mysql_query("SELECT * FROM `photo` WHERE `id_photo` = '".$id_photo."'");
  $ff = mysql_fetch_array($qq);
  
    //klurang point\
  mysql_query("UPDATE `user` SET `point`= `point`-10 WHERE `id_user` = '$id_user_ku'") or die(mysql_error());
  
  //tambah point\
  mysql_query("UPDATE `user` SET `point`= `point`+10 WHERE `id_user` = '".$ff['id_user']."'") or die(mysql_error());

  
  //download
 header("Content-disposition: attachment; filename=IcePict-" . md5(date('Ymdhis')) . ".jpeg");
  header("Content-type: image/jpeg");
  include("content/photo/".$ff['url']);
 // ambil user dari sesion
  $q = mysql_query("SELECT * FROM `user` WHERE `username` = '".$_SESSION['MM_Username']."'") or die(mysql_error());
  $f = mysql_fetch_array($q);
  // new download
mysql_query("INSERT INTO `download` (`id_download`, `photo_id`, `status`, `dari`) VALUES ('','".$colname_download."', 'allow', '".$id_user_ku."')");

  

  //header("location: content/photo/".$ff['url']);
}

 //notifikasi
 // ambil user dari sesion
  $q = mysql_query("SELECT * FROM `user` WHERE `username` = '".$_SESSION['MM_Username']."'") or die(mysql_error());
  $f = mysql_fetch_array($q);
 
 $id_awal = $f['id_user'];
 $tgl = date("Y-m-d");
 $id=$_GET['id'];
 $q = mysql_query("SELECT* FROM `photo` WHERE `id_photo`=$id");
 $f = mysql_fetch_array($q);
 $id_akhir=$f['id_user'];
  
// masukan notifikasi
mysql_query("INSERT INTO `notifikasi` (`judul`,`date`, `status`,`isi`,`id_awal`,`id_akhir`,`id`) VALUES ('download','$tgl'
,'unread','mendownload foto anda','$id_awal','$id_akhir','$id')") or die(mysql_error());
 ?> 
<?php
mysql_free_result($download);
?>