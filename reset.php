<?php require_once('Connections/konek.php'); ?>
<?php require_once('engine/boe-function.php'); ?>
<?php
// if password and new password reterived
if(isset($_GET['key']) && (@$_GET['key'] !== @$_SESSION['key'])){
	// destroy session
	$_SESSION['key'] = "";
	unset($_SESSION['key']);
}

if(!isset($_SESSION['key'])){
	// get the key
	$forgot_key = sql(@$_GET['key']);

	// cek apakah kosong?
	if($forgot_key == "" || empty($forgot_key)){
		header('location: index.php?err=url-expired');
		exit;
	}

	// cek ke database
	$q_user = mysql_query("SELECT * FROM `user` WHERE `status` = 'verified' AND `forgot_key` = '$forgot_key'") or die(mysql_error());
	$c_user = mysql_num_rows($q_user);

	// cek apakah ada di database
	if($c_user == 0 || $c_user == ""){
		header('location: index.php?err=url-expired');
		exit;
	}
	
	// create
	$_SESSION['key'] = $forgot_key;
}else{
	// sesson set and get post
	if($_SERVER['REQUEST_METHOD'] == "POST"){
		// retrieve
		$password = sql($_POST['newpassword']);
		$copassword = sql($_POST['newcopassword']);
		$key = sql($_SESSION['key']);
		
		// key
		if($key == "" || empty($key)){
			header('location: index.php?err=url-expired');
			exit;
		} 
		// cek ke database
		$q_user = mysql_query("SELECT * FROM `user` WHERE `status` = 'verified' AND `forgot_key` = '$key'");
		$c_user = mysql_num_rows($q_user);
		$f_user = mysql_fetch_array($q_user);

		// cek apakah ada di database
		if($c_user == 0 || $c_user == ""){
			header('location: index.php?err=url-expired');
			exit;
		}

		// match the code
		if($key !== $f_user['forgot_key']){
			header('location: index.php?err=url-expired');
			exit;
		}
		
		
		
		// password check
		if($password == "" || empty($password)){
			header('location: reset.php?err=fill-password');
			exit;
		}
		elseif($copassword == "" || empty($copassword)){
			header('location: reset.php?err=fill-cofrimpassword');
			exit;
		}
		
		// verify data
		if($password !== $copassword){
			header('location: reset.php?err=password-mismatch');
			exit;
		}
		
		// check password length
		if(strlen($password) < 5){
			header('location: reset.php?err=password-tooshort');
			exit;
		}

		//md5 
		$pwd = md5($password);
		
		// reset now
		$q_user = mysql_query("UPDATE `user` SET `password` = '$pwd' WHERE `forgot_key` = '$key' AND `status` = 'verified';") or die(mysql_error());
		if($q_user){
			// change the status and remove the code
			$q_user_ver = mysql_query("UPDATE `user` SET `forgot_key` = NULL WHERE `status` = 'verified' AND `forgot_key` = '$forgot_key'") or die(mysql_error());

			// destroy session
			$_SESSION['key'] = "";
			unset($_SESSION['key']);
			
			// show verified messages
			header('location: login.php?ok=password-changed');
			exit;
		}
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
					<h3><strong>IcePict </strong>Atur Ulang Sandi</h3>
					</div>
				</div>
				<!-- ./Content -->
				
				<!-- Foto -->
				<div class="row bg-dark photo padding">
					<div class="col-md-12">
						<br/>
						<div class="row">
						<?php echo errok("reset"); ?>
						
						<form id="form1" name="form1" method="POST" action=""><div class="col-md-12">
						<table class="table">
							<tr valign="baseline">
								<td nowrap="nowrap" align="right"><div align="left"><strong>Kata Sandi</strong></div></td>
								<td><input type="password" name="newpassword" id="p" class="form-control" placeholder="Kata Sandi..."></td>
							</tr>
							<tr valign="baseline">
								<td nowrap="nowrap" align="right"><div align="left"><strong>Ulangi Kata Sandi</strong></div></td>
								<td><input type="password" name="newcopassword" id="p" class="form-control" placeholder="Konfirmasi Kata Sandi..."></td>
							</tr>
						</table>
						<input type="hidden" name="key" value="<?php echo @$_SESSION['key']; ?>">
						<button type="submit" id="button" value="Login" class="btn btn-success pull-right"><i class="fa fa-sign-in"></i> Atur ulang</button>
								</div>
							</form>
						</div>
						<br/>
					</div>
				</div>	
</form>
</body>
</html>