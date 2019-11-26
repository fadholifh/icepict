<?php
session_start();
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_konek = "127.0.0.1";
$database_konek = "icepict";
$username_konek = "root";
$password_konek = "ffh11xx";
$konek = mysql_pconnect($hostname_konek, $username_konek, $password_konek) or trigger_error(mysql_error(),E_USER_ERROR); 


mysql_select_db($database_konek, $konek);


// version
$build_version = "v0.1.3 beta 1";
?>