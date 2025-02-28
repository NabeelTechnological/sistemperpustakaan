<?php	
//Security goes here

if (isset($_POST['del'])){
	$dataIdBuku   	=  isset($_POST['txtIdBuku']) ? $_POST['txtIdBuku'] : "";
		//insert idbuku 
		if(empty($dataIdBuku) ){
			echo "<div class='alert alert-danger alert-dismissable'>
            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
            <strong><i class='fa fa-times'></i>&nbsp; ID Buku belum diisi </strong>
            </div>";
		}else{
		$insQry = "DELETE from tpinbuku WHERE idbuku=? AND noapk = $_SESSION[noapk]";
		$stmt = mysqli_prepare($koneksidb,$insQry) or die ("Gagal menyiapkan statement: " . mysqli_error($koneksidb));
		mysqli_stmt_bind_param($stmt,"s",$dataIdBuku);
		mysqli_stmt_execute($stmt) or die ("Gagal Query Hapus Transaksi Buku : " . mysqli_error($koneksidb));
		mysqli_stmt_close($stmt);

		$insQry = "DELETE from tbuku WHERE idbuku=? AND noapk = $_SESSION[noapk]";
		$stmt = mysqli_prepare($koneksidb,$insQry) or die ("Gagal menyiapkan statement: " . mysqli_error($koneksidb));
		mysqli_stmt_bind_param($stmt,"s",$dataIdBuku);
		mysqli_stmt_execute($stmt) or die ("Gagal Query Hapus Buku : " . mysqli_error($koneksidb));
		mysqli_stmt_close($stmt);

		echo "<div class='alert alert-success alert-dismissable'>
            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
            <strong><i class='fa fa-check'></i>&nbsp; </strong> Sukses Hapus Data. 
            </div>";
		}
}

if(isset($_POST['btnHapusMasal'])){
	$dataHasilDari   	=  isset($_POST['txtHasilDari']) ? $_POST['txtHasilDari'] : "";
	$dataHasilSampai   	=  isset($_POST['txtHasilSampai']) ? $_POST['txtHasilSampai'] : "";

	if(empty($dataHasilDari) || empty($dataHasilSampai)){
		echo "<div class='alert alert-danger alert-dismissable'>
		<button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
		<strong><i class='fa fa-times'></i>&nbsp; Data Tidak Boleh Ada yang Kosong </strong>
		</div>";
	}else{
		$jml = $dataHasilSampai - $dataHasilDari + 1;
		for ($i=0; $i < $jml ; $i++) { 

			$insQry = "DELETE from tpinbuku WHERE idbuku=? AND noapk = $_SESSION[noapk]";
			$stmt = mysqli_prepare($koneksidb,$insQry) or die ("Gagal menyiapkan statement: " . mysqli_error($koneksidb));
			mysqli_stmt_bind_param($stmt,"s",$dataHasilDari);
			mysqli_stmt_execute($stmt) or die ("Gagal Query Hapus Transaksi Buku Masal : " . mysqli_error($koneksidb));
			mysqli_stmt_close($stmt);

			$insQry = "DELETE from tbuku WHERE idbuku=? AND noapk = $_SESSION[noapk]";
			$stmt = mysqli_prepare($koneksidb,$insQry) or die ("Gagal menyiapkan statement: " . mysqli_error($koneksidb));
			mysqli_stmt_bind_param($stmt,"s",$dataHasilDari);
			mysqli_stmt_execute($stmt) or die ("Gagal Query Hapus Buku Masal : " . mysqli_error($koneksidb));
			mysqli_stmt_close($stmt);
		
			echo "<div class='alert alert-success alert-dismissable'>
				<button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
				<strong><i class='fa fa-check'></i>&nbsp; </strong> Sukses Hapus Data Masal
				</div>";

			$dataHasilDari++;
		}
	}
}

if (isset($_GET['id'])) {
	$txtID    = isset($_GET['id']) ? $_GET['id'] : "";
	$qryCek   = "SELECT idbuku,kode,subyek,desjnsbuku,judul,pengarangnormal,pengarang,pengarang2,pengarang3,kodebuku,namapenerbit,nmkota,thterbit,nmbahasa,nmasalbuku,cetakan,edisi,vol,indeks,halpdh,tebal,illus,panjang,jilid,bibli,halbibli,isbn,lokasi FROM vw_tbuku WHERE idbuku = ? AND noapk = $_SESSION[noapk]";
	$stmt  = mysqli_prepare($koneksidb,$qryCek) or die ("Gagal menyiapkan statement: " . mysqli_error($koneksidb));
	mysqli_stmt_bind_param($stmt,"i",$txtID);
	mysqli_stmt_execute($stmt);
	mysqli_stmt_bind_result($stmt,$dataIdbuku,$dataKode,$dataSubyek,$dataDesJnsBuku,$dataJudul,$dataPengarangnormal,$dataPengarang,$dataPengarang2,$dataPengarang3,$dataKodeBuku,$dataNamaPenerbit,$dataNmKota,$dataThterbit,$dataNmBahasa,$dataNmAsalBuku,$dataCetakan,$dataEdisi,$dataVol,$dataIndeks,$dataHalpdh,$dataTebal,$dataIllus,$dataPanjang,$dataJilid,$dataBibli,$dataHalbibli,$dataIsbn,$dataLokasi);
	mysqli_stmt_fetch($stmt);
	mysqli_stmt_close($stmt);

}



?>
	<SCRIPT language="JavaScript">
	function submitform() {
		document.form1.submit();
	}
	</SCRIPT>
<div class="portlet box <?= $_SESSION['warnabar'] ?>">
	<div class="portlet-title">
		<div class="caption">Hapus Buku</div>
		<div class="tools">
			<a href="javascript:;" class="collapse"></a>
			<a href="javascript:;" class="reload"></a>
			<a href="javascript:;" class="remove"></a>
		</div>
	</div>
	<div class="portlet-body form">
		<form action="<?php $_SERVER['PHP_SELF']; ?>" method="post" class="form-horizontal" role="form" autocomplete="off" name="form1" enctype="multipart/form-data">
		<input type="hidden" name="txtTersedia" id="txtTersedia">
			<div class="form-body">
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
						<div class="form-group">
							<label class="col-lg-4 control-label">Kode klasifikasi</label>
							<div class="col-lg-8">
								<input type="text" id="txtKode" name="txtKode" value="<?php echo @$dataKode; ?>" class=" form-control sm" readonly /></span>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-4 control-label">Subyek</label>
							<div class="col-lg-8">
								<input type="text" id="txtSubyek" name="txtSubyek" value="<?php echo @$dataSubyek; ?>" class="form-control sm" readonly/></span>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-4 control-label">Jenis Buku</label>
							<div class="col-lg-8">
							<input type="text" name="txtJnsBuku" class="form-control" value="<?= @$dataDesJnsBuku ?>" readonly>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-4 control-label">Judul</label>
							<div class="col-lg-8">
								<input type="text" id="txtJudul" name="txtJudul" value="<?= (isset($dataJudul)) ? htmlspecialchars($dataJudul) : ""; ?>" class=" form-control sm" readonly /></span>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-4 control-label">Pengarang-1</label>
							<div class="col-lg-8">
								<input type="text" id="txtPengarangNormal" name="txtPengarangNormal" placeholder="Isikan Normal" value="<?php echo @$dataPengarangnormal; ?>" class="form-control sm" readonly /></span>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-4 control-label">Pengarang-1</label>
							<div class="col-lg-8">
								<input type="text" id="txtPengarang" name="txtPengarang" placeholder="Isikan Marga Dulu" value="<?php echo @$dataPengarang; ?>" class=" form-control sm" readonly /></span>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-4 control-label">Pengarang-2</label>
							<div class="col-lg-8">
								<input type="text" id="txtpengarang2" name="txtPengarang2" placeholder="Isikan Normal" value="<?= (@$dataPengarang2) ? $dataPengarang2 : ""; ?>" class="form-control sm" readonly /></span>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-4 control-label">Pengarang-3</label>
							<div class="col-lg-8">
								<input type="text" id="txtpengarang3" name="txtPengarang3" placeholder="Isikan Normal" value="<?= (@$dataPengarang3) ? $dataPengarang3 : ""; ?>" class="form-control sm" readonly /></span>
							</div>
						</div>

						<div class="form-group">
							<label class="col-lg-4 control-label">Kode Buku</label>
							<div class="col-lg-5">
								<input type="text" id="txtKodeBuku" value="<?= (@$dataKode && @$dataPengarang && @$dataJudul) ? kodebuku($dataKode,$dataPengarang,$dataJudul) : "" ?>" class="form-control sm" readonly readonly /></span>
							</div>
							<label class="col-lg-1 control-label">C</label>
							<div class="col-lg-2">
								<input type="number" min="0" id="c" name="txtKodeBuku" value="<?php echo @$dataKodeBuku; ?>" class="form-control sm" readonly />
							</div>
						</div>

						<div class="form-group">
							<label class="col-lg-4 control-label">Penerbit</label>
							<div class="col-lg-8">
							<input type="text" name="txtNamaPenerbit" class="form-control" value="<?= @$dataNamaPenerbit ?>" readonly>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-4 control-label">Tempat Terbit</label>
							<div class="col-lg-8">
							<input type="text" name="txtNmKota" class="form-control" value="<?= @$dataNmKota ?>" readonly>
							</div>
						</div>
						<div class="form-group">
						<label class="col-lg-4 control-label">Tahun</label>
							<div class="col-lg-3">
								<input type="number" min="0" id="txtTahunTerbit" name="txtTahunTerbit" class="form-control sm" value="<?= @$dataThterbit ?>" readonly /></span>
							</div>
						</div>
					</div>


					<div class="col-lg-6">

						<div class="form-group">
							<label class="col-lg-2 control-label">Bahasa</label>
							<div class="col-lg-10">
							<input type="text" name="txtNmBahasa" class="form-control" value="<?= @$dataNmBahasa ?>" readonly>
							</div>
						</div>

						<div class="form-group">
							<label class="col-lg-2 control-label">Asal Buku</label>
							<div class="col-lg-10">
							<input type="text" name="txtNmAsalBuku" class="form-control" value="<?= @$dataNmAsalBuku ?>" readonly>
							</div>
						</div>

						<div class="form-group">
							<label class="col-lg-2 control-label">Cetakan</label>
							<div class="col-lg-2">
								<input type="number" min="0" id="txtcetakan" name="txtCetakan" value="<?php echo @$dataCetakan; ?>" class="form-control sm" readonly /></span>
							</div>
							<label class="col-lg-1 control-label">Edisi</label>
							<div class="col-lg-2">
								<input type="number" min="0" id="txtedisi" name="txtEdisi" value="<?php echo @$dataEdisi; ?>" class="form-control sm" readonly /></span>
							</div>
							<label class="col-lg-1 control-label">Vol</label>
							<div class="col-lg-4">
								<input type="number" min="0" placeholder="0 = Tidak Ada" id="txtvol" name="txtVol" value="<?php echo @$dataVol; ?>" class="form-control sm" readonly /></span>
							</div>
						</div>

						<div class="form-group">
							<label class="col-lg-4 control-label" for="txtIndex"><input type="checkbox" name="txtIndeks" id="txtIndeks" value="1" <?= (@$dataIndeks==1 || !@$dataIndeks) ? "checked" : ""	?>> Index</label>
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
								<input type="text" id="txthalpdh" name="txtHalPdh" value="<?php echo @$dataHalpdh; ?>" class="form-control sm" readonly /></span>
							</div>
							<div class="col-lg-3">
								<input type="number" min="0" id="txttebal" name="txtTebal" value="<?php echo @$dataTebal; ?>" class="form-control sm" readonly /></span>
							</div>
							<div class="col-lg-1">
								<input type="checkbox" name="txtIllus" value="1"  <?=(@$dataIllus==1 || !@$dataIllus) ? "checked" : "" ?>>
							</div>
							<div class="col-lg-4">
								<input type="number" min="0" id="txtpanjang" name="txtPanjang" value="<?php echo @$dataPanjang; ?>" class="form-control sm" readonly /></span>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-2 control-label">Seri</label>
							<div class="col-lg-10">
								<input type="text" id="txtjilid" name="txtJilid" placeholder="0 = Tidak Ada" value="<?php echo @$dataJilid; ?>" class="form-control sm" readonly /></span>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-5 control-label"><input type="checkbox" name="txtBibli" value="1" <?=(@$dataBibli==1 || !@$dataBibli) ? "checked" : "" ?>>Bibliografi</label>
						</div>
						<div class="form-group">
							<label class="col-lg-2 control-label">Hlm Bib</label>
							<div class="col-lg-10">
								<input type="text" id="txthalbibli" placeholder="[Contoh : 143-144]" name="txtHalBibli" value="<?php echo @$dataHalbibli; ?>" class="form-control sm" readonly /></span>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-2 control-label">ISBN</label>
							<div class="col-lg-10">
								<input type="text" id="txtisbn" name="txtIsbn" value="<?php echo @$dataIsbn; ?>" class="form-control sm" readonly /></span>
							</div>
						</div>

						<div class="form-group">
							<label class="col-lg-2 control-label">Lokasi</label>
							<div class="col-lg-10">
								<input type="text" id="txtlokasi" name="txtLokasi" value="<?php echo @$dataLokasi; ?>" class="form-control sm" readonly /></span>
							</div>
						</div>

					</div>
				</div>
			</div>

			<footer class="panel-footer">
				<div class="row">
					<div class="form-group">
						<div class="col-lg-offset-3 col-lg-6">
							<button type="button" data-toggle="modal" data-target="#deleteConfirmationModal" id="btnHapus" class="btn <?= $_SESSION['warnabar'] ?>"><i class="fa fa-trash-o"></i> Hapus Data</button>
							<a href="?content=tambahubahbuku" class="btn <?= $_SESSION['warnabar'] ?>"><i class="fa fa-undo"></i> Kembali</a>
							<?php if($_SESSION['iduser']=="SU"){ ?>
								<button type="button" data-toggle="modal" data-target="#hapusMasalModal" id="btnHapusMasal" class="btn <?= $_SESSION['warnabar'] ?>"><i class="fa fa-files-o"></i> Hapus Masal</button>
							<?php } ?>
							</div>
						</div>
				</div>
			</footer>
			<!-- KONFIRMASI DELETE -->
			<div class="modal fade" id="deleteDipinjamModal" tabindex="-1" role="dialog" aria-labelledby="deleteDipinjamModalLabel" aria-hidden="true">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
								<h4 class="modal-title" id="deleteDipinjamModalLabel">Perhatian</h4>
							</div>
							<div class="modal-body">
								<p>Buku masih dipinjam !</p>
								<p>Anda yakin akan menghapusnya?</p>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
								<button type="button" data-toggle="modal" data-target="#deleteConfirmationModal" class="btn <?= $_SESSION['warnabar'] ?>">Yakin</button>
							</div>
						</div>
					</div>
				</div>

			<div class="modal fade" id="deleteConfirmationModal" tabindex="-1" role="dialog" aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
								<h4 class="modal-title" id="deleteConfirmationModalLabel">Konfirmasi</h4>
							</div>
							<div class="modal-body">
								<p>HATI-HATI !</p>
								<p>Penghapusan (Delete), akan menghapus transaksi yang berkaitan dengan buku tersebut.</p>
								<p>Anda Yakin?</p>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
								<button type="submit" name="del" class="btn btn-danger">Hapus</button>
							</div>
						</div>
					</div>
				</div>
		</form>
	</div>
</div>




<!-- MODAL HAPUS MASAL -->

<div class="modal fade form" id="hapusMasalModal" tabindex="-1" role="dialog" aria-labelledby="hapusMasalModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="hapusMasalModalLabel">Hapus Buku Masal</h4>
                </div>
				<form method="post" class="form-horizontal">
                <div class="modal-body">
					<div class="well">
						<h4>Hapus Masal Khusus Super User</h4>
						<div class="form-group">
							<label class="col-lg-4 control-label">ID Buku Hasil :</label>
							<div class="col-lg-5">
								<input type="number" min="0" id="txtHasilDari" name="txtHasilDari" value="" class="form-control sm" required/>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-4 control-label">S.D.</label>
							<div class="col-lg-5">
								<input type="number" min="0" id="txtHasilSampai" name="txtHasilSampai" value="" class=" form-control sm" required/>
							</div>
						</div>
					</div>
				</div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="button" data-toggle="modal" data-target="#deleteConfirmationMasalModal" class="btn btn-danger ?>"><i class="fa fa-play-o"></i>Hapus Masal</button>
                </div>

				<!-- KONFIRMASI DELETE -->
			
			<div class="modal fade" id="deleteConfirmationMasalModal" tabindex="-1" role="dialog" aria-labelledby="deleteConfirmationMasalModalLabel" aria-hidden="true">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
								<h4 class="modal-title" id="deleteConfirmationMasalModalLabel">Konfirmasi</h4>
							</div>
							<div class="modal-body">
								<p>HATI-HATI !</p>
								<p>Penghapusan (Delete), akan menghapus transaksi yang berkaitan dengan buku tersebut.</p>
								<p>Anda Yakin?</p>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-secondary" data-dismiss="modal">Tidak</button>
								<button type="submit" name="btnHapusMasal" class="btn btn-danger">Ya</button>
							</div>
						</div>
					</div>
				</div>
			</form>
            </div>
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

	$("#txtIdBuku").on("input",function(event){
                    var data = $(this).val();
                    $.ajax({
                        type: "POST",
                        url: "action.php?act=9d",
                        data: {txtIdBuku: data},
                        success: function(response){
							if(JSON.parse(response)===0){
                            $("#btnHapus").attr("data-target","#deleteDipinjamModal");
							}else{
							$("#btnHapus").attr("data-target","#deleteConfirmationModal");
							}
                        }
                    });
            });

	$(document).ready(function(){
		$.ajax({
			type: "POST",
			url: "action.php?act=9d",
			data: {txtIdBuku: $("#txtIdBuku").val()},
			success: function(response){
				if(JSON.parse(response)===0){
				$("#btnHapus").attr("data-target","#deleteDipinjamModal");
				}else{
				$("#btnHapus").attr("data-target","#deleteConfirmationModal");
				}
			}
		});
	});
</script>