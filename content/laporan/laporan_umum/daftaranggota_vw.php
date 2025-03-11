<?php
    $dataIdjnsang       = (isset($_SESSION['dataIdjnsang'])) ? $_SESSION['dataIdjnsang'] : "";
    $dataJnsang       = (isset($_SESSION['dataJnsang'])) ? $_SESSION['dataJnsang'] : "";
    $dataKelas       = (isset($_SESSION['dataKelas'])) ? $_SESSION['dataKelas'] : "";
    $tampil             = (isset($_SESSION['tampil'])) ? $_SESSION['tampil'] : "";

    if(isset($_SESSION['pesanKesalahan'])) {
        echo $_SESSION['pesanKesalahan'];
        unset($_SESSION['pesanKesalahan']);
    }
    
?>

<form action="export.php?lap=daftaranggota&page=<?= basename($_SERVER["SCRIPT_FILENAME"])?>" method="post" class="form-horizontal" data-validate="parsley" role="form"  name="form1">
<div class="portlet box <?= $_SESSION['warnabar'] ?>">
    <div class="portlet-title">
        <div class="caption">Daftar Anggota Lengkap</div>
    </div>
    
    <div class="portlet-body form">
   <div class="form-body">

        <div class="form-group">
            <label class="col-lg-2 control-label">Jenis Anggota: </label>
            <div class="col-lg-4">
                <select name="txtIdjnsang" class="form-control" required>
                    <option value="1"  <?= (@$dataIdjnsang==1) ? "selected" : "" ?>>Siswa</option>
                    <option value="2" <?= (@$dataIdjnsang==2) ? "selected" : "" ?>>Guru/Karyawan</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="col-lg-2 control-label">Kelas</label>
            <div class="col-lg-4">
                <select name="txtKelas" data-placeholder="- Pilih Cakupan -" class="select2me form-control" id="kelas" required>
                    <?php
                    $dataSql = "SELECT idkelas,deskelas FROM rkelas WHERE noapk = $_SESSION[noapk] ORDER BY idkelas ";
                    $dataQry = mysqli_query( $koneksidb, $dataSql) or die ("Gagal Query".mysqli_error($koneksidb));
                    while ($dataRow = mysqli_fetch_array($dataQry)) {
                    $cek = (@$dataKelas==$dataRow['idkelas']) ? "selected" :"";
                    echo "<option value='$dataRow[idkelas]' $cek>$dataRow[deskelas]</option>";
                    }
                    $sqlData ="";
                    ?>
                </select>
            </div>
        </div>
    
   </div>      

<footer class="panel-footer">
	    <div class="row">
	        <div class="form-group">
	            <div class="col-lg-offset-2 col-lg-10">
	            	<button type="submit" name="btnSave" value="tampil" class="btn blue"><i class="fa fa-check"></i> Tampilkan</button>
	                <button type="submit" name="btnSave" value="cetak"  class="btn blue"><i class="fa fa-print"></i> Export Excel</button>
	                <button type="button" class="btn blue" onclick="refreshLagi()"><i class="fa fa-undo"></i> Batalkan</button>
	            </div>
	        </div>
	    </div>
	</footer>
    </div>
    </div>

<script>
// refresh 2x
document.addEventListener('DOMContentLoaded', function() {
    let url = new URL(window.location.href);
    if(url.searchParams.get('secondReload')){
    
            url.searchParams.delete('secondReload');
    
            history.replaceState(null, '', url.toString());
            window.location.href = url.toString();
        }
});
    //fungsi agar refresh 2x
function refreshLagi() {
    // Cek apakah ini reload pertama atau kedua
    var url = new URL(window.location.href);
        url.searchParams.set('secondReload', 'true');
        url.searchParams.delete('btnsave');
        window.location.href = url.toString();

}
</script>

    <?php 
if (isset($_GET['btnsave'])){
 
?>
 <br>
    <div class="portlet box <?= $_SESSION['warnabar'] ?>">
        <div class="portlet-title">
            <div class="caption">Data Daftar Anggota Lengkap</div>
        </div>
        <div class="portlet-body fieldset-form">
      <table class="table table-condensed table-bordered table-hover" id="sample_2" width="100%">
        <thead>
          <tr class="active">
                <td>NO</td>
                <td>ID ANGGOTA</td>
                <td>NAMA</td>
                <td>JNS KELAMIN</td>
                <td>TELEPON</td>
                <td>BERLAKU S.D.</td>
          </tr>
        </thead>
      </table>

      <div id="separateForm" style="margin-top: 50px;"> </div>
    <div class="form-group">
    <label class="control-label col-lg-1" for="separateFormInput1">Alamat</label>
    <div class="col-lg-6">
        <textarea class="form-control" id="separateFormInput1" rows="1"></textarea>
    </div>
    <div class="col-lg-4">
        <input class="form-control" type="hidden" id="separateFormInput2">
    </div>
    </div>

    <div class="form-group">

    <div  class="col-lg-4">
        <input class="form-control" type="hidden" id="separateFormInput4">
    </div>
    </div>


<?php 

$qry = "SELECT count(*) FROM ranggota WHERE idjnsang = ? AND noapk = $_SESSION[noapk]";
$stmt = mysqli_prepare($koneksidb,$qry);
mysqli_stmt_bind_param($stmt,"i",$dataIdjnsang);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt,$jmlSegolongan);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);

if ($dataIdjnsang==1) {
    $qry = "SELECT count(*) FROM ranggota WHERE idjnsang = ? AND idkelas = ? AND noapk = $_SESSION[noapk]";
    $stmt = mysqli_prepare($koneksidb,$qry);
    mysqli_stmt_bind_param($stmt,"is",$dataIdjnsang,$dataKelas);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt,$jmlperkelas);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
}

$qry = "SELECT count(*) FROM ranggota WHERE noapk = $_SESSION[noapk]";
$stmt = mysqli_prepare($koneksidb,$qry);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt,$jmlTotal);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);

?>

<div class="well" style="height: 70px; margin-top: 50px;">
<input type="hidden" name="txtJmlPerKelas" value="<?= (@$jmlperkelas) ? @$jmlperkelas : 0 ?>">
  <label class="col-lg-2">Jumlah Anggota <?= @$dataJnsang ?> :  </label>
  <div class="col-lg-3">
  <input type="text" class="form-control" name="txtJmlGolongan" value="<?= @$jmlSegolongan ?>" readonly>  
  </div>
  <label class="col-lg-2">Jumlah Anggota Total : </label>
  <div class="col-lg-3">
  <input type="text" class="form-control" name="txtJmlTotal" value="<?= @$jmlTotal ?>" readonly>  
  </div>
</div>

	  </fieldset>
	 
	

    </form>  
</div>
</div>
<!-- Datatable Script -->
  <script src="plugin/datatable/jquery-3.5.1.js"></script>
  <script src="plugin/datatable/jquery.dataTables.min.js"></script>
  <link rel="stylesheet" href="plugin/datatable/jquery.dataTables.min.css">
  
  <script>
 
        $(document).ready(function() {

        $("#sample_2").dataTable().fnDestroy();

        $('#sample_2').dataTable( {  
            "bProcessing": true,
            "bServerSide": true,
            "bDestroy":true,
            "bFilter": true,
            "ordering":true,
            "sAjaxSource": "action.php?act=44",
            "columns": [
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null
            ],
            "fnRowCallback": function(nRow, aData, iDisplayIndex) {
                $(nRow).on('click', function() {
                    displaySeparateForm(aData);
                });
            },
            "columnDefs": [
            { className: "dt-center", "targets": [0] }  ,
            { "targets": [6,7,8,9], "visible": false }
            ],
            "iDisplayLength": 10,
            "bInfo": true,
            "sPaginationType" : 'full_numbers'
        } );

        function displaySeparateForm(data) {
        $("#separateFormInput1").val(data[6]).prop("readonly", true);
        $("#separateFormInput2").val(data[7]).prop("readonly", true);
        $("#separateFormInput3").val(data[8]).prop("readonly", true);
        $("#separateFormInput4").val(data[9]).prop("readonly", true);
        }
    });

  </script>  

<?php 
}else{
    if(isset($_SESSION['dataIdjnsang'])){ unset($_SESSION['dataIdjnsang']);}
    if(isset($_SESSION['dataJnsang'])){ unset($_SESSION['dataJnsang']);}
    if(isset($_SESSION['dataKelas'])){ unset($_SESSION['dataKelas']);}
    if(isset($_SESSION['tampil'])){ unset($_SESSION['tampil']);}
    ?>
<?php
}
?>