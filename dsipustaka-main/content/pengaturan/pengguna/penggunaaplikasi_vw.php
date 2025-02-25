<?php
//security goes here 
  if(isset($_POST['del'])){
    $txtID    = $_POST['id'];
    
    //Cek apakah sudah ada dipakai
    // $sqlp = "SELECT * FROM rhakmenu WHERE iduser='".$txtID."'";
    // $qryp = mysqli_query($koneksidb,$sqlp);
    // if (mysqli_num_rows($qryp)>0){
    //     echo "<div class='alert alert-warning alert-dismissable'>
    //         <button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
    //         <strong><i class='fa fa-check'></i>&nbsp;".$txtID ."</strong> Gagal dihapus! Iduser sudah digunakan
    //         </div>";
    // }else {
    $insQry = "DELETE FROM ruser WHERE iduser = ? ";
		$stmt = mysqli_prepare($koneksidb,$insQry) or die ("Gagal menyiapkan statement: " . mysqli_error($koneksidb));
		mysqli_stmt_bind_param($stmt,"s", $txtID);
		mysqli_stmt_execute($stmt) or die ("Gagal Query Hapus User : " . mysqli_error($koneksidb));
		mysqli_stmt_close($stmt);

        echo "<div class='alert alert-success alert-dismissable'>
            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
            <strong><i class='fa fa-check'></i>&nbsp;".$txtID ."</strong> Sukses dihapus. 
            </div>";
    // }
  }
?>

<!-- <form action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" class="form-horizontal"> -->
  <div class="portlet box <?= $_SESSION['warnabar'] ?>">
    <div class="portlet-title">
        <div class="caption">Data Pengguna Aplikasi</div>
        <div class="actions">
            <a href="?content=tambahpengguna" class="btn  <?= $_SESSION['warnatombol'] ?>"><i class="fa fa-plus-circle"></i> Tambah Data</a>  
        </div>
    </div>
  
    <div class="portlet-body form">
    <div class="data-table-area"> 
    <div class="data-table-list"> 
      <table class="table table-condensed table-bordered table-hover" id="sample_2" width="100%">
        <thead>
          <tr class="active">
                <td width="5%">NO</td>
                <td>ID USER</td>
                <td>NAMA</td>
                <td>HAK AKSES</td>
                <td width="5%"><div align="center">ACTION</div></td>
              </tr>
        </thead>
         
      </table>
    </div>
    </div>
	 </div>
	</div>
  <!-- </form>   -->

<!-- KONFIRMASI DELETE -->

<div class="modal fade" id="deleteConfirmationModal" role="dialog" aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">
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
          "sAjaxSource": "action.php?act=1",  
          "aColumns": [
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
            let Id = $(this).data('id');
            $(".modal-footer #d_id").val(Id);
      });
  });

  </script>  

    	