<?php	
//Security goes here


//declare variable post

$iduser = $_SESSION['iduser'];
$noapk  = $_SESSION['noapk'];

if (isset($_POST['btnSave'])){
    $txtID    = isset($_POST['txtIdBuku']) ? $_POST['txtIdBuku'] : "";
    $dataKondisi   = isset($_POST['txtKondisi']) ? $_POST['txtKondisi'] : "";
    $dataTersedia = isBukuIersedia($koneksidb,$txtID);
    if($dataTersedia === 0){
        echo "<div class='alert alert-danger alert-dismissable'>
		<button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
		<strong><i class='fa fa-times'></i>&nbsp; Buku sedang dipinjam, tidak bisa diubah kondisinya</strong>
		</div>";

    }else{
        $qryCek   = "UPDATE tbuku SET tersedia = ? WHERE idbuku = ? AND noapk = $_SESSION[noapk]";
        $stmt  = mysqli_prepare($koneksidb,$qryCek) or die ("Gagal menyiapkan statement: " . mysqli_error($koneksidb));
        mysqli_stmt_bind_param($stmt,"ii",$dataKondisi,$txtID);
        mysqli_stmt_execute($stmt) or die ("Gagal query ubah kondisi buku: " . mysqli_error($koneksidb));
        mysqli_stmt_close($stmt);

        logTransaksi($iduser, date('Y-m-d H:i:s'), 'Kondisi data Buku Diubah', $noapk);

        echo "<div class='alert alert-success alert-dismissable'>
				<button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
				<strong><i class='fa fa-check'></i>&nbsp; </strong> Pengubahan kondisi sukses
				</div>";
    }	
}

if(isset($_GET['id'])){
    $txtID    = isset($_GET['id']) ? $_GET['id'] : "";
    $dataTersedia = isBukuIersedia($koneksidb,$txtID);
    if($dataTersedia === 0){
        echo "<div class='alert alert-danger alert-dismissable'>
		<button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
		<strong><i class='fa fa-times'></i>&nbsp; Buku sedang dipinjam, tidak bisa diubah kondisinya</strong>
		</div>";

    }else{
        $qryCek   = "SELECT idbuku,tersedia,kode,subyek,desjnsbuku,judul,pengarangnormal,pengarang,pengarang2,pengarang3,kodebuku,namapenerbit,thterbit FROM vw_tbuku WHERE idbuku = ? AND noapk = $_SESSION[noapk]";
        $stmt  = mysqli_prepare($koneksidb,$qryCek) or die ("Gagal menyiapkan statement: " . mysqli_error($koneksidb));
        mysqli_stmt_bind_param($stmt,"i",$txtID);
        mysqli_stmt_execute($stmt) or die ("Gagal query tampilkan data buku: " . mysqli_error($koneksidb));
        mysqli_stmt_bind_result($stmt,$dataIdbuku,$dataTersedia,$dataKode,$dataSubyek,$dataDesJnsBuku,$dataJudul,$dataPengarangnormal,$dataPengarang,$dataPengarang2,$dataPengarang3,$dataKodeBuku,$dataNamaPenerbit,$dataThterbit);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);

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
    <div class="caption">Buku Rusak / Hilang</div>
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
                        <label class="col-lg-2 control-label">ID Buku</label>
                        <div class="col-lg-3">
                            <input type="number" min="0" id="txtIdBuku" name="txtIdBuku" value="<?php echo @$dataIdbuku; ?>" class="form-control sm" oninput="updateLinkHref()" required /></span>
                        </div>
                        <div class="col-lg-1">
                            <button type="button" id="cariBuku" class="btn <?= $_SESSION['warnabar'] ?>"><i class="fa fa-search"></i> cari</button>
                        </div>
                        <label class="col-lg-2 control-label">Kondisi</label>
                        <div class="col-lg-4">
                            <select name="txtKondisi" id="txtKondisi" class="form-control" required>
                                <option value="1" <?= (@$dataTersedia==1) ? "selected" : "" ?>>Normal</option>
                                <option value="2" <?= (@$dataTersedia==2) ? "selected" : "" ?>>Rusak</option>
                                <option value="3" <?= (@$dataTersedia==3) ? "selected" : "" ?>>Hilang</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-2 control-label">Kode klasifikasi</label>
                        <div class="col-lg-5">
                            <input type="text" id="txtKode" name="txtKode" value="<?php echo @$dataKode; ?>" class="kdbuku form-control sm" readonly/></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-2 control-label">Subyek</label>
                        <div class="col-lg-5">
                            <input type="text" id="txtSubyek" name="txtSubyek" value="<?php echo @$dataSubyek; ?>" class="form-control sm" readonly/></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-2 control-label">Jenis Buku</label>
                        <div class="col-lg-5">
                        <input type="text" name="txtJnsBuku" class="form-control" value="<?= @$dataDesJnsBuku ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-2 control-label">Judul</label>
                        <div class="col-lg-5">
                            <input type="text" id="txtJudul" name="txtJudul" value="<?= (isset($dataJudul)) ? htmlspecialchars($dataJudul) : ""; ?>" class="kdbuku form-control sm" readonly /></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-2 control-label">Pengarang-1</label>
                        <div class="col-lg-5">
                            <input type="text" id="txtPengarangNormal" name="txtPengarangNormal" value="<?php echo @$dataPengarangnormal; ?>" class="form-control sm" readonly /></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-2 control-label">Pengarang-2</label>
                        <div class="col-lg-5">
                            <input type="text" id="txtpengarang2" name="txtPengarang2" value="<?= (@$dataPengarang2) ? $dataPengarang2 : ""; ?>" class="form-control sm" readonly /></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-2 control-label">Pengarang-3</label>
                        <div class="col-lg-5">
                            <input type="text" id="txtpengarang3" name="txtPengarang3" value="<?= (@$dataPengarang3) ? $dataPengarang3 : ""; ?>" class="form-control sm" readonly /></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-lg-2 control-label">Kode Buku</label>
                        <div class="col-lg-3">
                            <input type="text" id="txtKodeBuku" value="<?= (@$dataKode && @$dataPengarang && @$dataJudul) ? kodebuku($dataKode,$dataPengarang,$dataJudul) : "" ?>" class="form-control sm" readonly /></span>
                        </div>
                        <label class="col-lg-1 control-label">C</label>
                        <div class="col-lg-1">
                            <input type="number" min="0" id="c" name="txtKodeBuku" value="<?php echo @$dataKodeBuku; ?>" class="form-control sm" readonly />
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-lg-2 control-label">Penerbit</label>
                        <div class="col-lg-5">
                        <input type="text" name="txtNamaPenerbit" class="form-control" value="<?= @$dataNamaPenerbit ?>" readonly>
							</div>
                        </div>

                    <div class="form-group">
                        <label class="col-lg-2 control-label">Tahun</label>
                        <div class="col-lg-5">
                            <input type="number" min="0" id="txtTahunTerbit" name="txtTahunTerbit" value="<?= (@$dataThterbit)? $dataThterbit : "" ; ?>" class="form-control sm" readonly /></span>
                        </div>
                    </div>
        </div>

        <footer class="panel-footer">
            <div class="row">
                <div class="form-group">
                    <div class="col-lg-offset-3 col-lg-6">
                        <button type="submit" name="btnSave" class="btn <?= $_SESSION['warnabar'] ?>"><i class="fa fa-save"></i> Simpan</button>
                        <a href="?content=bukurusak" class="btn <?= $_SESSION['warnabar'] ?>"><i class="fa fa-undo"></i> Kembali</a>
                    </div>
                </div>
            </div>
        </footer>
    </form>
</div>
</div>

<script>
    document.getElementById('cariBuku').addEventListener('click', function() {
		var selectedType = document.getElementById('txtIdBuku').value; 

		var currentUrl = window.location.href;
		var newUrl;

		var regex = /[?&]id=[^&]*/g;
		var newUrl = currentUrl.replace(regex, '');

		if (newUrl.includes('?')) {
			newUrl += '&id=' + selectedType;
		} else {
			newUrl += '?id=' + selectedType;
		}

		window.history.pushState({
			path: newUrl
		}, '', newUrl);
		window.location.reload();
	});
</script>