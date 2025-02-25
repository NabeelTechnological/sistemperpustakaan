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
    $qry   = "SELECT idbuku,kode,judul,pengarang,kodebuku FROM vw_tbuku WHERE idbuku >= ? AND idbuku <= ? AND noapk = $_SESSION[noapk] ORDER BY idbuku";
	$stmt  = mysqli_prepare($koneksidb,$qry) or die ("Gagal menyiapkan statement: " . mysqli_error($koneksidb));
	mysqli_stmt_bind_param($stmt,"ii",$dataIdBukuDari,$dataIdBukuSampai);
	mysqli_stmt_execute($stmt);
	mysqli_stmt_bind_result($stmt,$dataIdbuku,$dataKode,$dataJudul,$dataPengarang,$dataKodeBuku);

}

?>

<style>

	.tbl {
		width: 350px;
		margin: 20px;
		overflow: hidden;
        text-align: center;
	}

    .cnt {
        display: flex;
        flex-wrap: wrap;
    }
</style>

<div class="cnt">
<?php while (mysqli_stmt_fetch($stmt)) {

?>
    <table class="tbl" border="1">
        <colgroup>
            <col style="width: 34%;">
            <col style="width: 33%;">
            <col style="width: 33%;">
        </colgroup>
        <tr>
            <td colspan="3">
                <h4 class="txt">PERPUSTAKAAN</h4>
                <h4 class="txt"><?= $nmsekolah ?></h4>
            </td>
        </tr>
        <tr>
            <td colspan="2"  style="text-align: left;">
                <p class="nomor"><?=$dataIdbuku?></p>
            </td>
            <td>
                <h4 class="txt"><?= $dataKode  ?></h4>
                <h4 class="txt"><?= strtoupper(substr($dataPengarang,0,3))  ?></h4>
                <h4 class="txt"><?= strtolower(substr($dataJudul,0,1)) ?></h4>
                <h4 class="txt">c.<?= $dataKodeBuku ?></h4>
            </td>
        </tr>
    </table>



<?php }
mysqli_stmt_close($stmt);?>
</div>