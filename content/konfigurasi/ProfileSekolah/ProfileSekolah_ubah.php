<?php	
// Ensure database connection is established
// Assuming $koneksidb is the MySQL connection resource
$iduser = $_SESSION['iduser'];
$noapk  = $_SESSION['noapk'];
// If the save button is pressed
if (isset($_POST['btnSave'])){
    $dataidsekolahlama   = isset($_POST['txtidsekolahlama']) ? $_POST['txtidsekolahlama'] : "";
    // $dataidsekolahbaru   = isset($_POST['txtidsekolahbaru']) ? $_POST['txtidsekolahbaru'] : "";
	$dataidsekolahbaru = isset($_POST['txtnmsekolah']) ? trim ($_POST['txtnmsekolah']) : "";
	$dataAlamat = isset($_POST['txtalamat']) ? trim ($_POST['txtalamat']) : "";
    $dataNoTelp = isset($_POST['txtnotelp']) ? trim($_POST['txtnotelp']) : "";
    $dataNoIjin = isset($_POST['txtnoijin']) ? trim($_POST['txtnoijin']) : "";
    $dataKota = isset($_POST['txtkota']) ? trim($_POST['txtkota']) : "";
    $dataProv = isset($_POST['txtprov']) ? trim($_POST['txtprov']) : "";
    $dataKdPos = isset($_POST['txtkdpos']) ? trim($_POST['txtkdpos']) : "";

	// Validate the city name is not empty
	if(empty($dataidsekolahbaru) || empty($dataAlamat) || empty($dataNoTelp) || empty($dataNoIjin) || empty($dataKota) || empty($dataProv) || empty($dataKdPos) ){
		echo "<div class='alert alert-danger alert-dismissable'>
            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
            <strong><i class='fa fa-times'></i>&nbsp; Data Tidak Boleh Kosong </strong>
            </div>";
	} else {
		// Update city name in the database
		$insQry = "UPDATE rsekolah SET nmsekolah = ?, alamat = ?, notelp = ?, noijin = ?, kota = ?, prov = ?, kdpos = ? WHERE idsekolah = ? AND noapk = ?";
		$stmt = mysqli_prepare($koneksidb, $insQry) or die ("Gagal menyiapkan statement: " . mysqli_error($koneksidb));
		mysqli_stmt_bind_param($stmt, "ssssssssi", $dataNmSekolah, $dataAlamat, $dataNoTelp, $dataNoIjin, $dataKota, $dataProv, $dataKdPos, $dataIdsekolah, $noapk);
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
	if (!empty($txtID)) { 
		// Jalankan query 
	} else {
		echo "ID Sekolah tidak boleh kosong!";
	}
	// Fetch the city data from the database
	 $qryCek = mysqli_query($koneksidb, "SELECT idsekolah, nmsekolah, alamat, notelp, noijin, kota, prov, kdpos FROM rsekolah 
        WHERE noapk = '".mysqli_real_escape_string($koneksidb, $_SESSION['noapk'])."' 
        AND idsekolah = '".mysqli_real_escape_string($koneksidb, $txtID)."'") 
        or die('Gagal Query Cek.'. mysqli_error($koneksidb));

	if (mysqli_num_rows($qryCek) > 0) {
		$rs = mysqli_fetch_array($qryCek);
        $dataidsekolahlama = $rs['idsekolah'];
        $dataidsekolahbaru = $rs['idsekolah'];
		// $dataNmSekolah = $rs['nmsekolah'];
        $dataAlamat = $rs['alamat'];
        $dataNoTelp = $rs['notelp'];
        $dataNoIjin = $rs['noijin'];
        $datakota = $rs['kota'];
        $dataProv = $rs['prov'];
        $dataKdPos = $rs['kdpos'];
	} 
}
?>

<div class="portlet box <?= $_SESSION['warnabar'] ?>">
	<div class="portlet-title">
		<div class="caption">Form Edit profile Sekolah</div>
	</div>
	<div class="portlet-body form">
		<form action="<?php $_SERVER['PHP_SELF']; ?>" method="post" class="form-horizontal" role="form" autocomplete="off">
			<div class="form-body">
				<div class="form-group">
					<div class="col-lg-">
					<input type="hidden" name="txtidsekolahlama" value="<?= $dataidsekolahlama ?>" />
					</div>	
				</div> 
				<div class="form-group">
					<label class="col-lg-2 control-label">Nama Sekolah</label>
					<div class="col-lg-3">
					<input type="text" id="txtnmsekolah" name="txtnmsekolah" class="form-control sm" required value="<?= $dataidsekolahbaru ?>" />
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-2 control-label">Alamat</label>
					<div class="col-lg-3">
					<input type="text" id="txtalamat" name="txtalamat" class="form-control sm" required value="<?= $dataAlamat ?>" />
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-2 control-label">No Telepon</label>
					<div class="col-lg-3">
					<input type="text" id="txtnotelp" name="txtnotelp" class="form-control sm" required value="<?= $dataNoTelp ?>" />
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-2 control-label">No Ijin</label>
					<div class="col-lg-3">
					<input type="text" id="txtnoijin" name="txtnoijin" class="form-control sm" required value="<?= $dataNoIjin ?>" />
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-2 control-label">Nama Kota</label>
					<div class="col-lg-3">
					<input type="text" id="txtkota" name="txtkota" class="form-control sm" required value="<?= $datakota ?>" />
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-2 control-label">Provinsi</label>
					<div class="col-lg-3">
					<input type="text" id="txtprov" name="txtprov" class="form-control sm" required value="<?= $dataProv ?>" />
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-2 control-label">Kode Pos</label>
					<div class="col-lg-3">
                    <input type="text" id="txtkdpos" name="txtkdpos" class="form-control sm" required value="<?= $dataKdPos ?>" />
					</div>
				</div>
				
			</div>
			<footer class="panel-footer">
				<div class="row">
					<div class="form-group">
						<div class="col-lg-offset-2 col-lg-10">
							<button type="submit" name="btnSave" class="btn <?= $_SESSION['warnabar'] ?>"><i class="fa fa-save"></i> Simpan Data</button>
							<a href="?content=ProfileSekolah" class="btn <?= $_SESSION['warnabar'] ?>"><i class="fa fa-undo"></i> Kembali</a>
						</div>
					</div>
				</div>
			</footer>
		</form>
	</div>
</div>
