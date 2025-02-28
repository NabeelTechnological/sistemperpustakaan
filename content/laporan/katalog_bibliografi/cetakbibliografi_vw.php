<?php 
	$dataSubyek  			=  isset($_POST['txtSubyek']) ? $_POST['txtSubyek'] : "";
?>

<form action="<?php $_SERVER['PHP_SELF']; ?>" method="post" class="form-horizontal" data-validate="parsley"  name="form1">
<div class="portlet box <?= $_SESSION['warnabar'] ?>">
    <div class="portlet-title">
        <div class="caption">Cetak Bibliografi</div>
        <div class="tools">
            <a href="javascript:;" class="collapse"></a>
            <a href="javascript:;" class="reload"></a>
            <a href="javascript:;" class="remove"></a>
        </div>
    </div>
    <div class="portlet-body form">
    <div class="form-body">

        <div class="form-group">
        <label class="col-lg-2 control-label">Golongan Kode Buku</label>
            <div class="col-lg-5">
              <select id="txtSubyek" name="txtSubyek" data-placeholder="- Pilih Golongan -" class="select2me form-control" required>
              <option value=""></option>
                    <?php
                    $dataSql = "SELECT kode, subyek FROM ttemsubyek WHERE noapk = $_SESSION[noapk] ORDER BY kode ";
                    $dataQry = mysqli_query($koneksidb, $dataSql) or die("Gagal Query" . mysqli_error($koneksidb));
                    $kode = 0;
                    while ($dataRow = mysqli_fetch_array($dataQry)) {
                        if (@$dataSubyek."00" == $dataRow['kode']) {
                            $cek = " selected";
                        } else {
                            $cek = "";
                        }
                        echo "<option value='$kode' $cek>[$dataRow[kode]] $dataRow[subyek]</option>";
                        $kode++;
                    }
                    $sqlData = "";
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

	                <button type="submit" name="btnCetak" formaction="?content=printbibliografi" value="cetak"  class="btn blue"><i class="fa fa-print"></i> Cetak</button>

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
if (@$_POST['btnSave']=="tampil"){
?>
<br>
<div class="portlet box <?= $_SESSION['warnabar'] ?>">
    <div class="portlet-title">
      <div class="caption">Tampilan Cetak Bibliografi</div>
      <div class="actions">
 
      </div>
    </div>
    <div class="portlet-body fieldset-form" id="sample" style="overflow: auto;">
  	  
    </div>
  </div>  
   
   <!-- Datatable Script -->
  <script src="plugin/datatable/jquery-3.5.1.js"></script>
  <script src="plugin/datatable/jquery.dataTables.min.js"></script>
  <link rel="stylesheet" href="plugin/datatable/jquery.dataTables.min.css">
  <script>
    $(document).ready(function(){
        let txtSubyek = $('#txtSubyek').val();

        $.ajax({
            url: "action.php?act=36", 
            type: "POST", 
            data: {
                'txtSubyek':txtSubyek
            },
            success: function(response){
            $("#sample").html(response); 
            }
        });
        });
        history.replaceState(null, null, location.href);

  </script>  

<?php } ?>

