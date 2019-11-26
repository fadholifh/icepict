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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
// if post method
if ((isset($_POST["forgot"])) && ($_POST["forgot"] == "forgot")) {
	// get variable
	$email = xss($_POST['email']);
	
	// check empty field
	if($email == "" || empty($email)){
		header('location: forgot.php?err=fill-email');
		exit;
	}

	// check email exist or not 
	$q_user = mysql_query("SELECT * FROM `user` WHERE `email` = '$email'");
	$f_user = mysql_fetch_array($q_user);
	$c_user = mysql_num_rows($q_user);
	if($c_user == 0 || empty($c_user)){
		// if email not found
		header('location: forgot.php?err=email-notfound');
		exit;
	}
	
	// update the forgot key
	// change the status and edit the code
	$forgot_key = $email.rand(222,344).date("Y-m-d H:i:s").rand(11111,99999);
	$forgot_key = md5(md5($forgot_key));
	$q_user_ver = mysql_query("UPDATE `user` SET `forgot_key` = '$forgot_key' WHERE `status` = 'verified' AND `email` = '$email'") or die(mysql_error());

	
	// get detailed information
	$fullname = $f_user['fullname'];
	$email = $f_user['email'];
	// send email configuration
	$to = "$fullname <$email>";
	$from = "Boentil <not-reply@boentil.com>";
	$subject = "Atur ulang Kata Sandi";
	$content = "
		Halo $fullname, <br/><br/>

		Someone recently requested a password change for your account in &quot;$sitename&quot;. 
		If this was you, you can set a new password  <a href=\"http://www.boentil.com/reset.php?key=".$ver_code."\">here</a> :<br/><br/>

		<a href=\"http://www.boentil.com/reset.php?key=".$ver_code."\">Reset</a><br/><br/>

		If you don't want to change your password or didn't request this, just ignore and delete this message.<br/><br/>

		To keep your account secure, please don't forward this email to anyone.<br/><br/>

		Thanks!<br/>
		Boentil<br/>

	";
	$header = "From: not-reply@boentil.com\r\n";
	$header .= "Content-Type: text/html; charset=utf-8\r\n";
	$header .= "MIME-Version: 1.0\r\n";
	$header .= "Content-Transfer-Encoding: quoted-printable";

	// send it
	mail($to, $subject, $content, $header);
	
	// redirect (done)
	header('location: forgot.php?ok=email-sent');
	exit;
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
					<h3><strong>IcePict </strong>Lupa Kata Sandi</h3>
					</div>
				</div>
				<!-- ./Content -->
				
				<!-- Foto -->
				<div class="row bg-dark photo padding">
					<div class="col-md-12">
						<br/>
						<div class="row">
						<?php echo errok("forgot"); ?>
						
						<form id="form1" name="form1" method="POST" action=""><div class="col-md-12">
						<table class="table">
							<tr valign="baseline">
								<td nowrap="nowrap" align="right" width="20%"><div align="left"><strong>E-mail</strong></div></td>
								<td><input type="email" name="email" class="form-control" placeholder="Masukan E-mail anda..."></td>
							</tr>
						</table>
						<input type="hidden" name="forgot" value="forgot"/>
						<button type="submit" name="button" id="button" value="Login" class="btn btn-success pull-right"><i class="fa fa-sign-in"></i> Selanjutnya</button>
								</div>
							</form>
						</div>
						<br/>
					</div>
				</div>	
</form>
</body>
</html>