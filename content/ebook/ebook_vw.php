<?php
session_start();

$iduser = $_SESSION['iduser'];
$noapk  = $_SESSION['noapk'];

if(isset($_POST['del'])){
    $txtID = $_POST['idebook'] ?? '';

    if(empty($txtID)){
        die("Error: ID tidak dikirim atau kosong!");
    }

    // Query hapus dengan prepared statement
    $insQry = "DELETE FROM tebook WHERE idebook = ? AND noapk = ?";
    $stmt = mysqli_prepare($koneksidb, $insQry);
    mysqli_stmt_bind_param($stmt, "ss", $txtID, $noapk);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    logTransaksi($iduser, date('Y-m-d H:i:s'), 'Data E-book Dihapus', $noapk);

    echo "<div class='alert alert-danger alert-dismissable'>
    <button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
    <strong><i class='fa fa-times'></i>&nbsp; E-BOOK $judul Berhasil Diubah </strong>
    </div>";
    exit;
}
?>



<div class="portlet box <?= $_SESSION['warnabar'] ?>">
    <div class="portlet-title">
        <div class="caption">Data E-BOOK</div>
        <div class="actions">
            <a href="?content=ebooktambah" class="btn <?= $_SESSION['warnatombol'] ?>"><i class="fa fa-plus-circle"></i> Tambah E-BOOK</a>  
        </div>
    </div>
    <div class="portlet-body fieldset-form">
      <table class="table table-bordered table-hover" id="sample_2" width="100%">
        <thead>
          <tr class="active">
                <th width="5%">NO</th>
                <th>Id Ebook</th>
                <th>Judul</th>
                <th>Penerbit</th>
                <th>Pengarang</th>
                <th>Upload Time</th>
                <th>E-Book</th>
                <th width="5%"><div align="center">ACTION</div></th>
          </tr>
        </thead>
      </table>
    </div>
</div>




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
                    <input type="hidden" name="idebook" id="d_id" value="">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" name="del" class="btn btn-danger delPopUp" data-id="<?= $row['idebook'] ?>" data-toggle="modal" data-target="#deleteConfirmationModal">Hapus</button>
                    <!-- <button type="submit" name="del" class="btn btn-danger">Hapus</button> -->
                    </form>
                </div>
            </div>
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
          "sAjaxSource": "action.php?act=50",  
          "aColumns": [
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

      $(document).on("click", ".delPopUp", function () {
    let Id = $(this).data('id');  // Ambil ID dari tombol yang diklik
    $("#d_id").val(Id);  // Masukkan ke input hidden di modal
});

  });
  </script>  

    	