<?php
    $dataRekap         = (isset($_SESSION['dataRekap'])) ? $_SESSION['dataRekap'] : "";
    $tampil             = (isset($_SESSION['tampil'])) ? $_SESSION['tampil'] : "";

    if(isset($_SESSION['pesanKesalahan'])) {
        echo $_SESSION['pesanKesalahan'];
        unset($_SESSION['pesanKesalahan']);
    }
    
?>

<form action="export.php?lap=rekapkoleksibuku&page=<?= basename($_SERVER["SCRIPT_FILENAME"])?>" method="post" class="form-horizontal" data-validate="parsley" role="form"  name="form1">
<div class="portlet box <?= $_SESSION['warnabar'] ?>">
    <div class="portlet-title">
        <div class="caption">Rekapitulasi Koleksi Buku</div>
    </div>
    
    <div class="portlet-body form">
   <div class="form-body">
       <div class="form-group">
          <label class="col-lg-2 control-label">Rekap Berdasarkan :</label>
            <div class="col-lg-5">
                <select name="txtRekap" class="form-control" required>
                    <option value="" selected disabled>- Pilih Rekap -</option>
                    <option value="rekapjenisbuku" <?= (@$dataRekap=="rekapjenisbuku") ? "selected" : "" ?>>Rekap Jenis Buku</option>
                    <option value="rekapbukureferensi" <?= (@$dataRekap=="rekapbukureferensi") ? "selected" : "" ?>>Rekap Buku Referensi</option>
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
      <div class="caption">Data Rekapitulasi Koleksi Buku</div>
      <div class="actions">
 
      </div>
    </div>
    <div class="portlet-body fieldset-form">
  	  <table class="table table-condensed table-bordered table-hover" id="sample_2" width="100%">
        <thead>
            <tr class="active"> 
                <td rowspan="2" width="3%">NO</td>
                <td colspan="2"><div align="center">JUMLAH</div></td>
            </tr>
            <tr class="active"> 
                <td><div align="center">BERDASARKAN JUDUL</div></td>
                <td><div align="center">BERDASARKAN EKSEMPLAR</div></td> 
            </tr>
        </thead>
        
      </table>
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
          "sAjaxSource": "action.php?act=42",  
          "aColumns": [ 
          	null,
            null
          ],
          "columnDefs": [
          { className: "dt-center", "targets": [0] }  
          ],
          "iDisplayLength": 10,
          "bInfo": true,
           "sPaginationType" : 'full_numbers'
      } );

    });

  </script>  

<?php 
}else{
    if(isset($_SESSION['dataRekap'])){ unset($_SESSION['dataRekap']);}
    if(isset($_SESSION['tampil'])){ unset($_SESSION['tampil']);}
    ?>
<?php
}
?>

