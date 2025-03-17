<?php
// Security goes here

// declare variable post
if (isset($_POST['btnSave'])) {
    $dataIdUser = isset($_SESSION['iduser']) ? $_SESSION['iduser'] : "";
    $oldPassword = $_POST['txtOldPassword'];
    $newPassword = $_POST['txtNewPassword'];
    
    if (empty($oldPassword) || empty($newPassword)) {
        echo "<div class='alert alert-danger alert-dismissable'>
        <button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
        <strong><i class='fa fa-times'></i>&nbsp; Password lama dan baru tidak boleh kosong </strong>
        </div>";
    } else {
        // Ambil password lama dari database
        $qryCek = mysqli_query($koneksidb, "SELECT pwduser FROM ruser WHERE iduser = '" . mysqli_real_escape_string($koneksidb, $dataIdUser) . "'") 
            or die('Gagal Query Cek.' . mysqli_error($koneksidb));
        
        if (mysqli_num_rows($qryCek) > 0) {
            $rs = mysqli_fetch_array($qryCek);
            $dataPwdUser = $rs['pwduser'];
            
            // Validasi password lama
            if (password_verify($oldPassword, $dataPwdUser)) {
                // Hash password baru
                $dataPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                
                // Update password baru
                $insQry = "UPDATE ruser SET pwduser = ? WHERE iduser = ?";
                $stmt = mysqli_prepare($koneksidb, $insQry) or die("Gagal menyiapkan statement: " . mysqli_error($koneksidb));
                mysqli_stmt_bind_param($stmt, "ss", $dataPassword, $dataIdUser);
                mysqli_stmt_execute($stmt) or die("Gagal Query Update User : " . mysqli_error($koneksidb));
                mysqli_stmt_close($stmt);

                echo "<div class='alert alert-success alert-dismissable'>
                    <button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
                    <strong><i class='fa fa-check'></i>&nbsp;Password berhasil diubah.</strong>
                    </div>";
            } else {
                echo "<div class='alert alert-danger alert-dismissable'>
                    <button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
                    <strong><i class='fa fa-times'></i>&nbsp; Password lama tidak sesuai! </strong>
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
		<div class="caption">Ganti Password</div>
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
				<label class="col-lg-2 control-label">Password Lama</label>
				<div class="col-lg-3">
					<input type="hidden" name="pwduser" value="<?php echo $dataPwdUser; ?>">
					<input type="text" placeholder="Masukkan password Lama" id="txtOldPassword" name="txtOldPassword" required class="form-control sm"/></span>
	    		</div>
			</div>	
		<div class="form-body">
	    	<div class="form-group">
				<label class="col-lg-2 control-label">Password</label>
				<div class="col-lg-3">
					<input type="hidden" name="pwduser" value="<?php echo $dataPwdUser; ?>">
					<input type="text" placeholder="Masukkan password baru" id="txtNewPassword" name="txtNewPassword" required class="form-control sm"/></span>
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