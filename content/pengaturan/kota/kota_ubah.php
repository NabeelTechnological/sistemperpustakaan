<?php	
// Ensure database connection is established
// Assuming $koneksidb is the MySQL connection resource
$iduser = $_SESSION['iduser'];
$noapk  = $_SESSION['noapk'];
// If the save button is pressed
if (isset($_POST['btnSave'])){
	$dataIdkota = isset($_POST['txtIdKota']) ? $_POST['txtIdKota'] : "";
	$dataKota = isset($_POST['txtKota']) ? trim($_POST['txtKota']) : "";

	// Validate the city name is not empty
	if (empty($dataKota)) {
		echo "<div class='alert alert-danger alert-dismissable'>
            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
            <strong><i class='fa fa-times'></i>&nbsp; Nama Kota Tidak Boleh Kosong </strong>
            </div>";
	} else {
		// Update city name in the database
		$insQry = "UPDATE rkota SET nmkota = ? WHERE idkota = ? AND noapk = ?";
		$stmt = mysqli_prepare($koneksidb, $insQry) or die ("Gagal menyiapkan statement: " . mysqli_error($koneksidb));
		mysqli_stmt_bind_param($stmt, "sii", $dataKota, $dataIdkota, $_SESSION['noapk']);
		mysqli_stmt_execute($stmt) or die ("Gagal Query Update Kota : " . mysqli_error($koneksidb));
		mysqli_stmt_close($stmt);

		logTransaksi($iduser, date('Y-m-d H:i:s'), 'Data Kota Diubah', $noapk);

		echo "<div class='alert alert-success alert-dismissable'>
            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
            <strong><i class='fa fa-check'></i>&nbsp;</strong> Data Sukses diubah.
            </div>";
	}					
} else {
	$txtID = isset($_GET['id']) ? $_GET['id'] : "";
	// Fetch the city data from the database
	$qryCek = mysqli_query($koneksidb, "SELECT idkota, nmkota FROM rkota 
		WHERE noapk = ".$_SESSION['noapk']." AND idkota = '".mysqli_real_escape_string($koneksidb, $txtID)."'") 
		or die('Gagal Query Cek.'. mysqli_error($koneksidb));

	if (mysqli_num_rows($qryCek) > 0) {
		$rs = mysqli_fetch_array($qryCek);
		$dataIdkota = $txtID;
		$dataKota = $rs['nmkota'];
	} 
}
?>

<div class="portlet box <?= $_SESSION['warnabar'] ?>">
	<div class="portlet-title">
		<div class="caption">Form Ubah Kota</div>
	</div>
	<div class="portlet-body form">
		<form action="<?php $_SERVER['PHP_SELF']; ?>" method="post" class="form-horizontal" role="form" autocomplete="off">
			<div class="form-body">
				<div class="form-group">
					<div class="col-lg-3">
						<input type="hidden" id="txtIdKota" name="txtIdKota" class="form-control sm" value="<?= $dataIdkota ?>" readonly/>
					</div>	
				</div> 
				<div class="form-group">
					<label class="col-lg-2 control-label">Kota</label>
					<div class="col-lg-3">
						<input type="text" id="txtKota" name="txtKota" class="form-control sm" required value="<?= $dataKota ?>" />
					</div>
				</div>
			</div>
			<footer class="panel-footer">
				<div class="row">
					<div class="form-group">
						<div class="col-lg-offset-2 col-lg-10">
							<button type="submit" name="btnSave" class="btn <?= $_SESSION['warnabar'] ?>"><i class="fa fa-save"></i> Simpan Data</button>
							<a href="?content=kota" class="btn <?= $_SESSION['warnabar'] ?>"><i class="fa fa-undo"></i> Kembali</a>
						</div>
					</div>
				</div>
			</footer>
		</form>
	</div>
</div>
