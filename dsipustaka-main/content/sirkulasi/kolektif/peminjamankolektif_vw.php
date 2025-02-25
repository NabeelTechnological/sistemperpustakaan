<?php
//security goes here 

if (isset($_GET['id'])) {
    $txtID = $_GET['id'];
    $dataKelas = $_GET['kelas'];
    $berlaku = getBerlaku($koneksidb,$txtID);

    if($berlaku==""){
        echo "<div class='alert alert-danger alert-dismissable'>
        <button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
        <strong><i class='fa fa-times'></i>&nbsp; ID Anggota yang dicari tidak ada</strong>
        </div>";

    }else if($berlaku>=date("Y-m-d")){
        
        $qry = "SELECT nipnis, nama
        FROM ranggota 
        WHERE nipnis = ? AND noapk = $_SESSION[noapk]";

        
                $stmt = mysqli_prepare($koneksidb, $qry) or die("Gagal menyiapkan statement : " . mysqli_error($koneksidb));
                mysqli_stmt_bind_param($stmt, "s", $txtID);
                mysqli_stmt_execute($stmt) or die("Gagal Query Tampil Anggota : " . mysqli_error($koneksidb));
                mysqli_stmt_bind_result($stmt, $dataIdAnggota, $dataNama);
                mysqli_stmt_fetch($stmt);
                mysqli_stmt_close($stmt);    
            
    }else{
        echo "<div class='alert alert-danger alert-dismissable'>
        <button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
        <strong><i class='fa fa-times'></i>&nbsp; Masa berlaku keanggotaan sudah habis</strong>
        </div>";
    }
}

if (isset($_GET['buku'])) {
    $txtID = $_GET['buku'];
    $dataKelas = $_GET['kelas'];
    $qry = "SELECT idbuku, judul, pengarang
        FROM tbuku
        WHERE idbuku = ? AND noapk = $_SESSION[noapk]";


    $stmt = mysqli_prepare($koneksidb, $qry) or die("Gagal menyiapkan statement : " . mysqli_error($koneksidb));
    mysqli_stmt_bind_param($stmt, "s", $txtID);
    mysqli_stmt_execute($stmt) or die("Gagal Query Tampil Buku : " . mysqli_error($koneksidb));
    mysqli_stmt_bind_result($stmt, $dataIdBuku, $dataJudul, $dataPengarang);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    if(empty($dataIdBuku)){
        echo "<div class='alert alert-danger alert-dismissable'>
        <button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
        <strong><i class='fa fa-times'></i>&nbsp; Buku yang dicari tidak ada</strong>
        </div>";
    }
}

if (isset($_POST['btnSave'])) {
    $dataKelas = $_POST['txtKelas'] ?? "";
    $dataIdAnggota = $_POST['txtIdAnggota'] ?? "";
    $dataIdBuku = $_POST['txtIdBuku'] ?? "";
    $dataJmlPinjam = $_POST['txtJmlPinjam'] ?? "";
    $dataNama = $_POST['txtNama'] ?? "";
    $dataJudul = $_POST['txtJudul'] ?? "";
    $dataPengarang = $_POST['txtPengarang'] ?? "";

    if (empty($dataIdAnggota) || empty($dataIdBuku) || empty($dataKelas) || empty($dataJmlPinjam) || empty($dataNama) || empty($dataJudul) || empty($dataPengarang)) {
        echo "<div class='alert alert-danger alert-dismissable'>
              <button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
              <strong><i class='fa fa-times'></i>&nbsp; Data Tidak Boleh Ada yang Kosong </strong>
              </div>";
    } else {
        // Insert to tpinjampaket table
        $insQry = "INSERT INTO tpinjampaket 
           (idbuku, idkelas, nipnis, ispinjam, tglpinjam, jampinjam, jmlpinjam, iduser, noapk, nama, judul, pengarang) 
           VALUES (?, ?, ?, 1, CURDATE(), DATE_FORMAT(NOW(), '%H:%i'), ?, ?, $_SESSION[noapk], ?, ?, ?)";

// Modify the bind parameters to include the new fields
$stmt = mysqli_prepare($koneksidb, $insQry) or die("Gagal menyiapkan statement: " . mysqli_error($koneksidb));
mysqli_stmt_bind_param($stmt, "sssissss", $dataIdBuku, $dataKelas, $dataIdAnggota, $dataJmlPinjam, $_SESSION['iduser'], $dataNama, $dataJudul, $dataPengarang);
mysqli_stmt_execute($stmt) or die("Gagal Query Insert Pinjam Buku Kolektif : " . mysqli_error($koneksidb));
mysqli_stmt_close($stmt);


        echo "<div class='alert alert-success alert-dismissable'>
              <button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
              <strong><i class='fa fa-check'></i>&nbsp;</strong>Data Sukses insert. 
              </div>";
    }
}


if (isset($_POST['del'])) {
    $txtIDBuku       = $_POST['idBuku'];
    $txtTglPinjam    = $_POST['tglPinjam'];

    if (date("Y-m-d") == $txtTglPinjam) {
        if (!empty($txtIDBuku)) {
            $insQry = "DELETE FROM tpinjampaket WHERE idbuku = ? AND noapk = $_SESSION[noapk]";
            $stmt = mysqli_prepare($koneksidb, $insQry) or die("Gagal menyiapkan statement: " . mysqli_error($koneksidb));
            mysqli_stmt_bind_param($stmt, "s", $txtIDBuku);
            mysqli_stmt_execute($stmt) or die("Gagal Query Hapus : " . mysqli_error($koneksidb));
            mysqli_stmt_close($stmt);

            echo "<div class='alert alert-success alert-dismissable'>
            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
            <strong><i class='fa fa-check'></i>&nbsp;</strong>Berhasil melakukan pembatalan peminjaman.
            </div>";
        } 
    } else {
        echo "<div class='alert alert-danger alert-dismissable'>
    <button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
    <strong><i class='fa fa-times'></i>&nbsp; Tanggal peminjaman (" . IndonesiaTgl($txtTglPinjam) . ") TIDAK SAMA dengan tanggal hari ini (" . date("d-m-Y") . "). <br>Pembatalan tidak diperbolehkan.</strong>
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
                <p>Pembatalan hanya dapat dilakukan di hari peminjaman.</p>
                <p>Anda yakin?</p>
            </div>
            <div class="modal-footer">
                <form method="post">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tidak</button>
                    <input type="hidden" name="idBuku" id="d_idBuku" value="">
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
        <div class="caption">Peminjaman Kolektif
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
                    <div class="row">
                        <div class="well">
                            <div class="form-group">
                                    <label class="col-lg-2 control-label">Kelas</label>
                                    <div class="col-lg-2">
                                    <select name="txtKelas" id="txtKelas"  data-placeholder="- Pilih Kelas -" class="select2me form-control" required >
                                    <option value=""></option> 
                                    <?php
                                            $dataSql = "SELECT idkelas,deskelas FROM rkelas WHERE noapk = $_SESSION[noapk] ORDER BY idkelas ";
                                            $dataQry = mysqli_query( $koneksidb, $dataSql) or die ("Gagal Query".mysqli_error($koneksidb));
                                            while ($dataRow = mysqli_fetch_array($dataQry)) {
                                            $cek = (@$dataKelas==$dataRow['idkelas']) ? "selected" :"";
                                            echo "<option value='$dataRow[idkelas]' $cek>$dataRow[deskelas]</option>";
                                            }
                                            $sqlData ="";
                                    ?>
                                    </select>
                                    </div>
                            </div>

                            <div class="form-group">
                                <label class="col-lg-2 control-label">NIS - Nama Peminjam</label>
                                <div class="col-lg-2">
                                    <input type="text" id="txtIdAnggota" name="txtIdAnggota" value="<?= @$dataIdAnggota ?>" class="form-control sm" required />
                                </div>
                                <div class="col-lg-4">
                                    <input type="text" id="txtNama" name="txtNama" value="<?= @$dataNama ?>" class="form-control sm" readonly required/>
                                </div>
                                <div class="col-lg-1">
                                    <button type="button" id="cariAnggota" class="btn <?= $_SESSION['warnabar'] ?>"><i class="fa fa-search"></i> Cari Siswa</button>
                                </div>
                            </div>
                        </div>
                        <div class="well">

                            <div class="form-group">
                                    <label class="col-lg-2 control-label">ID Buku Master</label>
                                    <div class="col-lg-2">
                                        <input type="text" id="txtIdBuku" value="<?= @$dataIdBuku ?>" name="txtIdBuku" class="form-control sm" required />
                                    </div>
                                    <div class="col-lg-2">
                                        <button type="button" id="cariBuku" class="btn <?= $_SESSION['warnabar'] ?>"><i class="fa fa-search"></i> Cari</button>
                                    </div>
                            </div>

                            <div class="form-group">
                                <label class="col-lg-2 control-label">Judul</label>
                                <div class="col-lg-6">
                                    <input type="text" id="txtJudul" name="txtJudul" value="<?= @$dataJudul ?>" class="form-control sm" readonly required/>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-lg-2 control-label">Pengarang</label>
                                <div class="col-lg-6">
                                    <input type="text" id="txtPengarang" name="txtPengarang" value="<?= @$dataPengarang ?>" class="form-control sm" readonly required />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-2 control-label">Jumlah Dipinjam</label>
                                <div class="col-lg-2">
                                    <input type="number" id="txtJmlPinjam" name="txtJmlPinjam" class="form-control sm" value="<?= @$dataJmlPinjam?>" required />
                                </div>
                            </div>


                        </div>
                    </div>
                    <footer class="panel-footer">
                        <div class="row">
                            <div class="form-group">                           
                                <div class="col-lg-offset-2 col-lg-10">
                                    <button type="submit" name="btnSave" class="btn <?= $_SESSION['warnabar'] ?>"><i class="fa fa-save"></i> Pinjamkan</button>
                                    <a href="?content=peminjamankolektif" class="btn <?= $_SESSION['warnabar'] ?>"><i class="fa fa-undo"></i> Kembali</a>
                                </div>
                            </div>
                        </div>
                    </footer>
        </form>

        <div class=" portlet box <?= $_SESSION['warnabar'] ?>">
            <div class="portlet-title">
                <div class="caption">Data Peminjaman Kolektif</div>
            </div>

            <div class="portlet-body fieldset-form">

            <table class="table table-bordered table-hover table-condensed" id="sample_2" width="100%">

                    <thead>
                        <tr class="active">
                            <td width="5%">NO</td>
                            <td>KELAS PINJAM</td>
                            <td>NAMA PEMINJAM</td>
                            <td>TANGGAL JAM</td>
                            <td>JUDUL BUKU</td>
                            <td>JML PINJAM</td>
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
                $("#sample_2").dataTable().fnDestroy();

                var table = $('#sample_2').dataTable({
                    "bProcessing": true,
                    "bServerSide": true,
                    "bDestroy": true,
                    "sAjaxSource": "action.php?act=17",
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

        });

        $(document).on("click", ".delPopUp", function() {
            let tglPinjam = $(this).data('tglpinjam');
            let idBuku = $(this).data('idbuku');
            $(".modal-footer #d_idBuku").val(idBuku);
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
            replaceGetURL(document.getElementById('txtKelas').value, "kelas");
        });

        let cariBuku = document.getElementById('cariBuku');
        if (cariBuku) {
            document.getElementById('cariBuku').addEventListener('click', function() {
                replaceGetURL(document.getElementById('txtIdBuku').value, "buku");
                replaceGetURL(document.getElementById('txtKelas').value, "kelas");
            });
        }

    </script>