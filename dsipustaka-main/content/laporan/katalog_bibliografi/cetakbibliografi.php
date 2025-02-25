<?php 
$kode = $_POST['txtSubyek'];
$kodeSubyek = $kode."00";
$dataSubyekQry = $kode."%";

if ($kode==NULL) {
    echo "<div class='alert alert-danger alert-dismissable'>
            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
            <strong><i class='fa fa-times'></i>&nbsp; Golongan Kode Buku Belum Dipilih </strong>
            </div>";
}else{

    $qry   = "SELECT subyek FROM ttemsubyek WHERE kode LIKE ? AND noapk = $_SESSION[noapk]";
	$stmt  = mysqli_prepare($koneksidb,$qry) or die ("Gagal menyiapkan statement: " . mysqli_error($koneksidb));
	mysqli_stmt_bind_param($stmt,"s",$kodeSubyek);
	mysqli_stmt_execute($stmt);
	mysqli_stmt_bind_result($stmt,$subyek);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    $qry   = "SELECT idbuku,kode,subyek,judul,pengarangnormal,pengarang,pengarang2,pengarang3,namapenerbit,nmkota,thterbit,cetakan,edisi,indeks,halpdh,tebal,illus,panjang,jilid,bibli,halbibli,isbn,year(tglentri) FROM vw_tbuku WHERE kode LIKE ? AND idjnsbuku <> 4 AND noapk = $_SESSION[noapk] GROUP BY judul ORDER BY kode";
	$stmt  = mysqli_prepare($koneksidb,$qry) or die ("Gagal menyiapkan statement: " . mysqli_error($koneksidb));
	mysqli_stmt_bind_param($stmt,"s",$dataSubyekQry);
	mysqli_stmt_execute($stmt);
	mysqli_stmt_bind_result($stmt,$dataIdbuku,$dataKode,$dataSubyek,$dataJudul,$dataPengarangnormal,$dataPengarang,$dataPengarang2,$dataPengarang3,$dataNamapenerbit,$dataNmKota,$dataThterbit,$dataCetakan,$dataEdisi,$dataIndeks,$dataHalpdh,$dataTebal,$dataIllus,$dataPanjang,$dataJilid,$dataBibli,$dataHalbibli,$dataIsbn,$dataTahunEntri);


}

?>

<style>
    @media screen {
        body {
            display: none;
        }
    }

    @media print{
        .footer {
            display: none;
        }
    }

	td {
		vertical-align: top;
		word-wrap: break-word;
	}

	.container {
		width: 12.5cm;
        height: 7.5cm;
		margin: 20px;
        page-break-inside: avoid;

	}
</style>

<div style="margin: 20px;">
    <h4>[<?= $kode ?>00] <?= $subyek ?></h4>
</div>

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
	<tr>
		<td colspan="2" style="padding: 0 0;"><p class="text-left"><?= $dataKode." ".ucwords(strtolower(substr($dataPengarang,0,3)))." ".strtolower(substr($dataJudul,0,1))  ?></p></td>
	</tr>
    <tr>
        <td colspan="2" style="padding: 0 15px;"><?= strtoupper($dataPengarangnormal) ?></td>
    </tr>
	<tr>
        <td style="padding: 0 20px;"></td>
		<td style="padding: 0 0;">
        <p><?= strtoupper($dataJudul) ?></p>
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
            <p><?= $dataThterbit." - ".$dataIdbuku." / ".$dataTahunEntri ?></p>
		</td>
	</tr>
</table>
</div>

<?php }
mysqli_stmt_close($stmt);?>

<script>
 (function() {

window.onload = function() {
  window.print();
  if (!isMobileDevice()) {
    setTimeout(() => {
      window.close();
      history.back();
    }, 500);
  }
};

function isMobileDevice() {
  return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
}

})();
</script>