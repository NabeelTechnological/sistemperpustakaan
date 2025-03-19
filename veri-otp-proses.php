<?php
//Brojo 20221107
session_start();
include 'config/inc.connection.php';

if (! isset($_SESSION['isLogIndsiOBEbelumOk'])){
  echo "<script>window.location.href='index.php';</script>";
}else {
  if ($_SESSION['isLogIndsiOBEbelumOk'] <>'2ktuYZ639OIs'){
      echo "<script>window.location.href='index.php';</script>";
  }
}

$iduser= isset($_SESSION['iduser']) ? $_SESSION['iduser'] : '';
$otp    = $_POST['otp']; 

$sqlo = "select a.*, b.* from otp_expiry a inner join ruser b on a.iduser=b.iduser where a.iduser='$iduser' and a.otp='$otp'";
$qryo = mysqli_query($koneksidb,$sqlo) or die ("Gagal query otp. ". mysqli_error($koneksidb));

if(mysqli_num_rows($qryo)>0){
    $rs= mysqli_fetch_array($qryo);  

    //setelah sukses otp, maka hapus otp untuk user tersebut
    $sqldel = "delete from otp_expiry where iduser=? "; 
    $stmt = mysqli_prepare($koneksidb, $sqldel) or die ("Gagal menyiapkan statement0: " . mysqli_error($koneksidb)); 
    mysqli_stmt_bind_param($stmt, "s", $iduser); 
    mysqli_stmt_execute($stmt) or die ("Gagal del OTP : " . mysqli_error($koneksidb));
    mysqli_stmt_close($stmt); 


    //buat session  
    unset($_SESSION['isLogIndsiOBEbelumOk']);

    //**** VARIABEL GLOBAL ****// 
    // $_SESSION['iduser']  = $rs['iduser'];
    // $_SESSION['namauser']    = $rs['nama'];
    // $_SESSION['isloginsukses'] = true;
    // $_SESSION['kdjab']       = $rs['kdjab']; 
    // $_SESSION['thawalso']    = 2023 ;
    // $_SESSION['tglawalso']  = date("Y-m-d", strtotime("2023-05-24")); 
    
    // $_SESSION['dataTglAwal'] = date('Y-m-d');
    // $_SESSION['dataTglAkhir'] = date('Y-m-d');

    $_SESSION['iduser'] 	      = $rs['iduser'];
    $_SESSION['namauser'] 	    = $rs['nmuser'];
    $_SESSION['isloginsukses']  = true;
    $_SESSION['kdjab'] 		      = $rs['leveluser'];
    $_SESSION['noapk'] 		      = $rs['noapk'];
    $_SESSION['isLogIndsiPOk1']='1jUhdsYQ9OIs'; 
 
    //HAK MENU
    echo '<script>window.location="admin.php"</script>';
}else{
    echo "<script>window.location.href='veri-otp.php?msge=s';</script>";
}

?>