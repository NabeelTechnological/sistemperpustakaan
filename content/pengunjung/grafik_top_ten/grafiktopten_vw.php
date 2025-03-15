<?php 
if(isset($_POST['btnTampil'])){
    if(empty($_POST['txtJenis']) || empty($_POST['txtTahun'])){
        echo "<div class='alert alert-danger alert-dismissable'>
        <button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
        <strong><i class='fa fa-times'></i>&nbsp; Data Tidak Boleh Ada yang Kosong </strong>
        </div>";
    } else {
        // Menentukan bulan berdasarkan pilihan
        $bulanNumber = ($_POST['txtBulan'] == "Semua Bulan") ? "" : date('m', strtotime($_POST['txtBulan']));
        
        // Query untuk mengambil data
        $qry = "SELECT nipnis, nama, count(*) as jmlkunjung FROM vw_tkunjung 
                WHERE desjenisang = ? 
                AND YEAR(tglkunjung) = ? 
                AND noapk = $_SESSION[noapk]";

        if ($bulanNumber != "") {
            $qry .= " AND MONTH(tglkunjung) = ?";
        }

        $qry .= " GROUP BY nipnis, nama 
                  ORDER BY jmlkunjung DESC
                  LIMIT 10";

        // Menyiapkan statement dan mengeksekusi query
        $stmt = mysqli_prepare($koneksidb, $qry) or die("Gagal menyiapkan statement : " . mysqli_error($koneksidb));
        
        if ($bulanNumber != "") {
            mysqli_stmt_bind_param($stmt, "sss", $_POST['txtJenis'], $_POST['txtTahun'], $bulanNumber);
        } else {
            mysqli_stmt_bind_param($stmt, "ss", $_POST['txtJenis'], $_POST['txtTahun']);
        }
        
        mysqli_stmt_execute($stmt) or die("Gagal Query Tampil Top Ten : " . mysqli_error($koneksidb));
        mysqli_stmt_bind_result($stmt, $dataNipnis, $dataNama, $dataJmlKunjung);

        // Mengumpulkan data hasil query
        while (mysqli_stmt_fetch($stmt)) {
            $dataAnggota = "[$dataNipnis] " . $dataNama;
            $data[] = array(
                'jenis' => $_POST['txtJenis'],
                'bulan' => $_POST['txtBulan'],
                'tahun' => $_POST['txtTahun'],
                'anggota' => $dataAnggota,
                'jmlkunjung' => $dataJmlKunjung
            );
        }

        // Menampilkan pesan jika tidak ada data
        if (empty($data)) {
            echo "<div class='alert alert-danger alert-dismissable'>
            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
            <strong><i class='fa fa-times'></i>&nbsp; Data tidak ada </strong>
            </div>";
        }

        mysqli_stmt_close($stmt);
    }
}
?>

<form action="<?php $_SERVER['PHP_SELF']; ?>" method="post" class="form-horizontal" data-validate="parsley" name="form1">
    <div class="portlet box <?= $_SESSION['warnabar'] ?>">
        <div class="portlet-title">
            <div class="caption">Grafik Top 10 Pengunjung Perpustakaan</div>
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
                        <div><label class="radio"><input type="radio" name="txtJenis" value="Siswa" <?= (@$_POST['txtJenis']=="Siswa") ? "checked" : "" ?>>Siswa</label></div>
                        <div><label class="radio"><input type="radio" name="txtJenis" value="Guru" <?= (@$_POST['txtJenis']=="Guru") ? "checked" : "" ?>>Guru / Kary</label></div>
                    </div>

                    <div class="form-group">
                        <div>
                            <label class="col-lg-6 control-label">Bulan :</label>
                            <div class="col-lg-3">
                                <select name="txtBulan" class="form-control sm">
                                    <option value="Semua Bulan" <?= (@$_POST['txtBulan'] == "Semua Bulan") ? "selected" : "" ?>>Semua Bulan</option>
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
                            <div class="col-lg-3">
                                <input type="number" name="txtTahun" value="<?= @$_POST['txtTahun'] ?>" class="form-control sm">
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
                            <a href="content/pengunjung/grafik_top_ten/grafiktopten_excel.php?tahun=<?= urlencode($_POST['txtTahun']) ?>&jenis=<?= urlencode($_POST['txtJenis']) ?>&bulan=<?= urlencode($_POST['txtBulan']) ?>" class="btn blue"><i class="fa fa-file-excel-o"></i> Export Excel</a>

                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
</form>

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
        if("anggota" in data[0]) {
            categories = data.map(item => item.anggota);
            dataValues = data.map(item => item.jmlkunjung);
            textTitle = "Pengunjung";
        }

        Highcharts.chart('container_1', {
            chart: { type: 'column' },
            title: { 
                text: 'Grafik Pengunjung Perpustakaan <?= getNmsekolah($koneksidb) ?>',
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
                text: "Bulan: " + data[0].bulan + " Tahun: " + data[0].tahun,
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
            exporting: { filename: 'Grafik Pengunjung ' + data[0].bulan + " " + data[0].tahun },
            credits: { enabled: false }
        });
    }
</script>

<?php } ?>
