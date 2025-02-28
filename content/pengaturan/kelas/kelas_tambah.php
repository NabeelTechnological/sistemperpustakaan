<?php	
//Security goes here
	 
//declare variable post
$dataIdKelas		=  isset($_POST['txtSingkatanKelas']) ? $_POST['txtSingkatanKelas'] : "";
$dataDesKelas	 	=  isset($_POST['txtNamaKelas']) ? $_POST['txtNamaKelas'] : "";

if (isset($_POST['btnSave'])){
		//insert ket pinjam 
		if(empty($dataIdKelas) || empty($dataDesKelas) ){
			echo "<div class='alert alert-danger alert-dismissable'>
            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
            <strong><i class='fa fa-times'></i>&nbsp; Data Tidak Boleh Ada yang Kosong </strong>
            </div>";
		}else{
		$insQry = "insert into rkelas (idkelas, deskelas, noapk) 
		values (?,?,$_SESSION[noapk]) ";
		$stmt = mysqli_prepare($koneksidb,$insQry) or die ("Gagal menyiapkan statement: " . mysqli_error($koneksidb));
		mysqli_stmt_bind_param($stmt,"ss",$dataIdKelas, $dataDesKelas);
		mysqli_stmt_execute($stmt) or die ("Gagal Query Insert Kelas : " . mysqli_error($koneksidb));
		mysqli_stmt_close($stmt);

		echo "<div class='alert alert-success alert-dismissable'>
            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
            <strong><i class='fa fa-check'></i>&nbsp;".$dataIdKelas ."</strong> Sukses insert. 
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
		<div class="caption">Form Tambah Kelas</div>
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
				<label class="col-lg-2 control-label">Singkatan Kelas</label>
				<div class="col-lg-3">
					<input type="text" id="txtSingkatanKelas" name="txtSingkatanKelas" class="form-control sm" required/></span>
				</div>	
	    	</div> 
	    	<div class="form-group">
				<label class="col-lg-2 control-label">Nama Kelas</label>
				<div class="col-lg-3">
					<input type="text" id="txtNamaKelas" name="txtNamaKelas" class="form-control sm" required/></span>
	    		</div>
			</div>
			<footer class="panel-footer">
			    <div class="row">
			        <div class="form-group">
			            <div class="col-lg-offset-2 col-lg-10">
			                <button type="submit" name="btnSave" class="btn <?= $_SESSION['warnabar'] ?>"><i class="fa fa-save"></i> Simpan Data</button>
			                <a href="?content=kelas" class="btn <?= $_SESSION['warnabar'] ?>"><i class="fa fa-undo"></i> Kembali</a>
			            </div>
			        </div>
			    </div>
			</footer>
		</form>
	</div>
</div>