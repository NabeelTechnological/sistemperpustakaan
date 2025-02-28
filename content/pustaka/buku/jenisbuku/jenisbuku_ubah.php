<?php	
//Security goes here
//declare variable post
if (isset($_POST['btnSaveTeks'])){
	$dataIdBuku  		=  isset($_POST['txtIdBuku']) ? $_POST['txtIdBuku'] : "";
	$dataKdPel 			=  isset($_POST['txtKdPel']) ? $_POST['txtKdPel'] : "";
	$dataKdJenjang     	=  isset($_POST['txtKdJenjang']) ? $_POST['txtKdJenjang'] : "";

		//update jenis buku
		$insQry = "update tbuku set kdpel = ?, kdjenjang = ?, idjnsbuku=4 WHERE idbuku = ? AND noapk = $_SESSION[noapk]";
		$stmt = mysqli_prepare($koneksidb,$insQry) or die ("Gagal menyiapkan statement: " . mysqli_error($koneksidb));
		mysqli_stmt_bind_param($stmt,"sss", $dataKdPel, $dataKdJenjang, $dataIdBuku);
		mysqli_stmt_execute($stmt) or die ("Gagal Query Update Jenis Buku : " . mysqli_error($koneksidb));
		mysqli_stmt_close($stmt);

			echo "<div class='alert alert-success alert-dismissable'>
	            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
	            <strong><i class='fa fa-check'></i>&nbsp;</strong>Data Sukses diubah. 
	            </div>";				
}else if (isset($_POST['btnSaveJenis'])){
	$dataIdBuku  		=  isset($_POST['txtIdBuku']) ? $_POST['txtIdBuku'] : "";
	$dataIdJnsBuku  	=  isset($_POST['txtIdJnsBuku']) ? $_POST['txtIdJnsBuku'] : "";

		//update jenis buku
		$insQry = "update tbuku set idjnsbuku = ? WHERE idbuku = ? AND noapk = $_SESSION[noapk]";
		$stmt = mysqli_prepare($koneksidb,$insQry) or die ("Gagal menyiapkan statement: " . mysqli_error($koneksidb));
		mysqli_stmt_bind_param($stmt,"ss",$dataIdJnsBuku,$dataIdBuku);
		mysqli_stmt_execute($stmt) or die ("Gagal Query Update Jenis Buku : " . mysqli_error($koneksidb));
		mysqli_stmt_close($stmt);

			echo "<div class='alert alert-success alert-dismissable'>
	            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
	            <strong><i class='fa fa-check'></i>&nbsp;</strong>Data Sukses diubah. 
	            </div>";				
}

else {
		$txtID    = isset($_GET['id']) ? $_GET['id'] : "";
		$qryCek   = mysqli_query($koneksidb, "SELECT idbuku, kdpel, kdjenjang, idjnsbuku FROM vw_tbuku 
								WHERE noapk = $_SESSION[noapk] AND idbuku = '".mysqli_real_escape_string($koneksidb, $txtID)."'	") or die('Gagal Query Cek.'. mysqli_error($koneksidb));
		if (mysqli_num_rows($qryCek)>0){
			  $rs = mysqli_fetch_array($qryCek);
			  $dataIdBuku  		=  $rs['idbuku'];
			  $dataIdMapel		=  $rs['kdpel'];
			  $dataIdKelas	 	=  $rs['kdjenjang']; 
			  $dataIdJnsBuku	=  $rs['idjnsbuku']; 
		} 

}

?>
	<SCRIPT language="JavaScript">
	function submitform() {
		document.form1.submit();
	}
	</SCRIPT>

<!-- BUKU TEKS -->
<div class="portlet box <?= $_SESSION['warnabar'] ?>">
	<div class="portlet-title">
		<div class="caption">
		<button onclick="tab('.bukuteks','.jenisbuku')" class="btn blue">Form Ubah ke Buku Teks</button>
        <button onclick="tab('.jenisbuku','.bukuteks')" class="btn blue">Form Ubah Jenis Buku</button>
		</div>
		<div class="tools">
			<a href="javascript:;" class="collapse"></a>
			<a href="javascript:;" class="reload"></a>
			<a href="javascript:;" class="remove"></a>
		</div>
	</div>
	<div class="portlet-body form">
	<form action="<?php $_SERVER['PHP_SELF']; ?>" method="post" class="form-horizontal" role="form" autocomplete="off" name="form1">
		<div class="form-body bukuteks ">
		<input type="hidden" id="txtIdBuku" name="txtIdBuku" value="<?php echo $dataIdBuku; ?>"/>
		<!-- BUKU TEKS -->
			<div class="form-group">
				<label class="col-lg-2 control-label">Mapel</label>
				<div class="col-lg-3">
				<select name="txtKdPel"  data-placeholder="- Pilih Mapel -" class="select2me form-control" required>
				<option value=""></option> 
				<?php
						$dataSql = "SELECT kdpel, nmpel FROM rpelajaran ORDER BY kdpel ";
						$dataQry = mysqli_query( $koneksidb, $dataSql) or die ("Gagal Query".mysqli_error($koneksidb));
						while ($dataRow = mysqli_fetch_array($dataQry)) {
						if ($dataIdMapel == $dataRow['kdpel']) {
							$cek = " selected";
						} else { $cek=""; }
						echo "<option value='$dataRow[kdpel]' $cek>$dataRow[nmpel]</option>";
						}
						$sqlData ="";
				?>
				</select>
	    		</div>
			</div>
			<div class="form-group">
				<label class="col-lg-2 control-label">Kelas</label>
				<div class="col-lg-3">
				<select name="txtKdJenjang"  data-placeholder="- Pilih Kelas -" class="select2me form-control" required>
				<option value=""></option> 
				<?php
						$dataSql = "SELECT kdjenjang FROM rjenjang ORDER BY kdjenjang ";
						$dataQry = mysqli_query( $koneksidb, $dataSql) or die ("Gagal Query".mysqli_error($koneksidb));
						while ($dataRow = mysqli_fetch_array($dataQry)) {
						if ($dataIdKelas == $dataRow['kdjenjang']) {
							$cek = " selected";
						} else { $cek=""; }
						echo "<option value='$dataRow[kdjenjang]' $cek>$dataRow[kdjenjang]</option>";
						}
						$sqlData ="";
				?>
				</select>
	    		</div>
			</div>
			<footer class="panel-footer">
			    <div class="row">
			        <div class="form-group">
			            <div class="col-lg-offset-2 col-lg-10">
			                <button type="submit" name="btnSaveTeks" class="btn <?= $_SESSION['warnabar'] ?>"><i class="fa fa-save"></i> Simpan Data</button>
			                <a href="?content=update_jenis_buku" class="btn <?= $_SESSION['warnabar'] ?>"><i class="fa fa-undo"></i> Kembali</a>
			            </div>
			        </div>
			    </div>
			</footer>
		</div>
		<div class="form-body jenisbuku hidden">
		<input type="hidden" id="txtIdBuku" name="txtIdBuku" value="<?php echo $dataIdBuku; ?>"/>
<!-- JENIS BUKU -->
<div class="form-group ">
				<label class="col-lg-2 control-label">JENIS BUKU</label>
				<div class="col-lg-3">
				<select name="txtIdJnsBuku"  data-placeholder="- Pilih Jenis Buku -" class="select2me form-control" required>
				<option value=""></option> 
				<?php
						$dataSql = "SELECT idjnsbuku, desjnsbuku FROM rjnsbuku WHERE idjnsbuku <> 4 ORDER BY idjnsbuku";
						$dataQry = mysqli_query( $koneksidb, $dataSql) or die ("Gagal Query".mysqli_error($koneksidb));
						while ($dataRow = mysqli_fetch_array($dataQry)) {
						if ($dataIdJnsBuku == $dataRow['idjnsbuku']) {
							$cek = " selected";
						} else { $cek=""; }
						echo "<option value='$dataRow[idjnsbuku]' $cek>$dataRow[desjnsbuku]</option>";
						}
						$sqlData ="";
				?>
				</select>
	    		</div>
			</div>
			<footer class="panel-footer">
			    <div class="row">
			        <div class="form-group">
			            <div class="col-lg-offset-2 col-lg-10">
			                <button type="submit" name="btnSaveJenis" class="btn <?= $_SESSION['warnabar'] ?>"><i class="fa fa-save"></i> Simpan Data</button>
			                <a href="?content=update_jenis_buku" class="btn <?= $_SESSION['warnabar'] ?>"><i class="fa fa-undo"></i> Kembali</a>
			            </div>
			        </div>
			    </div>
			</footer>
		</div>
		</form>
	</div>
</div>

<script>
    function tab(jenis1,jenis2){
        let show = document.querySelectorAll(jenis1);
        let hide = document.querySelectorAll(jenis2);

		show.forEach(e => {
			if(e.classList.contains("hidden")){
				e.classList.remove("hidden");
			}
		});

		hide.forEach(e => {
			if(!e.classList.contains("hidden")){
				e.classList.add("hidden");
			}
		});
    }
</script>