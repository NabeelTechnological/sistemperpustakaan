<?php 
$dataKode = $_POST['dataKode'];
$dataPengarang = $_POST['dataPengarang'];
$dataJudul = $_POST['dataJudul'];

$dataKodeBuku = kodebuku($dataKode,$dataPengarang,$dataJudul);
echo json_encode($dataKodeBuku);

?>