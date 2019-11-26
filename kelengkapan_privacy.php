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
//$img = @$_FILES['profilpic']['name'];
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
	// get variable
	$id_user = sql($_POST['id_user']);
	$private_account = @$_POST['private_account'];
	
	// get user setting
	$q_setting_user = mysql_query("SELECT * FROM `user` WHERE `id_user` = '$id_user' AND `username` = '".$_SESSION['MM_Username']."'") or die(mysql_error());
	$c_setting_user = mysql_num_rows($q_setting_user);
	if($c_setting_user < 1 || $c_setting_user == ""){
		header('location: 404.php');
		exit;
	}else{
		// get it
		$f_setting_user = mysql_fetch_array($q_setting_user);
		$user_setting = json_decode($f_setting_user['setting']);
	}
	
	// check private_account true/false
	if(!empty($private_account) && $private_account == "on"){
		$user_setting->private_account = true;
	}else{
		$user_setting->private_account = false;
	}
	
	
	// encode the setting
	$user_setting = json_encode($user_setting);
	
  $updateSQL = sprintf("UPDATE `user` SET setting=%s WHERE id_user=%s AND `username` = %s",
                       GetSQLValueString($user_setting, "text"),
                       GetSQLValueString($id_user, "text"),
                       GetSQLValueString($_SESSION['MM_Username'], "text"));

  mysql_select_db($database_konek, $konek);
  $Result1 = mysql_query($updateSQL, $konek) or die(mysql_error());
  
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header("location: kelengkapan_privacy.php?ok=changes-saved");

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
<a href="kelengkapan_photos.php" class="btn btn-primary">Foto Profil</a>
<a href="kelengkapan_privacy.php" class="btn btn-primary disabled">Privasi</a>
<br/>
<br/>

<?php echo errok("kelengkapan_privacy"); ?>
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
  <table class="table">
    <tr valign="baseline">
      <td nowrap="nowrap" align="right" width="150px;">Akun Privat:</td>
	  <td>
			<label>
			<?php
				// decode from setting
				$user_setting = json_decode($row_user['setting']);
				$private_account = $user_setting->private_account;
				
				// if private_account true
				if($private_account){
					$private_account_ck = ' checked="checked"';
				}else{
					$private_account_ck = "";
				}
			
			?>
				<input type="checkbox"<?php echo $private_account_ck; ?> name="private_account"></label>
			<p class="text-sm"><i>Jika diaktifkan maka hanya teman yang ada ikuti yang bisa melihat foto anda.</i></p>
	  </td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td><button type="submit" id="button" value="simpan" class="btn btn-success pull-right"><i class="fa fa-check-circle"></i> Simpan</button></td>
    </tr>
  </table>
  <input type="hidden" name="MM_update" value="form1" />
  <input type="hidden" name="id_user" value="<?php echo $row_user['id_user']; ?>" />
</form>
<p>&nbsp;</p>
	
	
	<!-- build -->
	<div style="bottom: 0px; right: 10px; position: fixed; color: rgba(0,0,0,.2)">
		<p><?php echo $build_version; ?></p>
	</div>
</body>
</html>
<?php
mysql_free_result($user);
?>
