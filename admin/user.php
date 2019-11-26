<?php require_once('../Connections/konek.php'); ?>
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

mysql_select_db($database_konek, $konek);
$query_tmp = "SELECT * FROM `user`";
$tmp = mysql_query($query_tmp, $konek) or die(mysql_error());
$row_tmp = mysql_fetch_assoc($tmp);
$totalRows_tmp = mysql_num_rows($tmp);
?>
<!DOCTYPE HTML>
<html>
<head>
	<title>User</title>
</head>
<body>
<table border="1">
  <tr>
    <td>id_user</td>
    <td>email</td>
    <td>email_status</td>
    <td>username</td>
    <td>fullname</td>
    <td>profilpic</td>
    <td>reg_date</td>
    <td>point</td>
    <td>status</td>
  </tr>
  <?php do { ?>
    <tr>
      <td><?php echo $row_tmp['id_user']; ?></td>
      <td><?php echo $row_tmp['email']; ?></td>
      <td><?php echo $row_tmp['email_status']; ?></td>
      <td><?php echo $row_tmp['username']; ?></td>
      <td><?php echo $row_tmp['fullname']; ?></td>
      <td><img src="../content/profilpic/<?php echo $row_tmp['profilpic']; ?>" width="80px"/></td>
      <td><?php echo $row_tmp['reg_date']; ?></td>
      <td><?php echo $row_tmp['point']; ?></td>
      <td><?php echo $row_tmp['status']; ?></td>
    </tr>
    <?php } while ($row_tmp = mysql_fetch_assoc($tmp)); ?>
</table>
</body>
</html>
<?php
mysql_free_result($tmp);
?>
