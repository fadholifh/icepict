IcePict versi 0.1.3 beta 1
---------------------
bug:
- xss & sqli
- upload > bisa upload status tok
- like/ice => ajax
- admin password => md5

fixed:
- multiple username signup (fixed)
- ferivikasi (fixed)
- forgot password (fixed)
- ganti > username -> check (fixed)
- ganti > bio textarea (fixed)
- ganti > bio 300che (fixed)
- register > email check
- ganti > email => verifikasi
- ganti > password md5
- ganti > profile
- ganti > privasi
- serach > empty query 
- komentar > textarea
- timeline > jika kosong tampilkan pesan
- profil > follow url mismatch
- profil user .htaccess
- notifikasi
- komentar cannot be null
- upload > filter thumbnails


----------------
new features:
- verified account
--- nik
--- scan ktp
	
register:
status:
- unverified 
- verified (active)
- banned (temp)
- deleted (permanenet)

kurang:
- fullname --> register