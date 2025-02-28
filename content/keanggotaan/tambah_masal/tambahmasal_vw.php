<?php
//security goes here 
  if(isset($_POST['del'])){
    $txtID    = $_POST['id'];

    if($txtID == '-'){
        echo "<div class='alert alert-danger alert-dismissable'>
            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
            <strong><i class='fa fa-check'></i>&nbsp;</strong>Kelas Belum Dipilih. 
            </div>";

     } else  if($txtID != ''){
    $insQry = "DELETE FROM ranggota WHERE idkelas=? AND noapk = $_SESSION[noapk]";
    $stmt = mysqli_prepare($koneksidb,$insQry) or die ("Gagal menyiapkan statement: " . mysqli_error($koneksidb));
    mysqli_stmt_bind_param($stmt,"s", $txtID);
    mysqli_stmt_execute($stmt) or die ("Gagal Query Hapus Siswa : " . mysqli_error($koneksidb));
    mysqli_stmt_close($stmt);

    echo "<div class='alert alert-success alert-dismissable'>
        <button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
        <strong><i class='fa fa-check'></i>&nbsp;</strong>Data Siswa $txtID Sukses dihapus. 
        </div>";

    }else{
        $insQry = "DELETE FROM ranggota WHERE idjnsang = 2 AND noapk = $_SESSION[noapk]";
		$stmt = mysqli_prepare($koneksidb,$insQry) or die ("Gagal menyiapkan statement: " . mysqli_error($koneksidb));
		mysqli_stmt_execute($stmt) or die ("Gagal Query Hapus Guru/Karyawan : " . mysqli_error($koneksidb));
		mysqli_stmt_close($stmt);

        echo "<div class='alert alert-success alert-dismissable'>
            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
            <strong><i class='fa fa-check'></i>&nbsp;</strong>Data Guru/Karyawan Sukses dihapus. 
            </div>";
    }
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



<div id="pesan">
</div>
<div class="portlet box <?= $_SESSION['warnabar'] ?>">
	<div class="portlet-title">
		<div class="caption">Tambah / Edit Anggota Masal</div>
        <div class="caption">
            <button onclick="tab('.siswa','.guru')" class="btn <?= $_SESSION['warnatombol'] ?>">SISWA</button>
            <button onclick="tab('.guru','.siswa')" class="btn <?= $_SESSION['warnatombol'] ?>">GURU/KARYAWAN</button>
        </div>
        <div class="tools">
            <a href="javascript:;" class="collapse"></a>
            <a href="javascript:;" class="reload"></a>
            <a href="javascript:;" class="remove"></a>
        </div>
    <div>
</div>
	</div>

<div class="portlet-body">
<form action="<?php $_SERVER['PHP_SELF']; ?>" id="uploadForm" method="post" class="form-horizontal" role="form" autocomplete="off" name="form1" enctype="multipart/form-data">
<div class="row">
<div class="col-lg-6">
<div class="form-body">
        <div class="form-group siswa">
            <input type="hidden" name="cek" value="" id="cek">
				<label class="col-lg-3 control-label">Kelas</label>
				<div class="col-lg-8">
				<select name="txtKelas" id="txtKelas"  data-placeholder="- Pilih Kelas -" class="select2me form-control" required >
				<option value=""></option> 
				<?php
						$dataSql = "SELECT idkelas,deskelas FROM rkelas WHERE noapk = $_SESSION[noapk] ORDER BY idkelas ";
						$dataQry = mysqli_query( $koneksidb, $dataSql) or die ("Gagal Query".mysqli_error($koneksidb));
						while ($dataRow = mysqli_fetch_array($dataQry)) {
						echo "<option value='$dataRow[idkelas]' $cek>$dataRow[deskelas]</option>";
						}
						$sqlData ="";
				?>
				</select>
	    		</div>
		</div>
        <div class="form-group">
				<label class="col-lg-3 control-label">Kota</label>
				<div class="col-lg-8">
				<select name="txtKota"  data-placeholder="- Pilih Kota -" class="select2me form-control" required>
				<option value=""></option> 
				<?php
						$dataSql = "SELECT idkota,nmkota FROM rkota WHERE noapk = $_SESSION[noapk] ORDER BY nmkota ";
						$dataQry = mysqli_query( $koneksidb, $dataSql) or die ("Gagal Query".mysqli_error($koneksidb));
						while ($dataRow = mysqli_fetch_array($dataQry)) {
						echo "<option value='$dataRow[idkota]' $cek>$dataRow[nmkota]</option>";
						}
						$sqlData ="";
				?>
				</select>
	    		</div>
		</div>
        <div class="form-group">
        <label class="col-lg-3 control-label">Berlaku s/d</label>
        <div class="col-lg-8"><input type="date" name="txtBerlaku" value="<?= date("Y-m-d", strtotime("+1 month")) ?>" class="form-control sm"></div>
        </div>
        <div class="form-group">
        <label class="col-lg-3 control-label">Ambil Data Excel</label>
            <div class="col-lg-8">
            <input type="file" name="file" accept=".xls,.xlsx" class="form-control sm" onchange="uploadFile(0)" >
            </div>
        </div>
    </div>
</div>
<div class="col-lg-6">
<h4 class="siswa">Siswa</h4>
<h4 class="guru hidden">Guru/Karyawan</h4>
                <div>
                    <p><b>Keterangan Data Excel :</b></p>
                    <p class="siswa">
    1. Data Excel adalah data Siswa yang harus terdiri dari  
    4 (empat)  kolom saja. <br>
    2. Empat kolom tersebut adalah NIS, NAMA, ALAMAT, 
    JNSKEL (L/P) <br>
    3. Tidak boleh ada header/judul dll <br>
    4. Row pertama berupa data pertama. <br>
    5. Sebaiknya lakukan penghapusan data siswa kelas lama <br></p>
                    <p class="guru hidden">
 1. Data Excel adalah data Guru dan karyawan  yang 
harus terdiri dari 4 (empat)  kolom saja. <br>
2. Empat kolom tersebut adalah NIP/NUPTK, NAMA, 
ALAMAT, JNSKEL (L/P) <br>
3. Tidak boleh ada header/judul dll  <br>
4. Row pertama berupa data pertama. <br></p>
                </div>
</div>
</div>
    <footer class="panel-footer">
			    <div class="row">
			        <div class="form-group">
			            <div class="col-lg-offset-2 col-lg-10">
			                <button type="button" name="btnSaveSiswa" class="siswa btn <?= $_SESSION['warnabar'] ?>" onclick="uploadFile(1)"><i class="fa fa-save"></i> Simpan Data</button>
			                <button type="button" name="btnSaveGuru" class="guru hidden btn <?= $_SESSION['warnabar'] ?>" onclick="uploadFile(2)"><i class="fa fa-save"></i> Simpan Data</button>
			                <a href="?content=tambahmasal" class="btn <?= $_SESSION['warnabar'] ?>"><i class="fa fa-undo"></i> Kembali</a>
                            <button type="button" id="btnDeleteSiswa" data-toggle='modal' data-target='#deleteConfirmationModal' data-id='-' class="siswa btn red delPopUp"><i class="fa fa-trash-o"></i> Hapus Siswa Per Kelas</button>
                            <button type="button" id="btnDeleteGuru" data-toggle='modal' data-target='#deleteConfirmationModal' data-id='' class="guru hidden btn red delPopUp"><i class="fa fa-trash-o"></i> Hapus Guru/Kary Semua</button>
			            </div>
			        </div>
			    </div>
			</footer>
</form>
<div class=" portlet box <?= $_SESSION['warnabar'] ?>">
    <div class="portlet-title">
        <div class="caption">Data Excel</div>
    </div>
  
    <div class="portlet-body fieldset-form">
         
      <table class="table table-bordered table-hover table-condensed" width="100%">
        <thead>
          <tr class="active">
                <td width="5%">NO</td>
                <td width="15%" class="siswa">NIS</td>
                <td width="15%" class="guru hidden">NIP/NUPTK</td>
                <td>NAMA</td>
                <td>ALAMAT</td>
                <td  width="5%">JENIS KELAMIN</td>
              </tr>
        </thead>
         <tbody id="tableBody">
         </tbody>
      </table>
	  </fieldset>
	 </div>
	</div>
</div>

<script>
    $(document).ready(function(){
        $(document).on("click", ".delPopUp", function () {
            let Id = $(this).data('id');
            if(Id != ''){
                $(".modal-footer #d_id").val(Id);
            }
         });
    
        $(document).on("change", "#txtKelas", function () {
            let Id = $(this).val();
            console.log(Id);
            $("#btnDeleteSiswa").data("id",Id);  
        });
    });

    function tab(jenis1,jenis2){
        let show = document.querySelectorAll(jenis1);
        let hide = document.querySelectorAll(jenis2);

		show.forEach(e => {
			if(e.classList.contains("hidden")){
				e.classList.remove("hidden");
			}
		});

		hide.forEach(e => {
			if(!e.classList.contains("hidden")){
				e.classList.add("hidden");
			}
		});
    }

    function uploadFile(param) {
            let cek = document.getElementById('cek');
            if(param==1){
                cek.value = "siswa";
            }else if(param==2){
                cek.value = "guru";
            }
            var form = document.getElementById('uploadForm');
            var formData = new FormData(form);

            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'action.php?act=12', true);
            xhr.onload = function () {
                if (xhr.status === 200) {
                    if(param==1 || param==2){
                        document.getElementById('pesan').innerHTML = xhr.responseText;
                    }else{
                        document.getElementById('tableBody').innerHTML = xhr.responseText;
                    }
                }
            };
            xhr.send(formData);

            if(param==1){
                cek.value = "";
            }else if(param==2){
                cek.value = "";
            }
            event.preventDefault();
        }

        
</script>
