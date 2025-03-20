<?php 
// Pastikan session tidak double start
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Pastikan file koneksi tersedia
$connectionPath = realpath(__DIR__ . '/../../../config/inc.connection.php');
if (!$connectionPath || !file_exists($connectionPath)) {
    die("<div class='alert alert-danger'>File koneksi tidak ditemukan!</div>");
}

// Panggil koneksi
require_once $connectionPath;


$dataCetakBerdasar = $_POST['txtCetakBerdasar'];
$dataIdBukuDari = intval($_POST['txtIdBukuDari']);
$dataIdBukuSampai = intval($_POST['txtIdBukuSampai']);

// Pastikan session tersedia
if (!isset($_SESSION['noapk']) || empty($_SESSION['noapk'])) {
    echo "<div class='alert alert-danger'>Session noapk tidak tersedia!</div>";
    exit;
}

$noapk = $_SESSION['noapk'];

// Query untuk mengambil rentang ID buku
$qry = "SELECT idbuku, kode, subyek, judul, pengarangnormal, pengarang, 
        pengarang2, pengarang3, namapenerbit, nmkota, thterbit, cetakan, 
        edisi, indeks, halpdh, tebal, illus, panjang, jilid, bibli, halbibli, isbn 
        FROM vw_tbuku 
        WHERE idbuku BETWEEN ? AND ? AND noapk = ?";

$stmt = mysqli_prepare($koneksidb, $qry);
if (!$stmt) {
    die("Gagal menyiapkan statement: " . mysqli_error($koneksidb));
}

// Binding parameter
mysqli_stmt_bind_param($stmt, "iii", $dataIdBukuDari, $dataIdBukuSampai, $noapk);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Jika tidak ada data
if (mysqli_num_rows($result) == 0) {
    echo "<div class='alert alert-warning'>Tidak ada data ditemukan!</div>";
    exit;
}

?>

<style>
    td {
        vertical-align: top;
        word-wrap: break-word;
        padding-left: 10px;
    }

    .kodebk {
        text-align: center;
        padding-right: 20px;
    }

    .container {
        width: 12.5cm;
        height: 7.5cm;
        margin: 20px;
        page-break-inside: avoid;
    }
</style>

<?php while ($row = mysqli_fetch_assoc($result)): ?>
    <div class="container">
        <table>
            <?php if ($dataCetakBerdasar == "judul") { ?>
                <tr>
                    <td colspan="2" style="padding-left: 50px;">
                        <p><?= ucwords(strtolower($row['judul'])) ?></p>
                    </td>
                </tr>
            <?php } ?>
            <tr>
                <td class="kodebk"><p><?= $row['kode'] ?></p></td>
                <td></td>
            </tr>
            <tr>
                <td class="kodebk"><p><?= strtoupper(substr($row['pengarang'], 0, 3)) ?></p></td>
                <td><p><?= ucwords(strtolower($row['pengarangnormal'])) ?></p></td>
            </tr>
            <tr>
                <td class="kodebk"><p><?= strtolower(substr($row['judul'], 0, 1)) ?></p></td>
                <td>
                    <p><?= "{$row['judul']}/ {$row['pengarangnormal']}, -- " ?>
                    <?= ($row['edisi'] != 0) ? "Ed. {$row['edisi']} (Cet. {$row['cetakan']})--," : "Cet. {$row['cetakan']}--," ?>
                    <?= "{$row['nmkota']} : {$row['namapenerbit']}, {$row['thterbit']}, {$row['halpdh']}, {$row['tebal']} hlm. : " ?>
                    <?= ($row['illus'] != 0) ? "ilus. ; " : "" ?>
                    <?= "{$row['panjang']} cm"; ?>
                    </p>
                    <p><?= ($row['jilid'] != 0) ? "Seri" : ""; ?></p>
                    <p>
                        <?= ($row['bibli'] != 0) ? "Bib. : hlm {$row['halbibli']}; " : ""; ?>
                        <?= ($row['indeks'] != 0) ? "Indeks. <br>" : ""; ?>
                        <?= "ISBN : {$row['isbn']}"; ?>
                    </p>
                    <p>
                        <?= "1. {$row['subyek']}. I. Judul &emsp;"; ?>
                        <?php 
                        if ($row['pengarang2'] != "-") {
                            echo "II. {$row['pengarang2']} &emsp;";
                            if ($row['pengarang3'] != "-") {
                                echo "III. {$row['pengarang3']} ";
                            }
                        }
                        ?>
                    </p>
                </td>
            </tr>
        </table>
    </div>
<?php endwhile; ?>

<script>
    (function() {
        window.onload = function() {
            window.print();
            if (!isMobileDevice()) {
                setTimeout(() => {
                    window.close();
                    history.back();
                }, 500);
            }
        };

        function isMobileDevice() {
            return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
        }
    })();
</script>

<?php 
mysqli_stmt_close($stmt);
?>
