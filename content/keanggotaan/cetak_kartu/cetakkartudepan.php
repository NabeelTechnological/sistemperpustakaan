<?php
if (!isset($_POST['selected_data'])) {
    $pagename 		= $_GET['page'];
    $_SESSION['pesanKesalahan'] = "<div class='alert alert-danger alert-dismissable'>
    <button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
    <strong><i class='fa fa-times'></i>&nbsp; Data Belum Dipilih</strong>
    </div>";
echo "<script>window.location='".$pagename."?content=cetakkartu'</script>";
}else{
 $nmsekolah = getNmsekolah($koneksidb);

 $selectedData = isset($_POST['selected_data']) ? $_POST['selected_data'] : array();
 $conditions = implode(',', array_map('intval', $selectedData));
 $qry = "SELECT nipnis, idjnsang, idkelas, nama, alamat, berlaku, jnskel, photo FROM ranggota WHERE noapk = $_SESSION[noapk] AND nipnis IN ($conditions)";
 $stmt = mysqli_prepare($koneksidb,$qry) or die ("Gagal menyiapkan statement: " . mysqli_error($koneksidb));
 mysqli_stmt_execute($stmt) or die ("Gagal Query Select Anggota : " . mysqli_error($koneksidb));
 mysqli_stmt_bind_result($stmt,$nomor,$jenis,$kelas,$nama,$alamat,$berlaku,$jnskel,$photo);

 
?>

    <style>
         @media screen {
            body {
                display: none;
            }
        }

        @media print{
            .footer {
                display: none;
            }
        }

        .custom-column {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .kartu {
            width: 9cm;
            height: 6cm;
            border: 1px solid black;
            margin: 1%;
            page-break-inside: avoid;
        }

        .kartu h4, td, p {
            font-family: arial;
            color: black;
            font-size: 12px !important;  
            padding:0;
            margin: 0;
            line-height: 1.1;
        }

        .datakartu {
            font-size: 10px !important;
        }
        
        td {
            vertical-align:top;
        }

    </style>
<div class="page-container row">

<?php
$i = 1;
while(mysqli_stmt_fetch($stmt)): 
if($jenis=="1"){
        $jenis_anggota = "UNTUK SISWA";
    }
else if($jenis=="2"){
    $jenis_anggota = "GURU/KARYAWAN";
}    

// Konversi BLOB ke Base64
$photo_base64 = base64_encode($photo);
$photo_src = "data:image/jpeg;base64," . $photo_base64;

 


?>

<div class="col-xs-6">
<div class="kartu">
    <div class="text-center" style="margin:5px;">
        <h4 style="margin:0;"><strong>KTA PERPUSTAKAAN [<?=$jenis_anggota?>]</strong></h4>
        <p style="margin:0;"><?= $nmsekolah ?></p>
    </div>
    <hr class="bg-dark" style="margin:0;">
    <div class="row" style="margin:0; height:180px;">
        <div class="col-xs-8 custom-column" style="margin-top:5px; height:100%;">
            <table style="width:100%; table-layout: fixed;"> 
            <colgroup>
            <col style="width: 35%;">
            <col style="width: 5%;">
            <col style="width: 60%;">
        </colgroup>
                <tr>
                    <td >Nomor</td>
                    <td>:</td>
                    <td class="datakartu nomor"><?=$nomor?></td>
                </tr>
                <tr>
                    <td>Nama</td>
                    <td>:</td>
                    <td class="datakartu"><?=$nama?></td>
                </tr>
                <tr>
                    <td>Alamat</td>
                    <td>:</td>
                    <td class="datakartu" style="word-wrap: break-word;"><?=  substr($alamat,0,60)?></td>
                </tr>
                <tr>
                    <td>Jns Kel</td>
                    <td>:</td>
                    <td class="datakartu"><?=$jnskel?></td>  
                </tr>
                <tr>
                    <td>Kelas</td>
                    <td>:</td>
                    <td class="datakartu"><?=$kelas?></td>
                </tr>
            </table>
            <div style="position:relative; bottom:0;">
            <p style="margin:2px 0;">Berlaku s.d. <?=$berlaku?></p>
            <svg id="barcode-<?=$i++?>"></svg>
            </div>
        </div>
        <div class="col-xs-4 text-right" style="margin-top:5px;">
            <!-- <img src="<?= $photo ?>" alt="foto" style="width:2.25cm; height:2.8cm"> -->
           <?php
if (!empty($photo)) {
    $photo_src = "data:image/jpeg;base64," . base64_encode($photo);
} else {
    // blank image putih
    $blank = imagecreatetruecolor(135, 168); // 2.25cm x 2.8cm @ 150dpi = 135x168 px
    $white = imagecolorallocate($blank, 255, 255, 255);
    imagefill($blank, 0, 0, $white);
    ob_start();
    imagejpeg($blank);
    $blankData = ob_get_clean();
    imagedestroy($blank);
    $photo_src = 'data:image/jpeg;base64,' . base64_encode($blankData);
}
?>
<img src="<?= $photo_src ?>" alt="foto" style="width:2.25cm; height:2.8cm;">



        </div>
    </div>
</div>
</div>
<?php endwhile; ?>
</div>
<script src="https://unpkg.com/jsbarcode@latest/dist/JsBarcode.all.min.js"></script>
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
document.addEventListener("DOMContentLoaded", function() {

let nomor = document.querySelectorAll(".nomor");
let no = 1;

let id;
nomor.forEach(e => {
  id = "#barcode-" + no.toString();
  let no_nomor = e.innerHTML;
  no++;
  JsBarcode(id, no_nomor, {
    width: 1,
    height: 30,
    displayValue: false
  });

});

});
</script>
<?php
mysqli_stmt_close($stmt);
} ?>
