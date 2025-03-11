<?php
session_start();
include "config/inc.connection.php";
include "config/inc.library.php";
	  
	
$txtUsername 		= $_POST['username'];
$txtPassword		= $_POST['password'];

$qry = "SELECT iduser, nmuser, pwduser, leveluser, noapk FROM ruser WHERE iduser = ? ";
$stmt = mysqli_prepare($koneksidb,$qry) or die ("Gagal menyiapkan statement: " . mysqli_error($koneksidb));
mysqli_stmt_bind_param($stmt,"s",$txtUsername);
mysqli_stmt_execute($stmt) or die ("Gagal : " . mysqli_error($koneksidb));
mysqli_stmt_bind_result($stmt,$iduser,$nmuser,$pw,$leveluser,$noapk);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);

if(password_verify($txtPassword, $pw)){ 
	$_SESSION['iduser'] 	= $iduser;
	$_SESSION['namauser'] 	= $nmuser;
	$_SESSION['isloginsukses'] = true;
    $_SESSION['kdjab'] 		= $leveluser;
    $_SESSION['noapk'] 		= $noapk;
	$_SESSION['isLogIndsiPOk1']='1jUhdsYQ9OIs'; 
 
	//$_SESSION['pesan'] = 'Selamat datang '.$login['nama'].'  di esikatERP ';
	
	logTransaksi($iduser, date('Y-m-d H:i:s'), 'Masuk sistem',$noapk);

	
	echo '<script>window.location="admin.php"</script>';
 
} else {
	echo '<script>window.location="index.php"</script>';
}


?>