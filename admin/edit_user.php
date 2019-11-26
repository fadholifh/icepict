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
$img = 
@$_FILES['profilpic']['name'];
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
  $updateSQL = sprintf("UPDATE `user` SET email=%s, password=%s, username=%s, fullname=%s, bio=%s, profilpic=%s, point=%s WHERE id_user=%s",
                       GetSQLValueString($_POST['email'], "text"),
                       GetSQLValueString($_POST['password'], "text"),
                       GetSQLValueString($_POST['username'], "text"),
                       GetSQLValueString($_POST['fullname'], "text"),
                       GetSQLValueString($_POST['bio'], "text"),
                     GetSQLValueString($img, "text"),
                       GetSQLValueString($_POST['point'], "int"),
                       GetSQLValueString($_POST['id_user'], "int"));

  mysql_select_db($database_konek, $konek);
  $Result1 = mysql_query($updateSQL, $konek) or die(mysql_error());
   $source = $_FILES['profilpic']['tmp_name'];
  copy($source,"../content/profilpic/".$img);
  
  $insertGoTo = "manage_user.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}
$colname_edit_user = "-1";
if (isset($_GET['id'])) {
  $colname_edit_user = $_GET['id'];
}

mysql_select_db($database_konek, $konek);
$query_edit_user = sprintf("SELECT * FROM `user` WHERE id_user = %s", GetSQLValueString($colname_edit_user, "int"));
$edit_user = mysql_query($query_edit_user, $konek) or die(mysql_error());
$row_edit_user = mysql_fetch_assoc($edit_user);
$totalRows_edit_user = mysql_num_rows($edit_user);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Edit User</title>
<link rel="stylesheet" href="../assets/css/bootstrap.min.css">
	<link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
	<div class="nav nav-bars">
		<ul class="pull-right">
			<li><a href="manage_user.php">Manage User</a></li>
			<li><a href="logout.php">Logout</a></li>
		</ul>
	</div>
    <div class="main-content">
		<div class="row">
			<div class="col-md-6 col-md-offset-3">
				<!-- Content -->
				<div class="row bg-dark photo padding">
			  <div class="col-md-12">
						<h3><strong>Ice Pict</strong> Edit User</h3>
                        
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
<form action="<?php echo $editFormAction; ?>" method="post" enctype="multipart/form-data" name="form1" id="form1">
  <table align="center" class="table">
    <tr valign="baseline">
      <td><input type="text" name="email" value="<?php echo htmlentities($row_edit_user['email'], ENT_COMPAT, 'utf-8'); ?>" size="32" class= "form-control input-sm" placeholder="email"/></td>
    </tr>
    <tr valign="baseline">
      <td><input type="text" name="password" value="<?php echo htmlentities($row_edit_user['password'], ENT_COMPAT, 'utf-8'); ?>" size="32"class= "form-control input-sm" placeholder="password" /></td>
    </tr>
    <tr valign="baseline">
      <td><input type="text" name="username" value="<?php echo htmlentities($row_edit_user['username'], ENT_COMPAT, 'utf-8'); ?>" size="32"class= "form-control input-sm" placeholder="username" /></td>
    </tr>
    <tr valign="baseline">
      <td><input type="text" name="fullname" value="<?php echo htmlentities($row_edit_user['fullname'], ENT_COMPAT, 'utf-8'); ?>" size="32"class= "form-control input-sm" placeholder="fullname" /></td>
    </tr>
    <tr valign="baseline">
      <td><input type="text" name="bio" value="<?php echo htmlentities($row_edit_user['bio'], ENT_COMPAT, 'utf-8'); ?>" size="32"class= "form-control input-sm" placeholder="bio" /></td>
    </tr>
    <tr valign="baseline">
      <td><img src="../content/photo/<?php echo htmlentities($row_edit_user['profilpic'], ENT_COMPAT, 'utf-8'); ?>" width="50px"/><input type="file" name="profilpic" value="" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td><input type="text" name="point" value="<?php echo htmlentities($row_edit_user['point'], ENT_COMPAT, 'utf-8'); ?>" size="32"class= "form-control input-sm" placeholder="point" /></td>
    </tr>
    <tr valign="baseline">
      <td><input type="submit" value="simpan" class="btn btn-success"/></td>
    </tr>
  </table>
  <input type="hidden" name="MM_update" value="form1" />
  <input type="hidden" name="id_user" value="<?php echo $row_edit_user['id_user']; ?>" />
</form>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($edit_user);
?>
