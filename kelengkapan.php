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
	$username = sql($_POST['username']);
	$bio = xss($_POST['bio']);
	$email = $_POST['email'];
	
	// check bio length
	if(strlen($bio) >= 300){
		header('location: kelengkapan.php?err=bio-toolong');
		exit;
	}
	// check username length
	if(strlen($username) > 15){
		header('location: kelengkapan.php?err=username-toolong');
		exit;
	}
	// too short
	if(strlen($username) < 3){
		header('location: kelengkapan.php?err=username-tooshort');
		exit;
	}	
	
	// check username exist or not 
	$q_user = mysql_query("SELECT * FROM `user` WHERE `username` = '$username'");
	$f_user = mysql_fetch_array($q_user);
	$c_user = mysql_num_rows($q_user);
	if($c_user > 0 && $username !== $_SESSION['MM_Username']){
		// if username exist redirect back to login
		header('location: kelengkapan.php?err=username-exist');
		exit;
	}

	// email filter
	$check_email = explode("@", $email);
	// check the @
	if(count($check_email) !== 2){
		header('location: kelengkapan.php?err=email-invalid');
		exit;
	}else{
		// check the before and after @
		if(strlen($check_email[0]) < 1 || strlen($check_email[1]) < 1){
			header('location: kelengkapan.php?err=email-invalid');
			exit;
		}else{
			$host_check_email = explode(".", $check_email[1]);
			// check email host valid or not
			if(count($host_check_email) < 2){
				header('location: kelengkapan.php?err=email-invalid');
				exit;
			}else{
				// check domain
				if(strlen($host_check_email[count($host_check_email)-1]) < 1){
					header('location: kelengkapan.php?err=email-invalid');
					exit;
				}
			}
			
			// check before 
			if(strlen($check_email[0]) < 1){
				header('location: kelengkapan.php?err=email-invalid');
				exit;
			}
		}
		
		// sqli
		$email = xss(sql($check_email[0]))."@".xss(sql($check_email[1]));
	}
	
	// email changed?
	$q_email_user = mysql_query("SELECT `email` FROM `user` WHERE `email` = '$email' AND `username` <> '$username' ");
	$c_email_user = mysql_num_rows($q_email_user);
	if($c_email_user > 0){
		header('location: kelengkapan.php?err=email-already-used');
		exit;
	}
	
	// the saved email same as before?
	$q_email_user = mysql_query("SELECT `email` FROM `user` WHERE `username` = '$username' ");
	$c_email_user = mysql_num_rows($q_email_user);
	$f_email_user = mysql_fetch_array($q_email_user);
	if($c_email_user < 1){
		header('location: kelengkapan.php?err=email-already-used');
		exit;
	}else{
		// if not match
		if($email !== $f_email_user['email']){
			mysql_query("UPDATE `user` SET `email_status` = 'unverified' WHERE `email` = '".$f_email_user['email']."' AND `username` = '$username'") or die(mysql_error());
		}
	}
	
  $updateSQL = sprintf("UPDATE `user` SET email=%s, username=%s, fullname=%s, bio=%s WHERE id_user=%s",
                       GetSQLValueString($email, "text"),
                       GetSQLValueString($username, "text"),
                       GetSQLValueString($_POST['fullname'], "text"),
                       GetSQLValueString($bio, "text"),
                       GetSQLValueString($_POST['id_user'], "int"));

  mysql_select_db($database_konek, $konek);
  $Result1 = mysql_query($updateSQL, $konek) or die(mysql_error());
  

   $insertGoTo = "kelengkapan.php?ok=changes-saved";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header("location: kelengkapan.php?ok=changes-saved");

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
<a href="kelengkapan.php" class="btn btn-primary disabled">Informasi Dasar</a>
<a href="kelengkapan_password.php" class="btn btn-primary">Katasandi</a>
<a href="kelengkapan_photos.php" class="btn btn-primary">Foto Profil</a>
<a href="kelengkapan_privacy.php" class="btn btn-primary">Privasi</a>
<br/>
<br/>

<?php echo errok("kelengkapan"); ?>

<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
  <table class="table">
    <tr valign="baseline">
      <td nowrap="nowrap" align="right" width="100px">Email</td>
      <td>
		<input type="text" name="email" value="<?php echo htmlentities($row_user['email'], ENT_COMPAT, 'utf-8'); ?>" size="32"class= "form-control input-sm" placeholder="email" />
		<?php
			// if email not verified yet
			$username = $_SESSION['MM_Username'];
			$q_email_ver_user = mysql_query("SELECT `email_status` FROM `user` WHERE `username` = '$username ' AND `email_status` = 'unverified'");
			$c_email_ver_user = mysql_num_rows($q_email_ver_user);
			if($c_email_ver_user > 0){
		?>
		<p><i>E-mail</i> belum diverifikasi, <a href="kelengkapan_email.php">Kirim ulang kode verifikasi</a>.</p>
		<?php } ?>
	  </td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Username:</td>
      <td><input type="text" name="username" value="<?php echo htmlentities($row_user['username'], ENT_COMPAT, 'utf-8'); ?>" size="32" class= "form-control input-sm" placeholder="username" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Fullname:</td>
      <td><input type="text" name="fullname" value="<?php echo htmlentities($row_user['fullname'], ENT_COMPAT, 'utf-8'); ?>" size="32"class= "form-control input-sm" placeholder="fullname" /></td> 
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Bio:</td>
      <td>
	  <textarea name="bio" class= "form-control input-sm" placeholder="bio"/><?php echo htmlentities($row_user['bio'], ENT_COMPAT, 'utf-8'); ?></textarea>
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
