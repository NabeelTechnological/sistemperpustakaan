<?php	
//Security goes here
	 
//declare variable post
$dataIdUser   	=  isset($_POST['txtIdUser']) ? $_POST['txtIdUser'] : "";
$dataPassword 	=  isset($_POST['txtPassword']) ? password_hash($_POST['txtPassword'],PASSWORD_DEFAULT) : "";
$dataNama     	=  isset($_POST['txtNama']) ? $_POST['txtNama'] : "";
$dataWa     	=  isset($_POST['txtWa']) ? $_POST['txtWa'] : "";
$dataLevel   =  isset($_POST['txtLevel']) ? $_POST['txtLevel'] : "";

if (isset($_POST['btnSave'])){
		//insert iduser 
		if(empty($dataIdUser) || empty($dataPassword) || empty($dataNama) || empty($dataWa) || empty($dataLevel) ){
			echo "<div class='alert alert-danger alert-dismissable'>
            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
            <strong><i class='fa fa-times'></i>&nbsp; Data Tidak Boleh Ada yang Kosong </strong>
            </div>";
		}else{
		$insQry = "insert into ruser (iduser, nmuser, pwduser, wa, leveluser, noapk) 
		values (?, ?, ?, ?, $_SESSION[noapk]) ";
		$stmt = mysqli_prepare($koneksidb,$insQry) or die ("Gagal menyiapkan statement: " . mysqli_error($koneksidb));
		mysqli_stmt_bind_param($stmt,"sssss",$dataIdUser, $dataNama, $dataPassword, $dataWa, $dataLevel);
		mysqli_stmt_execute($stmt) or die ("Gagal Query Insert User : " . mysqli_error($koneksidb));
		mysqli_stmt_close($stmt);


		echo "<div class='alert alert-success alert-dismissable'>
            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
            <strong><i class='fa fa-check'></i>&nbsp;".$dataIdUser ."</strong> Sukses insert. 
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
		<div class="caption">Form Tambah Pengguna</div>
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
					<input type="text" id="txtIdUser" name="txtIdUser" class="form-control sm" required/></span>
				</div>	
	    	</div> 
	    	<div class="form-group">
				<label class="col-lg-2 control-label">Password</label>
				<div class="col-lg-3">
					<input type="password" id="txtPassword" name="txtPassword" class="form-control sm" required/></span>
	    		</div>
			</div>
			<div class="form-group">
				<label class="col-lg-2 control-label">Wa</label>
				<div class="col-lg-3">
					<input type="text" id="txtWa" name="txtWa" class="form-control sm" required/></span>
	    		</div>
			</div>
			<div class="form-group">
				<label class="col-lg-2 control-label">Nama</label>
				<div class="col-lg-3">
					<input type="text" id="txtNama" name="txtNama"  class="form-control sm" required/></span>
	    		</div>
			</div>
	    		<div class="form-group">
				<label class="col-lg-2 control-label">Hak Akses</label>
				<div class="col-lg-3">
					<select name="txtLevel"  data-placeholder="- Pilih Level -" class="select2me form-control" required>
						<option value=""></option>
						<option value="1">Admin</option>
						<option value="2">Operator</option>
						<option value="3">Pengunjung</option>
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