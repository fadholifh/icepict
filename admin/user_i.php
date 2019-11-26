<?php require_once('../Connections/konek.php'); ?>
<?php require_once('../engine/boe-function.php'); ?>
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
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
	// get variable
	$username = sql($_POST['username']);
	$email = xss($_POST['email']);
	$password = sql($_POST['password']);
	$copassword = sql($_POST['copassword']);
	
	// set temporary session
	$temp_reg = array(
		'username' => $username,
		'email' => $email
	);
	$_SESSION['temp_reg'] = json_encode($temp_reg);
	
	// check empty field
	if($username == "" || empty($username)){
		header('location: user_i.php?err=fill-username');
		exit;
	}
	elseif($email == "" || empty($email)){
		header('location: user_i.php?err=fill-email');
		exit;
	}
	elseif($password == "" || empty($password)){
		header('location: user_i.php?err=fill-password');
		exit;
	}
	elseif($copassword == "" || empty($copassword)){
		header('location: user_i.php?err=fill-cofrimpassword');
		exit;
	}
	
	// check username length
	if(strlen($username) > 15){
		header('location: user_i.php?err=username-toolong');
		exit;
	}
	// too short
	if(strlen($username) < 3){
		header('location: user_i.php?err=username-tooshort');
		exit;
	}	
	
	// check username exist or not 
	$q_user = mysql_query("SELECT * FROM `user` WHERE `username` = '$username'");
	$f_user = mysql_fetch_array($q_user);
	$c_user = mysql_num_rows($q_user);
	if($c_user > 0){
		// if username exist redirect back to login
		header('location: user_i.php?err=username-exist');
		exit;
	}
	
	// verify data
	if($password !== $copassword){
		header('location: user_i.php?err=password-mismatch');
		exit;
	}
	
	// check password length
	if(strlen($password) < 5){
		header('location: user_i.php?err=password-tooshort');
		exit;
	}
	
	// email filter
	$check_email = explode("@", $email);
	// check the @
	if(count($check_email) !== 2){
		header('location: user_i.php?err=email-invalid');
		exit;
	}else{
		// check the before and after @
		if(strlen($check_email[0]) < 1 || strlen($check_email[1]) < 1){
			header('location: user_i.php?err=email-invalid');
			exit;
		}else{
			$host_check_email = explode(".", $check_email[1]);
			// check email host valid or not
			if(count($host_check_email) < 2){
				header('location: user_i.php?err=email-invalid');
				exit;
			}else{
				// check domain
				if(strlen($host_check_email[count($host_check_email)-1]) < 1){
					header('location: user_i.php?err=email-invalid');
					exit;
				}
			}
			
			// check before 
			if(strlen($check_email[0]) < 1){
				header('location: user_i.php?err=email-invalid');
				exit;
			}
		}
		
		// sqli
		$email = xss(sql($check_email[0]))."@".xss(sql($check_email[1]));
	}
	
	
	// get value and filter
	$reg_date = date("Y-m-d H:i:s");
	// proceess
	$ver_code = $email.rand(222,344).$reg_date.rand(11111,99999);
	$ver_code = md5(md5($ver_code));
	$forgot_key = "";
	// insert to database
	$insertSQL = sprintf("INSERT INTO `user` (email, password, username, fullname, reg_date, ver_code, forgot_key, status) VALUES (%s, MD5(%s), %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($email, "text"),
                       GetSQLValueString($password, "text"),
                       GetSQLValueString($username, "text"),
                       GetSQLValueString($_POST['fullname'], "text"),
                       GetSQLValueString($reg_date, "text"),
                       GetSQLValueString($ver_code, "text"),
                       GetSQLValueString($forgot_key, "text"),
                       GetSQLValueString("unverified", "text")
					   );

  mysql_select_db($database_konek, $konek);
  $Result1 = mysql_query($insertSQL, $konek) or die(mysql_error());
	
	// get detailed information
	$fullname = $f_user['fullname'];
	$email = $f_user['email'];
	// send email configuration
	$to = "$fullname <$email>";
	$from = "Boentil <not-reply@boentil.com>";
	$subject = "Verifikasi Email Anda";
	$content = "
		Halo $fullname, <br/><br/>

		Someone recently requested a password change for your account in &quot;$sitename&quot;. 
		If this was you, you can set a new password  <a href=\"http://www.boentil.com/verify.php?key=".$ver_code."\">here</a> :<br/><br/>

		<a href=\"http://www.boentil.com/verify.php?key=".$ver_code."\">Verrifiy</a><br/><br/>

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
	
	// destroy temp reg session
	$_SESSION['temp_reg'] = "";
	unset($_SESSION['temp_reg']);
	
	// redirect (done)
	header('location:index.php?ok=email-sent');
	exit;
}

mysql_select_db($database_konek, $konek);
$query_user_reg = "SELECT * FROM `user`";
$user_reg = mysql_query($query_user_reg, $konek) or die(mysql_error());
$row_user_reg = mysql_fetch_assoc($user_reg);
$totalRows_user_reg = mysql_num_rows($user_reg);


// temp reg session
// if temp reg set
if(isset($_SESSION['temp_reg'])){
	$temp_reg = json_decode($_SESSION['temp_reg']);

	if($temp_reg->username !== ""){
		$value_username = $temp_reg->username;
	}else{
		$value_username = "";
	}
	
	if($temp_reg->email !== ""){
		$value_email = $temp_reg->email;
	}else{
		$value_email = "";
	}
}else{
	// not set!
	$value_username = "";
	$value_email = "";
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>IcePict Register</title>
<link rel="stylesheet" href="../assets/css/bootstrap.min.css">
	<link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/font-awesome.css">
</head>

<body>
	<div class="nav nav-bars">
		<ul class="pull-right">
			<li><a href="index.php"><strong>Ice</strong>Pict</a></li>
		</ul>
	</div>
    <div class="main-content">
		<div class="row">
			<div class="col-md-6 col-md-offset-3">
				<!-- Content -->
				<div class="row bg-dark photo padding">
					<div class="col-md-12">
						<h3><strong>Ice Pict</strong> Register</h3>
					</div>
				</div>
				<!-- ./Content -->
        <div class="row bg-dark photo padding">
					<div class="col-md-12">
					
<div class="row">
							<div class="col-md-1">
                            </div>
                            <div class="col-md-9">
							
							</div>
</div>
						<div class="row">
							<div class="col-md-12">
								
							</div>
						</div>
						<br/>
                        <div class="row">
							<div class="col-md-10 pull-left">
							</div>
							
						</div>
                        <div class="row">
							<div class="col-md-12">
                            </div>
</div>
</head>

<body>
<?php echo errok("register"); ?>
<form action="" method="post">
  <table class="table">
    <tr valign="baseline">
      <td nowrap="nowrap" align="right" width="20%"><div align="left"><strong>Username</strong></div></td>
      <td><input type="text" name="username" value="<?php echo $value_username ?>" size="32"class= "form-control input-sm" placeholder="Username anda" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right" width="20%"><div align="left"><strong>Fullname</strong></div></td>
      <td><input type="text" name="fullname" value="" size="32"class= "form-control input-sm" placeholder="Nama Lengkap anda" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right"><div align="left"><strong>E-mail</strong></div></td>
      <td><input type="text" name="email" value="<?php echo $value_email ?>" size="32"class= "form-control input-sm" placeholder="Masukan E-mail anda" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right"><div align="left"><strong>Password</strong></div></td>
      <td><input type="password" name="password" value="" size="32"class= "form-control input-sm" placeholder="Masukan katasandi anda" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right"><div align="left"><strong>Ulangi Password</strong></div></td>
      <td><input type="password" name="copassword" value="" size="32"class= "form-control input-sm" placeholder="Ketik lagi katasandi anda" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td><button type="submit" value="Simpan" class="btn btn-success"><i class="fa fa-check-circle"></i> Mendaftar</button></td>
    </tr>
  </table>
  <input type="hidden" name="MM_insert" value="form1" />
</form>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($user_reg);

?>
