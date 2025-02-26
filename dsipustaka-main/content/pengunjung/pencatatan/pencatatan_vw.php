<?php
//security goes here 

if (isset($_POST['btnSaveAnggota']) && !empty(@$_POST['txtIdAnggota'])) {
    $txtID = $_POST['txtIdAnggota'];
    $berlaku = getBerlaku($koneksidb,$txtID);

    if (empty($berlaku)) {
        echo "<div class='alert alert-danger alert-dismissable'>
        <button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
        <strong><i class='fa fa-times'></i>&nbsp; ID Anggota tersebut tidak ada</strong>
        </div>";

    }else if($berlaku>=date("Y-m-d")){
        $qry = "SELECT nipnis, Nama, idjnsang, berlaku FROM ranggota WHERE nipnis = ? ";
        $stmt = mysqli_prepare($koneksidb, $qry) or die("Gagal menyiapkan statement : " . mysqli_error($koneksidb));
        mysqli_stmt_bind_param($stmt, "s", $txtID);
        mysqli_stmt_execute($stmt) or die("Gagal Query Tampil Pengunjung : " . mysqli_error($koneksidb));
        mysqli_stmt_bind_result($stmt, $dataNipnis, $dataNama, $dataStatus, $dataBerlaku);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt); 

        // $datastatus = 'idjnsang';

        if(isPencatatan($koneksidb,$txtID)==date("Y-m-d")){
            echo "<div class='alert alert-danger alert-dismissable'>
            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
            <strong><i class='fa fa-times'></i>&nbsp; Anggota \"$dataNama\" sudah dicatat hari ini !!</strong>
            </div>";
            
        }else{
            
            if (isset($dataStatus)) {
                if ($dataStatus == 1) {
                    $dataStatus = 'siswa';
                } elseif ($dataStatus == 2) {
                    $dataStatus = 'guru';
                }
            
                switch ($dataStatus) {
                    case 'siswa':
                        echo "Siswa";
                        break;
                    case 'guru':
                        echo "Guru";
                        break;
                    default:
                        echo "Status tidak dikenal";
                        break;
                }
            }
                  
        $insQry = "insert into tkunjung (tglkunjung, nipnis, nama, desjenisang, stkunjung, kettamu, noapk) 
        values (CURDATE(), ?, '$dataNama', '$dataStatus', 'A', '-', $_SESSION[noapk]) ";
                $stmt = mysqli_prepare($koneksidb, $insQry) or die("Gagal menyiapkan statement: " . mysqli_error($koneksidb));
                mysqli_stmt_bind_param($stmt, "s", $txtID);
                mysqli_stmt_execute($stmt) or die("Gagal Query Insert Pencatatan Pengunjung : " . mysqli_error($koneksidb));
                mysqli_stmt_close($stmt);

                echo "<div class='alert alert-success alert-dismissable'>
                <button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
                <strong><i class='fa fa-check'></i>&nbsp;</strong>Anggota \"$dataNama\" berhasil DICATAT !!
                </div>";
        }
    }else{
        echo "<div class='alert alert-danger alert-dismissable'>
        <button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
        <strong><i class='fa fa-times'></i>&nbsp; Masa berlaku keanggotaan sudah habis</strong>
        </div>";
    }
}

if (isset($_POST['btnSaveTamu']) && !empty(@$_POST['txtNama'])) {
    $txtNama = $_POST['txtNama'];
    $txtKettamu = $_POST['txtKettamu'];

    if(isPencatatan($koneksidb,$txtNama)==date("Y-m-d")){
        echo "<div class='alert alert-danger alert-dismissable'>
        <button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
        <strong><i class='fa fa-times'></i>&nbsp; Tamu \"$txtNama\" sudah dicatat hari ini !!</strong>
        </div>";
    }else{
        $insQry = "insert into tkunjung (tglkunjung, nipnis, stkunjung, kettamu, noapk) 
        values (CURDATE(), ?, 'T', ?, $_SESSION[noapk]) ";
                $stmt = mysqli_prepare($koneksidb, $insQry) or die("Gagal menyiapkan statement: " . mysqli_error($koneksidb));
                mysqli_stmt_bind_param($stmt, "ss", $txtNama,$txtKettamu);
                mysqli_stmt_execute($stmt) or die("Gagal Query Insert Pencatatan Pengunjung : " . mysqli_error($koneksidb));
                mysqli_stmt_close($stmt);

                echo "<div class='alert alert-success alert-dismissable'>
            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
            <strong><i class='fa fa-check'></i>&nbsp;</strong>Tamu \"$txtNama\" berhasil DICATAT !!
            </div>";
    }

}


if (isset($_POST['del'])) {
    $txtIdKunjung     = $_POST['idkunjung'];
    $txtNama          = $_POST['Nama'];
    
            $insQry = "DELETE FROM tkunjung WHERE idkunjung = ?";
            $stmt = mysqli_prepare($koneksidb, $insQry) or die("Gagal menyiapkan statement: " . mysqli_error($koneksidb));
            mysqli_stmt_bind_param($stmt, "s", $txtIdKunjung);
            mysqli_stmt_execute($stmt) or die("Gagal Query Hapus : " . mysqli_error($koneksidb));
            mysqli_stmt_close($stmt);

            echo "<div class='alert alert-success alert-dismissable'>
            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
            <strong><i class='fa fa-check'></i>&nbsp;</strong> \"$txtnama\" berhasil DIHAPUS di daftar pengunjung hari ini
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
                <p>Anda yakin hapus data ini di daftar pengunjung hari ini?</p>
            </div>
            <div class="modal-footer">
                <form method="post">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tidak</button>
                    <input type="hidden" name="idkunjung" id="d_idkunjung" value="">
                    <input type="hidden" name="nama" id="d_nama" value="">
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
        <div class="caption">Pencatatan Pengunjung
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
        <div class="row-col-2">
            <form action="<?php $_SERVER['PHP_SELF']; ?>" id="uploadForm" method="post" class="form-horizontal" role="form" autocomplete="off" name="form1" enctype="multipart/form-data">
                <div class="form-body">
                            <div class="form-group">
                                <label class="col-lg-2 control-label">NIP / NIS</label>
                                <div class="col-lg-2">
                                    <input type="text" id="txtIdAnggota" name="txtIdAnggota" value="<?= @$dataNipnis ?>" class="anggota form-control sm" />
                                    <input type="text" id="txtIdTamu" name="txtIdTamu" value="-" class="tamu hidden form-control sm" readonly />
                                </div>
                                <div class="col-lg-1 anggota">
                                    <button type="submit" name="btnSaveAnggota" class="btn <?= $_SESSION['warnabar'] ?>"><i class="fa fa-pencil"></i> Catat</button>
                                </div>
                                <div class="col-lg-4">
                                    <label for="cbTamu"><input type="checkbox" name="cbTamu" id="cbTamu"> Pengunjung Tamu [Non Angogta]</label>  
                                </div>
                            </div>
                            ( ID Anggota )
                            <div class="form-group">
                                    <label class="col-lg-2 control-label">Nama</label>
                                    <div class="col-lg-5">
                                    <input type="text" id="txtNamaAnggota" name="txtNamaAnggota" value=" <?= @$dataNama ?>" class="anggota form-control sm" readonly/>
                                    <input type="text" id="txtNama" name="txtNama" class="tamu hidden form-control sm"/>
                                    </div>
                            </div>

                            <div class="form-group anggota">
                                <label class="col-lg-2 control-label">Status</label>
                                <div class="col-lg-2">
                                    <input type="text" id="txtStatus" name="txtStatus" value="<?php
                                                                                                if (isset($dataStatus)) {
                                                                                                    if ($dataStatus == 1) {
                                                                                                        $dataStatus = 'siswa';
                                                                                                    } elseif ($dataStatus == 2) {
                                                                                                        $dataStatus = 'guru';
                                                                                                    }
                                                                                                
                                                                                                    switch ($dataStatus) {
                                                                                                        case 'siswa':
                                                                                                            echo "Siswa";
                                                                                                            break;
                                                                                                        case 'guru':
                                                                                                            echo "Guru";
                                                                                                            break;
                                                                                                        default:
                                                                                                            echo "Status tidak dikenal";
                                                                                                            break;
                                                                                                    }
                                                                                                }
                                                                                                ?>" class="form-control sm" readonly/>
                                </div>
                                <label class="col-lg-1 control-label">Berlaku</label>
                                <div class="col-lg-2">
                                    <input type="date" id="txtBerlaku" name="txtBerlaku" value="<?= @$dataBerlaku ?>" class="form-control sm" readonly />
                                </div>
                            </div>

                            <div class="form-group tamu hidden">
                                <label class="col-lg-2 control-label">Alamat/Lembaga/Kelas</label>
                                <div class="col-lg-5">
                                    <input type="text" id="txtKettamu" name="txtKettamu" value="<?= @$dataKettamu ?>" class="form-control sm"/>
                                </div>
                                <div class="col-lg-1 tamu">
                                    <button type="submit" name="btnSaveTamu" class="btn <?= $_SESSION['warnabar'] ?>"><i class="fa fa-pencil"></i> Catat</button>
                                </div>
                            </div>
                </div>
                    <footer class="panel-footer">
                        <div class="row">
                            <div class="form-group">                           
                                <div class="col-lg-offset-2 col-lg-10">
                                    <a href="?content=pencatatan" class="btn <?= $_SESSION['warnabar'] ?>"><i class="fa fa-undo"></i> Kembali</a>
                                </div>
                            </div>
                        </div>
                    </footer>
        </form>

        <div class=" portlet box <?= $_SESSION['warnabar'] ?>">
            <div class="portlet-title">
                <div class="caption">Data Pengunjung Hari Ini</div>
            </div>

            <div class="portlet-body fieldset-form">

                <table class="table table-bordered table-hover table-condensed" id="sample_2" width="100%">
                    <thead>
                        <tr class="active">
                            <td width="5%">NO</td>
                            <td>ID ANGGOTA</td>
                            <td>NAMA</td>
                            <td>KETERANGAN</td>
                            <td>STATUS</td>
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

        $(document).ready(function() {
                $("#sample_2").dataTable().fnDestroy();

                var table = $('#sample_2').dataTable({
                    "bProcessing": true,
                    "bServerSide": true,
                    "bDestroy": true,
                    "sAjaxSource": "action.php?act=19",
                    "columns": [
                        null,
                        null,
                        null,
                        null,
                        null,
                        null
                    ],
                    "fnRowCallback": function(nRow, aData, iDisplayIndex) {
                        $(nRow).on('click', function() {
                                displaySeparateForm(aData,aData[4]);
                        });
                    },
                    "aoColumnDefs": [
    {
        "className": "dt-center", // Mengatur kelas CSS untuk sel di kolom pertama
        "aTargets": [0] // Kolom pertama
    },
    {
        "aTargets": [1, 2], // Kolom kedua dan ketiga
        "fnRender": function (oObj) {
            if (oObj.iDataColumn === 1 && oObj.aData[4] === 'Tamu') { 
                return "-";
            } else if (oObj.iDataColumn === 2 && oObj.aData[4] === 'Tamu') {
                return oObj.aData[1];
            } else {
                return oObj.aData[oObj.iDataColumn]; 
            }
        },
        "bUseRendered": false
    },
    {
        "aTargets": [3, 4], // Kolom keempat dan kelima
        "fnRender": function (oObj) {
            if (oObj.iDataColumn === 3 && oObj.aData[4] !== 'Tamu') { 
                return oObj.aData[6]; 
            } else if (oObj.iDataColumn === 4 && oObj.aData[4] !== 'Tamu') {
                return oObj.aData[7]; 
            } else {
                return oObj.aData[oObj.iDataColumn]; // Mengembalikan nilai asli jika tidak ada kustomisasi yang diperlukan
            }
        }
    },
    {
        "aTargets": [6, 7], // Kolom ketujuh dan kedelapan
        "bVisible": false // Menyembunyikan kolom
    }
],

                    "iDisplayLength": 10,
                    "bInfo": true,
                    "sPaginationType": 'full_numbers'
                });

            function displaySeparateForm(data,status) {
                $("#txtNama").prop("readonly", false);
                $("#txtKettamu").prop("readonly", false);
                $("#txtIdAnggota").prop("readonly", false);
                $("#txtNamaAnggota").prop("readonly", false);
                $("#txtStatus").prop("readonly", false);
                $("#txtBerlaku").prop("readonly", false);
                
                if (status == "Tamu") {
                    $("#txtNama").val(data[1]).prop("readonly", true);
                    $("#txtKettamu").val(data[3]).prop("readonly", true);
                    $("#cbTamu").prop("checked", true).change();
                }else{
                    $("#txtIdAnggota").val(data[1]).prop("readonly", true);
                    $("#txtNamaAnggota").val(data[2]).prop("readonly", true);
                    $("#txtStatus").val(data[7]).prop("readonly", true);
                    $("#txtBerlaku").val(data[6].match(/\d{4}-\d{2}-\d{2}/)[0]).prop("readonly", true); //mengambil tanggal saja
                    $("#cbTamu").prop("checked", false).change();
                }
            }

            $("#cbTamu").change(function() {
                if ($(this).is(":checked")) {
                    $(".tamu").removeClass("hidden");
                    $(".anggota").addClass("hidden");
                } else {
                    $(".tamu").addClass("hidden");
                    $(".anggota").removeClass("hidden");
                }
            });

            $('#txtNama').on('input', function() {
                var value = $(this).val();
                $(this).val(value.toUpperCase());
            });
        });

        $(document).on("click", ".delPopUp", function() {
            let idkunjung = $(this).data('idkunjung');
            $(".modal-footer #d_idkunjung").val(idkunjung);
            let nama = $(this).data('Nama');
            $(".modal-footer #d_Nama").val(nama);
        });
    
    </script>