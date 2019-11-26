<?php
/* ERROR AND SUCCESS HANDLING */

function errok($page, $json = false){
	// get the trigger
	$err = xss(@$_GET['err']);
	$ok = xss(@$_GET['ok']);

	// let's begin
	// register.php
	if($page == 'register' && $err == 'fill-username'){
		$msg_type 	= "warning";
		$msg 		= "Harap isi <i>username</i>.";
	}
	elseif($page == 'register' && $err == 'fill-email'){
		$msg_type 	= "warning";
		$msg 		= "Harap isi <i>E-mail</i>.";
	}
	elseif($page == 'register' && $err == 'fill-email'){
		$msg_type 	= "warning";
		$msg 		= "Harap isi <i>E-mail</i>.";
	}
	elseif($page == 'register' && $err == 'fill-password'){
		$msg_type 	= "warning";
		$msg 		= "Harap isi <i>Kata Sandi</i>.";
	}
	elseif($page == 'register' && $err == 'fill-cofrimpassword'){
		$msg_type 	= "warning";
		$msg 		= "Harap isi <i>Konfirmasi Kata Sandi</i>.";
	}
	elseif($page == 'register' && $err == 'username-toolong'){
		$msg_type 	= "warning";
		$msg 		= "<i>Username</i> terlalu panjang. Maksimal adalah 14 karakter.";
	}
	elseif($page == 'register' && $err == 'username-tooshort'){
		$msg_type 	= "warning";
		$msg 		= "<i>Username</i> terlalu pendek. Minimal adalah 3 karakter.";
	}
	elseif($page == 'register' && $err == 'username-exist'){
		$msg_type 	= "warning";
		$msg 		= "<i>Username</i> sudah dipakai. Mohon pilih username lain.";
	}
	elseif($page == 'register' && $err == 'password-mismatch'){
		$msg_type 	= "warning";
		$msg 		= "<i>Kata Sandi</i> tidak cocok. Mohon masukan ulang kata sandi anda.";
	}
	elseif($page == 'register' && $err == 'password-tooshort'){
		$msg_type 	= "warning";
		$msg 		= "<i>Kata Sandi</i> terlalu lemah. Gunakan kombinasi angka, huruf dan simbol minimal 6 karakter.";
	}
	elseif($page == 'register' && $err == 'email-invalid'){
		$msg_type 	= "warning";
		$msg 		= "Format <i>E-mail</i> tidak valid, periksa kembali pengejaan email anda.";
	}
	
	// index.php
	elseif($page == 'index' && $ok == 'email-sent'){
		$msg_type 	= "success";
		$msg 		= "Kami sudah mengirim kode verifikasi ke email anda.";
	}
	elseif($page == 'index' && $err == 'url-expired'){
		$msg_type 	= "warning";
		$msg 		= "Alamat sudah kadaluarsa.";
	}
	
	// login.php
	elseif($page == 'login' && $err == 'mismatch-combination'){
		$msg_type 	= "warning";
		$msg 		= "Kombinasi tidak cocok, mohon periksa kembali.";
	}
	elseif($page == 'login' && $ok == 'password-changed'){
		$msg_type 	= "success";
		$msg 		= "<i>Kata Sandi</i> berhasil diubah.";
	}
	
	// forgot.php
	elseif($page == 'forgot' && $err == 'fill-email'){
		$msg_type 	= "warning";
		$msg 		= "Harap isi <i>E-mail</i>.";
	}
	elseif($page == 'forgot' && $err == 'email-notfound'){
		$msg_type 	= "warning";
		$msg 		= "<i>E-mail</i> tidak ditemukan.";
	}
	elseif($page == 'forgot' && $ok == 'email-sent'){
		$msg_type 	= "success";
		$msg 		= "<i>E-mail</i> terkirim, silahkan ikuti petunjuk berikutnya.";
	}
	
	// reset.php
	elseif($page == 'reset' && $err == 'fill-password'){
		$msg_type 	= "warning";
		$msg 		= "Harap isi <i>Kata Sandi</i>.";
	}
	elseif($page == 'reset' && $err == 'fill-cofrimpassword'){
		$msg_type 	= "warning";
		$msg 		= "Harap isi <i>Konfirmasi Kata Sandi</i>.";
	}
	elseif($page == 'reset' && $err == 'password-mismatch'){
		$msg_type 	= "warning";
		$msg 		= "<i>Kata Sandi</i> tidak cocok. Mohon masukan ulang kata sandi anda.";
	}
	elseif($page == 'reset' && $err == 'password-tooshort'){
		$msg_type 	= "warning";
		$msg 		= "<i>Kata Sandi</i> terlalu lemah. Gunakan kombinasi angka, huruf dan simbol minimal 6 karakter.";
	}
	
	
	// kelengkapan.php
	elseif($page == 'kelengkapan' && $ok == 'changes-saved'){
		$msg_type 	= "success";
		$msg 		= "Perubahan tersimpan.";
	}
	elseif($page == 'kelengkapan' && $err == 'username-toolong'){
		$msg_type 	= "warning";
		$msg 		= "<i>Username</i> terlalu panjang. Maksimal adalah 14 karakter.";
	}
	elseif($page == 'kelengkapan' && $err == 'username-tooshort'){
		$msg_type 	= "warning";
		$msg 		= "<i>Username</i> terlalu pendek. Minimal adalah 3 karakter.";
	}
	elseif($page == 'kelengkapan' && $err == 'username-exist'){
		$msg_type 	= "warning";
		$msg 		= "<i>Username</i> sudah dipakai. Mohon pilih username lain.";
	}
	elseif($page == 'kelengkapan' && $err == 'bio-toolong'){
		$msg_type 	= "warning";
		$msg 		= "<i>Bio</i> maksimal adalah 300 karakter.";
	}
	elseif($page == 'kelengkapan' && $err == 'email-invalid'){
		$msg_type 	= "warning";
		$msg 		= "Format <i>E-mail</i> tidak valid, periksa kembali pengejaan email anda.";
	}
	elseif($page == 'kelengkapan' && $err == 'email-already-used'){
		$msg_type 	= "warning";
		$msg 		= "<i>E-mail</i> sudah digunakan oleh akun lain.";
	}
	
	// kelengkapan_password.php
	elseif($page == 'kelengkapan_password' && $ok == 'changes-saved'){
		$msg_type 	= "success";
		$msg 		= "Perubahan tersimpan.";
	}
	elseif($page == 'kelengkapan_password' && $err == 'fill-password'){
		$msg_type 	= "warning";
		$msg 		= "Harap isi <i>Kata Sandi Sekarang</i>.";
	}
	elseif($page == 'kelengkapan_password' && $err == 'fill-newpassword'){
		$msg_type 	= "warning";
		$msg 		= "Harap isi <i>Kata Sandi Baru</i>.";
	}
	elseif($page == 'kelengkapan_password' && $err == 'fill-newcofrimpassword'){
		$msg_type 	= "warning";
		$msg 		= "Harap isi <i>Konfirmasi Kata Sandi</i>.";
	}
	elseif($page == 'kelengkapan_password' && $err == 'newpassword-mismatch'){
		$msg_type 	= "warning";
		$msg 		= "<i>Kata Sandi Baru</i> tidak cocok. Mohon masukan ulang kata sandi baru anda.";
	}
	elseif($page == 'kelengkapan_password' && $err == 'newpassword-tooshort'){
		$msg_type 	= "warning";
		$msg 		= "<i>Kata Sandi Baru</i> terlalu lemah. Gunakan kombinasi angka, huruf dan simbol minimal 6 karakter.";
	}
	elseif($page == 'kelengkapan_password' && $err == 'current-password-wrong'){
		$msg_type 	= "warning";
		$msg 		= "<i>Kata Sandi Sekarang</i> salah, periksa kembali.";
	}
	
	// kelengkapan_photos.php
	elseif($page == 'kelengkapan_photos' && $ok == 'changes-saved'){
		$msg_type 	= "success";
		$msg 		= "Perubahan tersimpan.";
	}
	elseif($page == 'kelengkapan_photos' && $err == 'file-notallowed'){
		$msg_type 	= "danger";
		$msg 		= "File Gambar tidak diperbolehkan.";
	}
	elseif($page == 'kelengkapan_photos' && $err == 'select-your-file'){
		$msg_type 	= "info";
		$msg 		= "Pilih dokumen foto anda, tipe gambar yang diperbolehkan (jpg, jpeg, gif, png).";
	}
	
	// kelengkapan_privacy.php
	elseif($page == 'kelengkapan_privacy' && $ok == 'changes-saved'){
		$msg_type 	= "success";
		$msg 		= "Perubahan tersimpan.";
	}
	
	// profile_user.php
	elseif($page == 'profile_user' && $ok == 'followed'){
		$msg_type 	= "success";
		$msg 		= "Anda berhasil mengikuti.";
	}
	
	// timeline.php
	elseif($page == 'timeline' && $err == 'fill-comment'){
		$msg_type 	= "warning";
		$msg 		= "Harap isi komentar anda.";
	}
	
	
	// output
	if($err !== "" || $ok !== ""){
		if($json){
			$json_out = array(
				'msg_type' => $msg_type,
				'msg' => $msg
			);
			$out = json_encode($json_out);
		}else{
			$out = '<div class="alert alert-'.$msg_type.'">'.$msg.'</div>';	
		}
	
		// return
		return $out;
	}
}
?>