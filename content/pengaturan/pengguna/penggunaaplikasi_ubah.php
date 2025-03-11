<?php	
//Security goes here

$iduser = $_SESSION['iduser'];
$noapk  = $_SESSION['noapk'];

//declare variable post
if (isset($_POST['btnSave'])){
	$dataIdUser   	=  isset($_POST['txtIdUser']) ? $_POST['txtIdUser'] : ""; 	
	if(isset($_POST['txtPassword']) && $_POST['txtPassword'] !="")
		{ $dataPassword = password_hash($_POST['txtPassword'],PASSWORD_DEFAULT); }
	else
		{ $dataPassword = $_POST['pwduser']; }
	$dataNama     	=  isset($_POST['txtNama']) ? $_POST['txtNama'] : "";
	$dataLevel   	=  isset($_POST['txtLevel']) ? $_POST['txtLevel'] : "";

		//update iduser 
		if(empty($dataIdUser) || empty($dataPassword) || empty($dataNama) || empty($dataLevel) ){
			echo "<div class='alert alert-danger alert-dismissable'>
            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
            <strong><i class='fa fa-times'></i>&nbsp; Data Tidak Boleh Ada yang Kosong </strong>
            </div>";
		}else{
		$insQry = "update ruser set nmuser = ? , pwduser = ?, leveluser = ? WHERE iduser = ?";
		$stmt = mysqli_prepare($koneksidb,$insQry) or die ("Gagal menyiapkan statement: " . mysqli_error($koneksidb));
		mysqli_stmt_bind_param($stmt,"ssss", $dataNama, $dataPassword, $dataLevel, $dataIdUser);
		mysqli_stmt_execute($stmt) or die ("Gagal Query Update User : " . mysqli_error($koneksidb));
		mysqli_stmt_close($stmt);

		logTransaksi($iduser, date('Y-m-d H:i:s'), 'data Pengguna Diubah', $noapk);

			echo "<div class='alert alert-success alert-dismissable'>
	            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
	            <strong><i class='fa fa-check'></i>&nbsp;".$dataIdUser ."</strong> Sukses diubah. 
	            </div>";	
		}				
}else {
		$txtID    = isset($_GET['id']) ? $_GET['id'] : "";
		$qryCek   = mysqli_query($koneksidb, "SELECT iduser,nmuser,pwduser, leveluser FROM ruser 
								WHERE iduser = '".mysqli_real_escape_string($koneksidb, $txtID)."'	") or die('Gagal Query Cek.'. mysqli_error($koneksidb));
		if (mysqli_num_rows($qryCek)>0){
			  $rs = mysqli_fetch_array($qryCek);
		   		$dataNama		  = $rs['nmuser'];
			    $dataPwdUser      = $rs['pwduser']; 
				$dataLevel        = $rs['leveluser']; 
				$dataIdUser   	  = $txtID;
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
		<div class="caption">Form Ubah Pengguna</div>
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
				<label class="col-lg-2 control-label">ID User</label>
				<div class="col-lg-3">
					<input type="text" id="txtIdUser" name="txtIdUser" value="<?php echo $dataIdUser; ?>"   class="form-control sm" readonly required/></span>
				</div>	
	    	</div> 
	    	<div class="form-group">
				<label class="col-lg-2 control-label">Password</label>
				<div class="col-lg-3">
					<input type="hidden" name="pwduser" value="<?php echo $dataPwdUser; ?>">
					<input type="password" id="txtPassword" name="txtPassword"  class="form-control sm"/></span>
	    		</div>
			</div>
			<div class="form-group">
				<label class="col-lg-2 control-label">Nama</label>
				<div class="col-lg-3">
					<input type="text" id="txtNama" name="txtNama" value="<?php echo $dataNama; ?>"  class="form-control sm" required/></span>
	    		</div>
			</div>
  	    	<div class="form-group">
				<label class="col-lg-2 control-label">Hak Akses</label>
				<div class="col-lg-3">
					<select name="txtLevel"   data-placeholder="- Pilih Level -" class="select2me form-control" required>
						<option value=""></option>
						<option value="1" <?= ($dataLevel == '1') ? "selected" : "" ?>>Admin</option>
						<option value="2" <?= ($dataLevel == '2') ? "selected" : "" ?>>Operator</option>
						<option value="3" <?= ($dataLevel == '3') ? "selected" : "" ?>>Pengunjung</option>
					</select>
	      		</div>
			</div>
			<footer class="panel-footer">
			    <div class="row">	
			        <div class="form-group">
			            <div class="col-lg-offset-2 col-lg-10">
			                <button type="submit" name="btnSave" class="btn <?= $_SESSION['warnabar'] ?>"><i class="fa fa-save"></i> Simpan Data</button>
			                <a href="?content=pengguna_aplikasi" class="btn <?= $_SESSION['warnabar'] ?>"><i class="fa fa-undo"></i> Kembali</a>
			            </div>
			        </div>
			    </div>
			</footer>
		</form>
	</div>
</div>