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

$maxRows_photo = 10;
$pageNum_photo = 0;
if (isset($_GET['pageNum_photo'])) {
  $pageNum_photo = $_GET['pageNum_photo'];
}
$startRow_photo = $pageNum_photo * $maxRows_photo;

mysql_select_db($database_konek, $konek);
$query_photo = "SELECT * FROM photo ORDER BY id_photo DESC";
$query_limit_photo = sprintf("%s LIMIT %d, %d", $query_photo, $startRow_photo, $maxRows_photo);
$photo = mysql_query($query_limit_photo, $konek) or die(mysql_error());
$row_photo = mysql_fetch_assoc($photo);

if (isset($_GET['totalRows_photo'])) {
  $totalRows_photo = $_GET['totalRows_photo'];
} else {
  $all_photo = mysql_query($query_photo);
  $totalRows_photo = mysql_num_rows($all_photo);
}
$totalPages_photo = ceil($totalRows_photo/$maxRows_photo)-1;

$id_photos_komen = "-1";
if (isset($row_photo['id_photo'])) {
  $id_photos_komen = $row_photo['id_photo'];
}
mysql_select_db($database_konek, $konek);
$query_komen = sprintf("SELECT * FROM komentar WHERE id_photo = %s ORDER BY id_komentar DESC", GetSQLValueString($id_photos_komen, "int"));
$komen = mysql_query($query_komen, $konek) or die(mysql_error());
$row_komen = mysql_fetch_assoc($komen);
$totalRows_komen = mysql_num_rows($komen);

$colname_user = "-1";
if (isset($row_photo['id_user'])) {
  $colname_user = $row_photo['id_user'];;
}
mysql_select_db($database_konek, $konek);
$query_user = sprintf("SELECT * FROM `user` WHERE id_user = %s", GetSQLValueString($colname_user, "int"));
$user = mysql_query($query_user, $konek) or die(mysql_error());
$row_user = mysql_fetch_assoc($user);
$totalRows_user = mysql_num_rows($user);

// declare
$total_timeline = 0;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>IcePict Timeline </title>
<link rel="stylesheet" href="assets/css/bootstrap.min.css">
	<link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/font-awesome.css">
</head>

<body>
	<div class="nav nav-bars">
		<ul class="pull-left">
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
				<!-- Title -->
				<div class="row bg-dark photo padding">
					<div class="col-md-12">
						<h3><strong>Ice Pict</strong> Timeline</h3>
					</div>
					
			
					
                    <?php include "search_form.php"; ?>
					<?php echo errok("timeline"); ?>
				</div>
				<?php do { ?>
						<?php
							$id_user_uploader = $row_photo['id_user'];
							
							// ambil user dari sesion
							$q2 = mysql_query("SELECT * FROM `user` WHERE `username` = '".$_SESSION['MM_Username']."'") or die(mysql_error());
							$f2 = mysql_fetch_array($q2);
							$id_userku = $f2['id_user'];
							
							// get relationshpip
							$qre = mysql_query("SELECT * FROM `relationship` WHERE `id_user_awal` = '$id_userku' AND `id_user_akhir` = '$id_user_uploader'");
							$apakahfollow = mysql_num_rows($qre);
							
							// jika sudah follow
							if(($apakahfollow > 0) OR ($id_user_uploader == $id_userku)){
								// query
								$q = mysql_query("SELECT * FROM `user` WHERE `id_user` = '".$row_photo['id_user']."'");
								while($f = mysql_fetch_array($q)){
						?>
				<!-- ./Content -->
                <div class="row bg-dark photo padding">
					<div class="col-md-12">
						<br/>
						<p>
							<img src="content/profilpic/<?php echo $f['profilpic']; ?>"class="img-circle pull-left" style="width: 50px; height: 50px; margin-right:12px"> 
						<?php
							// ambil user dari sesion
							$q2 = mysql_query("SELECT * FROM `user` WHERE `username` = '".$_SESSION['MM_Username']."'") or die(mysql_error());
							$f2 = mysql_fetch_array($q2);
							$id_userku = $f2['id_user'];

							if($id_userku == $row_photo['id_user']){
						?>
							<a href="profile.php"><h4><?php echo $f['username']; ?></h4></a>
						<?php }else{ ?>
							<a href="<?php echo $f['username']; ?>">  <h4><?php echo $f['username']; ?></h4></a>
						<?php } ?>
						</p>
						<?php } ?>
						<br />
						<br />
						<p><img src="content/photo/<?php echo $row_photo['url']; ?>"oncontextmenu="return false"class="img-thumbnail" width="100%"></p>
						<p><?php echo $row_photo['caption']; ?></p>
 
						<?php
							// ambil user dari sesion
							$q = mysql_query("SELECT * FROM `user` WHERE `username` = '".$_SESSION['MM_Username']."'") or die(mysql_error());
							$f = mysql_fetch_array($q);
							$id_userku = $f['id_user'];

							// hitung banyak like dari table like
							$q_like = mysql_query("SELECT * FROM `like` WHERE id_user = $id_userku AND id_photo = ".$row_photo['id_photo']) or die(mysql_error());
							$liked = mysql_num_rows($q_like);
							if($liked > 0){
						?>
						<p><a href="unlike.php?id=<?php echo $row_photo['id_photo']; ?>"class="btn btn-danger"><i class="fa fa-times"></i> unice  <div  class="col-md-2 pull-right"></div></a>  
						<?php }else{ ?>
						<p><a href="like.php?id=<?php echo $row_photo['id_photo']; ?>"class="btn btn-success"><i class="fa fa-glass"></i> ice  <div  class="col-md-2 pull-right"></div></a>  
						<?php } ?>
						<?php 
							// hitung banyak row yang id_photo sama dengan id_phoo ini
							$q_banyaklike = mysql_query("SELECT * FROM `like` WHERE id_photo = ".$row_photo['id_photo']) or die(mysql_error());
							$banyakice = mysql_num_rows($q_banyaklike);
							echo $banyakice; 
						?>
							<b>Iced</b>
						</p>

						<p>
							<div class="pull-right">
							<?php 
								// hitung banyak row yang id_photo sama dengan id_photo ini
								$q_banyakdown = mysql_query("SELECT * FROM `download` WHERE photo_id = ".$row_photo['id_photo']) or die(mysql_error());
								$banyakdownload = mysql_num_rows($q_banyakdown);
								echo $banyakdownload; 
							?>
								<b>downloaded</b>
								<p><a href="download.php?id=<?php echo $row_photo['id_photo']; ?>"class="btn btn-primary"><i class="fa fa-download"></i> download</a></p></div>

								<?php
									// ambil user dari sesion
									$q3 = mysql_query("SELECT * FROM `user` WHERE `username` = '".$_SESSION['MM_Username']."'") or die(mysql_error());
									$f3 = mysql_fetch_array($q);
									$id_userku = $f3['id_user'];
									// hitung banyak download dari table download
									$q_down = mysql_query("SELECT * FROM `download` WHERE photo_id = ".$row_photo['id_photo']) or die(mysql_error());
									$downloaded = mysql_num_rows($q_down);
								?>
    
								<?php
								// ambil user dari sesion
								$q2 = mysql_query("SELECT * FROM `user` WHERE `username` = '".$_SESSION['MM_Username']."'") or die(mysql_error());
								$f2 = mysql_fetch_array($q2);
								$id_userku = $f2['id_user'];

								if($id_userku == $row_photo['id_user']){
								?>
								<strong><a href="delete_foto.php?id=<?php echo $row_photo['id_photo']; ?>">hapus</a></strong>
 
								<?php } ?>
								<?php
								  $q = mysql_query("SELECT * FROM `komentar` WHERE `id_photo` = ".$row_photo['id_photo']." ORDER BY id_komentar ASC");
								  while($f = mysql_fetch_array($q)){
									  // get user
									  $qq = mysql_query("SELECT * FROM `user` WHERE `id_user` = '".$f['id_user']."'");
									  $ff = mysql_fetch_array($qq);
								?>
						</p>
						
						<p>
							<b><?php echo $ff['username']; ?></b> <?php echo $f['komentar']; ?>
							<?php
							if($id_userku == $f['id_user']){
							?>
							<a href="delete_komen.php?id=<?php echo  $f['id_komentar']; ?>" class="text-sm"><i>(Hapus)</i></a>
							<?php } ?>
						</p>
							<?php } ?>
						<form action="komentar.php" method="post" name="form1" id="form1">
							<br/>
							<input type="text" name="komentar" value="" size="32" class="form-control" placeholder="Komentar...">
							<br/>
							<button type="submit" id="button" value="komentar" class="btn btn-success pull-right"><i class="fa fa-comment"></i> Komentar</button></td>						
							<br/>
							<br/>
							<br/>
							<?php
								$qq = mysql_query("SELECT * FROM `user` WHERE `username` = '".$_SESSION['MM_Username']."'");
								$ff = mysql_fetch_array($qq);
							?>
							<input type="hidden" name="tanggal_komentar" value="<?php echo  date("Y-m-d"); ?>" />
							<input type="hidden" name="id_user" value="<?php echo $ff['id_user'] ?>" />
							  
							<input type="hidden" name="id_photo" value="<?php echo $row_photo['id_photo']; ?>" />
							<input type="hidden" name="MM_insert" value="form1" />
						</form>
					</div>
				</div>
					<?php
					$total_timeline++;
					}
					?>
			<?php } while ($row_photo = mysql_fetch_assoc($photo)); ?>
			
			<?php
				// if empty timeline
				if($total_timeline < 1){
				?>
				<!-- ./Content -->
                <div class="row bg-dark photo padding">
					<div class="col-md-12">
						<h4 class="text-center">Ini adalah timeline anda. </h4>
					</div>
				</div>
			<?php } ?>
			
			</div>
		</div>
	</div>
</body>
</html>
<?php
mysql_free_result($photo);

mysql_free_result($komen);

mysql_free_result($user);
?>
