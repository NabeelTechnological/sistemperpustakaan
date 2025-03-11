<?php	
//Security goes here
	 
//declare variable post
// Declare variable
$datanmkota = isset($_POST['txtKota']) ? trim($_POST['txtKota']) : "";

$iduser = $_SESSION['iduser'];
$noapk  = $_SESSION['noapk'];

// Jika tombol Simpan ditekan
if (isset($_POST['btnSave'])){
    if(empty($datanmkota)){
        echo "<div class='alert alert-danger alert-dismissable'>
            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
            <strong><i class='fa fa-times'></i>&nbsp; Data Tidak Boleh Ada yang Kosong </strong>
        </div>";
    } else {
        // Insert ke database tanpa idkota karena sudah auto_increment
        $insQry = "INSERT INTO rkota(nmkota, noapk) VALUES (?, ?)";
        $stmt = mysqli_prepare($koneksidb, $insQry) or die ("Gagal menyiapkan statement: " . mysqli_error($koneksidb));
        mysqli_stmt_bind_param($stmt, "si", $datanmkota, $_SESSION['noapk']);
        mysqli_stmt_execute($stmt) or die ("Gagal Query Insert kota : " . mysqli_error($koneksidb));
        mysqli_stmt_close($stmt);

		logTransaksi($iduser, date('Y-m-d H:i:s'), 'Data Kota Ditambah', $noapk);

        echo "<div class='alert alert-success alert-dismissable'>
            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
            <strong><i class='fa fa-check'></i>&nbsp; Sukses insert kota ".$datanmkota." </strong>
        </div>";
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
		<div class="caption">Form Tambah kota</div>
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
				<label class="col-lg-2 control-label">Kota</label>
				<div class="col-lg-3">
					<input type="text" id="txtKota" name="txtKota" class="form-control sm" required/></span>
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