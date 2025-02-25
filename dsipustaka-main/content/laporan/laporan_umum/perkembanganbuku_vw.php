<?php
    $dataPilihan         = (isset($_SESSION['dataPilihan'])) ? $_SESSION['dataPilihan'] : "";
    $dataTriWulan         = (isset($_SESSION['dataTriWulan'])) ? $_SESSION['dataTriWulan'] : "";
    $dataTahun1         = (isset($_SESSION['dataTahun1'])) ? $_SESSION['dataTahun1'] : "";
    $dataTahun2          = (isset($_SESSION['dataTahun2'])) ? $_SESSION['dataTahun2'] : "";
    $tampil             = (isset($_SESSION['tampil'])) ? $_SESSION['tampil'] : "";

    if(isset($_SESSION['pesanKesalahan'])) {
        echo $_SESSION['pesanKesalahan'];
        unset($_SESSION['pesanKesalahan']);
    }
    
?>


<form action="export.php?lap=perkembanganbuku&page=<?= basename($_SERVER["SCRIPT_FILENAME"])?>" method="post" class="form-horizontal" data-validate="parsley"  name="form1">
<div class="portlet box <?= $_SESSION['warnabar'] ?>">
    <div class="portlet-title">
        <div class="caption">Laporan Perkembangan Buku</div>
        <div class="tools">
            <a href="javascript:;" class="collapse"></a>
            <a href="javascript:;" class="reload"></a>
            <a href="javascript:;" class="remove"></a>
        </div>
    </div>
    <div class="portlet-body form">
    <div class="form-body">
        <div class="form-inline">
        <div class="form-group" style="margin-left:10px; margin-right:20px;">
                <div><label>Tampilan :</label></div>
            <div><label class="radio"><input type="radio" name="txtPilihan" value="triwulanan" <?= (@$dataPilihan=="tahunan") ? "" : "checked" ?>>Tiga Bulan</label></div>
            <div><label class="radio"><input type="radio" name="txtPilihan" value="tahunan" <?= (@$dataPilihan=="tahunan") ? "checked" : "" ?>>Tahunan</label></div>
        </div>
<input type="hidden" value="<?= $tampil ?>" name="tampil">

		<div class="form-group well triwulanan">
			<div class="col-lg-6">
                <select name="txtTriWulan" class="form-control" required>
                    <option value="1" <?= (@$dataTriWulan=="1") ? "selected" : "" ?>>I. Januari - Maret</option>
                    <option value="2" <?= (@$dataTriWulan=="2") ? "selected" : "" ?>>II. April - Juni</option>
                    <option value="3" <?= (@$dataTriWulan=="3") ? "selected" : "" ?>>III. Juli - September</option>
                    <option value="4" <?= (@$dataTriWulan=="4") ? "selected" : "" ?>>IV. Oktober - Desember</option>
                </select>
        	</div>
			<div class="col-lg-1">
				<input type="number" name="txtTahun1" value="<?= (@$dataTahun1) ? $dataTahun1 : date("Y") ?>" class="form-control sm">
        	</div>
		</div>

        <div class="form-group well hidden tahunan">
			<div class="col-lg-1" >
				<input type="number" name="txtTahun2" value="<?= (@$dataTahun2) ? $dataTahun2 : date("Y") ?>" class="form-control sm" style="width:100px;">
        	</div>
		</div>

    </div>

	</div>
	<footer class="panel-footer">
	    <div class="row">
	        <div class="form-group">
	            <div class="col-lg-offset-2 col-lg-10">
	            	<button type="submit" name="btnSave" value="tampil" class="triwulanan btn blue"><i class="fa fa-check"></i> Tampilkan Tiga Bulan</button>
	            	<button type="submit" name="btnSave" value="tampil" class="tahunan hidden btn blue"><i class="fa fa-check"></i> Tampilkan Tahunan</button>
	                <button type="submit" name="btnSave" value="cetak"  class="btn blue"><i class="fa fa-print"></i> Export Excel</button>
	                <button type="button" class="btn blue" onclick="refreshLagi()"><i class="fa fa-undo"></i> Batalkan</button>
	            </div>
	        </div>
	    </div>
	</footer>
	</div>
</div>
</form>

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
// fungsi agar form dipilih berdasarkan radio button yang diklik 
     $(document).ready(function() {
        let selectedValue = $('input[name="txtPilihan"]:checked').val();
        let pilihan = ["triwulanan", "tahunan"];

        if ($('.' + selectedValue).hasClass('hidden')) {
            $('.' + selectedValue).removeClass('hidden');
        }

        let pilihanBaru = $.grep(pilihan, function(value) {
            return value !== selectedValue;
        });

        if (!$('.' + pilihanBaru[0]).hasClass('hidden')) {
            $('.' + pilihanBaru[0]).addClass('hidden');
        }

    $('input[name="txtPilihan"]').change(function() {
        let selectedValue = $('input[name="txtPilihan"]:checked').val();
        let pilihan = ["triwulanan", "tahunan"];

        if ($('.' + selectedValue).hasClass('hidden')) {
            $('.' + selectedValue).removeClass('hidden');
        }

        let pilihanBaru = $.grep(pilihan, function(value) {
            return value !== selectedValue;
        });

        if (!$('.' + pilihanBaru[0]).hasClass('hidden')) {
            $('.' + pilihanBaru[0]).addClass('hidden');
        }
    });
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
      <div class="caption">Data Laporan Perkembangan Buku</div>
      <div class="actions">
 
      </div>
    </div>
    <div class="portlet-body fieldset-form">
  	  <table class="table table-condensed table-bordered table-hover" id="sample_2" width="100%">
        <thead>
          <tr class="active"> 
            <td rowspan="2" width="3%">NO</td>
            <td rowspan="2"><div align="center">GOLONGAN / KODE BUKU</div></td>
            <td colspan="4"><div align="center">JUMLAH JUDUL</div></td> 
            <td colspan="4"><div align="center">JUMLAH BUKU</div></td> 
          </tr>
          <tr class="active"> 
            <td><div align="center">SEBELUM</div></td>
            <td><div align="center">MASA DIPILIH</div></td> 
            <td><div align="center">PERSENTASE</div></td> 
            <td><div align="center">KESELURUHAN</div></td> 
            <td><div align="center">SEBELUM</div></td>
            <td><div align="center">MASA DIPILIH</div></td> 
            <td><div align="center">PERSENTASE</div></td> 
            <td><div align="center">KESELURUHAN</div></td> 
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
          "sAjaxSource": "action.php?act=43",  
          "aColumns": [ 
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
    if(isset($_SESSION['dataPilihan'])){ unset($_SESSION['dataPilihan']);}
    if(isset($_SESSION['dataTriWulan'])){ unset($_SESSION['dataTriWulan']);}
    if(isset($_SESSION['dataTahun1'])){ unset($_SESSION['dataTahun1']);}
    if(isset($_SESSION['dataTahun2'])){ unset($_SESSION['dataTahun2']);}
    if(isset($_SESSION['tampil'])){ unset($_SESSION['tampil']);}
    ?>
<?php
}
?>

