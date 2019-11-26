<?php
$id_user = xss($_GET['id']);
$q = mysql_query("DELETE FROM user WHERE id_user = $id_user");
		if($q){
			ok("Akun anda berhasil dihapus");
			red("?page=user");
		}else{
			er("Ups, Ada kesalahan!");
			red("?page=user");
		}	
?>