<?php
session_start();
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));


    $_SESSION['iduser']     = "";
    $_SESSION['namauser']   = "";
    $_SESSION['isloginsukses'] = false;
    $_SESSION['kdjab']      = "";
    $_SESSION['noapk']      = "";
    $_SESSION['isLogIndsiPOk1']= "";

	unset($_SESSION['iduser']);
    unset($_SESSION['namauser']);
	unset($_SESSION['isloginsukses']);
	unset($_SESSION['kdjab']);
	unset($_SESSION['noapk']);
    unset($_SESSION['isLogIndsiPOk1']);
	
    echo '<script>window.location="index.php"</script>';
?>