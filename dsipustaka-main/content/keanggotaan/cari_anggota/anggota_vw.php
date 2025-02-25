<?php
//security goes here 
  if(isset($_POST['del'])){
    $txtID    = $_POST['id'];
    
    $insQry = "DELETE FROM ranggota WHERE nipnis = ? AND noapk = $_SESSION[noapk]";
		$stmt = mysqli_prepare($koneksidb,$insQry) or die ("Gagal menyiapkan statement: " . mysqli_error($koneksidb));
		mysqli_stmt_bind_param($stmt,"s", $txtID);
		mysqli_stmt_execute($stmt) or die ("Gagal Query Hapus User : " . mysqli_error($koneksidb));
		mysqli_stmt_close($stmt);

        echo "<div class='alert alert-success alert-dismissable'>
            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
            <strong><i class='fa fa-check'></i>&nbsp;</strong>Data Sukses dihapus. 
            </div>";
    // }
  }
?>

<!-- KONFIRMASI DELETE -->

<div class="modal fade" id="deleteConfirmationModal" tabindex="-1" role="dialog" aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="deleteConfirmationModalLabel">Konfirmasi</h4>
                </div>
                <div class="modal-body">
                    <p>Yakin menghapus data?</p>
                </div>
                <div class="modal-footer">
                  <form method="post">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <input type="hidden" name="id" id="d_id" value="">
                    <button type="submit" name="del" class="btn btn-danger">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>



<!-- <form action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" class="form-horizontal"> -->
  <div class="portlet box <?= $_SESSION['warnabar'] ?>">
    <div class="portlet-title">
        <div class="caption">Cari Anggota</div>
    </div>
  
    <div class="portlet-body fieldset-form">
         
      <table class="table table-condensed table-bordered table-hover" id="sample_2" width="100%">
        <thead>
          <tr class="active">
                <td width="5%">NO</td>
                <td>NIS/NIK</td>
                <td>NAMA</td>
                <td>JENIS KELAMIN : <Br> L : Laki-Laki <br> P : Perempuan </td>
                <td>ID STATUS : <br> 1 : Siswa <br> 2 : Guru / Karyawan </td>
                <td>KOTA</td>
                <Td>ALAMAT</Td>
              </tr>
        </thead>
         
      </table>

<div class="row" id="separateForm" style="margin-top: 50px;">
    <div class="col-lg-6">
    <label class="control-label" for="separateFormInput1">NIS/NIK</label>
    <div>
        <textarea class="form-control" id="separateFormInput1" rows="2" readonly></textarea>
    </div>
    </div>
    <div class="col-lg-6">
    <label class="control-label" for="separateFormInput1">NAMA</label>
    <div>
        <textarea class="form-control" id="separateFormInput2" rows="2" readonly></textarea>
    </div>
    </div>
    <div class="col-lg-6">
    <label class="control-label" for="separateFormInput1">JENIS KELAMIN</label>
    <div>
        <textarea class="form-control" id="separateFormInput3" rows="2" readonly></textarea>
    </div>
    </div>
    <div class="col-lg-6">
    <label class="control-label" for="separateFormInput1">ID STATUS</label>
    <div>
        <textarea class="form-control" id="separateFormInput4" rows="2" readonly></textarea>
    </div>
    </div>
    <div class="col-lg-6">
    <label class="control-label" for="separateFormInput1">KOTA</label>
    <div>
        <textarea class="form-control" id="separateFormInput5" rows="2" readonly></textarea>
    </div>
    </div>
    <div class="col-lg-6">
    <label class="control-label" for="separateFormInput1">ALAMAT</label>
    <div>
        <textarea class="form-control" id="separateFormInput6" rows="2" readonly></textarea>
    </div>
    </div>
</div>
      
	  </fieldset>
	 </div>
	</div>
  <!-- </form>   -->

<!-- Datatable Script -->
<script src="plugin/datatable/jquery-3.5.1.js"></script>
  <script src="plugin/datatable/jquery.dataTables.min.js"></script>
  <link rel="stylesheet" href="plugin/datatable/jquery.dataTables.min.css">
  <script>
  $(document).ready(function() {

      $("#sample_2").dataTable().fnDestroy();
     
      var table = $('#sample_2').DataTable({
    "bProcessing": true,
    "bServerSide": true,
    "bDestroy": true,
    "sAjaxSource": "action.php?act=13",
    "columns": [
        null,
        null,
        null,
        null,
        null,
        null,
        null,
    ],
    "fnRowCallback": function(nRow, aData, iDisplayIndex) {
                $(nRow).on('click', function() {
                    displaySeparateForm(aData);
                });
            },
    "columnDefs": [
        { className: "dt-center", "targets": [0] },
        { "targets": [1, 2, 3, 4, 5, 6], "visible": false }
    ],
    "iDisplayLength": 10,
    "bInfo": true,
    "sPaginationType": 'full_numbers'
});

function displaySeparateForm(data) {
    // $("#separateFormInput1").val(data[0]).prop("readonly", true);
    // $("#separateFormInput1").val(data[0]).prop("readonly", true);
    $("#separateFormInput1").val(data[1]).prop("readonly", true);
    $("#separateFormInput2").val(data[2]).prop("readonly", true);
    $("#separateFormInput3").val(data[3]).prop("readonly", true);
    $("#separateFormInput4").val(data[4]).prop("readonly", true);
    $("#separateFormInput5").val(data[5]).prop("readonly", true);
    $("#separateFormInput6").val(data[6]).prop("readonly", true);
}


      $(document).on("click", ".delPopUp", function () {
            let Id = $(this).data('id');
            $(".modal-footer #d_id").val(Id);
      });
    });

  </script>  

    	