<?php	
//Security goes here


//declare variable post
if (isset($_POST['btnSave'])){
	$dataIdUser   	=  isset($_SESSION['iduser']) ? $_SESSION['iduser'] : ""; 
	$dataPhoto = NULL;
    if(isset($_FILES['txtFoto'])){
    if ($_FILES['txtFoto']['error'] == UPLOAD_ERR_OK) {
        $qry = mysqli_query($koneksidb,"SELECT photouser FROM ruser WHERE iduser = '".mysqli_real_escape_string($koneksidb, $dataIdUser)."'");
        $cek = mysqli_fetch_row($qry);
        if($cek){
            if($cek[0] != NULL){
                unlink($cek[0]);
            }
        }

        $dataPhoto = uploadFoto('txtFoto');
    }
    }

		//update iduser 
		$insQry = "update ruser set photouser = ? WHERE iduser = ?";
		$stmt = mysqli_prepare($koneksidb,$insQry) or die ("Gagal menyiapkan statement: " . mysqli_error($koneksidb));
		mysqli_stmt_bind_param($stmt,"ss", $dataPhoto, $dataIdUser);
		mysqli_stmt_execute($stmt) or die ("Gagal Query Update User : " . mysqli_error($koneksidb));
		mysqli_stmt_close($stmt);

			echo "<div class='alert alert-success alert-dismissable'>
	            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
	            <strong><i class='fa fa-check'></i>&nbsp;Photo ".$dataIdUser ."</strong> Sukses diubah. 
	            </div>";	
					
}

?>
	<SCRIPT language="JavaScript">
	function submitform() {
		document.form1.submit();
	}
	</SCRIPT>
<div class="portlet box <?= $_SESSION['warnabar'] ?>">
	<div class="portlet-title">
		<div class="caption">Ganti Photo</div>
		<div class="tools">
			<a href="javascript:;" class="collapse"></a>
			<a href="javascript:;" class="reload"></a>
			<a href="javascript:;" class="remove"></a>
		</div>
	</div>
	<div class="portlet-body form">
	<form action="<?php $_SERVER['PHP_SELF']; ?>" method="post" class="form-horizontal" role="form" autocomplete="off" name="form1" enctype="multipart/form-data">
		<div class="form-body">
			<div class="form-group">
				<label class="col-lg-2 control-label">Photo</label>
				<div class="col-lg-3">
					<input type="file" id="txtFoto" name="txtFoto" accept="image/*"/></span>
				</div>	
	    	</div> 
			<footer class="panel-footer">
			    <div class="row">
			        <div class="form-group">
			            <div class="col-lg-offset-2 col-lg-10">
			                <button type="submit" name="btnSave" class="btn <?= $_SESSION['warnabar'] ?>"><i class="fa fa-save"></i> Simpan Data</button>
			                <a href="?content=konfigurasiphoto" class="btn <?= $_SESSION['warnabar'] ?>"><i class="fa fa-undo"></i> Kembali</a>
			            </div>
			        </div>
			    </div>
			</footer>
		</form>
	</div>
</div>