<?php
//security goes here 

use PhpOffice\PhpSpreadsheet\Worksheet\Row;

if(isset($_SESSION['toastMessage'])) {
    echo $_SESSION['toastMessage'];
    unset($_SESSION['toastMessage']);
}
/*** 
 ***/
// //ujicoba semoga berhasil
// $dataJudul = '';
// $dataIdBuku='';
// $dataTersedia='';
// $stmt='';
// $dataJmlCd='';
// $dataNamaPeminjam='';
// $dataNonPaket='';
// $dataNama='';





if (!isset($_GET['id']) || $_GET['id'] == "") {
    
} else if (isset($_GET['id'])) {
    $txtID = $_GET['id'];
    $berlaku = getBerlaku($koneksidb, $txtID);

}

    if ($berlaku == NULL) {
        
    }else if ($berlaku >= date("Y-m-d")) {


        //  if (isset($_GET['id'])) 
        //    {
        //      $qry = "SELECT a.nipnis, a.Nama, a.berlaku,
        //     SUM(CASE WHEN ispaket = 0 AND iskembali = 0 THEN 1 ELSE 0 END) AS nonpaket,
        //     SUM(CASE WHEN ispaket = 1 AND iskembali = 0 THEN 1 ELSE 0 END) AS paket,
        //     c.maksitem, c.maksjkw, c.periode, c.idjnsang
        //      FROM ranggota a LEFT JOIN tpinbuku b ON a.nipnis = b.nipnis
        //      LEFT JOIN rreftrans c ON a.idjnsang = c.idjnsang WHERE a.nipnis = ? AND c.idjnspustaka = 1 AND a.noapk = $_SESSION[noapk]
        //      GROUP BY a.nipnis, a.Nama, a.berlaku, c.maksitem, c.maksjkw, c.periode, c.idjnsang";

        //         // $dataNama = $row['a.nama'];
        //         //$dataNama = isset($_POST['txtnama']) ? $_POST['txtnama'] : "";

             
        //         $stmt = mysqli_prepare($koneksidb, $qry) or die("Gagal menyiapkan statement : " . mysqli_error($koneksidb));
        //         mysqli_stmt_bind_param($stmt, "s", $txtID);
        //         mysqli_stmt_execute($stmt) or die("Gagal Query Tampil Anggota : " . mysqli_error($koneksidb));
        //         mysqli_stmt_bind_result($stmt, $dataNipnis, $dataNama, $dataBerlaku, $dataNonPaket, $dataPaket, $dataMaksItem, $dataMaksJkw, $dataPeriode, $dataIdJnsAng);
        //         mysqli_stmt_fetch($stmt);
        //         mysqli_stmt_close($stmt);

        if (isset($_GET['id'])) {
            // require_once 'koneksi.php'; // Pastikan koneksi database disertakan
            
            $txtID = $_GET['id'];
            $qry = "SELECT nipnis, nama, berlaku
            FROM ranggota
            WHERE nipnis = ? AND noapk = $_SESSION[noapk]";

                $stmt = mysqli_prepare($koneksidb, $qry) or die("Gagal menyiapkan statement : " . mysqli_error($koneksidb));
                mysqli_stmt_bind_param($stmt, "s", $txtID);
                mysqli_stmt_execute($stmt) or die("Gagal Query Tampil id : " . mysqli_error($koneksidb));
                mysqli_stmt_bind_result($stmt, $dataNipnis, $dataNama, $databerlaku);
                mysqli_stmt_fetch($stmt);
                mysqli_stmt_close($stmt);
            
            if (!isset($_SESSION['noapk'])) {
                die("Session noapk tidak tersedia.");
            }
            $noapk = $_SESSION['noapk'];
        
            $qry = "SELECT a.nipnis, a.Nama, a.berlaku,
                        COALESCE(SUM(CASE WHEN b.ispaket = 0 AND b.iskembali = 0 THEN 1 ELSE 0 END), 0) AS NonPaket,
                    COALESCE(SUM(CASE WHEN b.ispaket = 1 AND b.iskembali = 0 THEN 1 ELSE 0 END), 0) AS Paket,
            -- SUM(CASE WHEN b.ispaket = 0 AND iskembali = 0 THEN 1 ELSE 0 END) AS nonpaket,
            -- SUM(CASE WHEN b.ispaket = 1 AND iskembali = 0 THEN 1 ELSE 0 END) AS paket,
                        c.maksitem, c.maksjkw, c.periode, c.idjnsang
                    FROM ranggota a 
                    LEFT JOIN tpinbuku b ON a.nipnis = b.nipnis
                    LEFT JOIN rreftrans c ON a.idjnsang = c.idjnsang
                    WHERE a.nipnis = ? 
                    AND c.idjnspustaka = 1 
                    AND a.noapk = ?
                    GROUP BY a.nipnis, a.Nama, a.berlaku, c.maksitem, c.maksjkw, c.periode, c.idjnsang";
        
            $stmt = mysqli_prepare($koneksidb, $qry);
            if (!$stmt) {
                die("Gagal menyiapkan statement: " . mysqli_error($koneksidb));
            }

            // $dataIdJnsAng = 'idjnsang';

            // $dataNipnis = 'txtnipnis';
            // $dataNama = "";
            // $dataBerlaku = "";            
        
            mysqli_stmt_bind_param($stmt, "ss", $txtID, $noapk);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $dataNipnis, $dataNama, $databerlaku, $dataNonPaket, $dataPaket, $datamaksitem, $dataMaksJkw, $dataPeriode, $dataIdJnsAng);
        
            if (mysqli_stmt_fetch($stmt)) {
                // echo "Nama: " . $dataNama;
                // echo "Maks Item: " . $datamaksitem;
            } else {
                echo "Data tidak ditemukan.";
            }
        
            mysqli_stmt_close($stmt);
        
        
        

                // $dataNipnis = '';
                //  $dataNama = $_POST['a.Nama'];
                // $dataBerlaku = '';
                // $dataNonPaket = '';
                // $dataPaket = '';
                // $dataMaksItem = '';
                // $dataMaksJkw = '';
                // $dataPeriode = '';
                // $dataIdJnsAng = '';

                $dataIdAnggota         =  isset($_POST['txtIdAnggota']) ? $_POST['txtIdAnggota'] : "";
                $dataIdBuku         =  isset($_POST['txtIdBuku']) ? $_POST['txtIdBuku'] : "";
                $dataIdJnsAng       =  isset($_POST['txtIdJnsAng']) ? $_POST['txtIdJnsAng'] : "";
                $dataJadwalKembali  =  isset($_POST['txtJadwalKembali']) ? $_POST['txtJadwalKembali'] : "";
                $dataBukuPaket      =  isset($_POST['txtBukuPaket']) ? $_POST['txtBukuPaket'] : "0";
                $dataStatus = '';
                
            // } else if ($_GET['jenis'] == "cd") {

            //     $qry = "SELECT a.nipnis, a.nama,a.berlaku,
            //     SUM(CASE WHEN iskembali = 0 THEN 1 ELSE 0 END) AS jmlcd,
            //     c.maksitem, c.maksjkw, c.periode, c.idjnsang
            //      FROM ranggota a LEFT JOIN tpincd b ON a.nipnis = b.nipnis
            //      JOIN rreftrans c ON a.idjnsang = c.idjnsang WHERE a.nipnis = ? AND c.idjnspustaka = 2
            //      GROUP BY a.nipnis, a.nama, a.berlaku, c.maksitem, c.maksjkw, c.periode
            //      ";

            //     $stmt = mysqli_prepare($koneksidb, $qry) or die("Gagal menyiapkan statement : " . mysqli_error($koneksidb));
            //     mysqli_stmt_bind_param($stmt, "s", $txtID);
            //     mysqli_stmt_execute($stmt) or die("Gagal Query Tampil Anggota : " . mysqli_error($koneksidb));
            //     mysqli_stmt_bind_result($stmt, $dataNipnis, $dataNama, $dataBerlaku, $dataJmlCd, $dataMaksItem, $dataMaksJkw, $dataPeriode, $dataIdJnsAng);
            //     mysqli_stmt_fetch($stmt);
            //     mysqli_stmt_close($stmt);
            // }


            if (isset($_GET['buku'])) {
                $txtID = $_GET['buku'];
                $qry = "SELECT idbuku, judul, tersedia
            FROM tbuku
            WHERE idbuku = ? AND noapk = $_SESSION[noapk]
             ";

             $datajudul = $row['judul'];

                $stmt = mysqli_prepare($koneksidb, $qry) or die("Gagal menyiapkan statement : " . mysqli_error($koneksidb));
                mysqli_stmt_bind_param($stmt, "s", $txtID);
                mysqli_stmt_execute($stmt) or die("Gagal Query Tampil Buku : " . mysqli_error($koneksidb));
                mysqli_stmt_bind_result($stmt, $dataIdBuku, $datajudul, $dataTersedia);
                mysqli_stmt_fetch($stmt);
                mysqli_stmt_close($stmt);

                $qry = "SELECT b.Nama, a.ispaket
            FROM tpinbuku a LEFT JOIN ranggota b ON a.nipnis = b.nipnis
            WHERE a.idbuku = ? AND iskembali = 0 AND a.noapk = $_SESSION[noapk]
             ";

                $stmt = mysqli_prepare($koneksidb, $qry) or die("Gagal menyiapkan statement : " . mysqli_error($koneksidb));
                mysqli_stmt_bind_param($stmt, "s", $txtID);
                mysqli_stmt_execute($stmt) or die("Gagal Query Tampil Buku : " . mysqli_error($koneksidb));
                mysqli_stmt_bind_result($stmt, $dataNamaPeminjam, $dataispaket);
                mysqli_stmt_fetch($stmt);
                mysqli_stmt_close($stmt);

            if ($dataIdBuku == NULL) {
                echo "<div class='alert alert-danger alert-dismissable'>
                <button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
                <strong><i class='fa fa-times'></i>&nbsp; Buku yang dicari tidak ada</strong>
                </div>";
            }
        }
            // } else if (isset($_GET['cd'])) {
            //     $txtID = $_GET['cd'];
            //     $qry = "SELECT c.nama, a.idcd, a.judul, a.tersedia
            // FROM tcd a LEFT JOIN tpincd b on a.idcd = b.idcd  LEFT JOIN ranggota c on b.nipnis = c.nipnis 
            // WHERE a.idcd = ? AND noapk = $_SESSION[noapk]
            //  ";

            //     $stmt = mysqli_prepare($koneksidb, $qry) or die("Gagal menyiapkan statement : " . mysqli_error($koneksidb));
            //     mysqli_stmt_bind_param($stmt, "s", $txtID);
            //     mysqli_stmt_execute($stmt) or die("Gagal Query Tampil Anggota : " . mysqli_error($koneksidb));
            //     mysqli_stmt_bind_result($stmt, $dataNamaPeminjam, $dataIdCd, $dataJudul, $dataTersedia);
            //     mysqli_stmt_fetch($stmt);
            //     mysqli_stmt_close($stmt);
            // }


            if (isset($_POST['btnSave'])) {

                // $datadesjnsbuku = isset($_POST['txtdesjnsbuku']) ? $_POST['txtdesjnsbuku'] : ""; // Contoh mendefinisikan

                $dataIdAnggota         =  isset($_POST['txtIdAnggota']) ? $_POST['txtIdAnggota'] : "";
                $dataIdBuku         =  isset($_POST['txtIdBuku']) ? $_POST['txtIdBuku'] : "";
                $dataIdCd           =  isset($_POST['txtIdCd']) ? $_POST['txtIdCd'] : "";
                $dataIdJnsAng       =  isset($_POST['txtIdJnsAng']) ? $_POST['txtIdJnsAng'] : "";
                $dataJadwalKembali  =  isset($_POST['txtJadwalKembali']) ? $_POST['txtJadwalKembali'] : "";
                $dataBukuPaket      =  isset($_POST['txtBukuPaket']) ? $_POST['txtBukuPaket'] : "0";
                $dataStatus         =  isset($_POST['txtStatus']) ? $_POST['txtStatus'] : "";

                if (empty($dataIdAnggota) || (empty($dataIdBuku) && empty($dataIdCd))) {
                    $_SESSION['toastMessage'] = "<div class='alert alert-danger alert-dismissable'>
                <button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
                <strong><i class='fa fa-times'></i>&nbsp; Data Tidak Boleh Ada yang Kosong </strong>
                </div>";
                } else {
                    if ($dataStatus == "DIPINJAM" || $dataStatus == "RUSAK" || $dataStatus == "HILANG") {
                        $_SESSION['toastMessage'] = "<div class='alert alert-danger alert-dismissable'>
                <button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
                <strong><i class='fa fa-times'></i>&nbsp; Buku / CD $dataStatus , tidak bisa dipinjamkan</strong>
                </div>";
                    } else {
                        if (!empty($dataIdBuku)) {
                            $insQry = "insert into tpinbuku (idbuku, nipnis, idjnsang, iduser, tglpinjam, tglhrskembali, ispaket, judul, noapk) 
                values (?, ?, ?, ?, CURDATE(), ?, ?, ?, $_SESSION[noapk]) ";
                            $stmt = mysqli_prepare($koneksidb, $insQry) or die("Gagal menyiapkan statement: " . mysqli_error($koneksidb));
                            mysqli_stmt_bind_param($stmt, "ssissss", $dataIdBuku, $dataIdAnggota, $dataIdJnsAng, $_SESSION['iduser'], $dataJadwalKembali, $dataBukuPaket, $datajudul);
                            mysqli_stmt_execute($stmt) or die("Gagal Query Insert Pinjam Buku : " . mysqli_error($koneksidb));
                            mysqli_stmt_close($stmt);

                            $updateQry = "UPDATE tbuku SET tersedia = 0 WHERE idbuku = ? AND noapk = $_SESSION[noapk]";
                            $stmt = mysqli_prepare($koneksidb, $updateQry) or die("Gagal menyiapkan statement: " . mysqli_error($koneksidb));
                            mysqli_stmt_bind_param($stmt, "s", $dataIdBuku);
                            mysqli_stmt_execute($stmt) or die("Gagal Query Update Tersedia : " . mysqli_error($koneksidb));
                            mysqli_stmt_close($stmt);

                            $_SESSION['toastMessage'] = "<div class='alert alert-success alert-dismissable'>
                    <button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
                    <strong><i class='fa fa-check'></i>&nbsp;</strong>Data Sukses insert. 
                    </div>";
                        } 
                        
                //         else if (!empty($dataIdCd)) {
                //             $insQry = "insert into tpincd (idcd, nipnis, idjnsang, iduser,tglpinjam,tglhrskembali,noapk) 
                // values (?, ?, ?, ?, CURDATE(), ?, $_SESSION[noapk]) ";
                //             $stmt = mysqli_prepare($koneksidb, $insQry) or die("Gagal menyiapkan statement: " . mysqli_error($koneksidb));
                //             mysqli_stmt_bind_param($stmt, "ssiss", $dataIdCd, $dataIdAnggota, $dataIdJnsAng, $_SESSION['iduser'], $dataJadwalKembali);
                //             mysqli_stmt_execute($stmt) or die("Gagal Query Insert Pinjam CD : " . mysqli_error($koneksidb));
                //             mysqli_stmt_close($stmt);

                //             $updateQry = "UPDATE tcd SET tersedia = 0 WHERE idcd = ? AND noapk = $_SESSION[noapk]";
                //             $stmt = mysqli_prepare($koneksidb, $updateQry) or die("Gagal menyiapkan statement: " . mysqli_error($koneksidb));
                //             mysqli_stmt_bind_param($stmt, "s", $dataIdCd);
                //             mysqli_stmt_execute($stmt) or die("Gagal Query Update Tersedia : " . mysqli_error($koneksidb));
                //             mysqli_stmt_close($stmt);

                //             $_SESSION['toastMessage'] = "<div class='alert alert-success alert-dismissable'>
                //     <button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
                //     <strong><i class='fa fa-check'></i>&nbsp;</strong>Data Sukses insert. 
                //     </div>";
                //         }
                    }
                }
            }

            if (isset($_POST['del'])) {
                $txtIDBuku       = $_POST['idBuku'];
                $txtIDCd         = $_POST['idCd'];
                $txtTglPinjam    = $_POST['tglPinjam'];

                if (date("Y-m-d") == $txtTglPinjam) {
                    if (!empty($txtIDBuku)) {
                        $insQry = "DELETE FROM tpinbuku WHERE idbuku = ? AND noapk = $_SESSION[noapk]";
                        $stmt = mysqli_prepare($koneksidb, $insQry) or die("Gagal menyiapkan statement: " . mysqli_error($koneksidb));
                        mysqli_stmt_bind_param($stmt, "s", $txtIDBuku);
                        mysqli_stmt_execute($stmt) or die("Gagal Query Hapus : " . mysqli_error($koneksidb));
                        mysqli_stmt_close($stmt);

                        $updateQry = "UPDATE tbuku SET tersedia = 1 WHERE idbuku = ? AND noapk = $_SESSION[noapk]";
                        $stmt = mysqli_prepare($koneksidb, $updateQry) or die("Gagal menyiapkan statement: " . mysqli_error($koneksidb));
                        mysqli_stmt_bind_param($stmt, "s", $txtIDBuku);
                        mysqli_stmt_execute($stmt) or die("Gagal Query Update Tersedia : " . mysqli_error($koneksidb));
                        mysqli_stmt_close($stmt);

                        echo "<div class='alert alert-success alert-dismissable'>
                    <button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
                    <strong><i class='fa fa-check'></i>&nbsp;</strong>Berhasil melakukan pembatalan peminjaman.
                    </div>";
                    }
                    
                    // else if (!empty($txtIDCd)) {
                    //     $insQry = "DELETE FROM tpincd WHERE idcd = ? AND noapk = $_SESSION[noapk]";
                    //     $stmt = mysqli_prepare($koneksidb, $insQry) or die("Gagal menyiapkan statement: " . mysqli_error($koneksidb));
                    //     mysqli_stmt_bind_param($stmt, "s", $txtIDCd);
                    //     mysqli_stmt_execute($stmt) or die("Gagal Query Hapus : " . mysqli_error($koneksidb));
                    //     mysqli_stmt_close($stmt);

                    //     $updateQry = "UPDATE tcd SET tersedia = 1 WHERE idcd = ? AND noapk = $_SESSION[noapk]";
                    //     $stmt = mysqli_prepare($koneksidb, $updateQry) or die("Gagal menyiapkan statement: " . mysqli_error($koneksidb));
                    //     mysqli_stmt_bind_param($stmt, "s", $txtIDCd);
                    //     mysqli_stmt_execute($stmt) or die("Gagal Query Update Tersedia : " . mysqli_error($koneksidb));
                    //     mysqli_stmt_close($stmt);

                    //     echo "<div class='alert alert-success alert-dismissable'>
                    // <button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
                    // <strong><i class='fa fa-check'></i>&nbsp;</strong>Berhasil melakukan pembatalan peminjaman. 
                    // </div>";
                    // }
                } else {
                    echo "<div class='alert alert-danger alert-dismissable'>
            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
            <strong><i class='fa fa-times'></i>&nbsp; Tanggal peminjaman (" . IndonesiaTgl($txtTglPinjam) . ") TIDAK SAMA dengan tanggal hari ini (" . date("d-m-Y") . "). <br>Pembatalan tidak diperbolehkan.</strong>
            </div>";
                }
            }
        }
    } else {
        echo "<div class='alert alert-danger alert-dismissable'>
        <button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
        <strong><i class='fa fa-times'></i>&nbsp; Masa berlaku keanggotaan sudah habis</strong>
        </div>";
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
                <p>Pembatalan hanya dapat dilakukan di hari peminjaman.</p>
                <p>Anda yakin?</p>
            </div>
            <div class="modal-footer">
                <form method="post">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tidak</button>
                    <input type="hidden" name="idBuku" id="d_idBuku" value="">
                    <input type="hidden" name="idCd" id="d_idCd" value="">
                    <input type="hidden" name="tglPinjam" id="d_tglPinjam" value="">
                    <button type="submit" name="del" class="btn btn-danger">Yakin</button>
                </form>
            </div>
        </div>
    </div>
</div>



<div id="pesan">
</div>
<div class="portlet box <?= $_SESSION['warnabar'] ?>">
    <div class="portlet-title">
        <div class="caption">Peminjaman Individu
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
            <div class="row-col-2">
                <div class="form-body">
                    <input type="hidden" name="txtIdJnsAng" value="<?= $dataIdJnsAng ?>">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="col-lg-3 control-label">ID Anggota</label>
                                <div class="col-lg-3">
                                    <input type="text" id="txtIdAnggota" name="txtIdAnggota" value="<?= $dataNipnis ?>" class="form-control sm" required />
                                </div>
                                <div class="col-lg-1">
                                    <button type="button" id="cariAnggota" class="btn <?= $_SESSION['warnabar'] ?>"><i class="fa fa-search"></i> Cari</button>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-lg-3 control-label">Nama</label>
                                <div class="col-lg-6">
                                    <input type="text" id="txtNama" name="txtNama" value="<?= $dataNama ?>" class="form-control sm" readonly />
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-lg-3 control-label">Masa Berlaku Keanggotaan</label>
                                <div class="col-lg-3">
                                    <input type="date" id="txtberlaku" name="txtberlaku" value="<?= $databerlaku ?>" class="form-control sm" readonly />
                                </div>
                            </div>

                            <div class="form-group">
                                <?php // if ($_GET['jenis'] == "buku") { ?>
                                    <label class="col-lg-4 control-label">Jml Buku Dipinjam Non Paket</label>
                                    <div class="col-lg-2">
                                        <input type="number" id="txtNonPaket" name="txtNonPaket" value="<?= $dataNonPaket ?>" class="form-control sm" readonly />
                                    </div>
                                <?php // } else if ($_GET['jenis'] == "cd") { ?>
                                    <!-- <label class="col-lg-4 control-label">Jml CD Dipinjam</label>
                                    <div class="col-lg-2">
                                        <input type="number" id="txtCD" name="txtCD" value="<?= $dataJmlCd ?>" class="form-control sm" readonly />
                                    </div> -->
                                <?php // } ?>
                                <label class="col-lg-4 control-label">Maksimal Pinjam</label>
                                <div class="col-lg-2">
                                    <input type="number" id="txtmaksitem" name="txtmaksitem" value="<?= $datamaksitem ?>" class="form-control sm" readonly />
                                </div>
                            </div>

                            <div class="form-group">
                                <?php // if ($_GET['jenis'] == "buku") { ?>
                                    <label class="col-lg-4 control-label">Jumlah Buku Dipinjam Paket</label>
                                    <div class="col-lg-2">
                                        <input type="number" id="txtPaket" name="txtPaket" class="form-control sm" value="<?= $dataPaket ?>" readonly />
                                    </div>
                                <?php // } else if ($_GET['jenis'] == "cd") { ?>
                                    <!-- <div class="col-lg-6"></div> -->
                                <?php // } ?>
                                <label class="col-lg-4 control-label">Jangka Waktu
                                    <?php
                                    switch ($dataPeriode) {
                                        case "0":
                                            echo "Hari";
                                            break;
                                        case "1":
                                            echo "Pekan";
                                            break;
                                        case "2":
                                            echo "Bulan";
                                            break;
                                        case "3":
                                            echo "Semester";
                                            break;
                                    }
                                    ?></label>
                                <div class="col-lg-2">
                                    <input type="number" id="txtMaksHari" name="txtMaksHari" class="form-control sm" value="<?= $dataMaksJkw ?>" readonly />
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6" style="border-left: 1px solid grey;">
                            <label class="col-lg-3 control-label">Katalog Dipinjam :</label>

                            <div class="form-group">
                                <div class="col-lg-3">
                                    <label class="radio-inline"><input type="radio" name="txtJenis" id="txtJenis" value="buku" checked/>Buku</label>
                                    <!-- <label class="radio-inline"><input type="radio" name="txtJenis" id="txtJenis" value="cd" <?php //($_GET['jenis'] == "cd") ? "checked" : "" ?>>CD</label> -->
                                </div>
                                <div class="col-lg-5">
                                    <label class="control-label">Nama Peminjam [Jika Terpinjam]</label>
                                    <input type="text" id="txtNamaPeminjam" name="txtNamaPeminjam" value="<?= ($dataTersedia==0) ? $dataNamaPeminjam : "" ?>" class="form-control sm" readonly />
                                </div>
                            </div>

                            <div class="form-group">
                                <?php // if ($_GET['jenis'] == "buku") { ?>
                                    <label class="col-lg-2 control-label">ID Buku</label>
                                    <div class="col-lg-6">
                                        <input type="text" id="txtIdBuku" value="<?= $dataIdBuku ?>" name="txtIdBuku" class="form-control sm" required />
                                    </div>
                                    <div class="col-lg-2">
                                        <button type="button" id="cariBuku" class="btn <?= $_SESSION['warnabar'] ?>"><i class="fa fa-search"></i> Cari</button>
                                    </div>
                                <!-- <?php // } else if ($_GET['jenis'] == "cd") { ?>
                                    <label class="col-lg-2 control-label">ID CD</label>
                                    <div class="col-lg-6">
                                        <input type="text" id="txtIdCd" name="txtIdCd" value="<?php // $dataIdCd ?>" class="form-control sm" required />
                                    </div>
                                    <div class="col-lg-2">
                                        <button type="button" id="cariCd" class="btn <?php // $_SESSION['warnabar'] ?>"><i class="fa fa-search"></i> Cari</button>
                                    </div>
                                <?php // } ?> -->
                            </div>

                            <div class="form-group">
                                <label class="col-lg-2 control-label">Judul</label>
                                <div class="col-lg-9">
                                    <input type="text" id="txtjudul" name="txtjudul" value="<?= $datajudul ?>" class="form-control sm" readonly />
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-lg-2 control-label">Status</label>
                                <div class="col-lg-3">
                                    <input type="text" id="txtStatus" name="txtStatus" value="<?php
                                    if (isset($dataTersedia)) {
                                        echo desTersediaBuku($dataTersedia);
                                    }
                                    ?>" class="form-control sm" readonly />
                                </div>
                                <label class="col-lg-2 control-label">Jadwal Kembali</label>
                                <div class="col-lg-4">
                                    <input type="date" id="txtJadwalKembali" name="txtJadwalKembali" value="<?php
                    if (isset($dataMaksJkw) && isset($dataPeriode)) {
                    switch ($dataPeriode) {
                    case 0:
                    echo date("Y-m-d", strtotime("+" . $dataMaksJkw . " days"));
                    break;
                    case 1:
                    echo date("Y-m-d", strtotime("+" . $dataMaksJkw . " weeks"));
                    break;
                    case 2:
                    echo date("Y-m-d", strtotime("+" . $dataMaksJkw . " months"));
                    break;
                    case 3:
                    $dataMaksJkw = $dataMaksJkw * 6;
                    echo date("Y-m-d", strtotime("+" . $dataMaksJkw . " months"));
                    break;
                    default:
                    echo date("Y-m-d");
                    break;
                    }
    }
    ?>" class="form-control sm" required />
                                </div>
                            </div>

                            <div class="form-group">
                                <?php // if ($_GET['jenis'] == "buku") { ?>
                                    <label class="col-lg-3 control-label">Buku Paket</label>
                                    <div class="col-lg-2">
                                        <input type="checkbox" id="txtBukuPaket" name="txtBukuPaket" value="1" class="form-control sm" <?=($dataispaket==1) ? "checked" : "" ?>/>
                                    </div>
                                <?php //  } ?>
                                <div class="col-lg-3 pull-right">
                                    <button type="submit" name="btnSave" class="btn <?= $_SESSION['warnabar'] ?>"><i class="fa fa-save"></i> Pinjamkan</button>
                                </div>
                            </div>


                        </div>
                    </div>
                    <footer class="panel-footer">
                        <div class="row">
                            <div class="form-group">
                                <div class="col-lg-offset-2 col-lg-10">
                                    <a href="?content=peminjamanindividu" class="btn <?= $_SESSION['warnabar'] ?>"><i class="fa fa-undo"></i> Kembali</a>
                                </div>
                            </div>
                        </div>
                    </footer>
        </form>

        <div class=" portlet box <?= $_SESSION['warnabar'] ?>">
            <div class="portlet-title">
                <div class="caption">Data Peminjaman Individu</div>
            </div>

            <div class="portlet-body fieldset-form">

                <table class="table table-bordered table-hover table-condensed" id="sample_2" width="100%">
                    <thead>
                        <tr class="active">
                            <td width="5%">NO</td>
                            <?php if ($_GET['jenis'] == "buku") { ?>
                                <td>BUKU</td>
                                <td>ID BUKU</td>
                            <?php  } else if ($_GET['jenis'] == "cd") { ?>
                                <td>ID CD</td>
                            <?php  } ?>
                            <td>BUKU</td>
                            <TD>ID BUKU</TD>
                            <td>JUDUL</td>
                            <!-- <td>JENIS</td> -->
                            <td>TANGGAL PINJAM</td>
                            <td>JADWAL KEMBALI</td>
                            <td width="5%">ACTION</td>
                        </tr>
                    </thead>
                </table>
                </fieldset>
            </div>
        </div>
    </div>
</div>

<script src="plugin/datatable/jquery-3.5.1.js"></script>
<script src="plugin/datatable/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="plugin/datatable/jquery.dataTables.min.css">
<script>

    function removeParameterFromUrl(url, parameter) {
        var urlParts = url.split('?');
        if (urlParts.length >= 2) {
            var prefix = encodeURIComponent(parameter) + '=';
            var parts = urlParts[1].split(/[&;]/g);

            for (var i = parts.length; i-- > 0;) {
                if (parts[i].lastIndexOf(prefix, 0) !== -1) {
                    parts.splice(i, 1);
                }
            }

            url = urlParts[0] + (parts.length > 0 ? '?' + parts.join('&') : '');
        }
        return url;
    }

    $(document).ready(function() {
        const urlParams = new URLSearchParams(window.location.search);
        // const jenis = urlParams.get('jenis');
        const id = urlParams.get('id');

        if  (id) {
            $("#sample_2").dataTable().fnDestroy();

            var table = $('#sample_2').dataTable({
                "bProcessing": true,
                "bServerSide": true,
                "bDestroy": true,
                "sAjaxSource": "action.php?act=15",
                "fnServerParams": function(aoData) {
                    aoData.push({
                        "name": "id",
                        "value": id
                    });
                },
                "aColumns": [
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    null
                ],
                "columnDefs": [{
                    className: "dt-center",
                    "targets": [0]
                }],
                "iDisplayLength": 10,
                "bInfo": true,
                "sPaginationType": 'full_numbers'
            });
        }

    //     $('input[name="txtJenis"]').change(function() {
    //         let selectedValue = $('input[name="txtJenis"]:checked').val();

    //         // URL
    //         var currentUrl = window.location.href;
    //         var updatedUrl = removeParameterFromUrl(currentUrl, 'jenis');

    //         if (selectedValue !== null) {
    //             updatedUrl = (updatedUrl.includes('?')) ? updatedUrl + '&jenis=' + selectedValue : updatedUrl + '?jenis=' + selectedValue;
    //         }

    //         history.pushState({}, '', updatedUrl);
    //         location.reload()
    //     });
    });

    $(document).on("click", ".delPopUp", function() {
        let tglPinjam = $(this).data('tglpinjam');
        let idBuku = $(this).data('idbuku');
        let idCd = $(this).data('idcd');
        $(".modal-footer #d_idBuku").val(idBuku);
        $(".modal-footer #d_idCd").val(idCd);
        $(".modal-footer #d_tglPinjam").val(tglPinjam);
    });

    function replaceGetURL(input, param) {
        var selectedType = input;

        var currentUrl = window.location.href;

        var newUrl = removeParameterFromUrl(currentUrl, param);

        if (newUrl.includes('?')) {
            newUrl += '&' + param + '=' + selectedType;
        } else {
            newUrl += '?' + param + '=' + selectedType;
        }

        window.history.pushState({
            path: newUrl
        }, '', newUrl);
        window.location.reload();
    }

    document.getElementById('cariAnggota').addEventListener('click', function() {
        replaceGetURL(document.getElementById('txtIdAnggota').value, "id");
    });

    let cariBuku = document.getElementById('cariBuku');
    if (cariBuku) {
        document.getElementById('cariBuku').addEventListener('click', function() {
            var currentUrl = window.location.href;
            var newUrl = removeParameterFromUrl(currentUrl, "cd");
            window.history.pushState({
                path: newUrl
            }, '', newUrl);
            replaceGetURL(document.getElementById('txtIdBuku').value, "buku");
        });
    }

    let cariCd = document.getElementById('cariCd');
    if (cariCd) {
        document.getElementById('cariCd').addEventListener('click', function() {
            var currentUrl = window.location.href;
            var newUrl = removeParameterFromUrl(currentUrl, "buku");
            window.history.pushState({
                path: newUrl
            }, '', newUrl);
            replaceGetURL(document.getElementById('txtIdCd').value, "cd");
        });
    }
</script>

<?php if (isset($_POST['btnSave'])) { ?>
   <script>
    // refresh 2x
document.addEventListener('DOMContentLoaded', function() {
    let url = new URL(window.location.href);
    url.searchParams.set('secondReload', 'true');
    window.location.href = url.toString();

    if(url.searchParams.get('secondReload')){
    
            url.searchParams.delete('secondReload');
    
            history.replaceState(null, '', url.toString());
            window.location.href = url.toString();
        }

});
    
   </script>
<?php } ?>