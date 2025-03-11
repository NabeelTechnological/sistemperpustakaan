<?php	
// Security goes here

$iduser = $_SESSION['iduser'];
$noapk  = $_SESSION['noapk'];

// Declare variable post
if (isset($_POST['btnSave'])) {
    $dataIdKelasLama   = isset($_POST['txtIdKelasLama']) ? $_POST['txtIdKelasLama'] : ""; // ID Kelas lama untuk referensi update
    $dataIdKelasBaru   = isset($_POST['txtSingkatanKelas']) ? $_POST['txtSingkatanKelas'] : "";
    $dataDesKelas      = isset($_POST['txtNamaKelas']) ? $_POST['txtNamaKelas'] : "";

    // Cek jika ada field yang kosong
    if (empty($dataIdKelasBaru) || empty($dataDesKelas)) {
        echo "<div class='alert alert-danger alert-dismissable'>
                <button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
                <strong><i class='fa fa-times'></i>&nbsp; Data Tidak Boleh Ada yang Kosong </strong>
              </div>";
    } else {
        // Update kelas (singkatan kelas & nama kelas)
        $insQry = "UPDATE rkelas SET idkelas = ?, deskelas = ? WHERE idkelas = ? AND noapk = ?";
        $stmt = mysqli_prepare($koneksidb, $insQry) or die("Gagal menyiapkan statement: " . mysqli_error($koneksidb));
        mysqli_stmt_bind_param($stmt, "sssi", $dataIdKelasBaru, $dataDesKelas, $dataIdKelasLama, $_SESSION['noapk']);
        mysqli_stmt_execute($stmt) or die("Gagal Query Update Kelas : " . mysqli_error($koneksidb));
        mysqli_stmt_close($stmt);

        logTransaksi($iduser, date('Y-m-d H:i:s'), 'Data kelas Diubah', $noapk);

        echo "<div class='alert alert-success alert-dismissable'>
                <button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
                <strong><i class='fa fa-check'></i>&nbsp;".$dataIdKelasBaru ."</strong> Sukses diubah. 
              </div>";
    }
} else {
    $txtID = isset($_GET['id']) ? $_GET['id'] : "";
    $qryCek = mysqli_query($koneksidb, "SELECT idkelas, deskelas FROM rkelas 
                WHERE noapk = $_SESSION[noapk] AND idkelas = '".mysqli_real_escape_string($koneksidb, $txtID)."'") 
                or die('Gagal Query Cek.'. mysqli_error($koneksidb));

    if (mysqli_num_rows($qryCek) > 0) {
        $rs = mysqli_fetch_array($qryCek);
        $dataIdKelasLama = $rs['idkelas']; // Simpan ID lama untuk referensi update
        $dataIdKelasBaru = $rs['idkelas'];
        $dataDesKelas = $rs['deskelas'];
    } 
}
?>

<SCRIPT language="JavaScript">
    function submitform() {
        document.form1.submit();
    }
</SCRIPT>

<div class="portlet box <?= $_SESSION['warnabar'] ?>">
    <div class="portlet-title">
        <div class="caption">Form Ubah Kelas</div>
        <div class="tools">
            <a href="javascript:;" class="collapse"></a>
            <a href="javascript:;" class="reload"></a>
            <a href="javascript:;" class="remove"></a>
        </div>
    </div>
    <div class="portlet-body form">
        <form action="<?php $_SERVER['PHP_SELF']; ?>" method="post" class="form-horizontal" role="form" autocomplete="off" name="form1">
            <div class="form-body">
                <input type="hidden" name="txtIdKelasLama" value="<?= $dataIdKelasLama ?>" />

                <div class="form-group">
                    <label class="col-lg-2 control-label">Singkatan Kelas</label>
                    <div class="col-lg-3">
                        <input type="text" id="txtSingkatanKelas" name="txtSingkatanKelas" class="form-control sm" required value="<?= $dataIdKelasBaru ?>" />
                    </div>    
                </div> 

                <div class="form-group">
                    <label class="col-lg-2 control-label">Nama Kelas</label>
                    <div class="col-lg-3">
                        <input type="text" id="txtNamaKelas" name="txtNamaKelas" class="form-control sm" required value="<?= $dataDesKelas ?>" />
                    </div>
                </div>

                <footer class="panel-footer">
                    <div class="row">
                        <div class="form-group">
                            <div class="col-lg-offset-2 col-lg-10">
                                <button type="submit" name="btnSave" class="btn <?= $_SESSION['warnabar'] ?>"><i class="fa fa-save"></i> Simpan Data</button>
                                <a href="?content=kelas" class="btn <?= $_SESSION['warnabar'] ?>"><i class="fa fa-undo"></i> Kembali</a>
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
        </form>
    </div>
</div>
