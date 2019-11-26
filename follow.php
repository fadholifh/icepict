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

$MM_restrictGoTo = "login.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($_SERVER['QUERY_STRING']) && strlen($_SERVER['QUERY_STRING']) > 0) 
  $MM_referrer .= "?" . $_SERVER['QUERY_STRING'];
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}
?>
<?php
// user yg akan follow
$taget_id = sql($_GET['id']);
//ambil user saya

$q = mysql_query("SELECT * FROM user WHERE username = '".$_SESSION['MM_Username']."';");
$user_saya = mysql_fetch_array($q);
$user_saya_id = $user_saya['id_user'];

// tambah ke relation
mysql_query("INSERT INTO `relationship` (`id_user_awal`, `id_user_akhir`) VALUES ('$user_saya_id', '$taget_id');") or die(mysql_error());
//notifikasi
 // ambil user dari sesion
  $q = mysql_query("SELECT * FROM `user` WHERE `username` = '".$_SESSION['MM_Username']."'") or die(mysql_error());
  $f = mysql_fetch_array($q);
 
 $id_awal = $f['id_user'];
 $tgl = date("Y-m-d");
 $id_akhir=sql($_GET['id']);
 
// jika id saya unfollow id saya, wtf?
if($id_awal == $id_akhir){
	header('location: 404.php');
	exit;
}

// masukan notifikasi
mysql_query("INSERT INTO `notifikasi` (`judul`,`date`, `status`,`isi`,`id_awal`,`id_akhir`,`id`) VALUES ('follow','$tgl'
,'unread','mengikuti anda',$id_awal,$id_akhir,0)") or die(mysql_error());
  ?>

  
<?php
	// ambil target username 
	$q_target_user = mysql_query("SELECT * FROM `user` WHERE `id_user` = $taget_id") or die(mysql_error());
	$f_target_user = mysql_fetch_array($q_target_user);
	$target_username = $f_target_user['username'];
	
	// redirect
	header("location: $target_username?ok=followed");
?>
