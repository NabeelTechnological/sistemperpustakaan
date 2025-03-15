<?php
// Security measures (optional: include session checks)



// Declare variable post
$dataIdJnsAng = isset($_POST['txtIdJnsAng']) ? $_POST['txtIdJnsAng'] : "";
$datadesjenisang = isset($_POST['txtdesjenisang']) ? $_POST['txtdesjenisang'] : "";
$dataIdJnsPustaka = isset($_POST['txtIdJnsPustaka']) ? $_POST['txtIdJnsPustaka'] : "";
$dataMaksItem = isset($_POST['txtMaksItem']) ? $_POST['txtMaksItem'] : "";
$dataMaksJkw = isset($_POST['txtMaksJkw']) ? $_POST['txtMaksJkw'] : "";
$dataPeriode = isset($_POST['txtPeriode']) ? $_POST['txtPeriode'] : "";
$dataDenda = isset($_POST['txtDenda']) ? $_POST['txtDenda'] : "";

if (isset($_POST['btnSave'])) {
    if (empty($dataIdJnsAng) || empty($dataIdJnsPustaka) || empty($dataMaksItem) || empty($dataMaksJkw) || empty($dataDenda)) {
        echo "<div class='alert alert-danger'>
                <strong><i class='fa fa-times'></i> Data tidak boleh ada yang kosong.</strong>
              </div>";
    } else {
        // Cek apakah kombinasi idjnsang dan idjnspustaka sudah ada
        $cekQry = "SELECT idjnsang FROM rreftrans WHERE idjnsang = ? AND idjnspustaka = ? AND noapk = ? LIMIT 1";
        $stmtCek = mysqli_prepare($koneksidb, $cekQry);
        mysqli_stmt_bind_param($stmtCek, "ssi", $dataIdJnsAng, $dataIdJnsPustaka, $_SESSION['noapk']);
        mysqli_stmt_execute($stmtCek);
        mysqli_stmt_store_result($stmtCek);
        $jumlahData = mysqli_stmt_num_rows($stmtCek);
        mysqli_stmt_close($stmtCek);

        if ($jumlahData > 0) {
            echo "<div class='alert alert-danger'>
                    <strong><i class='fa fa-times'></i> Data untuk jenis anggota dan pustaka ini sudah ada!</strong>
                  </div>";
        } else {
            // Jika belum ada, lanjutkan insert data
            try {
                $insQry = "INSERT INTO rreftrans (idjnsang, idjnspustaka, maksitem, maksjkw, periode, denda, noapk, desjenisang) 
                           VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = mysqli_prepare($koneksidb, $insQry);
                mysqli_stmt_bind_param($stmt, "ssssssss", $dataIdJnsAng, $dataIdJnsPustaka, $dataMaksItem, $dataMaksJkw, $dataPeriode, $dataDenda, $_SESSION['noapk'], $datadesjenisang);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);

                echo "<div class='alert alert-success'>
                        <strong><i class='fa fa-check'></i> Data berhasil disimpan.</strong>
                      </div>";
            } catch (Exception $e) {
                echo "<div class='alert alert-danger'>
                        <strong><i class='fa fa-times'></i> Terjadi kesalahan saat menyimpan data.</strong>
                      </div>";
            }
        }
    }
}
?>

	<SCRIPT language="JavaScript">
	function submitform() {
		document.form1.submit();
	}
	</SCRIPT>
<div class="portlet box <?= $_SESSION['warnabar'] ?>">
	<div class="portlet-title">
		<div class="caption">Form Tambah Ketentuan Peminjaman</div>
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
							  $dataSql = "SELECT idjnsang, desjenisang FROM rjnsang ORDER BY idjnsang, desjenisang ";
							  $dataQry = mysqli_query( $koneksidb, $dataSql) or die ("Gagal Query".mysqli_error($koneksidb));
							  while ($dataRow = mysqli_fetch_array($dataQry)) {
								echo "<label class='radio-inline'><input type='radio' name='txtIdJnsAng' value='$dataRow[idjnsang]'/>$dataRow[desjenisang]</label>";
							  }
						?>
				</div>	
	    	</div> 
	    	<div class="form-group">
				<label class="col-lg-2 control-label">Jenis Pustaka</label>
				<div class="col-lg-3">
				<?php
							  $dataSql = "SELECT idjnspustaka, desjnspustaka FROM rjnspustaka ORDER BY idjnspustaka ";
							  $dataQry = mysqli_query( $koneksidb, $dataSql) or die ("Gagal Query".mysqli_error($koneksidb));
							  while ($dataRow = mysqli_fetch_array($dataQry)) {
								echo "<label class='radio-inline'><input type='radio' name='txtIdJnsPustaka' value='$dataRow[idjnspustaka]'/>$dataRow[desjnspustaka]</label>";
							  }
						?>
	    		</div>
			</div>
			<div class="form-group">
				<label class="col-lg-2 control-label">Maksimum Jumlah Sewa</label>
				<div class="col-lg-3">
					<input type="number" min="0" id="txtMaksItem" name="txtMaksItem" class="form-control sm" required/></span>
	    		</div>
			</div>
			<div class="form-group">
				<label class="col-lg-2 control-label">Maksimum Lama Sewa</label>
				<div class="col-lg-5">
					<div class="row">
				    <div class="col-xs-2">
					<input type="number" min="0" id="txtMaksJkw" name="txtMaksJkw"  class="form-control sm" required/>
					</div>
					<div class="col-md-10">
					<label class="radio-inline"><input type="radio" name="txtPeriode" value="0">Hari</label>
					<label class="radio-inline"><input type="radio" name="txtPeriode" value="1">Pekan</label>
					<label class="radio-inline"><input type="radio" name="txtPeriode" value="2">Bulan</label>
					<label class="radio-inline"><input type="radio" name="txtPeriode" value="3">Semester</label>
					</div>
					</div>
	    		</div>
			</div>
			<div class="form-group">
				<label class="col-lg-2 control-label">Denda per Hari</label>
				<div class="col-lg-3">
					<input type="number" min="0" step="100" id="txtDenda" name="txtDenda" class="form-control sm" required/></span>
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