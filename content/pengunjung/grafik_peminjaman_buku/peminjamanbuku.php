<?php 
if(isset($_POST['btnTampil'])){
    if(empty($_POST['txtTahun'])){
        echo "<div class='alert alert-danger alert-dismissable'>
        <button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
        <strong><i class='fa fa-times'></i>&nbsp; Tahun tidak boleh kosong </strong>
        </div>";
    } else {
        $tahun = $_POST['txtTahun'];
        $noapk = $_SESSION['noapk']; // Pastikan session ini sudah di-set sebelumnya

        // Query untuk mengambil data peminjaman
        $qry = "SELECT DATE_FORMAT(tglpinjam, '%Y-%m') AS bulan, COUNT(*) AS total_peminjaman 
                FROM tpinjampaket 
                WHERE YEAR(tglpinjam) = ? 
                AND noapk = ?
                GROUP BY bulan ORDER BY bulan";

        // Menyiapkan statement dan mengeksekusi query
        $stmt = mysqli_prepare($koneksidb, $qry);
        
        if (!$stmt) {
            die("Gagal menyiapkan statement: " . mysqli_error($koneksidb));
        }

        mysqli_stmt_bind_param($stmt, "si", $tahun, $noapk);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $bulan, $totalPeminjaman);

        $data = [];
        while (mysqli_stmt_fetch($stmt)) {
            $data[] = array(
                'bulan' => $bulan,
                'total_peminjaman' => $totalPeminjaman
            );
        }

        mysqli_stmt_close($stmt);

        // Menampilkan pesan jika tidak ada data
        if (empty($data)) {
            echo "<div class='alert alert-danger alert-dismissable'>
            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
            <strong><i class='fa fa-times'></i>&nbsp; Data tidak ditemukan </strong>
            </div>";
        }
    }
}
?>


<form action="<?php $_SERVER['PHP_SELF']; ?>" method="post" class="form-horizontal" data-validate="parsley" name="form1">
    <div class="portlet box <?= $_SESSION['warnabar'] ?>">
        <div class="portlet-title">
            <div class="caption">Grafik Peminjaman Buku Perpustakaan</div>
            <div class="tools">
                <a href="javascript:;" class="collapse"></a>
                <a href="javascript:;" class="reload"></a>
                <a href="javascript:;" class="remove"></a>
            </div>
        </div>
        <div class="portlet-body form">
            <div class="form-body">
                <div class="form-inline">
                    <div class="form-group">
                        <label class="col-lg-6 control-label">Tahun :</label>
                        <div class="col-lg-3">
                            <input type="number" name="txtTahun" value="<?= @$_POST['txtTahun'] ?>" class="form-control sm">
                        </div>
                    </div>
                </div>
            </div>
            <footer class="panel-footer">
                <div class="row">
                    <div class="form-group">
                        <div class="col-lg-offset-2 col-lg-10">
                            <button type="submit" name="btnTampil" class="btn blue"><i class="fa fa-bar-chart-o"></i> VIEW</button>
                            <a href="?content=grafikpeminjamanbuku" class="btn blue"><i class="fa fa-undo"></i> Kembali</a>
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
<script>
    var data = <?= json_encode($data) ?>;
    if (data.length > 0) {
        var categories = data.map(item => item.bulan);
        var dataValues = data.map(item => item.total_peminjaman);

        Highcharts.chart('container_1', {
            chart: { type: 'column' },
            title: { 
                text: 'Grafik Peminjaman Buku Perpustakaan',
                style: { fontSize: '14px', fontFamily: 'karnel' }
            },
            xAxis: { title: { text: 'Bulan' }, categories: categories },
            yAxis: { title: { text: 'Jumlah Peminjaman' } },
            plotOptions: {
                series: {
                    dataLabels: { enabled: true },
                    colorByPoint: true
                }
            },
            series: [{
                name: 'Jumlah Peminjaman',
                data: dataValues
            }],
            credits: { enabled: false }
        });
    }
</script>

<?php } ?>
