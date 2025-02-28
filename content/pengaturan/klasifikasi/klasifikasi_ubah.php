<?php	
//Security goes here


//declare variable post
if (isset($_POST['btnSave'])){
	$dataKode   	=  isset($_POST['txtKodeKlasifikasi']) ? $_POST['txtKodeKlasifikasi'] : "";
	$dataSubyek     =  isset($_POST['txtSubyek']) ? $_POST['txtSubyek'] : "";
    $dataKodeLama = isset($_POST['txtKodeLama']) ? $_POST['txtKodeLama'] : "";
$dataKodeBaru = isset($_POST['txtKodeKlasifikasi']) ? $_POST['txtKodeKlasifikasi'] : "";

		//update iduser 
		if(empty($dataKode) || empty($dataSubyek)){
			echo "<div class='alert alert-danger alert-dismissable'>
            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
            <strong><i class='fa fa-times'></i>&nbsp; Data Tidak Boleh Ada yang Kosong </strong>
            </div>";
		}else{
        $insQry = "UPDATE rsubyek SET kode = ?, subyek = ? WHERE kode = ? AND noapk = ?";
        $stmt = mysqli_prepare($koneksidb, $insQry) or die ("Gagal menyiapkan statement: " . mysqli_error($koneksidb));
        mysqli_stmt_bind_param($stmt, "ssss", $dataKodeBaru, $dataSubyek, $dataKodeLama, $_SESSION['noapk']);      
		mysqli_stmt_execute($stmt) or die ("Gagal Query Update Klasifikasi : " . mysqli_error($koneksidb));
		mysqli_stmt_close($stmt);

			echo "<div class='alert alert-success alert-dismissable'>
	            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
	            <strong><i class='fa fa-check'></i>&nbsp;".$dataKode ."</strong> Sukses diubah. 
	            </div>";	
		}				
}else {
		$txtID    = isset($_GET['id']) ? $_GET['id'] : "";
		$qryCek   = mysqli_query($koneksidb, "SELECT subyek FROM rsubyek 
								WHERE noapk = $_SESSION[noapk] AND kode = '".mysqli_real_escape_string($koneksidb, $txtID)."'	") or die('Gagal Query Cek.'. mysqli_error($koneksidb));
		if (mysqli_num_rows($qryCek)>0){
			  $rs = mysqli_fetch_array($qryCek);
		   		$dataSubyek		  = $rs['subyek'];
				$dataKode   	  = $txtID;
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
		<div class="caption">Form Ubah Klasifikasi</div>
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
				<label class="col-lg-2 control-label">Kode Klasifikasi</label>
				<div class="col-lg-3">
                     <input type="text" id="txtKodeKlasifikasi" name="txtKodeKlasifikasi" value="<?php echo $dataKode; ?>" class="form-control sm" required/>
                     <input type="hidden" name="txtKodeLama" value="<?php echo $dataKode; ?>"/>

                </span>
				</div>	
	    	</div> 
	    	<div class="form-group">
				<label class="col-lg-2 control-label">Subyek</label>
				<div class="col-lg-3">
					<input type="text" id="txtSubyek" name="txtSubyek"  class="form-control sm" value="<?php echo $dataSubyek; ?>" required/></span>
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
		</div>
		</form>
	</div>
</div>