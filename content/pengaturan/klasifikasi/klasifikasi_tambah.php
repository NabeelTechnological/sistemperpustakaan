<?php	
//Security goes here
	 
//declare variable post
$dataKode   	=  isset($_POST['txtKodeKlasifikasi']) ? $_POST['txtKodeKlasifikasi'] : "";
$dataSubyek     =  isset($_POST['txtSubyek']) ? $_POST['txtSubyek'] : "";

$iduser = $_SESSION['iduser'];
$noapk  = $_SESSION['noapk'];

if (isset($_POST['btnSave'])){
		//insert Klasifikasi 
		if(empty($dataKode) || empty($dataSubyek) ){
			echo "<div class='alert alert-danger alert-dismissable'>
            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
            <strong><i class='fa fa-times'></i>&nbsp; Data Tidak Boleh Ada yang Kosong </strong>
            </div>";
		}else{
		$insQry = "insert into rsubyek (kode, subyek, noapk) 
		values (?, ?, $_SESSION[noapk]) ";
		$stmt = mysqli_prepare($koneksidb,$insQry) or die ("Gagal menyiapkan statement: " . mysqli_error($koneksidb));
		mysqli_stmt_bind_param($stmt,"ss",$dataKode, $dataSubyek);
		mysqli_stmt_execute($stmt) or die ("Gagal Query Insert Klasifikasi : " . mysqli_error($koneksidb));
		mysqli_stmt_close($stmt);

		logTransaksi($iduser, date('Y-m-d H:i:s'), 'Data Pengelompokkan Ditambah', $noapk);

		echo "<div class='alert alert-success alert-dismissable'>
            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
            <strong><i class='fa fa-check'></i>&nbsp;".$dataKode ."</strong> Sukses insert. 
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
		<div class="caption">Form Tambah Klasifikasi</div>
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
				<label class="col-lg-2 control-label">Pengelompokan Buku</label>
				<div class="col-lg-3">
					<input type="text" id="txtKodeKlasifikasi" name="txtKodeKlasifikasi" class="form-control sm" required/></span>
				</div>	
	    	</div> 
	    	<div class="form-group">
				<label class="col-lg-2 control-label">Subyek</label>
				<div class="col-lg-3">
					<input type="text" id="txtSubyek" name="txtSubyek" class="form-control sm" required/></span>
	    		</div>
			</div>
			
	    
			</div>
			<footer class="panel-footer">
			    <div class="row">
			        <div class="form-group">
			            <div class="col-lg-offset-2 col-lg-10">
			                <button type="submit" name="btnSave" class="btn <?= $_SESSION['warnabar'] ?>"><i class="fa fa-save"></i> Simpan Data</button>
			                <a href="?content=kode_klasifikasi" class="btn <?= $_SESSION['warnabar'] ?>"><i class="fa fa-undo"></i> Kembali</a>
			            </div>
			        </div>
			    </div>
			</footer>
		</form>
	</div>
</div>