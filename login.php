<?php require_once('Connections/konek.php'); ?>
<?php require_once('engine/boe-function.php'); ?>
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
?>
<?php
// *** Validate request to login to this site.
if (!isset($_SESSION)) {
  session_start();
}

$loginFormAction = $_SERVER['PHP_SELF'];
if (isset($_GET['accesscheck'])) {
  $_SESSION['PrevUrl'] = $_GET['accesscheck'];
}

if (isset($_POST['u'])) {
  $loginUsername=$_POST['u'];
  $password=$_POST['p'];
  $MM_fldUserAuthorization = "";
  $MM_redirectLoginSuccess = "timeline.php";
  $MM_redirectLoginFailed = "login.php";
  $MM_redirecttoReferrer = false;
  mysql_select_db($database_konek, $konek);
  
  $LoginRS__query=sprintf("SELECT username, password FROM `user` WHERE username=%s AND password=MD5(%s)",
    GetSQLValueString($loginUsername, "text"), GetSQLValueString($password, "text")); 
   
  $LoginRS = mysql_query($LoginRS__query, $konek) or die(mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);
  if ($loginFoundUser) {
     $loginStrGroup = "";
    
	if (PHP_VERSION >= 5.1) {session_regenerate_id(true);} else {session_regenerate_id();}
    //declare two session variables and assign them
    $_SESSION['MM_Username'] = $loginUsername;
    $_SESSION['MM_UserGroup'] = $loginStrGroup;	      
	
	// update the forgot key
	mysql_query("UPDATE `user` SET `forgot_key` = NULL WHERE `username` = '$loginUsername'") or die(mysql_error());

    if (isset($_SESSION['PrevUrl']) && false) {
      $MM_redirectLoginSuccess = $_SESSION['PrevUrl'];	
    }
    header("Location: " . $MM_redirectLoginSuccess );
  }
  else {
    header("location: login.php?err=mismatch-combination");
	exit;
  }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>IcePict Login</title>
<link rel="stylesheet" href="assets/css/bootstrap.min.css">
	<link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/font-awesome.css">
</head>

<body>
<div class="main-content">
		<div class="row">
			<div class="col-md-6 col-md-offset-3">
				<!-- Content -->
				<div class="row bg-dark photo padding">
					<div class="col-md-12">
					<h3><strong>IcePict </strong>Masuk</h3>
					</div>
				</div>
				<!-- ./Content -->
				
				<!-- Foto -->
				<div class="row bg-dark photo padding">
					<div class="col-md-12">
						<br/>
						<div class="row">
						<?php echo errok("login"); ?>
						
						<form id="form1" name="form1" method="POST" action="<?php echo $loginFormAction; ?>"><div class="col-md-12">
						<table class="table">
							<tr valign="baseline">
								<td nowrap="nowrap" align="right" width="20%"><div align="left"><strong>Username</strong></div></td>
								<td><input type="text" name="u" id="u" class="form-control" placeholder="Username..."></td>
							</tr>
							<tr valign="baseline">
								<td nowrap="nowrap" align="right"><div align="left"><strong>Kata Sandi</strong></div></td>
								<td><input type="password" name="p" id="p"class="form-control" placeholder="Password..."></td>
							</tr>
						</table>
						<button type="submit" name="button" id="button" value="Login" class="btn btn-success pull-right"><i class="fa fa-sign-in"></i> Login</button>
						&nbsp;
						<a href="forgot.php" class="btn pull-right">Lupa Kata Sandi?</a>
								</div>
							</form>
						</div>
						<br/>
					</div>
				</div>	
</form>
</body>
</html>