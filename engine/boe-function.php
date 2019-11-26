<?php
// =============================================================
// == Boentil Function Core v 1.0
// == www.boentil.com
// == @author: Khakim Assidiqi <hamas182@gmail.com>
// =============================================================
include_once("boe-errormsg.php");

function xss($s){
	$s = htmlspecialchars($s);
	return $s;
}

function sql($str){	
	$rms = array("'","`","=",'"',"@","<",">","*");
	$str = str_replace($rms, '', $str);
	$str = stripcslashes($str);	
	$str = htmlspecialchars($str);
	return $str;
}
?>