<?php
session_start();
include "config/inc.connection.php";
include "config/inc.library.php";
	  
	
$txtUsername 		= $_POST['username'];
$txtPassword		= $_POST['password'];

$qry = "SELECT iduser, nmuser, pwduser, leveluser, noapk, wa FROM ruser WHERE iduser = ? ";
$stmt = mysqli_prepare($koneksidb,$qry) or die ("Gagal menyiapkan statement: " . mysqli_error($koneksidb));
mysqli_stmt_bind_param($stmt,"s",$txtUsername);
mysqli_stmt_execute($stmt) or die ("Gagal : " . mysqli_error($koneksidb));
mysqli_stmt_bind_result($stmt,$iduser,$nmuser,$pw,$leveluser,$noapk, $jid);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);

if(password_verify($txtPassword, $pw)){ 
	$_SESSION['iduser'] 	= $iduser;
	$_SESSION['isLogIndsiOBEbelumOk'] = '2ktuYZ639OIs';
	logTransaksi($iduser, date('Y-m-d H:i:s'), 'Masuk sistem',$noapk);

	if (isset($jid)) {
		$karakter = '0123456789';
		$otp      = substr(str_shuffle($karakter), 0, 6); //ambil 6 karakter angka random 
		mysqli_query($koneksidb, "DELETE FROM otp_expiry WHERE iduser='{$_SESSION['iduser']}'");
		//insert otp ke tabel 
		$sqlins = "INSERT INTO otp_expiry (otp,iduser) VALUE(?,?)";
		$stmt = mysqli_prepare($koneksidb, $sqlins) or die("Gagal menyiapkan statement1: " . mysqli_error($koneksidb));
		mysqli_stmt_bind_param($stmt, "ss", $otp, $iduser);
		mysqli_stmt_execute($stmt) or die("Gagal Query OTP : " . mysqli_error($koneksidb));
		mysqli_stmt_close($stmt);


		// kirim ke WA  
		$message = "Halo... Ini pesan dari web esikatERP. Nomor verikasi Login Anda adalah *$otp*";
		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://api.fonnte.com/send',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_POSTFIELDS => array(
				'target' => $jid,
				'message' => $message,
				// 'countryCode' => '62', //optional
			),
			CURLOPT_HTTPHEADER => array(
				'Authorization: k+3Ua!htJoCkBGz@i9Vg' //change TOKEN to your actual token, k+3Ua!... itu TOKEN 
			),
		));
		$response = curl_exec($curl);
		curl_close($curl);

		//forward ke halaman verifikasiotp 
		echo '<script>window.location="veri-otp.php"</script>';
		// echo '<script>window.location="admin.php"</script>';
	} else {
		$_SESSION['pesan'] = 'User belum punya no. WA. silahkan hubungi Admin';
		echo '<script>window.location="index.php"</script>';
	}
} else {
	echo '<script>window.location="index.php"</script>';
}


?>