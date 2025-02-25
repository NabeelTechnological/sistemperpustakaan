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
@media screen {
body {
    display: none;
}
}

@media print{
.footer {
    display: none;
}

.ref {
 color: red !important;
}
}
	.cnt {
		width: 4cm;
        height: 3cm;
		margin: 20px;
		overflow: hidden;
        text-align: center;
        border: 1px solid black;
        display: flex;
    page-break-inside: avoid;

	}

    .txt {
        margin: 0;
        padding: 0;
    }
    
    .isi{
        height: 100%;
        width: 100%;
        display: flex;
        flex-direction: column;
        align-content: space-between;
        padding: 3%;
    }

    .sub {
        flex: 1;
    }

    .subisi1 .txt{
        font-size: 55%;

    }

    .subisi2 .txt{
        font-size: 77%;

    }

    .subisi3 .txt{
        font-size: 47%;

    }

    .kotak{
        display: flex;
        flex-wrap: wrap;
    }
</style>

<div class="kotak">
<?php while (mysqli_stmt_fetch($stmt)) {

?>

<div class="cnt">
    <div class="isi">
    <div class="sub subisi1">
        <h4 class="txt">PERPUSTAKAAN SEKOLAH</h4>
        <h4 class="txt"><?= $nmsekolah ?></h4>
    </div>
    <div class="sub subisi2">
        <?php if ($dataIdJnsBuku==3) { ?>
            <h3 style="color: red;" class="txt ref">R</h3>
        <?php }else{ ?>
            <div></div>
        <?php } ?>
        <h3 class="txt"><?= $dataKode  ?></h3>
        <h3 class="txt"><?= strtoupper(substr($dataPengarang,0,3))  ?></h3>
        <h3 class="txt"><?= strtolower(substr($dataJudul,0,1)) ?></h3>
        <h3 class="txt">c.<?= $dataKodeBuku ?></h3>
    </div>
    <div class="sub subisi3" style="display: flex; justify-content:space-between; align-items:flex-end">
        <p class="txt">ID Buku = <?= $dataIdbuku?></p>
        <p class="txt">v. <?= $dataVol?></p>
    </div>
    </div>
</div>

<?php }
mysqli_stmt_close($stmt);?>
</div>
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