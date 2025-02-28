<?php 
	$txtKode    = isset($_POST['txtKode']) ? $_POST['txtKode'] : "";
	$qryCek   = "SELECT subyek FROM rsubyek WHERE kode = ? AND noapk = $_SESSION[noapk]";
	$stmt  = mysqli_prepare($koneksidb,$qryCek) or die ("Gagal menyiapkan statement: " . mysqli_error($koneksidb));
	mysqli_stmt_bind_param($stmt,"i",$txtKode);
	mysqli_stmt_execute($stmt);
	mysqli_stmt_bind_result($stmt,$dataSubyek);
	mysqli_stmt_fetch($stmt);
	echo json_encode($dataSubyek);
	mysqli_stmt_close($stmt);

?>