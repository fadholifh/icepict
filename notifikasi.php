<?php require_once('Connections/konek.php'); ?>
<?php
if (!isset($_SESSION)) {
 session_start();
}
$MM_authorizedUsers = "";
$MM_donotCheckaccess = "true";

// ambil user dari sesion
  $q2 = mysql_query("SELECT * FROM `user` WHERE `username` = '".$_SESSION['MM_Username']."'") or die(mysql_error());
  $f2 = mysql_fetch_array($q2);
  $id_userku = $f2['id_user'];
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

$maxRows_notifikasi = 10;
$pageNum_notifikasi = 0;
if (isset($_GET['pageNum_notifikasi'])) {
  $pageNum_notifikasi = $_GET['pageNum_notifikasi'];
}
$startRow_notifikasi = $pageNum_notifikasi * $maxRows_notifikasi;

mysql_select_db($database_konek, $konek);
$query_notifikasi = "SELECT * FROM notifikasi";
$query_limit_notifikasi = sprintf("%s LIMIT %d, %d", $query_notifikasi, $startRow_notifikasi, $maxRows_notifikasi);
$notifikasi = mysql_query($query_limit_notifikasi, $konek) or die(mysql_error());
$row_notifikasi = mysql_fetch_assoc($notifikasi);

if (isset($_GET['totalRows_notifikasi'])) {
  $totalRows_notifikasi = $_GET['totalRows_notifikasi'];
} else {
  $all_notifikasi = mysql_query($query_notifikasi);
  $totalRows_notifikasi = mysql_num_rows($all_notifikasi);
}
$totalPages_notifikasi = ceil($totalRows_notifikasi/$maxRows_notifikasi)-1;$maxRows_notifikasi = 10;
$pageNum_notifikasi = 0;
if (isset($_GET['pageNum_notifikasi'])) {
  $pageNum_notifikasi = $_GET['pageNum_notifikasi'];
}
$startRow_notifikasi = $pageNum_notifikasi * $maxRows_notifikasi;

mysql_select_db($database_konek, $konek);
// ambil user dari sesion
  $q = mysql_query("SELECT * FROM `user` WHERE `username` = '".$_SESSION['MM_Username']."'") or die(mysql_error());
  $f = mysql_fetch_array($q);
 
 $id_awal = $f['id_user'];
mysql_query("UPDATE `notifikasi` SET `status`= 'read' WHERE `id_akhir` =  $id_awal") or die(mysql_error());
 // ambil user dari sesion
  $q = mysql_query("SELECT * FROM `user` WHERE `username` = '".$_SESSION['MM_Username']."'") or die(mysql_error());
  $f = mysql_fetch_array($q);
 
 $id_awal = $f['id_user'];
$query_notifikasi = "SELECT * FROM notifikasi WHERE `id_akhir`=   $id_awal AND `id_awal`<>$id_userku ORDER BY id_notif DESC ";
$query_limit_notifikasi = sprintf("%s LIMIT %d, %d", $query_notifikasi, $startRow_notifikasi, $maxRows_notifikasi);
$notifikasi = mysql_query($query_limit_notifikasi, $konek) or die(mysql_error());
$row_notifikasi = mysql_fetch_assoc($notifikasi);

if (isset($_GET['totalRows_notifikasi'])) {
  $totalRows_notifikasi = $_GET['totalRows_notifikasi'];
} else {
  $all_notifikasi = mysql_query($query_notifikasi);
  $totalRows_notifikasi = mysql_num_rows($all_notifikasi);
}
$totalPages_notifikasi = ceil($totalRows_notifikasi/$maxRows_notifikasi)-1;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Notifikasi</title>
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
				<!-- Content -->
				<div class="row bg-dark photo padding">
					<div class="col-md-12">
						<h3><strong>Ice Pict</strong> Notifikasi</h3>
					</div>
                    
				</div>
				<!-- ./Content -->
                <div class="row bg-dark photo padding">
					<div class="col-md-12">

						<br/>


<table class="table">
  <tr>
    <td><strong>Notifikasi</strong></td>
  </tr>
  <?php do { ?>
	<?php
		// ambil user dari sesion
		$q = mysql_query("SELECT * FROM `user` WHERE `id_user` = '".$row_notifikasi['id_awal']."'") or die(mysql_error());
		$f = mysql_fetch_array($q);
	?>
  <tr>
	<td><?php echo $row_notifikasi['date']; ?> <a href="<?php echo $f['username']; ?>"> <?php echo $f['username']; ?></a> <?php echo $row_notifikasi['isi']; ?> 
<?php if($row_notifikasi['id'] != 0){ ?>
	<em><a href="preview_photo.php?id=<?php echo $row_notifikasi['id']; ?>">Lihat</a></em>
<?php } ?>
      
	</td>
  <tr>
<?php } while ($row_notifikasi = mysql_fetch_assoc($notifikasi)); ?>
</table>
<?php
// if empty 
if($totalRows_notifikasi == 0){
?>
		<h4 class="text-center">Ini adalah panel notifikasi anda. </h4><br/>

<?php
}
?>
</body>
</html>
<?php
mysql_free_result($notifikasi);
?>

