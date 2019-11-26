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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);

}


if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
	// get variabels
	$img = @$_FILES['profilpic']['name'];
	$img_type = @$_FILES['profilpic']['type'];
	$id_user = sql($_POST['id_user']);
	
	// if empty
	if(empty($img) || $img == ""){
		header('location: kelengkapan_photos.php?err=select-your-file');
		exit;
	}
	
	// get extession
	$img_ext = explode(".", $img);
	$img_ext = strtolower($img_ext[count($img_ext)-1]);
	
	// check type
	if($img_type !== "image/jpeg" && $img_type !==  "image/jpg" && $img_type !==  "image/gif" && $img_type !==  "image/png"){
		header('location: kelengkapan_photos.php?err=file-notallowed');
		exit;
	}
	
	// check ext
	if($img_ext !== "jpeg" && $img_ext !==  "jpg" && $img_ext !==  "gif" && $img_ext !==  "png"){
		header('location: kelengkapan_photos.php?err=file-notallowed');
		exit;
	}
	
	// cek
	if(!empty($img)){
		$source = $_FILES['profilpic']['tmp_name'];
		copy($source,"content/profilpic/pp_".md5($id_user).".$img_ext");
		copy($source,"content/profilpic/sm_pp_".md5($id_user).".$img_ext");
	}
  
  $updateSQL = sprintf("UPDATE `user` SET profilpic=%s WHERE id_user=%s",
                       GetSQLValueString("pp_".md5($id_user).".$img_ext", "text"),
                       GetSQLValueString($id_user, "text"));

  mysql_select_db($database_konek, $konek);
  $Result1 = mysql_query($updateSQL, $konek) or die(mysql_error());
  
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header("location: kelengkapan_photos.php?ok=changes-saved");

}

$colname_user = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_user = $_SESSION['MM_Username'];
}
mysql_select_db($database_konek, $konek);
$query_user = sprintf("SELECT * FROM `user` WHERE username = %s", GetSQLValueString($colname_user, "text"));
$user = mysql_query($query_user, $konek) or die(mysql_error());
$row_user = mysql_fetch_assoc($user);
$totalRows_user = mysql_num_rows($user);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Edit Profile</title>
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
    <div class="main-content">
		<div class="row">
			<div class="col-md-6 col-md-offset-3">
				<!-- Content -->
				<div class="row bg-dark photo padding">
					<div class="col-md-12">
						<h3><strong>Ice Pict</strong> Edit Profile</h3>
					</div>
				</div>
				<!-- ./Content -->
        <div class="row bg-dark photo padding">
					<div class="col-md-12">
					
<br/>

<!-- link -->
<a href="kelengkapan.php" class="btn btn-primary">Informasi Dasar</a>
<a href="kelengkapan_password.php" class="btn btn-primary">Katasandi</a>
<a href="kelengkapan_photos.php" class="btn btn-primary disabled">Foto Profil</a>
<a href="kelengkapan_privacy.php" class="btn btn-primary">Privasi</a>
<br/>
<br/>

<?php echo errok("kelengkapan_photos"); ?>

<form action="<?php echo $editFormAction; ?>" method="post" enctype="multipart/form-data" name="form1" id="form1">
  <table class="table">
    <tr valign="baseline">
      <td nowrap="nowrap" align="right" width="150px;">
		<img src="content/profilpic/sm_<?php echo $row_user['profilpic']; ?>" class="img-responsive img-thumbnail" style="width: 150px;">
	  </td>
      <td>
		<p><b>Ganti Foto Profil</b></p>
		<p class="small"><i>Ukuran maksimal gambar adalah 600kb (jpg, jpeg, png, gif).</i></p>
		<input type="file" name="profilpic" class="btn btn-default"/>
	  </td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td><button type="submit" name="button" id="button" value="simpan" class="btn btn-success pull-right"><i class="fa fa-check-circle"></i> Simpan</button></td>
    </tr>
  </table>
  <input type="hidden" name="MM_update" value="form1" />
  <input type="hidden" name="id_user" value="<?php echo $row_user['id_user']; ?>" />
</form>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($user);
?>
