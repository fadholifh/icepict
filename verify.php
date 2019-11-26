<?php require_once('Connections/konek.php'); ?>
<?php require_once('engine/boe-function.php'); ?>
<?php
// get the key
$ver_code = sql(@$_GET['key']);

// cek apakah kosong?
if($ver_code == "" || empty($ver_code)){
	header('location: index.php?err=url-expired');
	exit;
}

// cek ke database
$q_user = mysql_query("SELECT * FROM `user` WHERE `status` = 'unverified' AND `ver_code` = '$ver_code'");
$c_user = mysql_num_rows($q_user);
$f_user = mysql_fetch_array($q_user);

// cek apakah ada di database
if($c_user == 0 || $c_user == ""){
	header('location: index.php?err=url-expired');
	exit;
}

// match the code
if($ver_code !== $f_user['ver_code']){
	header('location: index.php?err=url-expired');
	exit;
}
	
// change the status and remove the code
$q_user_ver = mysql_query("UPDATE `user` SET `status` = 'verified',`ver_code` = NULL WHERE `status` = 'unverified' AND `ver_code` = '$ver_code'") or die(mysql_error());

// show verified messages
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>IcePict</title>
<link rel="stylesheet" href="assets/css/bootstrap.min.css">
	<link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/font-awesome.css">
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
					<div class="col-md-12 text-center">
						<h3>Pendaftaran</h3>
						<p>Terimakasih telah mengkonfirmasi akun anda. <br/>klik link berikut untuk masuk.</p>
						<a href="login.php" class="btn btn-primary">Masuk</a>
						<br/>
						<br/>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>

