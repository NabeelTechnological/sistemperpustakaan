<?php 
$dataIdBukuDari = $_POST['txtIdBukuDari'];
$dataIdBukuSampai = $_POST['txtIdBukuSampai'];

if (empty($dataIdBukuDari) || empty($dataIdBukuSampai)) {
    echo "<div class='alert alert-danger alert-dismissable'>
            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
            <strong><i class='fa fa-times'></i>&nbsp; Data Tidak Boleh Kosong </strong>
            </div>";
}else{
$nmsekolah = getNmsekolah($koneksidb);
    $qry   = "SELECT idbuku,kode,judul,pengarang,vol,idjnsbuku,kodebuku FROM vw_tbuku WHERE idbuku >= ? AND idbuku <= ? AND noapk = $_SESSION[noapk] ORDER BY idbuku";
	$stmt  = mysqli_prepare($koneksidb,$qry) or die ("Gagal menyiapkan statement: " . mysqli_error($koneksidb));
	mysqli_stmt_bind_param($stmt,"ii",$dataIdBukuDari,$dataIdBukuSampai);
	mysqli_stmt_execute($stmt);
	mysqli_stmt_bind_result($stmt,$dataIdbuku,$dataKode,$dataJudul,$dataPengarang,$dataVol,$dataIdJnsBuku,$dataKodeBuku);

}

?>

<style>
	.container {
		width: 350px;
		margin: 20px;
		overflow: hidden;
        text-align: center;
        border: 1px solid black;
	}

    .cnt {
        display: flex;
        flex-wrap: wrap;
    }
</style>

<div class="cnt">
<?php while (mysqli_stmt_fetch($stmt)) {

?>

<div class="container">
    <div>
        <h4>PERPUSTAKAAN SEKOLAH</h4>
        <h4><?= $nmsekolah ?></h4>
    </div>
    <div>
        <?php if ($dataIdJnsBuku==3) { ?>
            <h3 style="color: red;">R</h3>
        <?php }else{ ?>
            <div></div>
        <?php } ?>
        <h3><?= $dataKode  ?></h3>
        <h3><?= strtoupper(substr($dataPengarang,0,3))  ?></h3>
        <h3><?= strtolower(substr($dataJudul,0,1)) ?></h3>
        <h3>c.<?= $dataKodeBuku ?></h3>
    </div>
    <div style="display: flex; justify-content:space-between">
        <p>ID Buku = <?= $dataIdbuku?></p>
        <p>v. <?= $dataVol?></p>
    </div>
</div>

<?php }
mysqli_stmt_close($stmt);?>
</div>