<?php	
//Security goes here
	 
//declare variable post
$dataTgllibur=  isset($_POST['txtTanggal']) ? $_POST['txtTanggal'] : "";

$iduser = $_SESSION['iduser'];
$noapk  = $_SESSION['noapk'];


if (isset($_POST['btnSaveHaribesar'])) {
    if (empty($dataTgllibur)) {
        echo "<div class='alert alert-danger alert-dismissable'>
            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
            <strong><i class='fa fa-times'></i>&nbsp; Data Tidak Boleh Ada yang Kosong </strong>
            </div>";
    } else {
        $keterangan = isset($_POST['keterangan']) ? $_POST['keterangan'] : '';
        
        // Pastikan koneksi database tersedia
        if (!$koneksidb) {
            die("Koneksi database gagal: " . mysqli_connect_error());
        }

        // Query dengan placeholder
        $insQry = "INSERT INTO rtgllibur (tgllibur, deslibur, noapk, keterangan) VALUES (?, 1, ?, ?)";


        // Siapkan statement
        $stmt = mysqli_prepare($koneksidb, $insQry);
        if (!$stmt) {
            die("Gagal menyiapkan statement: " . mysqli_error($koneksidb));
        }

        // Konversi noapk ke integer
        $noapk = (int) $_SESSION['noapk'];

        // Bind parameter (string, integer, string)
        if (!mysqli_stmt_bind_param($stmt, "sis", $dataTgllibur, $noapk, $keterangan)) {
            die("Binding parameter gagal: " . mysqli_error($koneksidb));
        }

        // Eksekusi statement
        if (!mysqli_stmt_execute($stmt)) {
            die("Gagal eksekusi query: " . mysqli_error($koneksidb));
        }

        // Tutup statement setelah sukses eksekusi
        mysqli_stmt_close($stmt);

		logTransaksi($iduser, date('Y-m-d H:i:s'), 'Data Tanggal Libur Ditambah', $noapk);

        echo "<div class='alert alert-success alert-dismissable'>
            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
            <strong><i class='fa fa-check'></i>&nbsp;" . $dataTgllibur . "</strong> Sukses insert. 
            </div>";
    }
}



?>
	<SCRIPT language="JavaScript">
	function submitform() {
		document.form1.submit();
	}
	</SCRIPT>

<?php 

if (isset($_POST['btnSaveMinggu'])){
$tahun = $_POST['txtTahun'];
if (empty($tahun) || !is_numeric($tahun)) {
echo "<div class='alert alert-danger alert-dismissable'>
        <button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
        <strong><i class='fa fa-times'></i>&nbsp; Masukkan Tahun yang Valid </strong>
    </div>";
} else {

    for($bulan =1; $bulan <=12; $bulan++){
    for ($hari = 1; $hari <= 31; $hari++) {
        $tanggal = $tahun . '-' . str_pad($bulan, 2, '0', STR_PAD_LEFT) . '-' . str_pad($hari, 2, '0', STR_PAD_LEFT);
        $tanggalObj = new DateTime($tanggal);

        if ($tanggalObj->format('n') != $bulan) {
            break; 
        }

        if ($tanggalObj->format('w') == 0) {
            $sql = "INSERT INTO rtgllibur (tgllibur,deslibur,noapk) VALUES ('$tanggal',0,$_SESSION[noapk])";
            mysqli_query($koneksidb, $sql);
        }
    }
}

    echo "<div class='alert alert-success alert-dismissable'>
        <button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
        <strong><i class='fa fa-check'></i>&nbsp;</strong>Sukses Generate Data. 
    </div>";

}
}
?>

<div class="portlet box <?= $_SESSION['warnabar'] ?>">
	<div class="portlet-title">
		<div class="caption">Libur Hari Besar</div>
		<!-- <div class="caption">Libur Hari Besar</div> -->
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
    <label class="col-lg-2 control-label">Tanggal</label>
    <div class="col-lg-3">
        <input type="date" id="txtTanggal" name="txtTanggal" class="form-control sm" required/>
    </div>
    <label class="col-lg-2 control-label">Keterangan</label>
    <div class="col-lg-3">
    <input type="text" id="keterangan" name="keterangan" class="form-control" required />
</div>

</div>

	    	
		</div>
			<footer class="panel-footer">
			    <div class="row">
			        <div class="form-group">
			            <div class="col-lg-offset-2 col-lg-10">
			                <button type="submit" name="btnSaveHaribesar" class="btn <?= $_SESSION['warnabar'] ?>"><i class="fa fa-save"></i> Simpan Data</button>
			                <a href="?content=tanggal_libur" class="btn <?= $_SESSION['warnabar'] ?>"><i class="fa fa-undo"></i> Kembali</a>
			            </div>
			        </div>
			    </div>
			</footer>
		</form>
	</div>
</div>


<div class="portlet box <?= $_SESSION['warnabar'] ?>">
	<div class="portlet-title">
		<div class="caption">Libur Hari Minggu</div>
		<!-- <div class="caption">Libur Hari Besar</div> -->
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
				<label class="col-lg-2 control-label">Tahun</label>
				<div class="col-lg-3">
					<input type="text" id="txtTahun" name="txtTahun" class="form-control sm" required/></span>
	    		</div>
			</div>
	    	
		</div>
			<footer class="panel-footer">
			    <div class="row">
			        <div class="form-group">
			            <div class="col-lg-offset-2 col-lg-10">
			                <button type="submit" name="btnSaveMinggu" class="btn <?= $_SESSION['warnabar'] ?>"><i class="fa fa-save"></i> Generate Hari Minggu</button>
			                <a href="?content=tanggal_libur" class="btn <?= $_SESSION['warnabar'] ?>"><i class="fa fa-undo"></i> Kembali</a>
			            </div>
			        </div>
			    </div>
			</footer>
		</form>
	</div>
</div>