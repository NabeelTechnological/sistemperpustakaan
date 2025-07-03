<?php
$dataNipnis      = isset($_POST['txtIdAnggota']) ? $_POST['txtIdAnggota'] : "";
$dataNama        = isset($_POST['txtNama']) ? $_POST['txtNama'] : "";
$dataIdKelas     = isset($_POST['txtKelas']) ? $_POST['txtKelas'] : "";
$dataJnskel      = isset($_POST['txtJnsKel']) ? $_POST['txtJnsKel'] : "";
$dataTelp        = isset($_POST['txtTelp']) ? $_POST['txtTelp'] : "";
$dataBerlaku     = isset($_POST['txtBerlaku']) ? $_POST['txtBerlaku'] : "";
$dataAlamat      = isset($_POST['txtAlamat']) ? $_POST['txtAlamat'] : "";
$dataAlamat2     = isset($_POST['txtAlamatAlt']) ? $_POST['txtAlamatAlt'] : "";

$dataPhoto = NULL;
if (isset($_FILES['txtFoto']) && $_FILES['txtFoto']['error'] == UPLOAD_ERR_OK) {
    $fileTmp = $_FILES['txtFoto']['tmp_name'];
    $dataPhoto = file_get_contents($fileTmp);
}

$iduser = $_SESSION['iduser'];
$noapk  = $_SESSION['noapk'];

if (isset($_POST['btnSaveSiswa']) || isset($_POST['btnSaveGuru'])) {
    $isGuru = isset($_POST['btnSaveGuru']);

    // Validasi wajib
    if (empty($dataNipnis) || empty($dataNama) || empty($dataJnskel) || empty($dataBerlaku) || empty($dataAlamat) || (!$isGuru && empty($dataIdKelas))) {
        echo "<div class='alert alert-danger alert-dismissable'>
                <button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
                <strong><i class='fa fa-times'></i>&nbsp; Data Tidak Boleh Ada yang Kosong </strong>
              </div>";
    } else {
        if ($_POST['idEdit'] != "") {
            // ===================== UPDATE =======================
            if (!$isGuru) {
                // Update SISWA
                $insQry = "UPDATE ranggota SET 
                              idkelas = ?, 
                              nama = ?, 
                              jnskel = ?, 
                              telp = ?, 
                              berlaku = ?, 
                              alamat = ?, 
                              alamat2 = ?, 
                              photo = ? 
                           WHERE nipnis = ? AND noapk = ?";
                $stmt = mysqli_prepare($koneksidb, $insQry);
                mysqli_stmt_bind_param($stmt, "sssssssssi", $dataIdKelas, $dataNama, $dataJnskel, $dataTelp, $dataBerlaku, $dataAlamat, $dataAlamat2, $dataPhoto, $dataNipnis, $noapk);
            } else {
                // Update GURU
                $insQry = "UPDATE ranggota SET 
                              nama = ?, 
                              jnskel = ?, 
                              telp = ?, 
                              berlaku = ?, 
                              alamat = ?, 
                              alamat2 = ?, 
                              photo = ? 
                           WHERE nipnis = ? AND noapk = ?";
                $stmt = mysqli_prepare($koneksidb, $insQry);
                mysqli_stmt_bind_param($stmt, "ssssssssi", $dataNama, $dataJnskel, $dataTelp, $dataBerlaku, $dataAlamat, $dataAlamat2, $dataPhoto, $dataNipnis, $noapk);
            }

            mysqli_stmt_execute($stmt) or die("Gagal Query Update Anggota : " . mysqli_error($koneksidb));
            mysqli_stmt_close($stmt);
            logTransaksi($iduser, date('Y-m-d H:i:s'), 'Data Anggota diubah', $noapk);

            echo "<div class='alert alert-success alert-dismissable'>
                    <button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
                    <strong><i class='fa fa-check'></i>&nbsp;</strong>Data berhasil diubah.
                  </div>";
        } else {
            // ===================== INSERT =======================
            try {
                if (!$isGuru) {
                    // Insert SISWA
                    $insQry = "INSERT INTO ranggota 
                                (nipnis, idjnsang, idkelas, tgldaftar, nama, alamat, telp, berlaku, alamat2, photo, jnskel, noapk) 
                               VALUES (?, 1, ?, CURDATE(), ?, ?, ?, ?, ?, ?, ?, ?)";
                    $stmt = mysqli_prepare($koneksidb, $insQry);
                    mysqli_stmt_bind_param($stmt, "sssssssssi", $dataNipnis, $dataIdKelas, $dataNama, $dataAlamat, $dataTelp, $dataBerlaku, $dataAlamat2, $dataPhoto, $dataJnskel, $noapk);
                } else {
                    // Insert GURU
                    $insQry = "INSERT INTO ranggota 
                                (nipnis, idjnsang, tgldaftar, nama, alamat, telp, berlaku, alamat2, photo, jnskel, noapk) 
                               VALUES (?, 2, CURDATE(), ?, ?, ?, ?, ?, ?, ?, ?)";
                    $stmt = mysqli_prepare($koneksidb, $insQry);
                    mysqli_stmt_bind_param($stmt, "ssssssssi", $dataNipnis, $dataNama, $dataAlamat, $dataTelp, $dataBerlaku, $dataAlamat2, $dataPhoto, $dataJnskel, $noapk);
                }

                if (mysqli_stmt_execute($stmt)) {
                    echo "<div class='alert alert-success alert-dismissable'>
                            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
                            <strong><i class='fa fa-check'></i>&nbsp;</strong>Data berhasil disimpan.
                          </div>";
                    logTransaksi($iduser, date('Y-m-d H:i:s'), 'Data Anggota Ditambah', $noapk);
                } else {
                    echo "<div class='alert alert-danger'>Gagal insert data anggota. " . mysqli_stmt_error($stmt) . "</div>";
                }
                mysqli_stmt_close($stmt);
            } catch (Exception $e) {
                if (strpos($e->getMessage(), "Duplicate entry") !== false) {
                    echo "<div class='alert alert-danger alert-dismissable'>
                            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
                            <strong><i class='fa fa-times'></i>&nbsp; NIP / NIS sudah ada, tidak boleh sama</strong>
                          </div>";
                }
            }
        }
    }
}
?>



<!-- Sisa kode HTML dan Javascript Anda diletakkan di sini, tidak perlu ada perubahan -->
<!-- ... -->
<!-- PASTE THE REST OF YOUR HTML/JS CODE HERE -->

<div id="pesan">
</div>
<div class="portlet box <?= $_SESSION['warnabar'] ?>">
    <div class="portlet-title">
        <div class="caption">Tambah / Edit Anggota Individu</div>
        <div class="caption" style="margin-left: 10px;">
            <button onclick="jenis('siswa')" class="btn <?= $_SESSION['warnatombol'] ?>">SISWA</button>
            <button onclick="jenis('guru')" class="btn <?= $_SESSION['warnatombol'] ?>">GURU/KARYAWAN</button>
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
<?php 
// ... (Kode untuk mengambil data dan mengisi form)
$txtID      = isset($_GET['id']) ? $_GET['id'] : "";
$txtJenis   = isset($_GET['jenis']) ? $_GET['jenis'] : "";

if($txtJenis!="guru"){
    $qryCek  = mysqli_query($koneksidb, "SELECT nipnis, nama, idkelas, jnskel, telp, berlaku, alamat, alamat2 FROM ranggota 
                            WHERE nipnis = '".$txtID."' AND idjnsang = 1 AND noapk = $_SESSION[noapk]");
    if (mysqli_num_rows($qryCek)>0){
            $rs = mysqli_fetch_array($qryCek);
            $dataNamaS          =  $rs['nama'];
            $dataIdKelas        =  $rs['idkelas']; 
            $dataJnskelS        =  $rs['jnskel']; 
            $dataTelpS          =  $rs['telp']; 
            $dataBerlakuS       =  $rs['berlaku'];
            $dataAlamatS        =  $rs['alamat'];
            $dataAlamat2S       =  $rs['alamat2'];
            $dataNipnisS        =  $rs['nipnis'];
    }
}else if ($txtJenis=="guru"){
    $qryCek2  = mysqli_query($koneksidb, "SELECT nipnis, nama, jnskel, telp, berlaku, alamat, alamat2 FROM ranggota 
                            WHERE nipnis = '".$txtID."' AND idjnsang = 2 AND noapk = $_SESSION[noapk]");
    
    if (mysqli_num_rows($qryCek2)>0){
            $rs = mysqli_fetch_array($qryCek2);
            $dataNamaGK         =  $rs['nama']; 
            $dataJnskelGK       =  $rs['jnskel']; 
            $dataTelpGK         =  $rs['telp']; 
            $dataBerlakuGK      =  $rs['berlaku'];
            $dataAlamatGK       =  $rs['alamat'];
            $dataAlamat2GK      =  $rs['alamat2'];
            $dataNipnisGK       =  $rs['nipnis'];
    } 
}

if(@$_GET['jenis']!="guru"){ ?>
<form action="<?php $_SERVER['PHP_SELF']; ?>" id="uploadForm" method="post" class="form-horizontal" role="form" autocomplete="off" name="form1" enctype="multipart/form-data">
<div class="row-col-2">

<!-- FORM SISWA -->
<div class="form-body siswa">
    <h4>Siswa</h4>
        <div class="form-group siswa">
                <label class="col-lg-1 control-label">Kelas</label>
                <div class="col-lg-3">
                <select name="txtKelas" id="txtKelas"  data-placeholder="- Pilih Kelas -" class="select2me form-control" required >
                <option value=""></option> 
                <?php
                        $dataSql = "SELECT idkelas,deskelas FROM rkelas WHERE noapk = $_SESSION[noapk] ORDER BY idkelas";
                        $dataQry = mysqli_query( $koneksidb, $dataSql) or die ("Gagal Query".mysqli_error($koneksidb));
                        while ($dataRow = mysqli_fetch_array($dataQry)) {
                        $cek = (@$dataIdKelas==$dataRow['idkelas']) ? "selected" :"";
                        echo "<option value='$dataRow[idkelas]' $cek>$dataRow[deskelas]</option>";
                        }
                        $sqlData ="";
                ?>
                </select>
                </div>
        </div>

        <div class="form-group">
            <input type="hidden" name="idEdit" value="<?=@$dataNipnisS?>">
                <label class="col-lg-1 control-label">NIS</label>
                (ID Anggota)
                <div class="col-lg-3">
                    <input type="text" id="txtIdAnggotaS" name="txtIdAnggota" placeholder="NIS / ID ANGGOTA" value="<?=@$dataNipnisS?>" class="form-control sm" required/>
                </div>
                <div class="col-lg-1">
                <button type="button" id="cariAnggotaS" class="btn <?= $_SESSION['warnabar'] ?>"><i class="fa fa-search"></i> Cari</button>
                </div>
            </div>

        <div class="form-group">
            <label class="col-lg-1 control-label">Nama</label>
            <div class="col-lg-4">
                <input type="text" id="txtNama" name="txtNama" placeholder="Nama Pengguna" value="<?=@$dataNamaS?>" class="form-control sm" required/></span>
            </div>
            <label class="col-lg-1 control-label">Jenis Kelamin</label>
            <div class="col-lg-4">
                <label class="radio-inline"><input type="radio" name="txtJnsKel" value="L" <?= (@$dataJnskelS=="L") ? "checked" : "" ?> required/>Laki-laki</label>
                <label class="radio-inline"><input type="radio" name="txtJnsKel" value="P" <?= (@$dataJnskelS=="P") ? "checked" : "" ?> required/>Perempuan</label>
            </div>
        </div>

        <div class="form-group">
    <label class="control-label col-lg-1" for="separateFormInput1">Kota</label>
    <div class="col-lg-4">
        <textarea name="txtAlamat" placeholder="Nama Kota" class="form-control" id="separateFormInput1" rows="2"><?= @$dataAlamatS ?></textarea>
    </div>
    </div>

    <div class="form-group">
        <label class="control-label col-lg-1" for="separateFormInput1">Alamat</label>
        <div class="col-lg-4">
            <textarea name="txtAlamatAlt" placeholder="Nama Alamat" class="form-control" id="separateFormInput3" rows="2"><?= @$dataAlamat2S ?></textarea>
        </div>
    </div>

    
    <div class="form-group">
        <label class="col-lg-1 control-label">Telepon</label>
        <div class="col-lg-3">
            <input type="text" placeholder="Nomor Telepon" id="txtTelp" name="txtTelp" value="<?= @$dataTelpS ?>" class="form-control sm" required/></span>
        </div>
    </div>

    <div class="form-group">
        <label class="col-lg-1 control-label">Berlaku s/d</label>
        <div class="col-lg-3">
            <input type="date" id="txtBerlaku" name="txtBerlaku" value="<?= (@$dataBerlakuS != "") ? $dataBerlakuS : date("Y-m-d", strtotime("+1 month")) ?>" class="form-control sm" required/></span>
        </div>
        <label class="col-lg-2 control-label">Insert Foto</label>
        <div class="col-lg-3">
            <input type="file" id="txtFoto" name="txtFoto"  class="form-control sm"/></span>
        </div>
    </div>


    </div>
</div>
<footer class="panel-footer siswa">
                <div class="row">
                    <div class="form-group">
                        <div class="col-lg-offset-2 col-lg-10">
                            <button type="submit" name="btnSaveSiswa" class="btn <?= $_SESSION['warnabar'] ?>" ><i class="fa fa-save"></i> Simpan Data</button>
                            <a href="?content=tambahindividu" class="btn <?= $_SESSION['warnabar'] ?>"><i class="fa fa-undo"></i> Kembali</a>
                        </div>
                    </div>
                </div>
            </footer>

</form>
<?php }else{ ?>

<form action="<?php $_SERVER['PHP_SELF']; ?>" id="uploadForm" method="post" class="form-horizontal" role="form" autocomplete="off" name="form1" enctype="multipart/form-data">

<!-- FORM GURU / KARYAWAN -->
<div class="form-body guru">
<h4>Guru/Karyawan</h4>
        <div class="form-group">
            <input type="hidden" name="idEdit" value="<?=@$dataNipnisGK?>">
                <label class="col-lg-1 control-label">NIK</label>
                (ID Anggota)
                <div class="col-lg-3">
                    <input type="text" id="txtIdAnggotaGK" name="txtIdAnggota" placeholder="NIK / ID ANGGOTA" value="<?=@$dataNipnisGK?>" class="form-control sm" required/>
                </div>
                <div class="col-lg-1">
                <button type="button" id="cariAnggotaGK" class="btn <?= $_SESSION['warnabar'] ?>"><i class="fa fa-search"></i> Cari</button>
                </div>
            </div>

        <div class="form-group">
            <label class="col-lg-1 control-label">Nama</label>
            <div class="col-lg-4">
                <input type="text" id="txtNama" name="txtNama" placeholder="Nama Anggota" value="<?=@$dataNamaGK?>" class="form-control sm" required/></span>
            </div>
            <label class="col-lg-1 control-label">Jenis Kelamin</label>
            <div class="col-lg-4">
                <label class="radio-inline"><input type="radio" name="txtJnsKel" value="L" <?= (@$dataJnskelGK=="L") ? "checked" : "" ?> required/>Laki-laki</label>
                <label class="radio-inline"><input type="radio" name="txtJnsKel" value="P" <?= (@$dataJnskelGK=="P") ? "checked" : "" ?> required/>Perempuan</label>
            </div>
        </div>

        <div class="form-group">
    <label class="control-label col-lg-1" for="separateFormInput1">Kota</label>
    <div class="col-lg-4">
        <textarea name="txtAlamat" placeholder="Nama Kota" class="form-control" id="separateFormInput1" rows="2"><?= @$dataAlamatGK ?></textarea>
    </div>
    </div>

    <div class="form-group">
        <label class="control-label col-lg-1" for="separateFormInput1">Alamat</label>
        <div class="col-lg-4">
            <textarea name="txtAlamatAlt" placeholder="Nama Alamat" class="form-control" id="separateFormInput3" rows="2"><?= @$dataAlamat2GK ?></textarea>
        </div>
    </div>

    
    <div class="form-group">
        <label class="col-lg-1 control-label">Telepon</label>
        <div class="col-lg-3">
            <input type="text" id="txtTelp" name="txtTelp" placeholder="Nomor Telepon" value="<?= @$dataTelpGK ?>" class="form-control sm" required/></span>
        </div>
    </div>

    <div class="form-group">
        <label class="col-lg-1 control-label">Berlaku s/d</label>
        <div class="col-lg-3">
            <input type="date" id="txtBerlaku" name="txtBerlaku" value="<?= (@$dataBerlakuGK) ? $dataBerlakuGK : date("Y-m-d", strtotime("+1 month")) ?>" class="form-control sm" required/></span>
        </div>
        <label class="col-lg-2 control-label">Insert Foto</label>
        <div class="col-lg-3">
            <input type="file" id="txtFoto" name="txtFoto"  class="form-control sm"/></span>
        </div>
    </div>


    </div>
</div>
    <footer class="panel-footer guru">
                <div class="row">
                    <div class="form-group">
                        <div class="col-lg-offset-2 col-lg-10">
                            <button type="submit" name="btnSaveGuru" class="btn <?= $_SESSION['warnabar'] ?>"><i class="fa fa-save"></i> Simpan Data</button>
                            <a href="?content=tambahindividu" class="btn <?= $_SESSION['warnabar'] ?>"><i class="fa fa-undo"></i> Kembali</a>
                        </div>
                    </div>
                </div>
            </footer>
</form>

<?php } ?>

<script>

    function cari(selectedType){
      var currentUrl = window.location.href;
      var newUrl;

      var regex = /[?&]id=[^&]*/g;
      var newUrl = currentUrl.replace(regex, '');

      if (newUrl.includes('?')) {
        newUrl += '&id=' + selectedType;
      } else {
        newUrl += '?id=' + selectedType;
      }

      window.history.pushState({ path: newUrl }, '', newUrl);
      window.location.reload();
    }

    function jenis(selectedType){
      var currentUrl = window.location.href;
      var newUrl;

      var regex = /[?&]jenis=[^&]*/g;
      var newUrl = currentUrl.replace(regex, '');

      if (newUrl.includes('?')) {
        newUrl += '&jenis=' + selectedType;
      } else {
        newUrl += '?jenis=' + selectedType;
      }

      window.history.pushState({ path: newUrl }, '', newUrl);
      window.location.reload();
    }

    if(document.getElementById('cariAnggotaS')){
        document.getElementById('cariAnggotaS').addEventListener('click', function () {
            var selectedType = document.getElementById('txtIdAnggotaS').value; 
            cari(selectedType);
          });
    }

    if(document.getElementById('cariAnggotaGK')){
  document.getElementById('cariAnggotaGK').addEventListener('click', function () {
      var selectedType = document.getElementById('txtIdAnggotaGK').value; 
      cari(selectedType);
    });
}
        
</script>
