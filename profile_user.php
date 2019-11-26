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
  header("Location: ". $MM_restrictGoTo); 
  exit;
}
?>
<?php
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

if(isset($_GET['id'])){
	// old version use id
	$colname_user = "-1";
	if (isset($_GET['id'])) {
	  $colname_user = sql($_GET['id']);
	}
	mysql_select_db($database_konek, $konek);
	$query_user = sprintf("SELECT * FROM `user` WHERE id_user = %s", GetSQLValueString($colname_user, "int"));
	$user = mysql_query($query_user, $konek) or die(mysql_error());
	$row_user = mysql_fetch_assoc($user);
	$totalRows_user = mysql_num_rows($user);
}else{
	// new version use username
	$colname_user = "-1";
	if (isset($_GET['username'])) {
	  $colname_user = sql($_GET['username']);
	}
	mysql_select_db($database_konek, $konek);
	$query_user = sprintf("SELECT * FROM `user` WHERE username = %s", GetSQLValueString($colname_user, "text"));
	$user = mysql_query($query_user, $konek) or die(mysql_error());
	$row_user = mysql_fetch_assoc($user);
	$totalRows_user = mysql_num_rows($user);
}
// if user not found
if($totalRows_user < 1){
	header('location: 404.php');
	exit;
}
	
// if this user same as session
if($row_user['username'] == $_SESSION['MM_Username']){
	header('location: profile.php');
	exit;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Other User</title>
<link rel="stylesheet" href="assets/css/bootstrap.min.css">
	<link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/font-awesome.css">
</head>

<body>
	<div class="nav nav-bars">
		<ul class="pull-right">
			<li><a href="profile.php">Profile</a></li>
			<li><a href="upload.php">Upload</a></li>
			<li><a href="timeline.php">Timeline</a></li>
			<li><a href="logout.php">Logout</a></li>
		</ul>
	</div>
     <div class="nav nav1">
            <ul class="pull-right">
            <li><a href="notifikasi.php"> <i class="fa fa-bell"></i></a></li>
            
		</ul>
</div>
     <div class="main-content">
		<div class="row">
			<div class="col-md-6 col-md-offset-3">
				<!-- Content -->
				<div class="row bg-dark photo padding">
					<div class="col-md-12">
						<h3><strong>Ice Pict</strong></h3>
					</div>
				</div>
					<!-- ./Content -->
        <div class="row bg-dark photo padding">
					<div class="col-md-12">
					<br/>

<?php echo errok("profile_user"); ?>

<p><img src="content/profilpic/<?php echo $row_user['profilpic']; ?>"class="img-circle pull-left" style="width: 50px; height: 50px; margin-right:12px" /></p>
<p style="line-height:30px"> <?php echo $row_user['username']; ?></p>
<p>&nbsp;</p>
<p><strong>Full Name</strong> <br/><?php echo $row_user['fullname']; ?></p>
<p><strong>bio</strong><br/>
<?php echo $row_user['bio']; ?></p><hr />

<?php
//ambil user saya
$q = mysql_query("SELECT * FROM user WHERE username = '".$_SESSION['MM_Username']."';");
$user_saya = mysql_fetch_array($q);
$user_saya_id = $user_saya['id_user'];
// cek di relationship
$qq = mysql_query("SELECT * FROM relationship WHERE id_user_awal = $user_saya_id AND id_user_akhir = ".$row_user['id_user']);
if(mysql_num_rows($qq) > 0){
?>
<a href="unfollow.php?id=<?php echo $row_user['id_user']; ?>"><p class="btn btn-danger">unfollow</p></a><?php }else{ ?>
<a href="follow.php?id=<?php echo $row_user['id_user']; ?>"><p class="btn btn-primary">follow</p></a>
<?php } ?>
<?php

//hitung foto\
$qq= mysql_query("SELECT * FROM `photo` WHERE `id_user` = '".$row_user['id_user']."'")or die (mysql_error());
$banyak_foto = mysql_num_rows($qq);
//hitung follow\
$qq= mysql_query("SELECT * FROM `relationship` WHERE `id_user_awal` = '".$row_user['id_user']."'")or die (mysql_error());
$banyak_follow = mysql_num_rows($qq);
//hitung follow\
$qq= mysql_query("SELECT * FROM `relationship` WHERE `id_user_akhir` = '".$row_user['id_user']."'")or die (mysql_error());
$banyak_follower = mysql_num_rows($qq);
// point
$qq = mysql_query("SELECT * FROM `point` WHERE `dari` <= ".$row_user['point']." && `ke` >= ".$row_user['point']." ")or die (mysql_error());
$nama_point = mysql_fetch_array($qq);
  ?><hr/>
<p> <strong>Photo</strong> <?php echo $banyak_foto?><strong> <a href="follower.php?id=<?php echo $row_user['id_user']; ?>">Followers</a></strong> <?php echo $banyak_follower?><a href="following.php?id=<?php echo $row_user['id_user']; ?>"> <strong>Following</strong></a> <?php echo $banyak_follow?> <strong>Point</strong> <?php echo $row_user['point']; ?></p>
<p><strong>Predikat</strong> <i class="fa fa-trophy"></i><br/>
 <?php echo $nama_point['predikat']; ?></p><hr/>
<p class="photo padding">
<?php
// tampilkan user ini
$qq = mysql_query("SELECT * FROM photo WHERE id_user = '".$row_user['id_user']."'")or die (mysql_error());
while($ff = mysql_fetch_array($qq)){
@$foto++;	
?>

<a href="preview_photo.php?id=<?php echo $ff['id_photo']?>">
<img src="content/photo/<?php echo $ff['url']; ?>" width="100px" class="photo"/></a>
<?php } ?>
<?php if(@$foto<1){
	?>
Foto Kosong 
<?php } ?>
</p><hr/>
<p>&nbsp;</p>

</body>
</html>
<?php
mysql_free_result($user);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
</body>
</html>
