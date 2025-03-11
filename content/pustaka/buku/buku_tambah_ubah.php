<?php
//Security goes here

if (isset($_POST['txtJnsBuku'])) {
    $idjnsbuku = $_POST['txtJnsBuku'];

    // Ambil deskripsi jenis buku berdasarkan ID
    $query = "SELECT desjnsbuku FROM rjnsbuku WHERE idjnsbuku = '$idjnsbuku'";
    $result = mysqli_query($koneksidb, $query);
    $row = mysqli_fetch_assoc($result);
    $desjnsbuku = $row['desjnsbuku'];

    // Simpan ke tabel lain
    $insertQuery = "INSERT INTO tbuku (idjnsbuku, desjnsbuku) VALUES ('$idjnsbuku', '$desjnsbuku')";
    mysqli_query($koneksidb, $insertQuery) or die("Gagal menyimpan data: " . mysqli_error($koneksidb));
}

$iduser = $_SESSION['iduser'];
$noapk  = $_SESSION['noapk'];

if (isset($_POST['btnSave'])) {
	//declare variable post
	$dataIdBuku  			=  isset($_POST['txtIdBuku']) ? $_POST['txtIdBuku'] : "";
	$dataKode 				=  isset($_POST['txtKode']) ? $_POST['txtKode'] : "";
	$dataSubyek             =  isset($_POST['txtsubyek']) ? $_POST['txtsubyek'] : "";
	$dataJnsBuku  			=  isset($_POST['txtJnsBuku']) ? $_POST['txtJnsBuku'] : "";
	$dataJudul  			=  isset($_POST['txtJudul']) ? $_POST['txtJudul'] : "";
	$dataPengarangNormal  	=  isset($_POST['txtPengarangNormal']) ? $_POST['txtPengarangNormal'] : "";
	$dataPengarang	 		=  isset($_POST['txtPengarang']) ? $_POST['txtPengarang'] : "";
	$dataPengarang2			=  isset($_POST['txtPengarang2']) ? $_POST['txtPengarang2'] : "";
	$dataPengarang3			=  isset($_POST['txtPengarang3']) ? $_POST['txtPengarang3'] : "";
	$dataKodeBuku			=  isset($_POST['txtKodeBuku']) ? $_POST['txtKodeBuku'] : "";
	$dataNamaPenerbit		=  isset($_POST['txtNamaPenerbit']) ? $_POST['txtNamaPenerbit'] : "";
	$dataNmKota				=  isset($_POST['txtNmKota']) ? $_POST['txtNmKota'] : "";
	$dataTahunTerbit		=  isset($_POST['txtTahunTerbit']) ? $_POST['txtTahunTerbit'] : "";
	$dataNmBahasa			=  isset($_POST['txtNmBahasa']) ? $_POST['txtNmBahasa'] : "";
	$dataNmasalbuku			=  isset($_POST['txtNmAsalBuku']) ? $_POST['txtNmAsalBuku'] : "";
	$dataCetakan        	=  isset($_POST['txtCetakan']) ? $_POST['txtCetakan'] : "";
	$dataEdisi        		=  isset($_POST['txtEdisi']) ? $_POST['txtEdisi'] : "";
	$dataVol        		=  isset($_POST['txtVol']) ? $_POST['txtVol'] : "";
	$dataIndeks        		=  isset($_POST['txtIndeks']) ? $_POST['txtIndeks'] : 0;
	$dataHalPdh        		=  isset($_POST['txtHalPdh']) ? $_POST['txtHalPdh'] : "";
	$dataTebal        		=  isset($_POST['txtTebal']) ? $_POST['txtTebal'] : "";
	$dataIllus        		=  isset($_POST['txtIllus']) ? $_POST['txtIllus'] : 0;
	$dataPanjang        	=  isset($_POST['txtPanjang']) ? $_POST['txtPanjang'] : "";
	$dataJilid        		=  isset($_POST['txtJilid']) ? $_POST['txtJilid'] : "";
	$dataBibli        		=  isset($_POST['txtBibli']) ? $_POST['txtBibli'] : 0;
	$dataHalBibli        	=  isset($_POST['txtHalBibli']) ? $_POST['txtHalBibli'] : "";
	$dataIsbn        		=  isset($_POST['txtIsbn']) ? $_POST['txtIsbn'] : "";
	$dataLokasi        		=  isset($_POST['txtLokasi']) ? $_POST['txtLokasi'] : "";
	
	$dataCover1 = "";
	$dataNmFile = "";
    if(isset($_FILES['txtCover1'])){
    if ($_FILES['txtCover1']['error'] == UPLOAD_ERR_OK) {
        $qry = mysqli_query($koneksidb,"SELECT cover1 FROM tbuku WHERE idbuku = '$dataIdBuku' AND noapk = $_SESSION[noapk]");
        $cek = mysqli_fetch_row($qry);
        if($cek){
            if($cek[0] != NULL){
                unlink($cek[0]);
            }
        }
        $dataCover1 = uploadFoto('txtCover1');
		$dataNmFile = basename($dataCover1);
    }
    }

	//insert idpenerbit 
	if (
		empty($dataIdBuku) || empty($dataKode) || empty($dataSubyek) || empty($dataJnsBuku)
		|| empty($dataJudul) || empty($dataPengarangNormal)
		|| empty($dataPengarang) || empty($dataKodeBuku) 
		|| empty($dataNamaPenerbit)	|| empty($dataNmKota) 
		|| empty($dataTahunTerbit) || empty($dataNmBahasa)
		|| empty($dataCetakan) || empty($dataEdisi) 
	    || empty($dataHalPdh) 
		|| empty($dataTebal) || empty($dataPanjang)
		|| empty($dataIsbn)
		|| empty($dataLokasi)
	) {
		echo "<div class='alert alert-danger alert-dismissable'>
            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
            <strong><i class='fa fa-times'></i>&nbsp; Data Tidak Boleh Kosong </strong>
            </div>";
	} else {

		$cek = isIdBuku($koneksidb,$dataIdBuku);

		if($cek > 0){
			$insQry = "UPDATE tbuku SET kode = ?, idjnsbuku = ?, desjnsbuku = ?, judul = ?, pengarangnormal = ?, pengarang = ?, pengarang2 = ?, pengarang3 = ?, kodebuku = ?, Namapenerbit = ?, nmkota = ?, thterbit = ?, nmbahasa = ?, nmasalbuku = ?, cetakan = ?, edisi = ?, vol = ?, indeks = ?, halpdh = ?, tebal = ?, illus = ?, panjang = ?, jilid = ?, bibli = ?, halbibli = ?, isbn = ?, lokasi = ?, tglentri=CURDATE(), Cover1 = ?, nmfile = ?, jmlcopy = ?
			WHERE idbuku = ? AND noapk = $_SESSION[noapk]";
			$stmt = mysqli_prepare($koneksidb, $insQry) or die("Gagal menyiapkan statement: " . mysqli_error($koneksidb));
			mysqli_stmt_bind_param($stmt, "sssssssssssssssssssssssssssssss", $dataKode, $dataJnsBuku, $desjnsbuku, $dataJudul, $dataPengarangNormal, $dataPengarang, $dataPengarang2, $dataPengarang3, $dataKodeBuku, $dataNamaPenerbit, $dataNmKota, $dataTahunTerbit, $dataNmBahasa, $dataNmasalbuku, $dataCetakan,$dataEdisi,$dataVol,$dataIndeks,$dataHalPdh,$dataTebal,$dataIllus,$dataPanjang,$dataJilid,$dataBibli,$dataHalBibli,$dataIsbn,$dataLokasi,$dataCover1,$dataNmFile,$dataKodeBuku,$dataIdBuku);
			mysqli_stmt_execute($stmt) or die("Gagal Query Update Buku : " . mysqli_error($koneksidb));
			mysqli_stmt_close($stmt);

			logTransaksi($iduser, date('Y-m-d H:i:s'), 'Data Buku Ditambah/diubah', $noapk);

			echo "<div class='alert alert-success alert-dismissable'>
				<button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
				<strong><i class='fa fa-check'></i>&nbsp;</strong>Data Sukses edit. 
				</div>";

		}else{
			$insQry = "INSERT INTO tbuku (
				idbuku, kode, idjnsbuku, desjnsbuku, judul, pengarangnormal, pengarang, pengarang2, pengarang3, 
				kodebuku, idpenerbit,  namapenerbit, nmkota, thterbit, nmbahasa, nmasalbuku, cetakan, edisi, vol, 
				indeks, halpdh, tebal, illus, panjang, jilid, bibli, halbibli, isbn, lokasi, 
				tglentri, tersedia, Cover1, nmfile, jmlcopy, noapk
			) 
			VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, CURDATE(), 1, ?, ?, ?, ?)
			";
$stmt = mysqli_prepare($koneksidb, $insQry) or die("Gagal menyiapkan statement: " . mysqli_error($koneksidb));

// Pastikan jumlah parameter sesuai
mysqli_stmt_bind_param($stmt, "sssssssssssssssssssssssssssssssss", 
    $dataIdBuku, 
    $dataKode, 
    $dataJnsBuku, 
	$desjnsbuku,
    $dataJudul, 
    $dataPengarangNormal, 
    $dataPengarang, 
    $dataPengarang2, 
    $dataPengarang3, 
    $dataKodeBuku, 
	$dataidpenerbit,
    $dataNamaPenerbit, 
    $dataNmKota, 
    $dataTahunTerbit, 
    $dataNmBahasa, 
    $dataNmasalbuku, 
    $dataCetakan, 
    $dataEdisi, 
    $dataVol, 
    $dataIndeks, 
    $dataHalPdh, 
    $dataTebal, 
    $dataIllus, 
    $dataPanjang, 
    $dataJilid, 
    $dataBibli, 
    $dataHalBibli, 
    $dataIsbn, 
    $dataLokasi, 
    $dataCover1, 
    $dataNmFile, 
    $dataJmlCopy, 
    $_SESSION['noapk']
);

// Pastikan Anda memiliki variabel yang sesuai untuk setiap parameter
if (!mysqli_stmt_execute($stmt)) {
    die("Gagal Query Insert Buku : " . mysqli_error($koneksidb));
}
mysqli_stmt_close($stmt);

		}

	}
}
if (isset($_GET['id'])) {
	$txtID    = isset($_GET['id']) ? $_GET['id'] : "";
	$qryCek   = "SELECT idbuku,kode,subyek,idjnsbuku,desjnsbuku,judul,pengarangnormal,pengarang,pengarang2,pengarang3,kodebuku,idpenerbit,nmkota,thterbit,nmbahasa,kdasalbuku,cetakan,edisi,vol,indeks,halpdh,tebal,illus,panjang,jilid,bibli,halbibli,isbn,lokasi FROM vw_tbuku WHERE idbuku = ? AND noapk = $_SESSION[noapk]";
	$stmt  = mysqli_prepare($koneksidb,$qryCek) or die ("Gagal menyiapkan statement: " . mysqli_error($koneksidb));
	mysqli_stmt_bind_param($stmt,"i",$txtID);
	mysqli_stmt_execute($stmt);
	mysqli_stmt_bind_result($stmt,$dataIdbuku,$dataKode,$dataSubyek,$dataDesjnsbuku,$desjnsbuku,$dataJudul,$dataPengarangnormal,$dataPengarang,$dataPengarang2,$dataPengarang3,$dataKodeBuku,$dataNamapenerbit,$dataNmkota,$dataThterbit,$dataNmbahasa,$dataNmasalbuku,$dataCetakan,$dataEdisi,$dataVol,$dataIndeks,$dataHalpdh,$dataTebal,$dataIllus,$dataPanjang,$dataJilid,$dataBibli,$dataHalbibli,$dataIsbn,$dataLokasi);
	mysqli_stmt_fetch($stmt);
	mysqli_stmt_close($stmt);

}

if (isset($_POST['btnSaveEntriMasal'])) {
	$txtIdBukuMasal = $_POST['txtIdBukuMasal'];
	$txtJmlMasal = $_POST['txtJmlMasal'];
	$txtHasilDari = $_POST['txtHasilDari'];
	
	if(empty($txtIdBukuMasal) || $txtJmlMasal<1){
		echo "<div class='alert alert-danger alert-dismissable'>
		<button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
		<strong><i class='fa fa-times'></i>&nbsp; Data Tidak Boleh Kosong </strong>
		</div>";
	}else{
		$qryCek   = "SELECT idbuku,kode,idjnsbuku,desjnsbuku,judul,pengarangnormal,pengarang,pengarang2,pengarang3,kodebuku,subyek,namapenerbit,nmkota,thterbit,nmbahasa,nmasalbuku,cetakan,edisi,vol,indeks,halpdh,tebal,illus,panjang,jilid,bibli,halbibli,isbn,lokasi,Cover1,nmfile FROM vw_tbuku WHERE idbuku = ? AND noapk = $_SESSION[noapk]";
		$stmt  = mysqli_prepare($koneksidb,$qryCek) or die ("Gagal menyiapkan statement: " . mysqli_error($koneksidb));
		mysqli_stmt_bind_param($stmt,"s",$txtIdBukuMasal);
		mysqli_stmt_execute($stmt) or die ("Gagal query ambil data: " . mysqli_error($koneksidb));
		mysqli_stmt_bind_result($stmt,$dataIdBuku,$dataKode,$dataJnsBuku,$desjnsbuku,$dataJudul,$dataPengarangNormal,$dataPengarang,$dataPengarang2,$dataPengarang3,$dataKodeBuku,$dataSubyek,$dataNamaPenerbit,$dataNmKota,$dataTahunTerbit,$dataNmBahasa,$dataNmasalbuku,$dataCetakan,$dataEdisi,$dataVol,$dataIndeks,$dataHalPdh,$dataTebal,$dataIllus,$dataPanjang,$dataJilid,$dataBibli,$dataHalBibli,$dataIsbn,$dataLokasi,$dataCover1,$dataNmFile);
		mysqli_stmt_fetch($stmt);
		mysqli_stmt_close($stmt);
		for ($i=0; $i < $txtJmlMasal ; $i++) { 
			$dataKodeBuku++;


			// $dataKodeBuku = $txtHasilDari + $i++;
			//$dataKodeBuku = $txtHasilDari + $i++; // Menghasilkan ID buku yang unik

$insQry = "insert into tbuku (idbuku, kode, idjnsbuku, desjnsbuku, judul, pengarangnormal, pengarang, pengarang2, pengarang3, kodebuku, namapenerbit, nmkota, thterbit, nmbahasa, nmasalbuku, cetakan, edisi, vol, indeks, halpdh, tebal, illus, panjang, jilid, bibli, halbibli, isbn, lokasi, tglentri, tersedia, Cover1, nmfile, jmlcopy, noapk) 
			values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, CURDATE(), 1,?,?,?,$_SESSION[noapk]) ";
			$stmt = mysqli_prepare($koneksidb, $insQry) or die("Gagal menyiapkan statement: " . mysqli_error($koneksidb));
			mysqli_stmt_bind_param($stmt, "sssssssssssssssssssssssssssssss", $txtHasilDari, $dataKode, $dataJnsBuku, $desjnsbuku, $dataJudul, $dataPengarangNormal, $dataPengarang, $dataPengarang2, $dataPengarang3, $dataKodeBuku, $dataNamaPenerbit, $dataNmKota, $dataTahunTerbit, $dataNmBahasa, $dataNmasalbuku, $dataCetakan,$dataEdisi,$dataVol,$dataIndeks,$dataHalPdh,$dataTebal,$dataIllus,$dataPanjang,$dataJilid,$dataBibli,$dataHalBibli,$dataIsbn,$dataLokasi,$dataCover1,$dataNmFile,$dataKodeBuku);
			mysqli_stmt_execute($stmt) or die("Gagal Query Insert Buku Masal : " . mysqli_error($koneksidb));
			mysqli_stmt_close($stmt);
			
			$txtHasilDari++;

			logTransaksi($iduser, date('Y-m-d H:i:s'), 'Buku di Entri Masal', $noapk);
		}

		echo "<div class='alert alert-success alert-dismissable'>
			<button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
			<strong><i class='fa fa-check'></i>&nbsp;</strong>Data Sukses insert masal. 
			</div>";
	}
}

?>
<SCRIPT language="JavaScript">
	function submitform() {
		document.form1.submit();
	}
</SCRIPT>

<div id="pesan"></div>
<div class="portlet box <?= $_SESSION['warnabar'] ?>">
	<div class="portlet-title">
		<div class="caption">Tambah / Edit Buku</div>
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
					<label class="col-lg-3 control-label">ID Buku Terakhir : <?= getIdBukuTerakhir($koneksidb) ?></label>
				</div>
				<div class="row">
					<div class="col-lg-6">
						<div class="form-group">
							<label class="col-lg-4 control-label">ID Buku</label>
							<div class="col-lg-5">
								<input type="number" min="0" id="txtIdBuku" name="txtIdBuku" value="<?php echo @$dataIdbuku; ?>" class="form-control sm" oninput="updateLinkHref()" required /></span>
							</div>
							<div class="col-lg-3">
								<button type="button" id="cariBuku" class="btn <?= $_SESSION['warnabar'] ?>"><i class="fa fa-search"></i> cari</button>
							</div>
						</div>
						<!-- <div class="form-group">
							<label class="col-lg-4 control-label">Kode klasifikasi</label>
							 <div class="col-lg-8">
								<input type="text" id="txtKode" name="txtKode" value="<?php echo @$dataKode; ?>" class="kdbuku form-control sm" /></span>
							</div> 
													
						</div> -->

						<div class="form-group">
						<label class="col-lg-4 control-label">Kode Klasifikasi</label>
						<div class="col-lg-8">
							<select id="txtKode" name="txtKode" value="<?php echo @$dataKode; ?>" data-placeholder="- Pilih kode klasifikasi -" class="select2me form-control kdbuku sm" class="kdbuku form-control sm" required>
								<option value="<?php echo @$dataKode; ?>"></option>
								<?php
								$dataSql = "SELECT kode, subyek FROM rsubyek WHERE noapk = $_SESSION[noapk] ORDER BY id";
								$dataQry = mysqli_query($koneksidb, $dataSql) or die("Gagal Query: " . mysqli_error($koneksidb));
								while ($dataRow = mysqli_fetch_array($dataQry)) {
									$cek = (@$dataKode == $dataRow['kode']) ? "selected" : "";
									// echo "<option value='{$dataRow['kode']}' $cek>{$dataRow['kode']}</option>";
									echo "<option value='{$dataRow['kode']}' data-subyek='{$dataRow['subyek']}' $selected>{$dataRow['kode']}</option>";
								}
								?>
							</select>
						</div>
					</div>


						<div class="form-group">
							<label class="col-lg-4 control-label">Subyek</label>
							<div class="col-lg-8">
								<input type="text" id="txtsubyek" name="txtsubyek" value="<?php echo @$dataSubyek; ?>" class="form-control sm" readonly/></span>
							</div>
						</div>

								<script>$(document).ready(function() {
    // Function to update subyek field based on selected kode
    function updateSubyek(kode) {
        var selectedOption = $('#txtKode option[value="' + kode + '"]');
        var subyek = selectedOption.data('subyek');
        $('#txtsubyek').val(subyek);
    }

    // On page load, update subyek if there's already a selected kode
    var initialKode = $('#txtKode').val();
    if (initialKode) {
        updateSubyek(initialKode);
    }

    // When the kode is changed, update the subyek field accordingly
    $('#txtKode').on('change', function() {
        var selectedKode = $(this).val();
        updateSubyek(selectedKode);
    });
});
</script>

						<div class="form-group">
							<label class="col-lg-4 control-label">Jenis Buku</label>
							<div class="col-lg-8">
								<select name="txtJnsBuku" data-placeholder="- Pilih Jenis Buku -" class="select2me form-control" required>
									<option value=""></option>
									<?php
									$dataSql = "SELECT idjnsbuku, desjnsbuku FROM rjnsbuku ORDER BY desjnsbuku ";
									$dataQry = mysqli_query($koneksidb, $dataSql) or die("Gagal Query" . mysqli_error($koneksidb));
									while ($dataRow = mysqli_fetch_array($dataQry)) {
										if (@$dataDesjnsbuku == $dataRow['idjnsbuku']) {
											$cek = " selected";
										} else {
											$cek = "";
										}
										echo "<option value='$dataRow[idjnsbuku]' $cek>$dataRow[desjnsbuku]</option>";
									}
									$sqlData = "";
									?>
								</select>
								
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-4 control-label">Judul</label>
							<div class="col-lg-8">
								<input type="text" id="txtJudul" name="txtJudul" value="<?= (isset($dataJudul)) ? htmlspecialchars($dataJudul) : ""; ?>" class="kdbuku form-control sm" required /></span>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-4 control-label">Pengarang-1</label>
							<div class="col-lg-8">
								<input type="text" id="txtPengarangNormal" name="txtPengarangNormal" placeholder="Isikan Normal" value="<?php echo @$dataPengarangnormal; ?>" class="form-control sm" required /></span>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-4 control-label">MARGA</label>
							<div class="col-lg-8">
								<input type="text" id="txtPengarang" name="txtPengarang" placeholder="" value="<?php echo @$dataPengarang; ?>" class="kdbuku form-control sm" readonly required /></span>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-4 control-label">Pengarang-2</label>
							<div class="col-lg-8">
								<input type="text" id="txtpengarang2" name="txtPengarang2" placeholder="Isikan Normal" value="<?= (@$dataPengarang2) ? $dataPengarang2 : "-"; ?>" class="form-control sm" required /></span>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-4 control-label">Pengarang-3</label>
							<div class="col-lg-8">
								<input type="text" id="txtpengarang3" name="txtPengarang3" placeholder="Isikan Normal" value="<?= (@$dataPengarang3) ? $dataPengarang3 : "-"; ?>" class="form-control sm" required /></span>
							</div>
						</div>

						<div class="form-group">
							<label class="col-lg-4 control-label">Kode Buku</label>
							<div class="col-lg-5">
								<input type="text" id="txtKodeBuku" value="<?= (@$dataKode && @$dataPengarang && @$dataJudul) ? kodebuku($dataKode,$dataPengarang,$dataJudul) : "" ?>" class="form-control sm" readonly required /></span>
							</div>
							<label class="col-lg-1 control-label">C</label>
							<div class="col-lg-2">
								<input type="number" min="0" id="c" name="txtKodeBuku" value="<?php echo @$dataKodeBuku; ?>" class="form-control sm" readonly/>
							</div>
						</div>

						<div class="form-group">
							<label class="col-lg-4 control-label">Penerbit</label>
							<div class="col-lg-8">
								<select name="txtNamaPenerbit" data-placeholder="- Pilih Penerbit -" class="select2me form-control" required>
									<option value=""></option>
									<?php
									$dataSql = "SELECT idpenerbit, namapenerbit FROM rpenerbit WHERE noapk = $_SESSION[noapk] ORDER BY idpenerbit ";
									$dataQry = mysqli_query($koneksidb, $dataSql) or die("Gagal Query" . mysqli_error($koneksidb));
									while ($dataRow = mysqli_fetch_array($dataQry)) {
										if ($dataNamapenerbit == $dataRow['namapenerbit']) {
											$cek = " selected";
										} else {
											$cek = "";
										}
										echo "<option value='$dataRow[namapenerbit]' $cek>$dataRow[namapenerbit]</option>";
									}
									$sqlData = "";
									?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-4 control-label">Tempat Terbit</label>
							<div class="col-lg-4">
								<select name="txtNmKota" data-placeholder="- Pilih Tempat Terbit -" class="select2me form-control" required>
									<option value=""></option>
									<?php
									$dataSql = "SELECT idkota, nmkota FROM rkota WHERE noapk = $_SESSION[noapk] ORDER BY nmkota ";
									$dataQry = mysqli_query($koneksidb, $dataSql) or die("Gagal Query" . mysqli_error($koneksidb));
									while ($dataRow = mysqli_fetch_array($dataQry)) {
										if ($dataNmkota == $dataRow['nmkota']) {
											$cek = " selected";
										} else {
											$cek = "";
										}
										echo "<option value='$dataRow[nmkota]' $cek>$dataRow[nmkota]</option>";
									}
									$sqlData = "";
									?>
								</select>
							</div>
							<label class="col-lg-2 control-label">Tahun</label>
							<div class="col-lg-2">
								<input type="number" min="0" id="txtTahunTerbit" name="txtTahunTerbit" value="<?= (@$dataTahunterbit)? $dataTahunterbit : date("Y") ; ?>" class="form-control sm" required /></span>
							</div>
						</div>

					</div>


					<div class="col-lg-6">

						<div class="form-group">
							<label class="col-lg-2 control-label">Bahasa</label>
							<div class="col-lg-10">
								<select name="txtNmBahasa" data-placeholder="- Pilih Bahasa -" class="select2me form-control" required>
									<option value=""></option>
									<?php
									$dataSql = "SELECT nmbahasa, kdbahasa FROM rbahasa ORDER BY nmbahasa ";
									$dataQry = mysqli_query($koneksidb, $dataSql) or die("Gagal Query" . mysqli_error($koneksidb));
									while ($dataRow = mysqli_fetch_array($dataQry)) {
										if (@$dataNmbahasa == $dataRow['nmbahasa']) {
											$cek = " selected";
										} else {
											$cek = "";
										}
										echo "<option value='$dataRow[nmbahasa]' $cek>$dataRow[nmbahasa]</option>";
									}
									$sqlData = "";
									?>
								</select>
							</div>
						</div>

						<div class="form-group">
							<label class="col-lg-2 control-label">Asal Buku</label>
							<div class="col-lg-10">
								<select name="txtNmAsalBuku" data-placeholder="- Pilih Asal Buku -" class="select2me form-control" required>
									<option value=""></option>
									<?php
									$dataSql = "SELECT nmasalbuku, kdasalbuku FROM rasalbuku ORDER BY nmasalbuku ";
									$dataQry = mysqli_query($koneksidb, $dataSql) or die("Gagal Query" . mysqli_error($koneksidb));
									while ($dataRow = mysqli_fetch_array($dataQry)) {
										if ($dataNmasalbuku == $dataRow['kdasalbuku']) {
											$cek = " selected";
										} else {
											$cek = "";
										}
										echo "<option value='$dataRow[nmasalbuku]' $cek>$dataRow[nmasalbuku]</option>";
									}
									$sqlData = "";
									?>
								</select>
							</div>
						</div>

						<div class="form-group">
							<label class="col-lg-2 control-label">Cetakan</label>
							<div class="col-lg-2">
								<input type="number" min="0" id="txtcetakan" name="txtCetakan" value="<?php echo @$dataCetakan; ?>" class="form-control sm" required /></span>
							</div>
							<label class="col-lg-1 control-label">Edisi</label>
							<div class="col-lg-2">
								<input type="number" min="0" id="txtedisi" name="txtEdisi" value="<?php echo @$dataEdisi; ?>" class="form-control sm" required /></span>
							</div>
							<label class="col-lg-1 control-label">Vol</label>
							<div class="col-lg-4">
								<input type="number" min="0" placeholder="0 = Tidak Ada" id="txtvol" name="txtVol" value="<?php echo @$dataVol; ?>" class="form-control sm" required /></span>
							</div>
						</div>

						<div class="form-group">
							<label class="col-lg-4 control-label" for="txtIndex"><input type="checkbox" name="txtIndeks" id="txtIndeks" value="1" <?= (@$dataIndeks==0) ? "" : "checked"	?>> Index</label>
						</div>

						<div class="form-group">
							<label class="col-lg-2 control-label">Kolasi</label>
						</div>

						<div class="form-group">
							<label class="col-lg-4 control-label">Hlm Pendahuluan</label>
							<label class="col-lg-2 control-label">Halaman</label>
							<label class="col-lg-2 control-label">Ilus</label>
							<label class="col-lg-2 control-label">Tinggi</label>
						</div>

						<div class="form-group">
							<div class="col-lg-4">
								<input type="text" id="txthalpdh" name="txtHalPdh" value="<?php echo @$dataHalpdh; ?>" class="form-control sm" required /></span>
							</div>
							<div class="col-lg-3">
								<input type="number" min="0" id="txttebal" name="txtTebal" value="<?php echo @$dataTebal; ?>" class="form-control sm" required /></span>
							</div>
							<div class="col-lg-1">
								<input type="checkbox" name="txtIllus" value="1"  <?=(@$dataIllus==0) ? "" : "checked" ?>>
							</div>
							<div class="col-lg-4">
								<input type="number" min="0" id="txtpanjang" name="txtPanjang" value="<?php echo @$dataPanjang; ?>" class="form-control sm" required /></span>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-2 control-label">Seri</label>
							<div class="col-lg-10">
								<input type="text" id="txtjilid" name="txtJilid" placeholder="0 = Tidak Ada" value="<?php echo @$dataJilid; ?>" class="form-control sm" required /></span>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-5 control-label"><input type="checkbox" name="txtBibli" value="1" <?=(@$dataBibli==0) ? "" : "checked" ?>>Bibliografi</label>
						</div>
						<div class="form-group">
							<label class="col-lg-2 control-label">Hlm Bab</label>
							<div class="col-lg-10">
								<input type="text" id="txthalbibli" placeholder="[Contoh : 143-144]" name="txtHalBibli" value="<?php echo @$dataHalbibli; ?>" class="form-control sm" required /></span>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-2 control-label">ISBN</label>
							<div class="col-lg-10">
								<input type="text" id="txtisbn" name="txtIsbn" value="<?php echo @$dataIsbn; ?>" class="form-control sm" required /></span>
							</div>
						</div>

						<div class="form-group">
							<label class="col-lg-2 control-label">Lokasi</label>
							<div class="col-lg-10">
								<input type="text" id="txtlokasi" name="txtLokasi" value="<?php echo @$dataLokasi; ?>" class="form-control sm" required /></span>
							</div>
						</div>

						<div class="form-group">
							<label class="col-lg-2 control-label">Cover</label>
							<div class="col-lg-10">
								<input type="file" id="txtCover1" name="txtCover1" class="form-control sm" /></span>
							</div>
						</div>

					</div>
				</div>
			</div>

			<footer class="panel-footer">
				<div class="row">
					<div class="form-group">
						<div class="col-lg-offset-3 col-lg-6">
							<button type="submit" name="btnSave" class="btn <?= $_SESSION['warnabar'] ?>"><i class="fa fa-save"></i> Simpan Data</button>
							<a href="?content=tambahubahbuku" class="btn <?= $_SESSION['warnabar'] ?>"><i class="fa fa-undo"></i> Kembali</a>
							<button type="button" data-toggle="modal" data-target="#pesanModal" id="btnEntriMasal" class="btn <?= $_SESSION['warnabar'] ?>"><i class="fa fa-files-o"></i> Entri Masal</button>
						</div>
					</div>
				</div>
			</footer>
		</form>
	</div>
</div>


<!-- MODAL ENTRI MASAL -->

<div class="modal fade form" id="entriMasalModal" tabindex="-1" role="dialog" aria-labelledby="entriMasalModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="entriMasalModalLabel">Proses Entri Buku Masal Otomatis</h4>
                </div>
				<form method="post" class="form-horizontal">
                <div class="modal-body">
					<div class="well">
						<h4>Buku yang Diperbanyak</h4>
						<div class="form-group">
							<label class="col-lg-3 control-label">ID Buku :</label>
							<div class="col-lg-4">
								<input type="text" id="txtIdBukuMasal" name="txtIdBukuMasal" value="" class="form-control sm" readonly/>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-3 control-label">Judul Buku :</label>
							<div class="col-lg-9">
								<input type="text" id="txtJudulMasal" name="txtJudulMasal" value="" class=" form-control sm" readonly/>
							</div>
						</div>
					</div>
					<div class="well">
						<h4>Entri Masal</h4>
						<div class="form-group">
							<label class="col-lg-5 control-label">Jumlah Tambahan :</label>
							<div class="col-lg-3">
								<input type="number" min="1" id="txtJmlMasal" name="txtJmlMasal" value="1" class="entrimasal form-control sm"/>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-5 control-label">ID Buku Terakhir :</label>
							<label class="col-lg-2 control-label"> <?= getIdBukuTerakhir($koneksidb) ?></label>
						</div>
						<div class="form-group">
							<label class="col-lg-5 control-label">ID Buku Hasil :</label>
							<div class="col-lg-3">
								<input type="number" min="0" id="txtHasilDari" name="txtHasilDari" value="<?= getIdBukuTerakhir($koneksidb)+1 ?>" class="entrimasal form-control sm" required/>
							</div> 
							<label class="col-lg-1 control-label">S.D.</label>
							<div class="col-lg-3">
								<input type="number" id="txtHasilSampai" name="txtHasilSampai" value="<?= getIdBukuTerakhir($koneksidb)+1 ?>" class="form-control sm" readonly/>
							</div>
						</div>
					</div>
				</div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" name="btnSaveEntriMasal" class="btn <?= $_SESSION['warnabar'] ?>">Proses</button>
                </div>
			</form>
            </div>
        </div>
    </div>

<!-- MODAL PESAN -->

<div class="modal fade" id="pesanModal" tabindex="-1" role="dialog" aria-labelledby="pesanModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
				<div class='alert alert-danger alert-dismissable'><button type='button' class='close' data-dismiss='modal' aria-hidden='true'></button><strong><i class='fa fa-times'></i>&nbsp; ID Buku yang diisikan tidak ada di master. <br>Entri Buku Masal mengambil ID Buku sebagai sumber untuk di-copy. </strong></div>
                </div>
            </div>
        </div>
    </div>

<script>
	$("#txtJudul").on("input",function(){
		$(this).val($(this).val().toUpperCase());
	});

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

	$(document).ready(function(){
		var data = $("#txtIdBuku").val();
		if(data==""){
			$('#btnEntriMasal').attr('data-target', '#pesanModal');
		}else{
			$.ajax({
					type: "POST",
					url: "action.php?act=9c",
					data: {txtIdBuku: data},
					success: function(response){
						rs = JSON.parse(response);
						if(rs.cek > 0){
							$('#btnEntriMasal').attr('data-target', '#entriMasalModal');
							$('#txtIdBukuMasal').val(rs.dataIdBuku);
							$('#txtJudulMasal').val(rs.dataJudul);
						}else{
							$('#btnEntriMasal').attr('data-target', '#pesanModal');
						}
					}
				});
		}

		$("#txtKode").blur(function(event){
                    var data = $(this).val();
                    $.ajax({
                        type: "POST",
                        url: "action.php?act=9a",
                        data: {txtKode: data},
                        success: function(response){
                            $("#txtSubyek").val(JSON.parse(response));
                        }
                    });
            });

		$(".kdbuku").on("input", function(){
			var data1 = $("#txtKode").val();
			var data2 = $("#txtPengarang").val();
			var data3 = $("#txtJudul").val();
			
			$.ajax({
				type: "POST",
				url: "action.php?act=9b", 
				data: {dataKode: data1, dataPengarang: data2, dataJudul: data3},
				success: function(response){
					$("#txtKodeBuku").val(JSON.parse(response));
				}
			});
		});

		$("#txtPengarangNormal").blur(function(event){
                    var data = $(this).val();
					var words = data.split(" ");
					var capitalizedWords = [];

					words.forEach(function(word) {
                    // Ubah huruf pertama menjadi kapital dan sisa huruf menjadi kecil
                    var capitalizedWord = word.charAt(0).toUpperCase() + word.slice(1).toLowerCase();
                    capitalizedWords.push(capitalizedWord);
                	});

					$(this).val(capitalizedWords.join(" "));					

					var lastWord = capitalizedWords.pop(); // Menghapus dan mengambil kata terakhir
					var reversedSentence = lastWord + ", " + capitalizedWords.join(" ");

					$("#txtPengarang").val(reversedSentence);
                
            });

		// $("#txtIdBuku").on("input",function(){
		// 		var data = $(this).val();
		// 		$.ajax({
		// 			type: "POST",
		// 			url: "action.php?act=9c",
		// 			data: {txtIdBuku: data},
		// 			success: function(response){
		// 				rs = JSON.parse(response);
		// 				if(rs.cek > 0){
		// 					$('#btnEntriMasal').attr('data-target', '#entriMasalModal');
		// 					$('#txtIdBukuMasal').val(rs.dataIdBuku);
		// 					$('#txtJudulMasal').val(rs.dataJudul);
		// 				}else{
		// 					$('#btnEntriMasal').attr('data-target', '#pesanModal');
		// 				}
		// 			}
		// 		});
		// 	});

		$("#txtIdBuku").on("input", function() {
    var data = $(this).val().trim(); // Menghapus spasi ekstra di awal & akhir

    // Secara langsung memperbarui #c dengan input pengguna
    $('#c').val(data);

    if (data === "") {
        $('#btnEntriMasal').attr('data-target', '#pesanModal');
        return; // Hentikan eksekusi AJAX jika input kosong
    }

    $.ajax({
        type: "POST",
        url: "action.php?act=9c",
        data: { txtIdBuku: data },
        success: function(response) {
            try {
                var rs = JSON.parse(response);
                if (rs.cek > 0) {
                    $('#btnEntriMasal').attr('data-target', '#entriMasalModal');
                    $('#txtIdBukuMasal').val(rs.dataIdBuku);
                    $('#txtJudulMasal').val(rs.dataJudul);
                } else {
                    $('#btnEntriMasal').attr('data-target', '#pesanModal');
                }
            } catch (e) {
                console.error("Error parsing JSON:", e);
            }
        },
        error: function() {
            console.error("Gagal melakukan request AJAX.");
        }
    });
});


			$(".entrimasal").on("input",function(event){
                    var data = parseInt($("#txtJmlMasal").val());
					var txtHasilDari = parseInt($("#txtHasilDari").val());
					$("#txtHasilSampai").val(txtHasilDari+data-1);
            });
    });
</script>