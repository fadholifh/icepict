 <?php
//include "core/daftar.php";
$id_user = xss($_GET['id']);

if(!empty($_POST)){
	$username = xss($_POST['username']);
	$password = xss($_POST['password']);
	$email = xss($_POST['email']);
	$fullname = xss($_POST['fullname']); 
	
	$q = mysql_query("UPDATE user SET username = '$username', password = '$password', email = '$email', fullname = '$fullname' WHERE id_user = '$id_user'");
		if($q){
			ok("Akun anda berhasil diubah");
			red("?page=user");
		}else{
			er("Ups, Ada kesalahan!");
			red("?page=user");
		}
}

$q = mysql_query("SELECT * FROM user WHERE id_user = '$id_user'");
$f = mysql_fetch_array($q);
?>
<h2>Edit User</h2>
<form method="post" action="">
<table width="100%">
    <tr>
      <td>Username</td>
      <td><input type="text" name="username" class="form-control bordered" value="<?=$f['username']?>"/></td>
    </tr>
    <tr>
      <td>E-Mail</td>
      <td><input type="email" name="email" class="form-control bordered" value="<?=$f['email']?>"/></td>
    </tr>
    <tr>
      <td>Fullname</td>
      <td><input type="text" name="fullname" class="form-control bordered" value="<?=$f['fullname']?>"/></td>
    </tr>
    <tr>
      <td>Password</td>
      <td><input type="password" name="password" class="form-control bordered" value="<?=$f['password']?>"/></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input type="submit" value="Simpan" class="btn bordered"/></td>
    </tr>
  </table>
 </form>