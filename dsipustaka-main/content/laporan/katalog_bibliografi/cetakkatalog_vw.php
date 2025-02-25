<?php 
	$dataCetakBerdasar  			=  isset($_POST['txtCetakBerdasar']) ? $_POST['txtCetakBerdasar'] : "";
	$dataIdBukuDari  			=  isset($_POST['txtIdBukuDari']) ? $_POST['txtIdBukuDari'] : "";
	$dataIdBukuSampai  			=  isset($_POST['txtIdBukuSampai']) ? $_POST['txtIdBukuSampai'] : "";
?>

<form action="<?php $_SERVER['PHP_SELF']; ?>" method="post" class="form-horizontal" data-validate="parsley"  name="form1">
<div class="portlet box <?= $_SESSION['warnabar'] ?>">
    <div class="portlet-title">
        <div class="caption">Cetak Katalog</div>
        <div class="tools">
            <a href="javascript:;" class="collapse"></a>
            <a href="javascript:;" class="reload"></a>
            <a href="javascript:;" class="remove"></a>
        </div>
    </div>
    <div class="portlet-body form">
    <div class="form-body">

        <div class="form-group">
        <label class="col-lg-2 control-label">Cetak Berdasarkan</label>
            <div class="col-lg-2">
              <select id="txtCetakBerdasar" name="txtCetakBerdasar" class="form-control">
                <option value="judul" <?= (@$dataCetakBerdasar!="judul") ? "" : "selected" ?>>Judul</option>
                <option value="pengarang" <?= (@$dataCetakBerdasar=="pengarang") ? "selected" : "" ?>>Pengarang</option>
              </select>
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-lg-2 control-label">ID Buku Dari</label>
            <div class="col-lg-2">
                <input type="number" min="0" id="txtIdBukuDari" name="txtIdBukuDari" class="form-control sm" 
                value="<?= @$dataIdBukuDari?>"
                required/>
            </div> 
            <label class="col-lg-1 control-label">S.d.</label>
            <div class="col-lg-2">
                <input type="number" min="0" id="txtIdBukuSampai" name="txtIdBukuSampai" class="form-control sm" value="<?= @$dataIdBukuSampai?>" required/>
            </div>
        </div>

        <div class="form-group">
            <label class="col-lg-2 control-label">Jumlah</label>
            <div class="col-lg-2">
                <input type="number" id="txtJumlah" name="txtJumlah" class="form-control sm" readonly/>
            </div>
        </div>

	</div>
	<footer class="panel-footer">
	    <div class="row">
	        <div class="form-group">
	            <div class="col-lg-offset-2 col-lg-10">
	            	<button type="submit" name="btnSave" value="tampilKatalog" class="btn blue"><i class="fa fa-check"></i> Tampilkan Katalog</button>
	            	<button type="submit" name="btnSave" value="tampilBarcode" class="btn blue"><i class="fa fa-check"></i> Tampilkan Barcode</button>

                    <?php if (@$_POST['btnSave']=="tampilKatalog"){ ?>
	                <button type="submit" name="btnCetak" formaction="?content=printkatalog" value="cetak"  class="btn blue"><i class="fa fa-print"></i> Cetak</button>

                    <?php }else if (@$_POST['btnSave']=="tampilBarcode") { ?>
                    <button type="submit" name="btnCetak" formaction="?content=printbarcode" value="cetak"  class="btn blue"><i class="fa fa-print"></i> Cetak</button> 

                    <?php } ?>

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

        var dari =  parseInt($("#txtIdBukuDari").val());
        var sampai = parseInt($("#txtIdBukuSampai").val());
        $("#txtJumlah").val(sampai-dari+1);

    $("#txtIdBukuDari").on("input",function(event){
        var dari = parseInt($(this).val());
        var sampai = parseInt($("#txtIdBukuSampai").val());
        $("#txtJumlah").val(sampai-dari+1);
    });

    $("#txtIdBukuSampai").on("input",function(event){
        var sampai = parseInt($(this).val());
        var dari = parseInt($("#txtIdBukuDari").val());
        $("#txtJumlah").val(sampai-dari+1);
    });


</script>
<?php 
if ($dataIdBukuDari > $dataIdBukuSampai) {
    echo "<div class='alert alert-danger alert-dismissable'>
            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
            <strong><i class='fa fa-times'></i>&nbsp; ID Buku Dari harus lebih kecil dari ID Buku S.d. </strong>
            </div>";
}else{
if (@$_POST['btnSave']=="tampilKatalog"){
?>
<br>
<div class="portlet box <?= $_SESSION['warnabar'] ?>">
    <div class="portlet-title">
      <div class="caption">Tampilan Cetak Katalog</div>
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
        let txtCetakBerdasar = $('#txtCetakBerdasar').val();
        let txtIdBukuDari = $('#txtIdBukuDari').val();
        let txtIdBukuSampai = $('#txtIdBukuSampai').val();

        $.ajax({
            url: "action.php?act=34", 
            type: "POST", 
            data: {
                'txtCetakBerdasar':txtCetakBerdasar,
                'txtIdBukuDari':txtIdBukuDari,
                'txtIdBukuSampai':txtIdBukuSampai
            },
            success: function(response){
            $("#sample").html(response); 
            }
        });
        });
        history.replaceState(null, null, location.href);

  </script>  

<?php }else if(@$_POST['btnSave']=="tampilBarcode"){ ?>
    <br>
<div class="portlet box <?= $_SESSION['warnabar'] ?>">
    <div class="portlet-title">
      <div class="caption">Tampilan Cetak Barcode</div>
      <div class="actions">
 
      </div>
    </div>
    <div class="portlet-body fieldset-form" id="sample">
  	  
    </div>
  </div>  
   
   <!-- Datatable Script -->
  <script src="plugin/datatable/jquery-3.5.1.js"></script>
  <script src="plugin/datatable/jquery.dataTables.min.js"></script>
  <link rel="stylesheet" href="plugin/datatable/jquery.dataTables.min.css">
  <script>
    $(document).ready(function(){
        let txtCetakBerdasar = $('#txtCetakBerdasar').val();
        let txtIdBukuDari = $('#txtIdBukuDari').val();
        let txtIdBukuSampai = $('#txtIdBukuSampai').val();

        $.ajax({
            url: "action.php?act=34a", 
            type: "POST", 
            data: {
                'txtCetakBerdasar':txtCetakBerdasar,
                'txtIdBukuDari':txtIdBukuDari,
                'txtIdBukuSampai':txtIdBukuSampai
            },
            success: function(response){
            $("#sample").html(response); 
            }
        });
        });
        history.replaceState(null, null, location.href);
  </script>  
<?php }} ?>

