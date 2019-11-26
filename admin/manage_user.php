<?php require_once('../Connections/konek.php'); ?>
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

$maxRows_manage_user = 10;
$pageNum_manage_user = 0;
if (isset($_GET['pageNum_manage_user'])) {
  $pageNum_manage_user = $_GET['pageNum_manage_user'];
}
$startRow_manage_user = $pageNum_manage_user * $maxRows_manage_user;

mysql_select_db($database_konek, $konek);
$query_manage_user = "SELECT * FROM `user`";
$query_limit_manage_user = sprintf("%s LIMIT %d, %d", $query_manage_user, $startRow_manage_user, $maxRows_manage_user);
$manage_user = mysql_query($query_limit_manage_user, $konek) or die(mysql_error());
$row_manage_user = mysql_fetch_assoc($manage_user);

if (isset($_GET['totalRows_manage_user'])) {
  $totalRows_manage_user = $_GET['totalRows_manage_user'];
} else {
  $all_manage_user = mysql_query($query_manage_user);
  $totalRows_manage_user = mysql_num_rows($all_manage_user);
}
$totalPages_manage_user = ceil($totalRows_manage_user/$maxRows_manage_user)-1;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Manage User</title>
<link rel="stylesheet" href="../assets/css/bootstrap.min.css">
	<link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
	<div class="nav nav-bars">
		<ul class="pull-right">
			<li><a href="manage_user.php">Manage User</a></li>
            <li><a href="backup.php">Backup Data</a></li>
			<li><a href="logout.php">Logout</a></li>
		</ul>
	</div>
    <div class="main-content">
		<div class="row">
			<div class="col-md-9 col-md-offset-3">
				<!-- Content -->
				<div class="row bg-dark photo padding">
			  <div class="col-md-12">
						<h3><strong>Ice Pict </strong>Manage User</h3>
                        
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
<table class="table" width="100%">
  <tr>
    <td>email</td>
    <td>username</td>
    <td>fullname</td>
    <td>bio</td>
    <td>profilpic</td>
    <td>point</td>
    <td>Aksi</td>
  </tr>
  <?php do { ?>
    <tr>
      <td><?php echo $row_manage_user['email']; ?></td>
      <td><?php echo $row_manage_user['username']; ?></td>
      <td><?php echo $row_manage_user['fullname']; ?></td>
      <td><?php echo $row_manage_user['bio']; ?></td>
      <td><?php echo $row_manage_user['profilpic']; ?></td>
      <td><?php echo $row_manage_user['point']; ?></td>
      <td><a href="edit_user.php?id=<?php echo $row_manage_user['id_user']; ?>">edit</a> - <a href="hapus_user.php?id=<?php echo $row_manage_user['id_user']; ?>">hapus</a></td>
    </tr>
    <?php } while ($row_manage_user = mysql_fetch_assoc($manage_user)); ?>
</table>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($manage_user);
?>
