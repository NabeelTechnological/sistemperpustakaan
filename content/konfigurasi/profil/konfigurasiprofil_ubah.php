<?php	
//Security goes here


//declare variable post
if (isset($_POST['btnSave'])){
	$dataIdUser   	=  isset($_SESSION['iduser']) ? $_SESSION['iduser'] : ""; 	
	$dataNama     	=  isset($_POST['txtNama']) ? $_POST['txtNama'] : "";

		//update iduser 
		if(empty($dataNama)){
			echo "<div class='alert alert-danger alert-dismissable'>
            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
            <strong><i class='fa fa-times'></i>&nbsp; Nama Tidak Boleh Kosong </strong>
            </div>";
		}else{
		$insQry = "update ruser set nmuser = ? WHERE iduser = ?";
		$stmt = mysqli_prepare($koneksidb,$insQry) or die ("Gagal menyiapkan statement: " . mysqli_error($koneksidb));
		mysqli_stmt_bind_param($stmt,"ss", $dataNama, $dataIdUser);
		mysqli_stmt_execute($stmt) or die ("Gagal Query Update User : " . mysqli_error($koneksidb));
		mysqli_stmt_close($stmt);

			echo "<div class='alert alert-success alert-dismissable'>
	            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
	            <strong><i class='fa fa-check'></i>&nbsp;Profil ".$dataIdUser ."</strong> Sukses diubah. 
	            </div>";	
		}				
}else {
		$txtID = $_SESSION['iduser'];
		$qryCek   = mysqli_query($koneksidb, "SELECT nmuser FROM ruser 
								WHERE iduser = '".mysqli_real_escape_string($koneksidb, $txtID)."'	") or die('Gagal Query Cek.'. mysqli_error($koneksidb));
		if (mysqli_num_rows($qryCek)>0){
			  $rs = mysqli_fetch_array($qryCek);
		   		$dataNama		  = $rs['nmuser'];
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
		<div class="caption">Ganti Nama Profil</div>
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
				<label class="col-lg-2 control-label">Nama Profil</label>
				<div class="col-lg-3">
					<input type="text" id="txtNama" name="txtNama" value="<?php echo $dataNama; ?>"  class="form-control sm" required/></span>
	    		</div>
			</div>
			<footer class="panel-footer">
			    <div class="row">
			        <div class="form-group">
			            <div class="col-lg-offset-2 col-lg-10">
			                <button type="submit" name="btnSave" class="btn <?= $_SESSION['warnabar'] ?>"><i class="fa fa-save"></i> Simpan Data</button>
			                <a href="admin.php" class="btn <?= $_SESSION['warnabar'] ?>"><i class="fa fa-undo"></i> Kembali</a>
			            </div>
			        </div>
			    </div>
			</footer>
		</form>
	</div>
</div>