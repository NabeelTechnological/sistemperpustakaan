<?php 
$iduser = $_SESSION['iduser'];
$noapk  = $_SESSION['noapk'];

if(isset($_POST['editKartu'])){
$isiKartu = $_POST['editKartu'];
$qry = "UPDATE tkartublkng SET isi = ? WHERE noapk = $_SESSION[noapk]";
$stmt = mysqli_prepare($koneksidb,$qry) or die("Gagal menyiapkan statement : ". mysqli_error($koneksidb));
mysqli_stmt_bind_param($stmt,"s",$isiKartu);
mysqli_stmt_execute($stmt) or die ("Gagal Query Edit Isi Kartu Belakang : " . mysqli_error($koneksidb));
mysqli_stmt_close($stmt);

echo "<div class='alert alert-success alert-dismissable'>
<button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
<strong><i class='fa fa-check'></i>&nbsp;</strong>Kartu belakang Sukses diedit. 
</div>";
logTransaksi($iduser, date('Y-m-d H:i:s'), 'Kartu Belakang Diedit', $noapk);
}

if(isset($_SESSION['pesanKesalahan'])) {
  echo $_SESSION['pesanKesalahan'];
  unset($_SESSION['pesanKesalahan']);
}

?>

<!-- MODAL EDIT KARTU BELAKANG -->
<div class="modal fade" id="editKartu" tabindex="-1" role="dialog" aria-labelledby="editKartuLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="editKartuLabel">PENYUNTINGAN ISI KARTU BELAKANG</h4>
                </div>
                <?php 
                $qry = mysqli_query($koneksidb, "SELECT isi FROM tkartublkng WHERE noapk = $_SESSION[noapk]") or die("Gagal tampilkan data : ". mysqli_error($koneksidb));
                $rs = mysqli_fetch_assoc($qry);
                ?>
                <form method="post">
                <div class="modal-body">
                    <textarea name="editKartu" id="editor" cols="30" rows="10"><?= @$rs['isi'] ?></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn  <?= $_SESSION['warnatombol'] ?>">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

<form method="POST" class="form-horizontal">
  <div class="portlet box <?= $_SESSION['warnabar'] ?>">
    <div class="portlet-title">
        <div class="caption">Data Cetak Kartu</div>
        <div class="actions">
            <button type="submit" formaction="?content=cetakkartudepan&page=<?= basename($_SERVER["SCRIPT_FILENAME"])?>" class="btn <?= $_SESSION['warnatombol'] ?>"><i class="fa fa-plus-circle"></i> Cetak Kartu Depan</button>  
            <button type="submit" formaction="?content=cetakkartubelakang&page=<?= basename($_SERVER["SCRIPT_FILENAME"])?>" class="btn <?= $_SESSION['warnatombol'] ?>"><i class="fa fa-plus-circle"></i> Cetak Kartu Belakang</button>  
             </div>
    </div>
  
    <div class="portlet-body fieldset-form">
         
<div class="form-group">
    <label class="col-lg-3 control-label">Pencarian Berdasarkan:</label>
    <div class="col-lg-3">
      <select id="searchType" class="form-control">
        <option value="nama">Nama</option>
        <option value="nomasal">No Anggota Masal</option>
        <option value="noindividu">No Anggota Individu</option>
      </select>
    </div>
    <label class="col-lg-2 control-label">Kriteria Pencarian:</label>
    <div class="col-lg-4" id="searchIndividu">
      <input type="text" id="searchVal" placeholder="Klik di Luar Kolom Setelah Input" class="form-control">
    </div>
    <div class="col-lg-4 hidden" id="searchMasal">
      <input type="number" id="searchFrom" class="form-control">
      s.d.
      <input type="number" id="searchTo" placeholder="Klik di Luar Kolom Setelah Input" class="form-control">
    </div>
</div>

      <table class="table table-condensed table-bordered table-hover table-condensed" id="sample_2" width="100%">
        <thead>
          <tr class="active">
                <td width="5%">NO</td>
                <td width="20%">NO ANGGOTA</td>
                <td>NAMA</td>
                <td width="5%"><div align="center">ACTION</div></td>
              </tr>
        </thead>
      </table>
      <label for="checkAll" style="width:100%; margin-top: 10px; text-align:right;"> Pilih Semua
        <input type="checkbox" name="checkAll" id="checkAll">
      </label>
	  </fieldset>
	 </div>
	</div>
  </form>  

<!-- Datatable Script -->
  <script src="plugin/datatable/jquery-3.5.1.js"></script>
  <script src="plugin/datatable/jquery.dataTables.min.js"></script>
  <link rel="stylesheet" href="plugin/datatable/jquery.dataTables.min.css">
  <script src="assets/plugins/ckeditor/ckeditor.js"></script>

  <script>
  CKEDITOR.replace('editor');

  $(document).ready(function() {

$("#checkAll").on("change",function(){
        var checkboxes = $(this).closest('form').find(':checkbox').not($(this));
        checkboxes.prop('checked', $(this).is(':checked'));
});

    $('#searchType').change(function() {
      if ($(this).val() == 'nomasal') {
        if ($('#searchMasal').hasClass('hidden')) {
          $('#searchMasal').removeClass('hidden');
        }

        if(!$('#searchIndividu').hasClass('hidden')){
          $('#searchIndividu').addClass('hidden');
        }
      } else {
        if (!$('#searchMasal').hasClass('hidden')) {
        $('#searchMasal').addClass('hidden');
        }

        if($('#searchIndividu').hasClass('hidden')){
          $('#searchIndividu').removeClass('hidden');
        }
      }
    });

    $("#sample_2").dataTable().fnDestroy();

var table = $('#sample_2').dataTable({
    "bProcessing": true,
    "bServerSide": true,
    "searching": false,
    "bDestroy": true,
    "bFilter": true,
    "sAjaxSource": "action.php?act=14",
    "fnServerData": function(sSource, aoData, fnCallback) {
        // Mendapatkan nilai dari input pencarian
        var searchType = $('#searchType').val();

        if (searchType == "nomasal") {
            var searchFrom = $('#searchFrom').val();
            var searchTo = $('#searchTo').val();

            aoData.push({ "name": "searchFrom", "value": searchFrom });
            aoData.push({ "name": "searchTo", "value": searchTo });
        } else {
            var searchVal = $('#searchVal').val();

            aoData.push({ "name": searchType, "value": searchVal });
        }

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
        null
    ],
    "fnInitComplete": function() {
    $('#searchVal').blur(function(event) {
        table.fnDraw();
    });
    $('#searchTo').blur(function(event) {
        table.fnDraw();
    });
},


    "iDisplayLength": 10,
    "bInfo": true,
    "sPaginationType": 'full_numbers'
});


  });
  </script>  

    	