<?php 
if(isset($_POST['btnTampil'])){
    if(empty($_POST['txtTahun'])){
        echo "<div class='alert alert-danger alert-dismissable'>
        <button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
        <strong><i class='fa fa-times'></i>&nbsp; Data Tahun Tidak Boleh Kosong </strong>
        </div>";
    } else {
        if($_POST['txtBulan'] != "") {
            // Convert month name to month number
            $bulanNumber = date('m', strtotime($_POST['txtBulan']));

            $qry = "SELECT DAY(tglkunjung) as tgl, count(*) as jmlkunjung FROM vw_tkunjung 
            WHERE MONTH(tglkunjung) = ? AND YEAR(tglkunjung) = ? AND noapk = $_SESSION[noapk]
            GROUP BY tgl
            ORDER BY tgl
            ";
            $stmt = mysqli_prepare($koneksidb, $qry) or die("Gagal menyiapkan statement : " . mysqli_error($koneksidb));
            mysqli_stmt_bind_param($stmt, "ss", $bulanNumber, $_POST['txtTahun']);
            mysqli_stmt_execute($stmt) or die("Gagal Query Tampil Top Ten : " . mysqli_error($koneksidb));
            mysqli_stmt_bind_result($stmt, $dataTgl, $dataJmlKunjung);

            while (mysqli_stmt_fetch($stmt)) {
                $dataKeterangan = "Bulan : ". $_POST['txtBulan'] . " " . $_POST['txtTahun'];
                $data[] = array(
                    'keterangan' => $dataKeterangan,
                    'tanggal' => $dataTgl,
                    'jmlkunjung' => $dataJmlKunjung
                );
            }

            if (empty($data)) {
                echo "<div class='alert alert-danger alert-dismissable'>
                <button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
                <strong><i class='fa fa-times'></i>&nbsp; Data tidak ada </strong>
                </div>";
            }

            mysqli_stmt_close($stmt);
        } else {
            $qry = "SELECT MONTH(tglkunjung) as bulan, count(*) as jmlkunjung FROM vw_tkunjung 
            WHERE YEAR(tglkunjung) = ? AND noapk = $_SESSION[noapk]
            GROUP BY bulan
            ORDER BY bulan
            ";
            $stmt = mysqli_prepare($koneksidb, $qry) or die("Gagal menyiapkan statement : " . mysqli_error($koneksidb));
            mysqli_stmt_bind_param($stmt, "s", $_POST['txtTahun']);
            mysqli_stmt_execute($stmt) or die("Gagal Query Tampil Top Ten : " . mysqli_error($koneksidb));
            mysqli_stmt_bind_result($stmt, $dataBulan, $dataJmlKunjung);

            while (mysqli_stmt_fetch($stmt)) {
                $dataKeterangan = "Tahun : " . $_POST['txtTahun'];
                $data[] = array(
                    'keterangan' => $dataKeterangan,
                    'bulan' => substr(namaBulanIndonesia($dataBulan), 0, 3),
                    'jmlkunjung' => $dataJmlKunjung
                );
            }

            if (empty($data)) {
                echo "<div class='alert alert-danger alert-dismissable'>
                <button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
                <strong><i class='fa fa-times'></i>&nbsp; Data tidak ada </strong>
                </div>";
            }
            mysqli_stmt_close($stmt);
        }
    }
}
?>

<form action="<?php $_SERVER['PHP_SELF']; ?>" method="post" class="form-horizontal" data-validate="parsley" name="form1">
    <div class="portlet box <?= $_SESSION['warnabar'] ?>">
        <div class="portlet-title">
            <div class="caption">Grafik Jumlah Pengunjung</div>
            <div class="tools">
                <a href="javascript:;" class="collapse"></a>
                <a href="javascript:;" class="reload"></a>
                <a href="javascript:;" class="remove"></a>
            </div>
        </div>
        <div class="portlet-body form">
            <div class="form-body">
                <div class="form-inline">
                    <div class="form-group well" style="margin-left:10px; margin-right:20px;">
                        <div><label class="radio"><input type="radio" name="txtPilihan" value="bulan" <?= (!isset($_POST['txtPilihan']) || @$_POST['txtPilihan']=="bulan") ? "checked" : "" ?>>Per Bulan</label></div>
                        <div><label class="radio"><input type="radio" name="txtPilihan" value="tahun" <?= (@$_POST['txtPilihan']=="tahun") ? "checked" : "" ?>>Per Tahun</label></div>
                    </div>

                    <div class="form-group">
                        <div id="bulan" class="<?= (@$_POST['txtPilihan'] == "tahun") ? "hidden" : "" ?>">
                            <label class="col-lg-6 control-label">Bulan :</label>
                            <div class="col-lg-2">
                                <select name="txtBulan" class="form-control sm">
                                    <option value="">Semua Bulan</option>
                                    <option value="January" <?= (@$_POST['txtBulan'] == "January") ? "selected" : "" ?>>Januari</option>
                                    <option value="February" <?= (@$_POST['txtBulan'] == "February") ? "selected" : "" ?>>Februari</option>
                                    <option value="March" <?= (@$_POST['txtBulan'] == "March") ? "selected" : "" ?>>Maret</option>
                                    <option value="April" <?= (@$_POST['txtBulan'] == "April") ? "selected" : "" ?>>April</option>
                                    <option value="May" <?= (@$_POST['txtBulan'] == "May") ? "selected" : "" ?>>Mei</option>
                                    <option value="June" <?= (@$_POST['txtBulan'] == "June") ? "selected" : "" ?>>Juni</option>
                                    <option value="July" <?= (@$_POST['txtBulan'] == "July") ? "selected" : "" ?>>Juli</option>
                                    <option value="August" <?= (@$_POST['txtBulan'] == "August") ? "selected" : "" ?>>Agustus</option>
                                    <option value="September" <?= (@$_POST['txtBulan'] == "September") ? "selected" : "" ?>>September</option>
                                    <option value="October" <?= (@$_POST['txtBulan'] == "October") ? "selected" : "" ?>>Oktober</option>
                                    <option value="November" <?= (@$_POST['txtBulan'] == "November") ? "selected" : "" ?>>November</option>
                                    <option value="December" <?= (@$_POST['txtBulan'] == "December") ? "selected" : "" ?>>Desember</option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <label class="col-lg-6 control-label">Tahun :</label>
                            <div class="col-lg-2">
                                <input type="number" name="txtTahun" value="<?=  @$_POST['txtTahun'] ?>" class="form-control sm">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <footer class="panel-footer">
                <div class="row">
                    <div class="form-group">
                        <div class="col-lg-offset-2 col-lg-10">
                            <button type="submit" name="btnTampil" class="btn blue"><i class="fa fa-bar-chart-o"></i> VIEW</button>
                            <a href="?content=grafikjumlahpengunjung" class="btn blue"><i class="fa fa-undo"></i> Kembali</a>
                            <a href="content/pengunjung/grafik_jumlah_pengunjung/export_kunjungan.php?tahun=<?= $_POST['txtTahun'] ?>&bulan=<?= $_POST['txtBulan'] ?>" class="btn blue" class="btn btn-succes <i class="fa fa-file-excel-o" class="btn blue"></i> Export Excel</a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
</form>

<script>
$(document).ready(function() {
    $('input[name="txtPilihan"]').change(function() {
        let selectedValue = $('input[name="txtPilihan"]:checked').val();
        if(selectedValue=="bulan"){
            if ($('#bulan').hasClass('hidden')) {
                $('#bulan').removeClass('hidden');
            }
        } else {
            if (!$('#bulan').hasClass('hidden')) {
                $('#bulan').addClass('hidden');
            }
        }
    });
});
</script>

<?php if(isset($_POST['btnTampil']) && !empty(@$_POST['txtTahun'])){ ?>

<div class="portlet box <?= $_SESSION['warnabar'] ?>">
    <div class="portlet-title">
        <div class="caption">Grafik</div>
        <div class="tools">
            <a href="javascript:;" class="collapse"></a>
            <a href="javascript:;" class="reload"></a>
            <a href="javascript:;" class="remove"></a>
        </div>
    </div>        
    <div class="portlet-body">
        <div id='container_1'></div>
    </div>
</div>

<script src="./assets/scripts/highcharts.js"></script>
<script src="./assets/scripts/modules/data.js"></script>
<script src="./assets/scripts/highcharts-3d.js"></script>
<script src="./assets/scripts/modules/drilldown.js"></script>
<script src="./assets/scripts/modules/exporting.js"></script>
<script>
    var data = <?= json_encode($data) ?>;
    if (data.length > 0) {
        var dataValues = [];
        var categories = [];
        var textTitle = "";
        if("tanggal" in data[0]) {
            for (var i = 1; i <= 31; i++) {
                var tanggal = i.toString().padStart(2, '0');
                var dataTanggal = data.find(item => item.tanggal == tanggal);
                dataValues.push(dataTanggal ? dataTanggal.jmlkunjung : 0);
            }
            categories = Array.from({length: 31}, (_, i) => i + 1);
            textTitle = "Tanggal";
        } else if("bulan" in data[0]) {
            dataValues = Array.from({ length: 12 }, () => 0);
            categories = ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agu", "Sep", "Okt", "Nov", "Des"];
            data.forEach(item => {
                var bulanIndex = categories.indexOf(item.bulan);
                if (bulanIndex !== -1) {
                    dataValues[bulanIndex] += item.jmlkunjung;
                }
            });
            textTitle = "Bulan";
        }

        Highcharts.chart('container_1', {
            chart: { type: 'column' },
            title: { 
                text: 'PERPUSTAKAAN <?= getNmsekolah($koneksidb) ?> <br> Grafik Peminjaman Buku',
                style: { fontSize: '14px', fontFamily: 'karnel' }
            },
            xAxis: {
                title: { text: textTitle },
                categories: categories,
                labels: {
                    enabled: true,
                    formatter: function() {
                        var index = this.pos;
                        var value = dataValues[index];
                        return value > 0 ? this.value : '';
                    }
                }
            },
            yAxis: { title: { text: 'Jumlah Pengunjung' } },
            subtitle: {
                text: data[0].keterangan,
                style: { fontSize: '14px', fontFamily: 'karnel' }
            },
            plotOptions: {
                series: {
                    dataLabels: { enabled: true },
                    colorByPoint: true
                }
            },
            series: [{
                name: 'Jumlah Pengunjung',
                data: dataValues.map(function(visits) {
                    return {
                        y: visits, 
                        dataLabels: {
                            enabled: visits > 0,
                            format: '{point.y}',
                        }
                    };
                })
            }],
            exporting: { filename: 'Grafik Jumlah Pengunjung '+data[0].keterangan.replace(/:/g, "") },
            credits: { enabled: false }
        });
    }
</script>

<?php } ?>
