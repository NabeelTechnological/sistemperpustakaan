<?php	
//Security goes here


//declare variable get
		$dataIdUser   	=  isset($_GET['id']) ? $_GET['id'] : ""; 
		$dataNama 			= "";
		$qry  = mysqli_query($koneksidb,"select nama from ruser where iduser='".$dataIdUser."'") or die('Gagal query nama user.'.mysqli_error());
		if (mysqli_num_rows($qry)>0){
			$rs = mysqli_fetch_array($qry);
			$dataNama = $rs['nama'];
		}
		 

?>
	<SCRIPT language="JavaScript">
	function submitform() {
		document.form1.submit();
	}
	</SCRIPT>
<div class="portlet box <?= $_SESSION['warnabar'] ?>">
	<div class="portlet-title">
		<div class="caption">Form Kelola Hak Akses Pengguna</div>
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
					<input type="text" id="txtIdUser" name="txtIdUser" value="<?php echo $dataIdUser; ?>"   class="form-control sm" disabled/>
				</div>	
		    </div> 
				<div class="form-group">
					<label class="col-lg-2 control-label">Nama</label>
					<div class="col-lg-3">
						<input type="text" id="txtNama" name="txtNama" value="<?php echo $dataNama; ?>"  class="form-control sm" disabled />
		    	</div>
				</div>
				<div class="form-group">
					<label class="col-lg-2 control-label">Keterangan</label>
					<div class="col-lg-4">
						<input type="text" id="txtNama" name="txtNama" value="User Bea Cukai Hak Akses 7 Laporan bersifat tetap."  class="form-control sm" disabled />
		    	</div>
				</div>
 
			<footer class="panel-footer">
			    <div class="row">
			        <div class="form-group">
			            <div class="col-lg-offset-2 col-lg-10">
			                 
			                <a href="?content=penggunaaplikasi" class="btn <?= $_SESSION['warnabar'] ?>"><i class="fa fa-undo"></i> Kembali</a>
			            </div>
			        </div>
			    </div>
			</footer>
		</form>
	</div>
</div>