<?php	
session_start(); // Pastikan session dimulai sebelum digunakan

// Menangkap data dari form
$dataIdSekolah = isset($_POST['txtIdSekolah']) ? $_POST['txtIdSekolah'] : "";
$dataIdEbook = isset($_POST['txtIdEbook']) ? $_POST['txtIdEbook'] : "";
$dataJudul = isset($_POST['txtJudul']) ? $_POST['txtJudul'] : "";
$dataPenerbit = isset($_POST['txtPenerbit']) ? $_POST['txtPenerbit'] : "";
$dataPengarangNormal = isset($_POST['txtPengarang']) ? $_POST['txtPengarang'] : "";
$dataUploadTime = date("Y-m-d H:i:s"); // Auto-generate waktu upload

if (isset($_POST['btnSave'])) {
    if (empty($dataJudul) || empty($dataPenerbit) || empty($dataPengarangNormal)) {
        echo "<div class='alert alert-danger'>Data Tidak Boleh Kosong!</div>";
    } else {
        // **Upload file PDF**
        if ($_FILES['txtNmFile']['error'] === UPLOAD_ERR_NO_FILE) {
            echo "<div class='alert alert-danger'>File PDF tidak boleh kosong!</div>";
        } else {
            $allowedExtensions = ['pdf'];
            $fileExtension = strtolower(pathinfo($_FILES['txtNmFile']['name'], PATHINFO_EXTENSION));

            if (!in_array($fileExtension, $allowedExtensions)) {
                echo "<div class='alert alert-danger'>Hanya file PDF yang diperbolehkan!</div>";
            } else {
                $uploadDir = "file/";
                $filePath = $uploadDir . basename($_FILES['txtNmFile']['name']);

                if (move_uploaded_file($_FILES['txtNmFile']['tmp_name'], $filePath)) {
                    $dataNmFile = $_FILES['txtNmFile']['name'];

                    // **Pastikan session `noapk` tersedia**
                    if (!isset($_SESSION['noapk'])) {
                        echo "<div class='alert alert-danger'>Session noapk tidak tersedia!</div>";
                        exit;
                    }

                    // **Query untuk menyimpan ke database**
                    $insQry = "INSERT INTO tebook (idsekolah, idebook, nmfile, judul, penerbit, pengarangnormal, uploadtime, noapk) 
                               VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                    $stmt = mysqli_prepare($koneksidb, $insQry);

                    if ($stmt) {
                        mysqli_stmt_bind_param($stmt, "iisssssi", 
                            $dataIdSekolah, $dataIdEbook, $dataNmFile, $dataJudul, 
                            $dataPenerbit, $dataPengarangNormal, $dataUploadTime, $_SESSION['noapk']
                        );
                        $execute = mysqli_stmt_execute($stmt);

                        if ($execute) {
                            echo "<div class='alert alert-success'>Data berhasil disimpan!</div>";
                        } else {
                            echo "<div class='alert alert-danger'>Gagal menyimpan data: " . mysqli_error($koneksidb) . "</div>";
                        }

                        mysqli_stmt_close($stmt);
                    } else {
                        echo "<div class='alert alert-danger'>Gagal menyiapkan statement: " . mysqli_error($koneksidb) . "</div>";
                    }
                } else {
                    echo "<div class='alert alert-danger'>Gagal mengunggah file.</div>";
                }
            }
        }
    }
}
?>

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
	<form action="<?php $_SERVER['PHP_SELF']; ?>" method="post" class="form-horizontal" role="form" autocomplete="off" name="form1" enctype="multipart/form-data">
		<div class="form-body">
			<div class="form-group">
				<label class="col-lg-2 control-label">File PDF</label>
				<div class="col-lg-3">
                <input type="file" id="txtNmFile" name="txtNmFile" class="form-control sm" accept="application/pdf" required/></span>
				</div>	
	    	</div> 
	    	<div class="form-group">
				<label class="col-lg-2 control-label">Judul</label>
				<div class="col-lg-3">
					<input type="text" id="txtJudul" name="txtJudul" class="form-control sm" required/></span>
	    		</div>
			</div>
			<div class="form-group">
				<label class="col-lg-2 control-label">Penerbit</label>
				<div class="col-lg-3">
					<input type="text" id="txtPenerbit" name="txtPenerbit" class="form-control sm" required/></span>
	    		</div>
			</div>
			<div class="form-group">
				<label class="col-lg-2 control-label">Pengarang</label>
				<div class="col-lg-3">
					<input type="text" id="txtPengarang" name="txtPengarang"  class="form-control sm" required/></span>
	    		</div>
			</div>
			<footer class="panel-footer">
			    <div class="row">
			        <div class="form-group">
			            <div class="col-lg-offset-2 col-lg-10">
			                <button type="submit" name="btnSave" class="btn <?= $_SESSION['warnabar'] ?>"><i class="fa fa-save"></i> Simpan Data</button>
			                <a href="?content=ebook" class="btn <?= $_SESSION['warnabar'] ?>"><i class="fa fa-undo"></i> Kembali</a>
			            </div>
			        </div>
			    </div>
			</footer>
		</form>
	</div>
</div>