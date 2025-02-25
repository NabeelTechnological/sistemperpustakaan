<?php 
$dataCetakBerdasar = $_POST['txtCetakBerdasar'];
$dataIdBukuDari = $_POST['txtIdBukuDari'];
$dataIdBukuSampai = $_POST['txtIdBukuSampai'];

if (empty($dataCetakBerdasar) || empty($dataIdBukuDari) || empty($dataIdBukuSampai)) {
    echo "<div class='alert alert-danger alert-dismissable'>
            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
            <strong><i class='fa fa-times'></i>&nbsp; Data Tidak Boleh Kosong </strong>
            </div>";
}else{

    $qry   = "SELECT idbuku,kode,subyek,judul,pengarangnormal,pengarang,pengarang2,pengarang3,namapenerbit,nmkota,thterbit,cetakan,edisi,indeks,halpdh,tebal,illus,panjang,jilid,bibli,halbibli,isbn FROM vw_tbuku WHERE idbuku = ? AND noapk = $_SESSION[noapk]";
	$stmt  = mysqli_prepare($koneksidb,$qry) or die ("Gagal menyiapkan statement: " . mysqli_error($koneksidb));
	mysqli_stmt_bind_param($stmt,"i",$dataIdBukuDari);
	mysqli_stmt_execute($stmt);
	mysqli_stmt_bind_result($stmt,$dataIdbuku,$dataKode,$dataSubyek,$dataJudul,$dataPengarangnormal,$dataPengarang,$dataPengarang2,$dataPengarang3,$dataNamapenerbit,$dataNmKota,$dataThterbit,$dataCetakan,$dataEdisi,$dataIndeks,$dataHalpdh,$dataTebal,$dataIllus,$dataPanjang,$dataJilid,$dataBibli,$dataHalbibli,$dataIsbn);

}

?>

<style>
	td {
		vertical-align: top;
		word-wrap: break-word;
		padding-left: 10px;
	}

	.kodebk {
		text-align: center;
		padding-right: 20px;
	}

	.container {
		width: 12.5cm;
        height: 7.5cm;
		margin: 20px;
	}
</style>

<?php while (mysqli_stmt_fetch($stmt)) {

//capitalize words
$dataPengarangnormal = ucwords(strtolower($dataPengarangnormal));
$dataJudul = ucwords(strtolower($dataJudul));
$dataNmKota = ucwords(strtolower($dataNmKota));
$dataNamapenerbit = ucwords(strtolower($dataNamapenerbit));
$dataSubyek = ucwords(strtolower($dataSubyek));
$dataPengarang2 = ucwords(strtolower($dataPengarang2));
$dataPengarang3 = ucwords(strtolower($dataPengarang3));

?>

<div class="container">
<table>
	<?php 
	if ($dataCetakBerdasar=="judul") { ?>
	<tr>
		<td colspan="2" style="padding-left: 50px;">
		<?= "<p>$dataJudul</p>"; ?>
		</td>
	</tr>
<?php }?>
	<tr>
		<td class="kodebk"><p><?= $dataKode  ?></p></td>
		<td></td>
	</tr>
	<tr>
		<td class="kodebk"><p><?= strtoupper(substr($dataPengarang,0,3))  ?></p></td>
		<td>
			<p><?= $dataPengarangnormal ?></p>
		</td>
	</tr>
	<tr>
		<td class="kodebk"><p><?= strtolower(substr($dataJudul,0,1)) ?></p></td>
		<td>
		<p><?php 
			echo "$dataJudul/ $dataPengarangnormal. , --";
			echo ($dataEdisi!=0) ? "Ed. $dataEdisi (Cet. $dataCetakan)--," : "Cet. $dataCetakan.--, ";
			echo "$dataNmKota : $dataNamapenerbit, $dataThterbit, $dataHalpdh, $dataTebal hlm. : ";
			echo ($dataIllus!=0) ? "ilus. ; " : "";
			echo "$dataPanjang cm";
			?></p>
			<p><?= ($dataJilid!=0) ? "Seri" : "";?></p>
			<p><?php
			echo ($dataBibli!=0) ? "Bib. : hlm $dataHalbibli; " : "";
			echo ($dataIndeks!=0) ? "Indeks. <br>" : "";
			echo "ISBN : $dataIsbn";
			?></p>
			<p><?php 
			echo "1. $dataSubyek. I. Judul &emsp;";
			if($dataPengarang2!="-") {
				echo "II. $dataPengarang2 &emsp;";
				if ($dataPengarang3!="-") {
					echo "III. $dataPengarang3";
				}
			} 
			?>
			</p>
		</td>
	</tr>
</table>
</div>

<?php }
mysqli_stmt_close($stmt);?>