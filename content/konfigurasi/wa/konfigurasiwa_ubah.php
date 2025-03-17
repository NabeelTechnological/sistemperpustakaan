<?php	
session_start(); // Pastikan session dimulai

// Periksa koneksi database
if (!isset($koneksidb)) {
    die("Koneksi database tidak ditemukan.");
}

// Pastikan user sudah login
$dataIdUser = isset($_SESSION['iduser']) ? $_SESSION['iduser'] : "";

if (isset($_POST['btnSave'])) {
    $nomorWaBaru = trim($_POST['txtWaBaru']);

    // Validasi input tidak boleh kosong
    if ($nomorWaBaru === "") {
        echo "<div class='alert alert-danger alert-dismissable'>
        <button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
        <strong><i class='fa fa-exclamation-circle'></i> Nomor WA tidak boleh kosong!</strong>
        </div>";
    } else {
        // Ambil nomor WA lama dari database
        $queryCek = mysqli_prepare($koneksidb, "SELECT wa FROM ruser WHERE iduser = ?");
        mysqli_stmt_bind_param($queryCek, "s", $dataIdUser);
        mysqli_stmt_execute($queryCek);
        mysqli_stmt_bind_result($queryCek, $nomorWaLama);
        mysqli_stmt_fetch($queryCek);
        mysqli_stmt_close($queryCek);

        // Periksa apakah nomor WA benar-benar berubah
        if ($nomorWaLama === $nomorWaBaru) {
            echo "<div class='alert alert-warning alert-dismissable'>
            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
            <strong><i class='fa fa-info-circle'></i> Nomor WA tidak berubah.</strong>
            </div>";
        } else {
            // Update nomor WA baru
            $queryUpdate = "UPDATE ruser SET wa = ? WHERE iduser = ?";
            $stmt = mysqli_prepare($koneksidb, $queryUpdate);
            mysqli_stmt_bind_param($stmt, "ss", $nomorWaBaru, $dataIdUser);
            if (mysqli_stmt_execute($stmt)) {
                echo "<div class='alert alert-success alert-dismissable'>
                <button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
                <strong><i class='fa fa-check'></i> Nomor WA berhasil diperbarui.</strong>
                </div>";
            } else {
                echo "<div class='alert alert-danger alert-dismissable'>
                <button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
                <strong><i class='fa fa-times'></i> Gagal mengupdate nomor WA.</strong>
                </div>";
            }
            mysqli_stmt_close($stmt);
        }
    }
} else {
    // Ambil data nomor WA lama
    $queryCek = mysqli_query($koneksidb, "SELECT wa FROM ruser WHERE iduser = '".mysqli_real_escape_string($koneksidb, $dataIdUser)."'");
    if ($row = mysqli_fetch_assoc($queryCek)) {
        $datawaUser = $row['wa'];
    } else {
        $datawaUser = "";
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
		<div class="caption">Ganti Nomor Wa</div>
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
				<label class="col-lg-2 control-label">Nomor Wa Lama</label>
				<div class="col-lg-3">
					<input type="text" name="pwduser" required class="form-control sm" value="<?php echo $datawaUser; ?>" readonly>
                    
					<input type="text" placeholder="Masukkan Nomor Wa Baru" id="txtWaBaru" name="txtWaBaru" required class="form-control sm"/></span>
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