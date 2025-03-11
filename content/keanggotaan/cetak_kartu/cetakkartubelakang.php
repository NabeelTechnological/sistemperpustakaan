<?php 

if (!isset($_POST['selected_data'])) {
		$pagename = $_GET['page'];
        $_SESSION['pesanKesalahan'] = "<div class='alert alert-danger alert-dismissable'>
		<button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
		<strong><i class='fa fa-times'></i>&nbsp; Data Belum Dipilih</strong>
		</div>";
    echo "<script>window.location='".$pagename."?content=cetakkartu'</script>";
} else {
    $selectedData = isset($_POST['selected_data']) ? $_POST['selected_data'] : array();
    $conditions = implode(',', array_map('intval', $selectedData));
    $qry = mysqli_query($koneksidb, "SELECT COUNT(*) as jml FROM ranggota WHERE nipnis IN ($conditions) AND noapk = $_SESSION[noapk]");
    $r = mysqli_fetch_assoc($qry);
    $jml = $r['jml'];


?>

<style>
    @media screen {
        body {
            display: none;
        }
    }

    @media print {
        .footer {
            display: none;
        }
    }

    .kartu {
        width: 9cm;
        height: 6cm;
        border: 1px solid black;
        margin: 1%;
        padding: 10px;
        page-break-inside: avoid;
        text-align: center;
    }

    .kartu h4 {
        font-family: Arial, sans-serif;
        font-size: 14px;
        font-weight: bold;
        margin-bottom: 5px;
    }

    .kartu p {
        font-family: Arial, sans-serif;
        font-size: 12px;
        margin: 5px 0;
        text-align: left;
    }

    .kartu ul {
        font-family: Arial, sans-serif;
        font-size: 12px;
        text-align: left;
        padding-left: 15px;
    }

</style>

<div class="page-container row">
<?php for ($i = 1; $i <= $jml; $i++): ?>
    <div class="col-xs-6">
        <div class="kartu">
            <h4>PERATURAN PEMINJAMAN BUKU</h4>
            <p>1. Kartu ini hanya dapat digunakan oleh pemilik yang sah.</p>
            <p>2. Setiap anggota boleh meminjam maksimal 3 buku.</p>
            <p>3. Lama peminjaman adalah 7 hari dan dapat diperpanjang sekali.</p>
            <p>4. Denda keterlambatan sebesar Rp. 500/hari/buku.</p>
            <p>5. Kehilangan atau kerusakan buku wajib diganti sesuai aturan.</p>
            <p>6. Kartu ini wajib dibawa saat meminjam buku.</p>
        </div>
    </div>
<?php endfor; ?>
</div>



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

<?php } ?>
