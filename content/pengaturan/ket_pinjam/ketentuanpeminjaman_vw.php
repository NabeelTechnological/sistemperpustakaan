<?php
//security goes here 
$iduser = $_SESSION['iduser'];
$noapk  = $_SESSION['noapk'];

if(isset($_POST['del'])){
    $txtID1    = $_POST['id1'];
    $txtID2   = $_POST['id2'];
    
    //Cek apakah sudah ada dipakai
    // $sqlp = "SELECT * FROM rhakmenu WHERE iduser='".$txtID."'";
    // $qryp = mysqli_query($koneksidb,$sqlp);
    // if (mysqli_num_rows($qryp)>0){
    //     echo "<div class='alert alert-warning alert-dismissable'>
    //         <button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
    //         <strong><i class='fa fa-check'></i>&nbsp;".$txtID ."</strong> Gagal dihapus! Iduser sudah digunakan
    //         </div>";
    // }else {

        $insQry = "DELETE FROM rreftrans WHERE idjnsang = ? AND idjnspustaka = ? AND noapk = $_SESSION[noapk]";
        $stmt = mysqli_prepare($koneksidb,$insQry) or die ("Gagal menyiapkan statement: " . mysqli_error($koneksidb));
        mysqli_stmt_bind_param($stmt,"ss", $txtID1, $txtID2);
        mysqli_stmt_execute($stmt) or die ("Gagal Query Hapus Keterangan Pinjam : " . mysqli_error($koneksidb));
        mysqli_stmt_close($stmt);

        logTransaksi($iduser, date('Y-m-d H:i:s'), 'Data Ketentuan Peminjaman Dihapus', $noapk);
        
        echo "<div class='alert alert-success alert-dismissable'>
            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
            <strong><i class='fa fa-check'></i>&nbsp;</strong>Data Sukses dihapus. 
            </div>";
    // }
  }
?>

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
                    <input type="hidden" name="id1" id="d_id1" value="">
                    <input type="hidden" name="id2" id="d_id2" value="">
                    <button type="submit" name="del" class="btn btn-danger">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

<!-- <form action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" class="form-horizontal"> -->
  <div class="portlet box <?= $_SESSION['warnabar'] ?>">
    <div class="portlet-title">
        <div class="caption">Data Ketentuan Peminjaman</div>
        <div class="actions">
            <a href="?content=tambahketpinjam" class="btn  <?= $_SESSION['warnatombol'] ?>"><i class="fa fa-plus-circle"></i> Tambah Data</a>  
        </div>
    </div>
    <div class="portlet-body fieldset-form">
         
      <table class="table table-condensed table-bordered table-hover table-condensed" id="sample_2" width="100%">
        <thead>
          <tr class="active">
                <td width="5%">NO</td>
                <td>ID JENIS ANGGOTA <br> (1:siswa 2:Guru)</td>
                <td>ID PUSTAKA <br> (1:Buku)</td>
                <td>MAKSIMUM JUMLAH SEWA</td>
                <td>MAKSIMUM LAMA SEWA</td>
                <td>PERIODE</td>
                <td>DENDA</td>
                <td width="5%"><div align="center">ACTION</div></td>
              </tr>
        </thead>
         
      </table>
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
     
      $('#sample_2').dataTable( {  
          "bProcessing": true,
          "bServerSide": true,
          "sAjaxSource": "action.php?act=2",  
          "aColumns": [
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
            let Id1 = $(this).data('id1');
            let Id2 = $(this).data('id2');
            $(".modal-footer #d_id1").val(Id1);
            $(".modal-footer #d_id2").val(Id2);
      });
  });
  </script>  

    	