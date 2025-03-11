<?php
session_start();
include "config/inc.connection.php";
include "config/inc.library.php";
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));

// Simpan iduser sebelum menghapus session
$iduser = $_SESSION['iduser'];
$noapk = $_SESSION['noapk'];

// Catat log sebelum session dihapus
if (!empty($iduser)) {
    logTransaksi($iduser, date('Y-m-d H:i:s'), 'Keluar sistem', $noapk);
}

// Hapus semua session
$_SESSION = array();
session_destroy();

// Redirect ke halaman login
echo '<script>window.location="index.php"</script>';

?>