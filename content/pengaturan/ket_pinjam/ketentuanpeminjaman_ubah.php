<?php	
//Security goes here


if (!isset($_SESSION['noapk'])) {
    die("Session noapk tidak ditemukan. Silakan login ulang.");
}

$iduser = $_SESSION['iduser'];
$noapk  = $_SESSION['noapk'];

// Declare variable post
if (isset($_POST['btnSave'])) {
    $dataIdJnsAng     = isset($_POST['txtIdJnsAng']) ? $_POST['txtIdJnsAng'] : "";
    $dataIdJnsPustaka = isset($_POST['txtIdJnsPustaka']) ? $_POST['txtIdJnsPustaka'] : "";
    $dataMaksItem     = isset($_POST['txtMaksItem']) ? $_POST['txtMaksItem'] : "";
    $dataMaksJkw      = isset($_POST['txtMaksJkw']) ? $_POST['txtMaksJkw'] : "";
    $dataPeriode      = isset($_POST['txtPeriode']) ? $_POST['txtPeriode'] : "";
    $dataDenda        = isset($_POST['txtDenda']) ? $_POST['txtDenda'] : "";
    $dataDesJenisAng  = isset($_POST['txtdesjenisang']) ? $_POST['txtdesjenisang'] : "";
    
    // Cek jika ada data yang kosong
    if (empty($dataIdJnsAng) || empty($dataIdJnsPustaka) || empty($dataMaksItem) || empty($dataMaksJkw) || empty($dataDenda)) {
        echo "<div class='alert alert-danger alert-dismissable'>
            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
            <strong><i class='fa fa-times'></i>&nbsp; Data Tidak Boleh Ada yang Kosong </strong>
            </div>";
    } else {
        // Query update
        $insQry = "UPDATE rreftrans 
                   SET maksitem = ?, maksjkw = ?, periode = ?, denda = ?, desjenisang = ? 
                   WHERE idjnsang = ? AND idjnspustaka = ? AND noapk = ?";

        $stmt = mysqli_prepare($koneksidb, $insQry);
        
        if (!$stmt) {
            die("Gagal menyiapkan statement: " . mysqli_error($koneksidb));
        }

        // Bind parameter
        mysqli_stmt_bind_param($stmt, "sssssssi", 
            $dataMaksItem, 
            $dataMaksJkw, 
            $dataPeriode, 
            $dataDenda, 
            $dataDesJenisAng, 
            $dataIdJnsAng, 
            $dataIdJnsPustaka, 
            $_SESSION['noapk']
        );

        // Eksekusi query
        mysqli_stmt_execute($stmt);

        // Cek apakah update berhasil
        if (mysqli_stmt_affected_rows($stmt) > 0) {
            echo "<div class='alert alert-success alert-dismissable'>
                <button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
                <strong><i class='fa fa-check'></i>&nbsp;</strong>Data Sukses diubah. 
                </div>";
                logTransaksi($iduser, date('Y-m-d H:i:s'), 'Ubah Data Ketentuan Peminjaman', $noapk);
                
        } else {
            echo "<div class='alert alert-warning alert-dismissable'>
                <button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
                <strong><i class='fa fa-exclamation-triangle'></i>&nbsp;</strong>Data tidak berubah atau tidak ditemukan.
                </div>";
        }

        mysqli_stmt_close($stmt);
    }
} else {
    // Jika tidak ada submit, ambil data yang akan diedit
    $txtID1 = isset($_GET['id1']) ? $_GET['id1'] : "";
    $txtID2 = isset($_GET['id2']) ? $_GET['id2'] : "";

    $qryCek = mysqli_query($koneksidb, 
        "SELECT idjnsang, idjnspustaka, maksitem, maksjkw, periode, denda 
        FROM rreftrans 
        WHERE noapk = '" . $_SESSION['noapk'] . "' 
        AND idjnsang = '" . mysqli_real_escape_string($koneksidb, $txtID1) . "' 
        AND idjnspustaka = '" . mysqli_real_escape_string($koneksidb, $txtID2) . "'"
    ) or die('Gagal Query Cek.'. mysqli_error($koneksidb));

    if (mysqli_num_rows($qryCek) > 0) {
        $rs = mysqli_fetch_array($qryCek);
        $dataIdJnsAng     = $txtID1;
        $dataIdJnsPustaka = $txtID2;
        $dataMaksItem     = $rs['maksitem'];
        $dataMaksJkw      = $rs['maksjkw'];
        $dataPeriode      = $rs['periode'];
        $dataDenda        = $rs['denda'];
    } 
 
}



?>
	<SCRIPT language="JavaScript">
	function submitform() {
		document.form1.submit();
	}
    $(document).ready(function() {
    // Setelah data berhasil diubah, reload DataTables
    $('#sample_2').DataTable().ajax.reload();
});
	</SCRIPT>
<div class="portlet box <?= $_SESSION['warnabar'] ?>">
	<div class="portlet-title">
		<div class="caption">Form Ubah Ketentuan Peminjaman</div>
		<div class="tools">
			<a href="javascript:;" class="collapse"></a>
			<a href="javascript:;" class="reload"></a>
			<a href="javascript:;" class="remove"></a>
		</div>
	</div>
	<div class="portlet-body form">
	<form action="<?php $_SERVER['PHP_SELF']; ?>" method="post" class="form-horizontal" role="form" autocomplete="off" name="form1">
		<div class="form-body">
		<div class="form-group">
                    <label class="col-lg-2 control-label">Jenis Anggota</label>
                    <div class="col-lg-3">
                        <?php
                        $dataSql = "SELECT idjnsang, desjenisang FROM rjnsang ORDER BY idjnsang";
                        $dataQry = mysqli_query($koneksidb, $dataSql) or die("Gagal Query".mysqli_error($koneksidb));

                        while ($dataRow = mysqli_fetch_array($dataQry)) {
                            $cek = ($dataIdJnsAng == $dataRow['idjnsang']) ? "checked" : "";
                            echo "<label class='radio-inline'>
                                    <input type='radio' name='txtIdJnsAng' value='{$dataRow['idjnsang']}' $cek disabled/>
                                    {$dataRow['desjenisang']}
                                  </label>";
                        }
                        ?>
                        <input type="hidden" name="txtIdJnsAng" value="<?= $dataIdJnsAng ?>">
                    </div>
                </div> 
	    	<div class="form-group">
				<label class="col-lg-2 control-label">Jenis Pustaka</label>
				<div class="col-lg-3">
				<?php
							  $dataSql = "SELECT idjnspustaka, desjnspustaka FROM rjnspustaka ORDER BY idjnspustaka ";
							  $dataQry = mysqli_query( $koneksidb, $dataSql) or die ("Gagal Query".mysqli_error($koneksidb));
							  while ($dataRow = mysqli_fetch_array($dataQry)) {
								if ($dataIdJnsPustaka == $dataRow['idjnspustaka']) {
									$cek = " checked";
								} else { $cek=""; }
								echo "<label class='radio-inline'><input type='radio' name='txtIdJnsPustaka' value='$dataRow[idjnspustaka]' $cek readonly/>$dataRow[desjnspustaka]</label>";
							  }
							  $sqlData ="";
						?>
	    		</div>
			</div>
			<div class="form-group">
				<label class="col-lg-2 control-label">Maksimum Jumlah Sewa</label>
				<div class="col-lg-3">
					<input type="number" id="txtMaksItem" name="txtMaksItem" value="<?php echo $dataMaksItem; ?>"  class="form-control sm" required/></span>
	    		</div>
			</div>
			<div class="form-group">
				<label class="col-lg-2 control-label">Maksimum Lama Sewa</label>
				<div class="col-lg-5">
					<div class="row">
				    <div class="col-xs-2">
					<input type="number" id="txtMaksJkw" name="txtMaksJkw" value="<?php echo $dataMaksJkw; ?>"  class="form-control sm" required/>
					</div>
					<div class="col-md-10">
					<label class="radio-inline"><input type="radio" name="txtPeriode" value="0" <?= ($dataPeriode==0) ? "checked" : "" ?>>Hari</label>
					<label class="radio-inline"><input type="radio" name="txtPeriode" value="1" <?= ($dataPeriode==1) ? "checked" : "" ?>>Pekan</label>
					<label class="radio-inline"><input type="radio" name="txtPeriode" value="2" <?= ($dataPeriode==2) ? "checked" : "" ?>>Bulan</label>
					<label class="radio-inline"><input type="radio" name="txtPeriode" value="3" <?= ($dataPeriode==3) ? "checked" : "" ?>>Semester</label>
					</div>
					</div>
	    		</div>
			</div>
			<div class="form-group">
				<label class="col-lg-2 control-label">Denda per Hari</label>
				<div class="col-lg-3">
					<input type="number" step="5" id="txtDenda" name="txtDenda" value="<?php echo $dataDenda; ?>"  class="form-control sm" required/></span>
	    		</div>
			</div>
			<footer class="panel-footer">
			    <div class="row">
			        <div class="form-group">
			            <div class="col-lg-offset-2 col-lg-10">
			                <button type="submit" name="btnSave" class="btn <?= $_SESSION['warnabar'] ?>"><i class="fa fa-save"></i> Simpan Data</button>
			                <a href="?content=ketentuan_peminjaman" class="btn <?= $_SESSION['warnabar'] ?>"><i class="fa fa-undo"></i> Kembali</a>
			            </div>
			        </div>
			    </div>
			</footer>
		</form>
	</div>
</div>