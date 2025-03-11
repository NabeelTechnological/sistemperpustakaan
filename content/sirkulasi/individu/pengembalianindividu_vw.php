<?php

$iduser = $_SESSION['iduser'];
$noapk  = $_SESSION['noapk'];

if (!isset($_GET['id'])) {
    echo "<div class='alert alert-danger alert-dismissable'>
    <button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
    <strong><i class='fa fa-times'></i>&nbsp; ID Anggota belum dipilih</strong>
    </div>";
} else if (isset($_GET['id'])) {
    $txtID = $_GET['id'];

    $qry = "SELECT nipnis,Nama,idjnsang FROM ranggota WHERE nipnis = ? AND noapk = $_SESSION[noapk]";

    $stmt = mysqli_prepare($koneksidb, $qry) or die("Gagal menyiapkan statement : " . mysqli_error($koneksidb));
    mysqli_stmt_bind_param($stmt, "s", $txtID);
    mysqli_stmt_execute($stmt) or die("Gagal Query Tampil Anggota : " . mysqli_error($koneksidb));
    mysqli_stmt_bind_result($stmt, $dataNipnis, $dataNama, $dataIdJnsAng);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    if (isset($_POST['btnPengembalian'])) {
        if (empty($_POST['selectedBooks'])) {
            echo "<div class='alert alert-danger alert-dismissable'>
            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
            <strong><i class='fa fa-times'></i>&nbsp; Baris Data Tabel Dipinjam Belum Dipilih</strong>
            </div>";
        } else {
            foreach ($_POST['selectedBooks'] as $txtPengembalian) {
                $txtIdJnsAng = $_POST['txtIdJnsAng'];

                $qry = "SELECT denda FROM rreftrans WHERE idjnsang = ? AND idjnspustaka = 1 AND noapk = $_SESSION[noapk]";
                $stmt = mysqli_prepare($koneksidb, $qry) or die("Gagal menyiapkan statement: " . mysqli_error($koneksidb));
                mysqli_stmt_bind_param($stmt, "s", $txtIdJnsAng);
                mysqli_stmt_execute($stmt) or die("Gagal Query Select Denda : " . mysqli_error($koneksidb));
                mysqli_stmt_bind_result($stmt, $denda);
                mysqli_stmt_fetch($stmt);
                mysqli_stmt_close($stmt);

                $updateQry = "UPDATE tpinbuku SET 
                                iskembali = 1, 
                                tglrealkembali = CURDATE(), 
                                isterlambat = CASE WHEN CURDATE() > tglhrskembali THEN 1 ELSE 0 END, 
                                bsudenda = CASE WHEN CURDATE() > tglhrskembali THEN ? ELSE 0 END 
                                WHERE idbuku = ? AND noapk = ?";
              
                $stmt = mysqli_prepare($koneksidb, $updateQry) or die("Gagal menyiapkan statement: " . mysqli_error($koneksidb));
                mysqli_stmt_bind_param($stmt, "dii", $denda, $txtPengembalian, $_SESSION['noapk']);
                mysqli_stmt_execute($stmt) or die("Gagal Query Update Tersedia: " . mysqli_error($koneksidb));
                mysqli_stmt_close($stmt);

                $updateQry = "UPDATE tbuku SET tersedia = 1 WHERE idbuku = ? AND noapk = $_SESSION[noapk]";
                $stmt = mysqli_prepare($koneksidb, $updateQry) or die("Gagal menyiapkan statement: " . mysqli_error($koneksidb));
                mysqli_stmt_bind_param($stmt, "i", $txtPengembalian);
                mysqli_stmt_execute($stmt) or die("Gagal Query Update Tersedia : " . mysqli_error($koneksidb));
                mysqli_stmt_close($stmt);
            }

            logTransaksi($iduser, date('Y-m-d H:i:s'), 'Buku Dikembalikan [Individu]', $noapk);

            echo "<div class='alert alert-success alert-dismissable'>
            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
            <strong><i class='fa fa-check'></i>&nbsp;</strong>Buku Berhasil Dikembalikan. 
            </div>";
        }
    }

    if (isset($_POST['btnPembatalan'])) {
        if (empty($_POST['selectedReturns'])) {
            echo "<div class='alert alert-danger alert-dismissable'>
            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
            <strong><i class='fa fa-times'></i>&nbsp; Baris Data Tabel Dikembalikan Hari Ini Belum Dipilih</strong>
            </div>";
        } else {
            foreach ($_POST['selectedReturns'] as $txtPembatalan) {
                $updateQry = "UPDATE tpinbuku SET iskembali = 0, tglrealkembali=NULL, isterlambat=0, bsudenda=0 WHERE idbuku = ? AND noapk = $_SESSION[noapk]";
                $stmt = mysqli_prepare($koneksidb, $updateQry) or die("Gagal menyiapkan statement: " . mysqli_error($koneksidb));
                mysqli_stmt_bind_param($stmt, "i", $txtPembatalan);
                mysqli_stmt_execute($stmt) or die("Gagal Query Update Tersedia : " . mysqli_error($koneksidb));
                mysqli_stmt_close($stmt);

                $updateQry = "UPDATE tbuku SET tersedia = 0 WHERE idbuku = ? AND noapk = $_SESSION[noapk]";
                $stmt = mysqli_prepare($koneksidb, $updateQry) or die("Gagal menyiapkan statement: " . mysqli_error($koneksidb));
                mysqli_stmt_bind_param($stmt, "i", $txtPembatalan);
                mysqli_stmt_execute($stmt) or die("Gagal Query Update Tersedia : " . mysqli_error($koneksidb));
                mysqli_stmt_close($stmt);
            }

            logTransaksi($iduser, date('Y-m-d H:i:s'), 'Buku Batal Ditampilkan [Individu]', $noapk);

            echo "<div class='alert alert-success alert-dismissable'>
            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
            <strong><i class='fa fa-check'></i>&nbsp;</strong>Buku Batal Dikembalikan. 
            </div>";
        }
    }
}
?>

<div id="pesan"></div>
<form action="<?php $_SERVER['PHP_SELF']; ?>" id="uploadForm" method="post" class="form-horizontal" role="form" autocomplete="off" name="form1" enctype="multipart/form-data">
<div class="portlet box <?= $_SESSION['warnabar'] ?>">
    <div class="portlet-title">
        <div class="caption">Pengembalian Individu</div>
        <div class="tools">
            <a href="javascript:;" class="collapse"></a>
            <a href="javascript:;" class="reload"></a>
            <a href="javascript:;" class="remove"></a>
        </div>
    </div>

    <div class="portlet-body">
        <div class="row-col-2">
            <div class="form-body">
                <input type="hidden" value="<?= @$dataIdJnsAng ?>" name="txtIdJnsAng">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                        <label class="col-lg-3 control-label">ID Anggota</label>
						<div class="col-lg-6">
							<select id="txtIdAnggota" name="txtIdAnggota" value="<?php echo @$dataNipnis; ?>" data-placeholder="- Pilih Id Anggota -" class="select2me form-control sm" class="kdbuku form-control sm" required>
								<option value="<?php echo @$dataNipnis; ?>"></option>
								<?php
								$dataSql = "SELECT nipnis, nama FROM ranggota WHERE noapk = $_SESSION[noapk] ORDER BY id";
								$dataQry = mysqli_query($koneksidb, $dataSql) or die("Gagal Query: " . mysqli_error($koneksidb));
								while ($dataRow = mysqli_fetch_array($dataQry)) {
									$cek = (@$dataNipnis == $dataRow['kode']) ? "selected" : "";
									// echo "<option value='{$dataRow['kode']}' $cek>{$dataRow['kode']}</option>";
									echo "<option value='{$dataRow['nipnis']}' data-nama='{$dataRow['nama']}' $selected>{$dataRow['nipnis']} - {$dataRow['nama']}</option>";
								}
								?>
							</select>
						</div>
                            <div class="col-lg-1">
                                <button type="button" id="cariAnggota" class="btn <?= $_SESSION['warnabar'] ?>"><i class="fa fa-search"></i> Cari</button>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-3 control-label">Nama</label>
                            <div class="col-lg-6">
                                <input type="text" id="txtNama" name="txtNama" value="<?= @$dataNama ?>" class="form-control sm" readonly />
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <label class="col-lg-4 control-label">Jenis Pustaka :</label>
                        <div class="form-group">
                            <div class="col-lg-3">
                                <label class="radio-inline"><input type="radio" name="txtJenis" id="txtJenis" value="buku" checked>Buku</label>
                            </div>
                        </div>
                    </div>
                </div>
                <footer class="panel-footer">
                    <div class="row">
                        <div class="form-group">
                            <div class="col-lg-offset-2 col-lg-10">
                                <a href="?content=pengembalianindividu" class="btn <?= $_SESSION['warnabar'] ?>"><i class="fa fa-undo"></i> Kembali</a>
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>

        <div class="portlet box <?= $_SESSION['warnabar'] ?>">
            <div class="portlet-title">
                <div class="caption">Dipinjam</div>
            </div>

            <div class="portlet-body fieldset-form">
                <table class="table table-bordered table-hover table-condensed" id="sample_2" width="100%">
                    <thead>
                        <tr class="active">
                            <td width="5%">Pilih</td> <!-- Kolom untuk checkbox -->
                            <td>ID BUKU</td>
                            <td>BUKU</td>
                            <td>JUDUL</td>
                            <td>TANGGAL PINJAM</td>
                            <td>HARUS KEMBALI</td>
                            <td>TERLAMBAT</td>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data akan diisi oleh DataTables -->
                    </tbody>
                </table>
            </div>
        </div>

        <div class="form-group">
            <div style="text-align: center;">
                <button type="submit" name="btnPengembalian" id="btnPengembalian" class="btn <?= $_SESSION['warnabar'] ?>"><i class="fa fa-arrow-down"></i> Pengembalian</button>
                <button type="submit" name="btnPembatalan" id="btnPembatalan" class="btn <?= $_SESSION['warnabar'] ?>"><i class="fa fa-arrow-up"></i> Pembatalan</button>
            </div>
        </div>

        <div class="portlet box <?= $_SESSION['warnabar'] ?>">
            <div class="portlet-title">
                <div class="caption">Dikembalikan Hari Ini</div>
            </div>

            <div class="portlet-body fieldset-form">
                <table class="table table-bordered table-hover table-condensed" id="sample_3" width="100%">
                    <thead>
                        <tr class="active">
                            <td width="5%">Pilih</td> <!-- Kolom untuk checkbox -->
                            <td>ID BUKU</td>
                            <td>BUKU</td>
                            <td>JUDUL</td>
                            <td>TANGGAL PINJAM</td>
                            <td>HARUS KEMBALI</td>
                            <td>TERLAMBAT</td>
                            <td>DENDA</td>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data akan diisi oleh DataTables -->
                    </tbody>
                </table>
            </div>
        </div>
    </form>

    <script src="plugin/datatable/jquery-3.5.1.js"></script>
    <script src="plugin/datatable/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="plugin/datatable/jquery.dataTables.min.css">
    <script>
        $(document).ready(function() {
            const urlParams = new URLSearchParams(window.location.search);
            const id = urlParams.get('id');

            if (id) {
                $("#sample_2").dataTable().fnDestroy();

                $('#sample_2').dataTable({
                    "bProcessing": true,
                    "bServerSide": true,
                    "bDestroy": true,
                    "sAjaxSource": "action.php?act=16",
                    "fnServerParams": function(aoData) {
                        aoData.push({
                            "name": "id",
                            "value": id
                        }, {
                            "name": "table",
                            "value": "sample_2"
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
                    "fnRowCallback": function(nRow, aData, iDisplayIndex) {
                        var checkbox = '<input type="checkbox" name="selectedBooks[]" value="' + aData[1] + '">';
                        $('td:eq(0)', nRow).html(checkbox); // Menempatkan checkbox di kolom pertama
                    },
                    "columnDefs": [{
                        className: "dt-center",
                        "targets": [0]
                    }],
                    "iDisplayLength": 10,
                    "bInfo": true,
                    "sPaginationType": 'full_numbers'
                });

                $("#sample_3").dataTable().fnDestroy();

                $('#sample_3').dataTable({
                    "bProcessing": true,
                    "bServerSide": true,
                    "bDestroy": true,
                    "sAjaxSource": "action.php?act=16",
                    "fnServerParams": function(aoData) {
                        aoData.push({
                            "name": "id",
                            "value": id
                        }, {
                            "name": "table",
                            "value": "sample_3"
                        });
                    },
                    "aColumns": [
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
                        var checkbox = '<input type="checkbox" name="selectedReturns[]" value="' + aData[1] + '">';
                        $('td:eq(0)', nRow).html(checkbox); // Menempatkan checkbox di kolom pertama
                    },
                    "columnDefs": [{
                        className: "dt-center",
                        "targets": [0]
                    }],
                    "iDisplayLength": 10,
                    "bInfo": true,
                    "sPaginationType": 'full_numbers'
                });
            }
        });

        document.getElementById('cariAnggota').addEventListener('click', function() {
            var currentUrl = window.location.href;
            var newUrl = removeParameterFromUrl(currentUrl, 'id');
            var selectedId = document.getElementById('txtIdAnggota').value;

            if (newUrl.includes('?')) {
                newUrl += '&id=' + selectedId;
            } else {
                newUrl += '?id=' + selectedId;
            }

            window.history.pushState({
                path: newUrl
            }, '', newUrl);
            window.location.reload();
        });

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
    </script>
</body>
</html>