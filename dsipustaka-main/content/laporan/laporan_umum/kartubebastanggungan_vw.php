<?php
$tampil             = (isset($_SESSION['tampil'])) ? $_SESSION['tampil'] : "";
$dataAnggota            = (isset($_SESSION['dataAnggota'])) ? $_SESSION['dataAnggota'] : "";
$dataIdAnggota            = (isset($_SESSION['dataIdAnggota'])) ? $_SESSION['dataIdAnggota'] : "";

if(isset($_SESSION['pesanKesalahan'])) {
    echo $_SESSION['pesanKesalahan'];
    unset($_SESSION['pesanKesalahan']);
}

?>
<form action="export.php?lap=kartubebastanggungan&page=<?= basename($_SERVER["SCRIPT_FILENAME"]) ?>" method="post" class="form-horizontal" data-validate="parsley" name="form1">
    <div class="portlet box <?= $_SESSION['warnabar'] ?>">
        <div class="portlet-title">
            <div class="caption">Kartu Bebas Tanggungan Perpustakaan </div>
            <div class="tools">
                <a href="javascript:;" class="collapse"></a>
                <a href="javascript:;" class="reload"></a>
                <a href="javascript:;" class="remove"></a>
            </div>
        </div>
        <div class="portlet-body form">
            <div class="form-body">
            <div class="row">
            <div class="col-lg-6">

<div class="form-group">
    <label class="col-lg-4 control-label">Anggota: </label>
    <div class="col-lg-6">
        <select name="txtAnggota" data-placeholder="- Pilih Anggota -" class="select2me form-control" required>
            <?php
            $options = array("Siswa", "Guru/Karyawan");

            foreach ($options as $option) {
                $dataAnggota = (@$dataAnggota) ? "$dataAnggota" : "Siswa";
                $selected = ($dataAnggota == $option) ? " selected" : "";
                echo "<option value='$option'$selected>$option</option>";
            }
            ?>
        </select>
    </div>
</div>
        </div>
                <div class="col-lg-6">
                <div class="form-group">
                <label class="col-lg-3 control-label">ID Anggota</label>
                <div class="col-lg-8"><select name="txtIdAnggota"  data-placeholder="- Pilih ID Anggota -" class="select2me form-control" required>
                    <option value=""></option> 
                    <?php
                            $dataSql = "SELECT nipnis, nama FROM ranggota WHERE noapk = $_SESSION[noapk] ORDER BY nipnis ";
                            $dataQry = mysqli_query( $koneksidb, $dataSql) or die ("Gagal Query".mysqli_error($koneksidb));
                            while ($dataRow = mysqli_fetch_array($dataQry)) {
                            if (@$dataIdAnggota == $dataRow['nipnis']) {
                                $cek = " selected";
                            } else { $cek=""; }
                            echo "<option value='$dataRow[nipnis]' $cek>$dataRow[nipnis] $dataRow[nama]</option>";
                            }
                            $sqlData ="";
                    ?>
                </select> </div>
                </div>
                </div>
    </div>

            </div>
        </div>
        <footer class="panel-footer">
            <div class="row">
                <div class="form-group">
                    <div class="col-lg-offset-2 col-lg-10">
                        <button type="submit" name="btnSave" value="tampil" class="btn blue"><i class="fa fa-check"></i> Tampilkan</button>
                        <button type="submit" name="btnSave" value="cetak" class="btn blue"><i class="fa fa-print"></i> Export Excel</button>
                        <button type="button" class="btn blue" onclick="refreshLagi()"><i class="fa fa-undo"></i> Batalkan</button>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</form>

<script>
    // refresh 2x
    document.addEventListener('DOMContentLoaded', function() {
        let url = new URL(window.location.href);
        if (url.searchParams.get('secondReload')) {

            url.searchParams.delete('secondReload');

            history.replaceState(null, '', url.toString());
            window.location.href = url.toString();
        }
    });
    // fungsi agar form dipilih berdasarkan radio button yang diklik 
    $(document).ready(function() {
        $('input[name="txtPilihan"]').change(function() {
            let selectedValue = $('input[name="txtPilihan"]:checked').val();
            let pilihan = ["harian", "bulanan", "custom"];

            if ($('#' + selectedValue).hasClass('hidden')) {
                $('#' + selectedValue).removeClass('hidden');
            }

            let pilihanBaru = $.grep(pilihan, function(value) {
                return value !== selectedValue;
            });

            if (!$('#' + pilihanBaru[0]).hasClass('hidden')) {
                $('#' + pilihanBaru[0]).addClass('hidden');
            }
            if (!$('#' + pilihanBaru[1]).hasClass('hidden')) {
                $('#' + pilihanBaru[1]).addClass('hidden');
            }
        });
    });


    //fungsi agar refresh 2x
    function refreshLagi() {
        // Cek apakah ini reload pertama atau kedua
        var url = new URL(window.location.href);
        url.searchParams.set('secondReload', 'true');
        url.searchParams.delete('btnsave');
        window.location.href = url.toString();

    }
</script>
<?php
if (isset($_GET['btnsave'])) {

?>
    <br>
    <div class="portlet box <?= $_SESSION['warnabar'] ?>">
        <div class="portlet-title">
            <div class="caption">Data Kartu Bebas Tanggungan Perpustakaan</div>
            <div class="actions">

            </div>
        </div>
        <div class="portlet-body fieldset-form">
            <table class="table table-condensed table-bordered table-hover" id="sample_2" width="100%">
                <thead>
                    <tr class="active">
                            <td width="3%" rowspan="2">NO. URUT</td>
                            <td colspan="2"><div align="center">BUKU</div></td>
                            <td colspan="2"><div align="center">TANGGAL</div></td>
                            <td rowspan="2"><div align="center">TERLAMBAT</div></td>
                        </tr>
                    <tr class="active">
                            <td><div align="center">ID BUKU</div></td>
                            <td><div align="center">JUDUL</div></td>
                            <td><div align="center">PINJAM</div></td>
                            <td><div align="center">JADWAL KEMBALI</div></td>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <!-- Datatable Script -->
    <script src="plugin/datatable/jquery-3.5.1.js"></script>
    <script src="plugin/datatable/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="plugin/datatable/jquery.dataTables.min.css">
    <script>
        $(document).ready(function() {

            $("#sample_2").dataTable().fnDestroy();

            $('#sample_2').dataTable({
                "bProcessing": true,
                "bServerSide": true,
                "sAjaxSource": "action.php?act=38",
                "aColumns": [
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
    </script>

<?php
} else {
    if (isset($_SESSION['tampil'])) {
        unset($_SESSION['tampil']);
    }
    if (isset($_SESSION['dataAnggota'])) {
        unset($_SESSION['dataAnggota']);
    }
    if (isset($_SESSION['dataIdAnggota'])) {
        unset($_SESSION['dataIdAnggota']);
    }
?>
<?php
}
?>

