<?php 
$txtID = $_POST['txtIdBuku'];
$qryCek   = "SELECT idbuku,judul FROM tbuku WHERE idbuku = ? AND noapk = $_SESSION[noapk]";
$stmt  = mysqli_prepare($koneksidb,$qryCek) or die ("Gagal menyiapkan statement: " . mysqli_error($koneksidb));
mysqli_stmt_bind_param($stmt,"s",$txtID);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt,$dataIdBuku,$dataJudul);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);

$cek = isIdBuku($koneksidb,$txtID);

$result = array("cek"=>$cek,"dataIdBuku"=>$dataIdBuku,"dataJudul"=>$dataJudul);

echo json_encode($result);
?>