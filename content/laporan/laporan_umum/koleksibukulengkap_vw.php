<?php
    $dataSubyek         = (isset($_SESSION['dataSubyek'])) ? $_SESSION['dataSubyek'] : "";
    $tampil             = (isset($_SESSION['tampil'])) ? $_SESSION['tampil'] : "";

    if(isset($_SESSION['pesanKesalahan'])) {
        echo $_SESSION['pesanKesalahan'];
        unset($_SESSION['pesanKesalahan']);
    }
    
?>

<form action="export.php?lap=koleksibukulengkap&page=<?= basename($_SERVER["SCRIPT_FILENAME"])?>" method="post" class="form-horizontal" data-validate="parsley" role="form"  name="form1">
<div class="portlet box <?= $_SESSION['warnabar'] ?>">
    <div class="portlet-title">
        <div class="caption">Koleksi Buku Lengkap</div>
    </div>
    
    <div class="portlet-body form">
   <div class="form-body">
       <div class="form-group">
          <label class="col-lg-2 control-label">Golongan Kode Buku</label>
            <div class="col-lg-5">
            <select name="txtSubyek" data-placeholder="- Pilih Golongan -" class="select2me form-control" required>
    <option value=""></option>
    <?php
    $dataSql = "SELECT kode, subyek FROM rsubyek WHERE noapk = '".mysqli_real_escape_string($koneksidb, $_SESSION['noapk'])."' ORDER BY kode";
    $dataQry = mysqli_query($koneksidb, $dataSql) or die("Gagal Query: " . mysqli_error($koneksidb));
    
    while ($dataRow = mysqli_fetch_array($dataQry)) {
        $cek = ($dataSubyek == $dataRow['kode']) ? "selected" : "";
        echo "<option value='{$dataRow['kode']}' $cek>[{$dataRow['kode']}] {$dataRow['subyek']}</option>";
    }
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

      <table class="table table-condensed table-bordered table-hover" id="sample_2" width="100%">
        <thead>
          <tr class="active">
                <td>NO</td>
                <td>ID BUKU</td>
                <td>KODE BUKU</td>
                <td>TGL ENTRI</td>
                <td>JUDUL</td>
          </tr>
        </thead>
      </table>

<div class="row" style="margin-top: 50px;">
    <div class="col-lg-4">
      <!-- <div class="form-group">
          <label class="col-lg-4 control-label">Jenis Buku</label>
          <div class="col-lg-8">
          <input type="text" id="txtJenisBuku" class="form-control" readonly>
          </div>
      </div> -->

      <div class="form-group">
          <label class="col-lg-4 control-label">Kode klasifikasi</label>
          <div class="col-lg-8">
            <input type="text" id="txtKlasifikasi" class="form-control sm" readonly />
          </div>
        </div>
        
        <!-- <div class="form-group">
          <label class="col-lg-4 control-label">Subyek</label>
          <div class="col-lg-8">
            <input type="text" id="txtSubyek" class="form-control sm" readonly/>
          </div>
        </div> -->

        <div class="form-group">
          <label class="col-lg-4 control-label">Pengarang-1</label>
          <div class="col-lg-8">
            <input type="text" id="txtPengarang" class="form-control sm" readonly /></span>
          </div>
        </div>

        <div class="form-group">
          <label class="col-lg-4 control-label">Pengarang-2</label>
          <div class="col-lg-8">
            <input type="text" id="txtPengarang2" class="form-control sm" readonly /></span>
          </div>
        </div>

        <div class="form-group">
          <label class="col-lg-4 control-label">Bahasa</label>
          <div class="col-lg-8">
            <input type="text" id="txtBahasa" class="form-control sm" readonly /></span>
          </div>
        </div>

    </div>
    <div class="col-lg-4">
        <div class="form-group">
          <label class="col-lg-4 control-label">Pengarang-3</label>
          <div class="col-lg-8">
            <input type="text" id="txtPengarang3" class="form-control sm" readonly /></span>
          </div>
        </div>
  
        <div class="form-group">
          <label class="col-lg-4 control-label">Penerbit</label>
          <div class="col-lg-8">
            <input type="text" id="txtPenerbit" class="form-control sm" readonly /></span>
          </div>
        </div>

        <div class="form-group">
          <label class="col-lg-4 control-label">Tempat Terbit</label>
          <div class="col-lg-8">
            <input type="text" id="txtTempatTerbit" class="form-control sm" readonly /></span>
          </div>
        </div>

        <div class="form-group">
          <label class="col-lg-4 control-label">Tahun Terbit</label>
          <div class="col-lg-8">
            <input type="text" id="txtTahunTerbit" class="form-control sm" readonly /></span>
          </div>
        </div>

        

    </div>
    <div class="col-lg-4">
        <div class="form-group">
            <label class="col-lg-4 control-label">Asal Buku</label>
            <div class="col-lg-8">
              <input type="text" id="txtAsalBuku" class="form-control sm" readonly /></span>
            </div>
        </div>

        <div class="form-group">
            <label class="col-lg-4 control-label">Seri</label>
            <div class="col-lg-8">
              <input type="text" id="txtSeri" class="form-control sm" readonly /></span>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-3 control-label">Edisi</label>
            <div class="col-lg-3">
              <input type="text" id="txtEdisi" class="form-control sm" readonly /></span>
            </div>
            <label class="col-lg-3 control-label">Cetakan</label>
            <div class="col-lg-3">
              <input type="text" id="txtCetakan" class="form-control sm" readonly /></span>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-4 control-label">ISBN</label>
            <div class="col-lg-8">
              <input type="text" id="txtISBN" class="form-control sm" readonly /></span>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-2 control-label">Status</label>
            <div class="col-lg-4">
              <input type="text" id="txtStatus" class="form-control sm" readonly /></span>
            </div>
            <label class="col-lg-2 control-label">Lokasi</label>
            <div class="col-lg-4">
              <input type="text" id="txtLokasi" class="form-control sm" readonly /></span>
            </div>
        </div>
    </div>
</div>

<?php 

$qry = "SELECT count(*) FROM tbuku WHERE kode LIKE ? AND noapk = $_SESSION[noapk]";
$stmt = mysqli_prepare($koneksidb,$qry);
$param = $dataSubyek."%";
mysqli_stmt_bind_param($stmt,"s",$param);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt,$jmlSegolongan);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);

$qry = "SELECT count(*) FROM tbuku";
$stmt = mysqli_prepare($koneksidb,$qry);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt,$jmlTotal);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);

$qry = "SELECT subyek FROM ttemsubyek WHERE kode = ? AND noapk = $_SESSION[noapk]";
$stmt = mysqli_prepare($koneksidb,$qry);
$param = $dataSubyek."00";
mysqli_stmt_bind_param($stmt,"s",$param);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt,$desSubyek);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);
?>

<div class="well" style="height: 70px;">
  <input type="hidden" name="txtDesSubyek" value="<?= $desSubyek ?>">
  <label class="col-lg-2">Jumlah Buku Se Golongan :  </label>
  <div class="col-lg-3">
  <input type="text" class="form-control" name="txtJmlGolongan" value="<?= $jmlSegolongan ?>" readonly>  
  </div>
  <label class="col-lg-2">Jumlah Buku Total : </label>
  <div class="col-lg-3">
  <input type="text" class="form-control" name="txtJmlTotal" value="<?= $jmlTotal ?>" readonly>  
  </div>
</div>

	  </fieldset>
	 
	

    </form>  

<!-- Datatable Script -->
  <script src="plugin/datatable/jquery-3.5.1.js"></script>
  <script src="plugin/datatable/jquery.dataTables.min.js"></script>
  <link rel="stylesheet" href="plugin/datatable/jquery.dataTables.min.css">
  
  <script>
  $(document).ready(function() {

      $("#sample_2").dataTable().fnDestroy();
     
      var table = $('#sample_2').DataTable( {  
          "bProcessing": true,
          "bServerSide": true,
          "bFilter": true,
          "searching":true,
          "ordering":true,
          "bDestroy":true,
          "sAjaxSource": "action.php?act=39",
          "fnServerData": function(sSource, aoData, fnCallback) {
          // Mendapatkan nilai dari input pencarian
                  var searchType = $('#searchType').val();
                  var searchVal = $('#searchVal').val();

                  // Menambahkan parameter pencarian sesuai dengan filter yang aktif
                  aoData.push({ "name": searchType, "value": searchVal });
              
                    $.ajax({
                        dataType: 'json',
                        type: 'GET',
                        url: sSource,
                        data: aoData,
                        success: function(response) {
                            console.log('Response:', response);
                            fnCallback(response);
                        },
                        error: function(xhr, status, error) {
                            console.error('Ajax request failed. Status:', status, 'Error:', error);
                            console.log('Server Response:', xhr.responseText);
                        }
                    });
        }, 
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
          "fnInitComplete": function() {
            $('#searchVal').keypress(function(event) {
                if (event.which === 13) {
                  table.fnDraw();
                }
            });
          },
          "fnRowCallback": function(nRow, aData, iDisplayIndex) {
                $(nRow).on('click', function() {
                    displaySeparateForm(aData);
                });
            },
          "columnDefs": [
          { className: "dt-center", "targets": [0] }  ,
          { "targets": [5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21], "visible": false }
          ],
          "iDisplayLength": 10,
          "bInfo": true,
          "sPaginationType" : 'full_numbers'  
      } );

      function displaySeparateForm(data) {
        $("#txtJenisBuku").val(data[5]).prop("readonly", true);
        $("#txtKlasifikasi").val(data[6]).prop("readonly", true);
        $("#txtSubyek").val(data[7]).prop("readonly", true);
        $("#txtPengarang").val(data[8]).prop("readonly", true);
        $("#txtPengarang2").val(data[9]).prop("readonly", true);
        $("#txtPengarang3").val(data[10]).prop("readonly", true);
        $("#txtPenerbit").val(data[11]).prop("readonly", true);
        $("#txtTempatTerbit").val(data[12]).prop("readonly", true);
        $("#txtTahunTerbit").val(data[13]).prop("readonly", true);
        $("#txtBahasa").val(data[14]).prop("readonly", true);
        $("#txtAsalBuku").val(data[15]).prop("readonly", true);
        $("#txtSeri").val(data[16]).prop("readonly", true);
        $("#txtEdisi").val(data[17]).prop("readonly", true);
        $("#txtCetakan").val(data[18]).prop("readonly", true);
        $("#txtISBN").val(data[19]).prop("readonly", true);
        $("#txtStatus").val(data[20]).prop("readonly", true);
        $("#txtLokasi").val(data[21]).prop("readonly", true);
    }
  });

  $(document).ready(function() {
    $("#txtIdAnggota").autocomplete({
        source: function(request, response) {
            $.ajax({
                url: "cari_anggota.php",
                type: "GET",
                dataType: "json",
                data: {
                    term: request.term
                },
                success: function(data) {
                    response($.map(data, function(item) {
                        return {
                            label: item.nipnis + " - " + item.nama,
                            value: item.nipnis
                        };
                    }));
                }
            });
        },
        minLength: 2,
        select: function(event, ui) {
            $("#txtIdAnggota").val(ui.item.value);
        }
    });

    // Fix for inconsistent value and options in select dropdown
    $("select[name='txtSubyek']").on("change", function() {
        let selectedVal = $(this).val();
        $(this).find("option").each(function() {
            if ($(this).val() === selectedVal) {
                $(this).prop("selected", true);
            } else {
                $(this).prop("selected", false);
            }
        });
    });
});


  </script>  

<?php 
}else{
    if(isset($_SESSION['dataSubyek'])){ unset($_SESSION['dataSubyek']);}
    if(isset($_SESSION['tampil'])){ unset($_SESSION['tampil']);}
    ?>
<?php
}
?>