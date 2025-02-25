<?php
    $dataHarian         = (isset($_SESSION['dataHarian'])) ? $_SESSION['dataHarian'] : "";
    $dataBulan          = (isset($_SESSION['dataBulan'])) ? $_SESSION['dataBulan'] : "";
    $dataTahun          = (isset($_SESSION['dataTahun'])) ? $_SESSION['dataTahun'] : "";
    $dataDariTanggal    = (isset($_SESSION['dataDariTanggal'])) ? $_SESSION['dataDariTanggal'] : "";
    $dataSampaiTanggal  = (isset($_SESSION['dataSampaiTanggal'])) ? $_SESSION['dataSampaiTanggal'] : "";
    $dataAnggota  = (isset($_SESSION['dataAnggota'])) ? $_SESSION['dataAnggota'] : "";
    $tampil             = (isset($_SESSION['tampil'])) ? $_SESSION['tampil'] : "";
    $dataPilihan             = (isset($_SESSION['dataPilihan'])) ? $_SESSION['dataPilihan'] : "";
    
    if(isset($_SESSION['pesanKesalahan'])) {
        echo $_SESSION['pesanKesalahan'];
        unset($_SESSION['pesanKesalahan']);
    }
?>


<form action="export.php?lap=koleksipustaka_peminjamanperanggota&page=<?= basename($_SERVER["SCRIPT_FILENAME"])?>" method="post" class="form-horizontal" data-validate="parsley"  name="form1">
<div class="portlet box <?= $_SESSION['warnabar'] ?>">
    <div class="portlet-title">
        <div class="caption">Laporan Pengunjung Per Anggota</div>
        <div class="tools">
            <a href="javascript:;" class="collapse"></a>
            <a href="javascript:;" class="reload"></a>
            <a href="javascript:;" class="remove"></a>
        </div>
    </div>
    <div class="portlet-body form">
        <div class="form-body">
            <div class="row">
                <div class="col-lg-6">
            <div class="form-inline">
        <div class="form-group" style="margin-left:10px; margin-right:20px;">
            <div><label>Pilihan Masa Laporan :</label></div>
            <div><label class="radio"><input type="radio" name="txtPilihan" value="harian" checked>Harian</label></div>
            <div><label class="radio"><input type="radio" name="txtPilihan" value="bulanan" <?= (@$dataPilihan=="bulanan") ? "checked" : "" ?>>Bulanan</label></div>
            <div><label class="radio"><input type="radio" name="txtPilihan" value="custom" <?= (@$dataPilihan=="custom") ? "checked" : "" ?>>Custom</label></div>
        </div>
        <input type="hidden" value="<?= $tampil ?>" name="jnsPengunjung">
		<div class="form-group well " id="harian">
            <div><label class="control-label">Harian</label></div>
			<label class="col-lg-5 control-label">Tanggal :</label>
			<div class="col-lg-3">
				<input type="date" name="txtHarian" value="<?= $dataHarian ?>" class="form-control sm">
        	</div>
		</div>

        <div class="form-group well hidden" id="bulanan">
            <div><label class="control-label">Bulanan</label></div>
			<label class="col-lg-3 control-label" style="padding-left:0;">Bulan </label>
			<div class="col-lg-1">
				<input type="number" name="txtBulan" value="<?= $dataBulan ?>" class="form-control sm" style="width:50px;">
        	</div>
			<label class="col-lg-3 control-label" style="padding-left:0; margin-left:50px;">Tahun </label>
			<div class="col-lg-1" >
				<input type="number" name="txtTahun" value="<?= $dataTahun ?>" class="form-control sm" style="width:100px;">
        	</div>
		</div>

        <div class="form-group well hidden" id="custom">
            <div><label class="control-label">Custom</label></div>
            <div>
			<label class="col-lg-6 control-label">Dari Tanggal :</label>
			<div class="col-lg-3">
				<input type="date" name="txtDariTanggal" value="<?= $dataDariTanggal ?>" class="form-control sm">
        	</div>
            </div>
            <div>
            <label class="col-lg-6 control-label">S.d. Tanggal :</label>
			<div class="col-lg-3">
				<input type="date" name="txtSampaiTanggal" value="<?= $dataSampaiTanggal ?>" class="form-control sm">
        	</div>
            </div>
		</div>
        </div>

        </div>
                <div class="col-lg-6">
                <div class="form-group">
                <label class="col-lg-3 control-label">No Anggota</label>
                <div class="col-lg-8"><select name="txtAnggota"  data-placeholder="- Pilih Nama Anggota -" class="select2me form-control" required>
                    <option value=""></option> 
                    <?php
                            $dataSql = "SELECT nipnis, nama FROM ranggota WHERE noapk = $_SESSION[noapk] ORDER BY nipnis ";
                            $dataQry = mysqli_query( $koneksidb, $dataSql) or die ("Gagal Query".mysqli_error($koneksidb));
                            while ($dataRow = mysqli_fetch_array($dataQry)) {
                            if ($dataAnggota == $dataRow['nipnis']) {
                                $cek = " selected";
                            } else { $cek=""; }
                            echo "<option value='$dataRow[nipnis]' $cek>$dataRow[nipnis] $dataRow[nama]</option>";
                            }
                            $sqlData ="";
                    ?>
                </select> </div>
                </div>
                </div>
                
            

    
    </div>

	</div>
	<footer class="panel-footer">
	    <div class="row">
	        <div class="form-group">
	            <div class="col-lg-offset-2 col-lg-10">
	            	<button type="submit" name="btnSave" value="tampil" class="btn blue"><i class="fa fa-check"></i> Tampilkan</button>
	                <button type="submit" name="btnSave" value="cetak" class="btn blue"><i class="fa fa-print"></i> Export Excel</button>
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
    $('input[name="txtPilihan"]').change(function() {
        let selectedValue = $('input[name="txtPilihan"]:checked').val();
        let pilihan = ["harian", "bulanan", "custom"];

        if ($('#' + selectedValue).hasClass('hidden')) {
            $('#' + selectedValue).removeClass('hidden');
        }

        let pilihanBaru = $.grep(pilihan, function(value) {
            return value !== selectedValue;
        });

        if (!$('#' + pilihanBaru[0]).hasClass('hidden')) {
            $('#' + pilihanBaru[0]).addClass('hidden');
        }
        if (!$('#' + pilihanBaru[1]).hasClass('hidden')) {
            $('#' + pilihanBaru[1]).addClass('hidden');
        }
    });
    let selectedValue = $('input[name="txtPilihan"]:checked').val();
    let pilihan = ["harian", "bulanan", "custom"];

    if ($('#' + selectedValue).hasClass('hidden')) {
        $('#' + selectedValue).removeClass('hidden');
    }

    let pilihanBaru = $.grep(pilihan, function(value) {
        return value !== selectedValue;
    });

    if (!$('#' + pilihanBaru[0]).hasClass('hidden')) {
        $('#' + pilihanBaru[0]).addClass('hidden');
    }
    if (!$('#' + pilihanBaru[1]).hasClass('hidden')) {
        $('#' + pilihanBaru[1]).addClass('hidden');
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
      <div class="caption">Data Peminjaman Per Anggota</div>
      <div class="actions">
 
      </div>
    </div>
    <div class="portlet-body fieldset-form">
  	  <table class="table table-condensed table-bordered table-hover" id="sample_2" width="100%">
        <thead>
          <tr class="active"> 
                <td width="3%">NO</td>
                <td><div align="center">TANGGAL PINJAM</div></td>
                <td><div align="center">TANGGAL PENGEMBALIAN</div></td>
                <td><div align="center">ID BUKU</div></td>
                <td><div align="center">JUDUL BUKU</div></td> 
                <td><div align="center">PENGARANG</div></td> 
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
          "sAjaxSource": "action.php?act=25",  
          "aColumns": [ 
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
    if(isset($_SESSION['dataHarian'])){ unset($_SESSION['dataHarian']);}
    if(isset($_SESSION['dataBulan'])){ unset($_SESSION['dataBulan']);}
    if(isset($_SESSION['dataTahun'])){ unset($_SESSION['dataTahun']);}
    if(isset($_SESSION['dataDariTanggal'])){ unset($_SESSION['dataDariTanggal']);}
    if(isset($_SESSION['dataSampaiTanggal'])){ unset($_SESSION['dataSampaiTanggal']);}
    if(isset($_SESSION['dataAnggota'])){ unset($_SESSION['dataAnggota']);}
    if(isset($_SESSION['tampil'])){ unset($_SESSION['tampil']);}
    if(isset($_SESSION['dataPilihan'])){ unset($_SESSION['dataPilihan']);}
    ?>
<?php
}
?>

