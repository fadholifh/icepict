<?php require_once('Connections/konek.php'); ?>
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

$img = rand(11111,99999).@$_FILES['url']['name'];

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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO photo (url, tanggal, caption, filter, `id_user`) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($img, "text"),
                       GetSQLValueString($_POST['tanggal'], "date"),
					   GetSQLValueString($_POST['caption'], "text"),
					   GetSQLValueString($_POST['filter'], "text"),
                       GetSQLValueString($_POST['id_user'], "int"));

  mysql_select_db($database_konek, $konek);
  $Result1 = mysql_query($insertSQL, $konek) or die(mysql_error());
  
  $source = $_FILES['url']['tmp_name'];
  copy($source,"content/photo/".$img);

if($_FILES['url']['type'] == "image/jpeg"){
	// grayscale
	if($_POST['filter'] == "a"){
		$im = imagecreatefromjpeg("content/photo/".$img);

		if($im && imagefilter($im, IMG_FILTER_GRAYSCALE))
		{
			echo 'Image converted to grayscale.';

			imagejpeg($im, "content/photo/".$img);
		}
		else
		{
			echo 'Conversion to grayscale failed.';
		}

		imagedestroy($im);
	}
	// negate
	elseif($_POST['filter'] == "b"){
		$im = imagecreatefromjpeg("content/photo/".$img);
		
		if($im && imagefilter($im, IMG_FILTER_COLORIZE, 0, 255, 0))
		{
			echo 'Image converted to grayscale.';

			imagejpeg($im, "content/photo/".$img);
		}
		else
		{
			echo 'Conversion to grayscale failed.';
		}

		imagedestroy($im);
	}
	// alpha
	elseif($_POST['filter'] == "alpha"){
		$im = imagecreatefromjpeg("content/photo/".$img);
		
		if($im && imagefilter($im, IMG_FILTER_COLORIZE, 12, 20, 34))
		{
			echo 'Image converted to alpha.';

			imagejpeg($im, "content/photo/".$img);
		}
		else
		{
			echo 'Conversion to alpha failed.';
		}

		imagedestroy($im);
	}
	// negate function
	function negate($im){
    if(function_exists('imagefilter'))
    {
        return imagefilter($im, IMG_FILTER_NEGATE);
    }

    for($x = 0; $x < imagesx($im); ++$x)
    {
        for($y = 0; $y < imagesy($im); ++$y)
        {
            $index = imagecolorat($im, $x, $y);
            $rgb = imagecolorsforindex($index);
            $color = imagecolorallocate($im, 255 - $rgb['red'], 255 - $rgb['green'], 255 - $rgb['blue']);

            imagesetpixel($im, $x, $y, $color);
        }
    }

    return(true);
}
}





  $insertGoTo = "timeline.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

$colname_myprofile = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_myprofile = $_SESSION['MM_Username'];
}
mysql_select_db($database_konek, $konek);
$query_myprofile = sprintf("SELECT * FROM `user` WHERE username = %s", GetSQLValueString($colname_myprofile, "text"));
$myprofile = mysql_query($query_myprofile, $konek) or die(mysql_error());
$row_myprofile = mysql_fetch_assoc($myprofile);
$totalRows_myprofile = mysql_num_rows($myprofile);


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>IcePict Upload</title>
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
						<h3><strong>Ice Pict</strong> Upload</h3>
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
</head>

<body>
<form action="<?php echo $editFormAction; ?>" method="post" enctype="multipart/form-data" name="form1" id="form1">
  <table align="center">
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td><input type="file" name="url" value="" size="32" class="btn btn-default"/></td>
      <td>Size Max 1 Mb</td>
    </tr>
    <tr valign="baseline">
      <td height="85" align="right" nowrap="nowrap">&nbsp;</td>
      <td align="right" nowrap="nowrap">&nbsp;</td>
      <td><label for="caption"></label>
      <textarea name="caption" id="caption" cols="45" rows="4" class="form-control" placeholder="caption..."></textarea></td>
      <td>&nbsp;</td>
    </tr>
    <tr valign="baseline">
      <td height="30" align="right" nowrap="nowrap">&nbsp;</td>
      <td align="right" nowrap="nowrap">&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr valign="baseline">
      <td height="30" align="right" nowrap="nowrap"><div align="justify"></div></td>
      <td align="right" nowrap="nowrap">&nbsp;</td>
      <td><table width="100%">
        <tr>
          <td height="24" class="text-center">
			<img src="content/filter/grayscale.jpg" style="width: 50px; height: 50px;" class="img-rounded"><br/>
			<input type="radio" name="filter" id="radio" value="a" /> grayscale
		  </td>
          <td class="text-center">
			<img src="content/filter/negate.jpg" style="width: 50px; height: 50px;" class="img-rounded"><br/>
			<input type="radio" name="filter" id="radio2" value="b" /> negate
		  </td>
          <td class="text-center">
			<img src="content/filter/alpha.jpg" style="width: 50px; height: 50px;" class="img-rounded"><br/>
			<input type="radio" name="filter" id="radio2" value="alpha" /> alpha
		  </td>
          <td class="text-center">
			  <img src="content/filter/none.jpg" style="width: 50px; height: 50px;" class="img-rounded"><br/>
		      <input name="filter" type="radio" id="radio3" value="c" checked="checked" /> none
		  </td>
          </tr>
      </table>        <label for="filter"></label></td>
      <td>&nbsp;</td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td><button type="submit" name="button" id="button" value="upload" class="btn btn-success"><i class="fa fa-upload"></i> Upload</button></td>
      <td>&nbsp;</td>
    </tr>
  </table>
  <input type="hidden" name="tanggal" value="<?php echo date("Y-m-d"); ?>" />
  <input type="hidden" name="id_user" value="<?php echo $row_myprofile['id_user']; ?>" />
  <input type="hidden" name="MM_insert" value="form1" />
</form>
<p></p>
</body>
</html>
<?php
mysql_free_result($myprofile);
?>
