<?php 
$txtID = $_POST['txtIdBuku'];
$dataTersedia = isBukuIersedia($koneksidb,$txtID);

echo json_encode($dataTersedia);
?>