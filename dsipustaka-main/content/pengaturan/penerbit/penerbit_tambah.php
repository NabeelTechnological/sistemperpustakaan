<?php	
//Security goes here
	 
//declare variable post
$dataNamaPenerbit  		=  isset($_POST['txtNamaPenerbit']) ? $_POST['txtNamaPenerbit'] : "";
$dataAlamatPenerbit 	=  isset($_POST['txtAlamatPenerbit']) ? $_POST['txtAlamatPenerbit'] : "";
$datanmkota    			=  isset($_POST['txtKota']) ? $_POST['txtKota'] : "";
$dataTelpon  		 	=  isset($_POST['txtTelponPenerbit']) ? $_POST['txtTelponPenerbit'] : "";
$dataFax  				=  isset($_POST['txtFaxPenerbit']) ? $_POST['txtFaxPenerbit'] : "";
$dataWebsite   			=  isset($_POST['txtWebsite']) ? $_POST['txtWebsite'] : "";

if (isset($_POST['btnSave'])){
		//insert idpenerbit 
		if(empty($dataNamaPenerbit) || empty($dataAlamatPenerbit) || empty($datanmkota)){
			echo "<div class='alert alert-danger alert-dismissable'>
            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
            <strong><i class='fa fa-times'></i>&nbsp; Data Tidak Boleh Ada yang Kosong </strong>
            </div>";
		}else{
		$insQry = "insert into rpenerbit (namapenerbit, alamatpenerbit, nmkota, telpon, fax, website, noapk) 
		values (?, ?, ?, ?, ?, ?, $_SESSION[noapk]) ";
		$stmt = mysqli_prepare($koneksidb,$insQry) or die ("Gagal menyiapkan statement: " . mysqli_error($koneksidb));
		mysqli_stmt_bind_param($stmt,"ssssss",$dataNamaPenerbit, $dataAlamatPenerbit, $datanmkota, $dataTelpon, $dataFax, $dataWebsite);
		mysqli_stmt_execute($stmt) or die ("Gagal Query Insert Penerbit : " . mysqli_error($koneksidb));
		mysqli_stmt_close($stmt);

		echo "<div class='alert alert-success alert-dismissable'>
            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
            <strong><i class='fa fa-check'></i>&nbsp;".$dataNamaPenerbit ."</strong> Sukses insert. 
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
		<div class="caption">Form Tambah Penerbit</div>
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
				<label class="col-lg-2 control-label">Nama Penerbit</label>
				<div class="col-lg-3">
					<input type="text" id="txtNamaPenerbit" name="txtNamaPenerbit" class="form-control sm" required/></span>
	    		</div>
			</div>
			<div class="form-group">
				<label class="col-lg-2 control-label">Alamat Penerbit</label>
				<div class="col-lg-3">
					<textarea name="txtAlamatPenerbit" id="txtAlamatPenerbit" cols="30" rows="3" class="form-control sm" required></textarea>
	    		</div>
			</div>
			<div class="form-group">
    <label class="col-lg-2 control-label">Kota</label>
    <div class="col-lg-3">
        <select name="txtKota" data-placeholder="- Pilih Kota -" class="select2me form-control" required>
            <option value=""></option> 
            <?php
                echo "No APK: " . $_SESSION['noapk']; // Debugging
                $dataSql = "SELECT nmkota, nmkota FROM rkota WHERE noapk = $_SESSION[noapk] ORDER BY nmkota ";
                $dataQry = mysqli_query($koneksidb, $dataSql) or die ("Gagal Query".mysqli_error($koneksidb));
                
                if (mysqli_num_rows($dataQry) == 0) {
                    echo "<div class='alert alert-warning'>Tidak ada data kota ditemukan.</div>";
                } else {
                    while ($dataRow = mysqli_fetch_array($dataQry)) {
                        echo "<option value='$dataRow[nmkota]' $cek>$dataRow[nmkota]</option>";
                    }
                }
            ?>
        </select>
    </div>
</div>
			<div class="form-group">
				<label class="col-lg-2 control-label">Telepon Penerbit</label>
				<div class="col-lg-3">
					<input type="text" id="txtTelponPenerbit" name="txtTelponPenerbit"  class="form-control sm"/></span>
	    		</div>
			</div>
			<div class="form-group">
				<label class="col-lg-2 control-label">Fax Penerbit</label>
				<div class="col-lg-3">
					<input type="text" id="txtFaxPenerbit" name="txtFaxPenerbit"  class="form-control sm"/></span>
	    		</div>
			</div>
			<div class="form-group">
				<label class="col-lg-2 control-label">Website</label>
				<div class="col-lg-3">
					<input type="text" id="txtWebsite" name="txtWebsite"  class="form-control sm"/></span>
	    		</div>
			</div>
			<footer class="panel-footer">
			    <div class="row">
			        <div class="form-group">
			            <div class="col-lg-offset-2 col-lg-10">
			                <button type="submit" name="btnSave" class="btn <?= $_SESSION['warnabar'] ?>"><i class="fa fa-save"></i> Simpan Data</button>
			                <a href="?content=penerbit" class="btn <?= $_SESSION['warnabar'] ?>"><i class="fa fa-undo"></i> Kembali</a>
			            </div>
			        </div>
			    </div>
			</footer>
		</form>
	</div>
</div>