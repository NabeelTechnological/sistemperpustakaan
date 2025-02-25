<?php
        if(isset($_POST['btnPengembalian'])){
            if(empty($_POST['btnPengembalian'])){
                echo "<div class='alert alert-danger alert-dismissable'>
                <button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
                <strong><i class='fa fa-times'></i>&nbsp; Baris Data Tabel Dipinjam Belum Dipilih</strong>
                </div>";
            }else{
            $txtPengembalian = $_POST['btnPengembalian'];
            $txtJmlKembali = $_POST['txtJmlKembali'];

                $updateQry = "UPDATE tpinjampaket SET ispinjam = 0, tglkembali=CURDATE(), jmlkembali = ?, jamkembali=DATE_FORMAT(NOW(), '%H:%i') WHERE idpaket = ? AND noapk = $_SESSION[noapk]";
                $stmt = mysqli_prepare($koneksidb, $updateQry) or die("Gagal menyiapkan statement: " . mysqli_error($koneksidb));
                mysqli_stmt_bind_param($stmt, "ii", $txtJmlKembali,$txtPengembalian);
                mysqli_stmt_execute($stmt) or die("Gagal Query Update Tersedia : " . mysqli_error($koneksidb));
                mysqli_stmt_close($stmt);

                echo "<div class='alert alert-success alert-dismissable'>
            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
            <strong><i class='fa fa-check'></i>&nbsp;</strong>Buku Berhasil Dikembalikan. 
            </div>";
            }
        }
        
        if(isset($_POST['btnPembatalan'])){
            if(empty($_POST['btnPembatalan'])){
                echo "<div class='alert alert-danger alert-dismissable'>
                <button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
                <strong><i class='fa fa-times'></i>&nbsp; Baris Data Tabel Dikembalikan Hari Ini Belum Dipilih</strong>
                </div>";
            }else{
            $txtPembatalan = $_POST['btnPembatalan'];

                $updateQry = "UPDATE tpinjampaket SET ispinjam = 1, tglkembali=NULL, jamkembali=NULL WHERE idpaket = ? AND noapk = $_SESSION[noapk]";
                $stmt = mysqli_prepare($koneksidb, $updateQry) or die("Gagal menyiapkan statement: " . mysqli_error($koneksidb));
                mysqli_stmt_bind_param($stmt, "i", $txtPembatalan);
                mysqli_stmt_execute($stmt) or die("Gagal Query Update Tersedia : " . mysqli_error($koneksidb));
                mysqli_stmt_close($stmt);

                echo "<div class='alert alert-success alert-dismissable'>
                <button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
                <strong><i class='fa fa-check'></i>&nbsp;</strong>Buku Batal Dikembalikan. 
                </div>";
            }
        }

?>

<div id="pesan">
</div>
<form action="<?php $_SERVER['PHP_SELF']; ?>" id="uploadForm" method="post" class="form-horizontal" role="form" autocomplete="off" name="form1" enctype="multipart/form-data">
<div class="portlet box <?= $_SESSION['warnabar'] ?>">
    <div class="portlet-title">
        <div class="caption">Pengembalian Kolektif
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
<div class=" portlet box <?= $_SESSION['warnabar'] ?>">
    <div class="portlet-title">
        <div class="caption">Dipinjam</div>
    </div>

    <div class="portlet-body fieldset-form">

        <table class="table table-bordered table-hover table-condensed" id="sample_2" width="100%">
            <thead>
                <tr class="active">
                    <td width="5%">NO</td>
                    <td>KELAS PINJAM</td>
                    <td>NAMA PEMINJAM</td>
                    <td>TANGGAL JAM PINJAM</td>
                    <td>JUDUL BUKU</td>
                    <td>JML PINJAM</td>
                </tr>
            </thead>
        </table>
        </fieldset>
    </div>
</div>

<div class="form-group">
    <div style="text-align: center;">
    <input type="hidden" name="txtJmlKembali" id="txtJmlKembali">
        <button type="submit" name="btnPengembalian" id="btnPengembalian" class="btn <?= $_SESSION['warnabar'] ?>"><i class="fa fa-arrow-down"></i> Pengembalian</button>
        <button type="submit" name="btnPembatalan" id="btnPembatalan" class="btn <?= $_SESSION['warnabar'] ?>"><i class="fa fa-arrow-up"></i> Pembatalan</button>
    </div>
</div>

<div class=" portlet box <?= $_SESSION['warnabar'] ?>">
    <div class="portlet-title">
        <div class="caption">Dikembalikan Hari Ini</div>
    </div>

    <div class="portlet-body fieldset-form">

        <table class="table table-bordered table-hover table-condensed" id="sample_3" width="100%">
            <thead>
                <tr class="active">
                    <td width="5%">NO</td>
                    <td>KELAS PINJAM</td>
                    <td>NAMA PEMINJAM</td>
                    <td>TANGGAL JAM PINJAM</td>
                    <td>JUDUL BUKU</td>
                    <td>JML PINJAM</td>
                    <td>JML KEMBALI</td>
                </tr>
            </thead>
        </table>
        </fieldset>
    </div>
</div>
    </div>
</div>
</form>

<script src="plugin/datatable/jquery-3.5.1.js"></script>
<script src="plugin/datatable/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="plugin/datatable/jquery.dataTables.min.css">
<script>
    $(document).ready(function() {

// TABEL ATAS

            $("#sample_2").dataTable().fnDestroy();

            $('#sample_2').dataTable({
                "bProcessing": true,
                "bServerSide": true,
                "bDestroy": true,
                "sAjaxSource": "action.php?act=18",
                "fnServerParams": function(aoData) {
                    aoData.push({
                        "name": "table",
                        "value": "sample_2"
                    });
                },
                "columns": [
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    null
                ],
            "fnRowCallback": function(nRow, aData, iDisplayIndex) {
                $(nRow).on('click', function() {
                    $("#btnPengembalian").val(aData[6]);
                    $("#txtJmlKembali").val(aData[5]);

                    if ($(this).hasClass('highlight')) {
                    $(this).css('background-color', '');
                } else {
                    $('#sample_2 tbody tr').css('background-color', ''); 
                    $(this).css('background-color', 'lightgrey'); 
                }
                });
            },
                "columnDefs": [
                    {className: "dt-center", "targets": [0]} ,
                    { "targets": [6], "visible": false }
                ],
                "iDisplayLength": 10,
                "bInfo": true,
                "sPaginationType": 'full_numbers'
            });

// TABEL BAWAH

            $("#sample_3").dataTable().fnDestroy();
        
            $('#sample_3').dataTable({
                "bProcessing": true,
                "bServerSide": true,
                "bDestroy": true,
                "sAjaxSource": "action.php?act=18",
                "fnServerParams": function(aoData) {
                    aoData.push({
                        "name": "table",
                        "value": "sample_3"
                    });
                },
                "columns": [
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    null
                ], 
                "fnRowCallback": function(nRow, aData, iDisplayIndex) {
                $(nRow).on('click', function() {
                    $("#btnPembatalan").val(aData[7]);

                    if ($(this).hasClass('highlight')) {
                    $(this).css('background-color', '');
                } else {
                    $('#sample_3 tbody tr').css('background-color', ''); 
                    $(this).css('background-color', 'lightgrey'); 
                }
                });
            },
            "columnDefs": [
                    {className: "dt-center", "targets": [0]} ,
                    { "targets": [7], "visible": false }
                ],
                "iDisplayLength": 10,
                "bInfo": true,
                "sPaginationType": 'full_numbers'
            });
        
    });
</script>