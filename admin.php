<?php
session_start();
include_once "config/inc.connection.php";
include_once "config/inc.library.php"; 

error_reporting(E_ALL ^ (E_NOTICE | E_WARNING |E_DEPRECATED));

//security goes here


if(!isset($_SESSION['iduser'])){
  $_SESSION['pesan'] = 'Session Expire';
    echo '<script>window.location="index.php"</script>';
}

$_SESSION['warnabar'] = 'blue';
$_SESSION['warnatombol'] = 'blue';
 

$sqlAmbil = "SELECT * FROM ruser WHERE iduser='".$_SESSION['iduser']."'";
$qryAmbil = mysqli_query( $koneksidb, $sqlAmbil) or die ("Eror hapus data".mysqli_error($koneksidb));
$dataLogin= mysqli_fetch_array($qryAmbil);
    
$dataNamaUserLogin  = $dataLogin['nmuser'];

$dataPhotoUserLogin = $dataLogin['photouser'];
// $dataLevelUserLogin = $dataLogin['level_user'];
// $dataTimeUserLogin  = $dataLogin['date_access'];

if($dataPhotoUserLogin=="") {
    $namaFoto = "photo/images.jpg";
  }
  else {
    $namaFoto = $dataPhotoUserLogin;
  }
$dataCari = isset($_REQUEST['keyword']) ? $_REQUEST['keyword'] : '';
?>
<!DOCTYPE html>
<!-- 
Template Name: Metronic - Responsive Admin Dashboard Template build with Twitter Bootstrap 3.1.1
Version: 2.0.2
Author: KeenThemes
Website: http://www.keenthemes.com/
Contact: support@keenthemes.com
Purchase: http://themeforest.net/item/metronic-responsive-admin-dashboard-template/4021469?ref=keenthemes
License: You must have a valid license purchased only from themeforest(the above link) in order to legally use the theme for your project.
-->
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en" class="no-js">
<!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
<meta charset="utf-8"/>
<title>DSI Pustaka</title>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<meta content="" name="description"/>
<meta content="" name="author"/>
<!-- BEGIN GLOBAL MANDATORY STYLES -->
<link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css"/>
<link href="assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
<link href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"  />
<link href="assets/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css"/>
<!-- END GLOBAL MANDATORY STYLES -->
<!-- BEGIN THEME STYLES -->
<link href="assets/css/style-metronic.css" rel="stylesheet" type="text/css"/>
<link href="assets/css/style.css" rel="stylesheet" type="text/css"/>
<link href="assets/css/style-responsive.css" rel="stylesheet" type="text/css"/>
<link href="assets/css/plugins.css" rel="stylesheet" type="text/css"/>
<link href="assets/css/themes/grey.css" rel="stylesheet" type="text/css" id="style_color"/>
<link href="assets/css/custom.css" rel="stylesheet" type="text/css"/>
<!-- END THEME STYLES -->
<link rel="stylesheet" type="text/css" href="assets/plugins/bootstrap-fileinput/bootstrap-fileinput.css"/>
<link rel="stylesheet" type="text/css" href="assets/plugins/bootstrap-switch/css/bootstrap-switch.min.css"/>
<link rel="stylesheet" type="text/css" href="assets/plugins/jquery-tags-input/jquery.tagsinput.css"/>
<link rel="stylesheet" type="text/css" href="assets/plugins/bootstrap-markdown/css/bootstrap-markdown.min.css">
<link rel="stylesheet" type="text/css" href="assets/plugins/typeahead/typeahead.css">
<link rel="stylesheet" type="text/css" href="assets/plugins/select2/select2.css"/>
<link rel="stylesheet" type="text/css" href="assets/plugins/select2/select2-metronic.css"/>
<link rel="stylesheet" href="assets/plugins/data-tables/DT_bootstrap.css"/>
<link rel="stylesheet" type="text/css" href="assets/plugins/jstree/dist/themes/default/style.min.css"/>

<link rel="stylesheet" type="text/css" href="assets/plugins/clockface/css/clockface.css"/>
<link rel="stylesheet" type="text/css" href="assets/plugins/bootstrap-datepicker/css/datepicker.css"/>
<link rel="stylesheet" type="text/css" href="assets/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css"/>
<link rel="stylesheet" type="text/css" href="assets/plugins/bootstrap-colorpicker/css/colorpicker.css"/>
<link rel="stylesheet" type="text/css" href="assets/plugins/bootstrap-daterangepicker/daterangepicker-bs3.css"/>
<link rel="stylesheet" type="text/css" href="assets/plugins/bootstrap-datetimepicker/css/datetimepicker.css"/>

<link rel="stylesheet" type="text/css" href="assets/plugins/bootstrap-select/bootstrap-select.min.css"/>
<link rel="stylesheet" type="text/css" href="assets/plugins/jquery-multi-select/css/multi-select.css"/>
<link rel="stylesheet" type="text/css" href="assets/plugins/bootstrap/css/bootstrap.min.css"/>


<!-- SCRIPT JS-->
<script src="assets/plugins/jquery-1.10.2.min.js" type="text/javascript"></script>
<link rel="shortcut icon" href="favicon.ico"/>
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<body class="page-header-fixed page-full-width">
<!-- BEGIN HEADER -->
<div class="header navbar navbar-fixed-top mega-menu" style="width:100%">
  <!-- BEGIN TOP NAVIGATION BAR -->
  <div class="header-inner" style="display: flex;
    justify-content: space-between; width:100%">
    <!-- BEGIN LOGO -->
    <a class="navbar-brand" style="margin-top: 0;" href="admin.php"> 
        &nbsp;&nbsp;&nbsp; <!-- <font size="6" letter-spacing: -13px; color='red'>D</font>
                        <font size="6" color='yellow'>S</font>
                        <font size="6" color='green'>I</font>
                        <font size="3" color='white'>Pustaka</font> -->
    <div style="display: inline-block; letter-spacing: -3px; transform: translateX(-10px);">
    <span style="font-size: 2.5rem; color: red;">D</span>
    <span style="font-size: 2.5rem; color: yellow;">S</span>
    <span style="font-size: 2.5rem; color: green;">I</span>
    
</div> <span style="font-size: 2.5rem; color: white;">Pustaka</span>

      <!-- <img src="assets/img/DSIPustaka.png" alt="logo" style="margin-top:3px" class="img-responsive" width="150px" /> -->
    </a>
    <!-- END LOGO -->
    <!-- BEGIN HORIZANTAL MENU -->
    <div class="hor-menu hidden-sm hidden-xs" style="flex:1; display: flex;
    justify-content: center; width:66%;">
      <ul class="nav navbar-nav" style="display:flex; justify-content: space-between;">
        <!-- <li class="classic-menu-dropdown"><a href="?content=home">Halaman Utama</a></li> -->
        <?php 
        if ($_SESSION['kdjab']=="1"){
          //Awal Menu Admin Kaber   
        ?>
          <li class="classic-menu-dropdown">
            <a data-toggle="dropdown" href="javascript:;">PENGATURAN<i class="fa fa-angle-down"></i></a>
            <ul class="dropdown-menu">
              <li><a href="?content=pengguna_aplikasi">Pengguna</a></li>
              <li><a href="?content=ketentuan_peminjaman">Ketentuan Peminjaman</a></li>
              <li><a href="?content=kelas">Kelas</a></li>
              <li><a href="?content=penerbit">Penerbit</a></li>
              <li><a href="?content=kode_klasifikasi">Pengelompokan Buku</a></li>
              <li><a href="?content=kota">Kota</a></li>
              <li><a href="?content=tanggal_libur">Tanggal Libur</a></li>
              <!-- <li><a href="?content=update_jenis_buku">Update Jenis Buku</a></li> -->
            </ul>
          </li>
          <li class="classic-menu-dropdown">
            <a data-toggle="dropdown" href="javascript:;">PUSTAKA<i class="fa fa-angle-down"></i></a>
            <ul class="dropdown-menu">
              <li class="dropdown-submenu"><a href="#" class="dropdown-item">Buku</a>
                  <ul class="dropdown-menu">
                  <li><a href="?content=buku" class="dropdown-item">Cari Buku</a></li>
                    <li><a href="?content=tambahubahbuku" class="dropdown-item">Tambah[Ubah] Buku</a></li>
                    <li><a href="?content=bukurusak" class="dropdown-item">Buku Rusak/Hilang</a></li>
                    <li><a href="?content=hapusbuku" class="dropdown-item">Hapus Buku</a></li>
                    
                  </ul>
              </li>
              <!-- <li class="dropdown-submenu"><a href="#" class="dropdown-item">CD</a>
                  <ul class="dropdown-menu">
                    <li><a href="#" class="dropdown-item">Tambah CD</a></li>
                    <li><a href="#" class="dropdown-item">CD Rusak</a></li>
                    <li><a href="#" class="dropdown-item">Hapus CD</a></li>
                    <li><a href="#" class="dropdown-item">Cari CD</a></li>
                  </ul>
              </li>
              <li class="dropdown-submenu"><a href="#" class="dropdown-item">Majalah/Koran</a>
                  <ul class="dropdown-menu">
                    <li><a href="#" class="dropdown-item">Tambah Majalah/Koran</a></li>
                    <li><a href="#" class="dropdown-item">Hapus Majalah/Koran</a></li>
                    <li><a href="#" class="dropdown-item">Cari Majalah/Koran</a></li>
                  </ul>
              </li>   -->
            </ul>
          </li>
          <li class="classic-menu-dropdown">
              <a data-toggle="dropdown" href="javascript:;">KEANGGOTAAN<i class="fa fa-angle-down"></i></a> 
              <ul class="dropdown-menu">
                <li><a href="?content=tambahindividu">Tambah[Ubah] Anggota</a></li>
                <li><a href="?content=carianggota">Cari Anggota</a></li>
                <li><a href="?content=cetakkartu">Cetak Kartu</a></li>
              </ul>
          </li>
         
          <li class="classic-menu-dropdown">
            <a data-toggle="dropdown" href="javascript:;">PEMINJAMAN BUKU<i class="fa fa-angle-down"></i></a>
            <ul class="dropdown-menu">
              <li class="dropdown-submenu"><a href="#" class="dropdown-item">Individu</a>
                  <ul class="dropdown-menu">
                    <li><a href="?content=peminjamanindividu" class="dropdown-item">Peminjaman Individu</a></li>
                    <li><a href="?content=pengembalianindividu" class="dropdown-item">Pengembalian Individu</a></li>
                  </ul>
              </li>
              <li class="dropdown-submenu"><a href="#" class="dropdown-item">Kolektif</a>
                  <ul class="dropdown-menu">
                    <li><a href="?content=peminjamankolektif" class="dropdown-item">Peminjaman Kolektif</a></li>
                    <li><a href="?content=pengembaliankolektif" class="dropdown-item">Pengembalian Kolektif</a></li>
                  </ul>
              </li>
            </ul>
          </li>
          <li class="classic-menu-dropdown">
            <a data-toggle="dropdown" href="javascript:;">PENGUNJUNG<i class="fa fa-angle-down"></i></a>
            <ul class="dropdown-menu">
              <li><a href="?content=pencatatan">Daftar Pengunjung</a></li>
              <li><a href="?content=grafiktopten">Grafik Top 10</a></li>
              <li><a href="?content=grafikjumlahpengunjung">Grafik Jumlah Pengunjung</a></li>
              <li><a href="?content=pelaporan" class="dropdown-item">Pelaporan Anggota</a></li>
              <li><a href="?content=grafikpeminjamanbuku" class="dropdown-item">Grafik Peminjaman Buku</a></li>
            </ul>
          </li>
          <li class="classic-menu-dropdown">
            <a data-toggle="dropdown" href="javascript:;">LAPORAN<i class="fa fa-angle-down"></i></a>
            <ul class="dropdown-menu">
              <li class="dropdown-submenu"><a href="#" class="dropdown-item">Koleksi Perpustakaan</a>
                  <ul class="dropdown-menu">
                    <li><a href="?content=bukuteks_peminjamanindividu" class="dropdown-item">Peminjaman Individu</a></li>
                    <li><a href="?content=bukuteks_pengembalianindividu" class="dropdown-item">Pengembalian Individu</a></li>
                    <li><a href="?content=bukuteks_peminjamanperanggota" class="dropdown-item">Peminjaman per Anggota</a></li>
                    <li><a href="?content=bukuteks_peminjamanperjenis" class="dropdown-item">Peminjaman Jenis</a></li> 
                    <li><a href="?content=bukuteks_peminjamankolektif" class="dropdown-item">Peminjaman Kolektif</a></li> 
                    <li><a href="?content=bukuteks_daftarpustakaterpinjam" class="dropdown-item">Daftar Pustaka Terpinjam</a></li> 
                  </ul>
              </li>
              <li class="dropdown-submenu"><a href="#" class="dropdown-item">Katalog & Bibliografi</a>
                  <ul class="dropdown-menu">
                    <li><a href="?content=cetakkatalog" class="dropdown-item">Cetak Katalog</a></li>
                    <li><a href="?content=cetakkodebukucd" class="dropdown-item">Cetak Kode (Punggung) Buku/CD</a></li>
                    <li><a href="?content=cetakbibliografi" class="dropdown-item">Cetak Bibliografi</a></li>
                  </ul>
              </li>
              <li class="dropdown-submenu"><a href="#" class="dropdown-item">Laporan Umum</a>
                  <ul class="dropdown-menu">
                    
                    <li><a href="?content=koleksibukulengkap" class="dropdown-item">Koleksi Buku Lengkap</a></li> 
                    <!-- <li><a href="?content=koleksibukureferensi" class="dropdown-item">Koleksi Kode klasifikasi</a></li> -->
                    <!-- <li><a href="?content=koleksicdlengkap" class="dropdown-item">Koleksi CD Lengkap</a></li> -->
                    <li><a href="?content=rekapkoleksibuku" class="dropdown-item">Rekap Koleksi Buku</a></li> 
                    <li><a href="?content=perkembanganbuku" class="dropdown-item">Perkembangan Buku</a></li>
                    <li><a href="?content=rekapdenda" class="dropdown-item">Rekap Denda</a></li>
                    <li>-------------------------</li> 
                    <li><a href="?content=daftaranggota" class="dropdown-item">Daftar Anggota</a></li>
                    <li><a href="?content=rekapjumlahanggota" class="dropdown-item">Rekap Jumlah Pengunjung / Anggota</a></li>
                    <li><a href="?content=kartubebastanggungan" class="dropdown-item">Kartu Bebas Tanggungan</a></li>
                    <li><a href="?content=logtransaksi" class="dropdown-item">Log History</a></li>
                  </ul>
              </li>
            </ul>
          </li>
          <!-- <li class="classic-menu-dropdown">
            <a data-toggle="dropdown" href="javascript:;">DATABASE<i class="fa fa-angle-down"></i></a>
            <ul class="dropdown-menu">
              <li><a href="?content=tabel">Cari Tabel</a></li>
            </ul>
          </li> -->
            
        <?php 
        } // akhir menu admin kaber
        else if ($_SESSION['kdjab']=="2") { ?>
         
          <li class="classic-menu-dropdown">
            <a data-toggle="dropdown" href="javascript:;">Peminjaman Buku<i class="fa fa-angle-down"></i></a>
            <ul class="dropdown-menu">
              <li class="dropdown-submenu"><a href="#" class="dropdown-item">Individu</a>
                  <ul class="dropdown-menu">
                    <li><a href="?content=peminjamanindividu" class="dropdown-item">Peminjaman Individu</a></li>
                    <li><a href="?content=pengembalianindividu" class="dropdown-item">Pengembalian Individu</a></li>
                  </ul>
              </li>
              <li class="dropdown-submenu"><a href="#" class="dropdown-item">Kolektif</a>
                  <ul class="dropdown-menu">
                    <li><a href="?content=peminjamankolektif" class="dropdown-item">Peminjaman Kolektif</a></li>
                    <li><a href="?content=pengembaliankolektif" class="dropdown-item">Pengembalian Kolektif</a></li>
                  </ul>
              </li>
            </ul>
          </li>
          <li class="classic-menu-dropdown">
            <a data-toggle="dropdown" href="javascript:;">PENGUNJUNG<i class="fa fa-angle-down"></i></a>
            <ul class="dropdown-menu">
              <li><a href="?content=pencatatan">Daftar Pengunjung</a></li>
              <li><a href="?content=grafiktopten">Grafik Top 10</a></li>
              <li><a href="?content=grafikjumlahpengunjung">Grafik Jumlah Pengunjung</a></li>
              <li><a href="?content=pelaporan">Pelaporan</a></li>
            </ul>
          </li>
          <li class="classic-menu-dropdown">
            <a data-toggle="dropdown" href="javascript:;">LAPORAN<i class="fa fa-angle-down"></i></a>
            <ul class="dropdown-menu">
              <li class="dropdown-submenu"><a href="#" class="dropdown-item">Koleksi Perpustakaan</a>
                  <ul class="dropdown-menu">
                    <li><a href="?content=bukuteks_peminjamanindividu" class="dropdown-item">Peminjaman Individu</a></li>
                    <li><a href="?content=bukuteks_pengembalianindividu" class="dropdown-item">Pengembalian Individu</a></li>
                    <li><a href="?content=bukuteks_peminjamanperanggota" class="dropdown-item">Peminjaman per Anggota</a></li>
                    <li><a href="?content=bukuteks_peminjamanperjenis" class="dropdown-item">Peminjaman Jenis</a></li> 
                    <li><a href="?content=bukuteks_peminjamankolektif" class="dropdown-item">Peminjaman Kolektif</a></li> 
                    <li><a href="?content=bukuteks_daftarpustakaterpinjam" class="dropdown-item">Daftar Pustaka Terpinjam</a></li> 
                  </ul>
              </li>
              <li class="dropdown-submenu"><a href="#" class="dropdown-item">Katalog & Bibliografi</a>
                  <ul class="dropdown-menu">
                    <li><a href="?content=cetakkatalog" class="dropdown-item">Cetak Katalog</a></li>
                    <li><a href="?content=cetakkodebukucd" class="dropdown-item">Cetak Kode (Punggung) Buku/CD</a></li>
                  </ul>
              </li>
              <li class="dropdown-submenu"><a href="#" class="dropdown-item">Laporan Umum</a>
                  <ul class="dropdown-menu">
                    <li>-------------------------</li> 
                    <li><a href="?content=koleksibukulengkap" class="dropdown-item">Koleksi Buku Lengkap</a></li> 
                    <!-- <li><a href="?content=koleksibukureferensi" class="dropdown-item">Koleksi Kode klasifikasi</a></li> -->
                    <!-- <li><a href="?content=koleksicdlengkap" class="dropdown-item">Koleksi CD Lengkap</a></li> -->
                    <li><a href="?content=rekapkoleksibuku" class="dropdown-item">Rekap Koleksi Buku</a></li> 
                    <li>-------------------------</li> 
                    <li><a href="?content=daftaranggota" class="dropdown-item">Daftar Anggota</a></li>
                    <li><a href="?content=rekapjumlahanggota" class="dropdown-item">Rekap Jumlah Pengunjung / Anggota</a></li>
                    <li><a href="?content=logtransaksi" class="dropdown-item">Log History</a></li>
                  </ul>
              </li>
            </ul>
          </li>
        <?php }else { 
        ?>  
         <li class="classic-menu-dropdown">
            <a  href="?content=buku">CARI BUKU<i class=""></i></a>
            
          </li>
          <li class="classic-menu-dropdown">

            <a href="?content=pencatatan">ABSENSI<i class=""></i></a>
            
          </li>
        <?php  
        } //Akhir Menu Bea Cukai
        ?>
        <a class="navbar-brand" style="margin-top: 0px; width: 25px;" href="admin.php">
          </a>
      </ul>
    </div>
    <p style="font-size:10px; color: yellow; margin-top:10px;"><?= getNmsekolah($koneksidb) ?></p>
    <!-- END HORIZANTAL MENU -->
    <!-- BEGIN RESPONSIVE MENU TOGGLER -->
    <a href="javascript:;" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
      <img src="assets/img/menu-toggler.png" alt=""/>
    </a>
    <!-- END RESPONSIVE MENU TOGGLER -->
    <!-- BEGIN TOP NAVIGATION MENU -->
    <ul class="nav navbar-nav pull-right">
      <!-- BEGIN NOTIFICATION DROPDOWN -->
      <li class="dropdown user">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
          <img alt="" src="<?php echo $namaFoto; ?>" height="28px"/>
          <span class="username hidden-1024">
             <?php echo $dataNamaUserLogin ?>
          </span>
          <i class="fa fa-angle-down"></i>
        </a>
        <ul class="dropdown-menu">
          <li><a href="?content=konfigurasiphoto"><i class="fa fa-picture-o"></i> Ganti Photo</a></li>
          <li><a href="?content=konfigurasipassword"><i class="fa fa-lock"></i> Ganti Password</a></li>
          <li><a href="?content=konfigurasiprofil"><i class="fa fa-user"></i> Ganti Profil</a></li>
          <li><a href="keluar.php"><i class="fa fa-sign-out"></i> Log Out</a></li>
        </ul>
      </li>
      <!-- END USER LOGIN DROPDOWN -->
    </ul>
    <!-- END TOP NAVIGATION MENU -->
  </div>
  <!-- END TOP NAVIGATION BAR -->
</div>
<!-- END HEADER -->
<div class="clearfix">
</div>
<!-- BEGIN CONTAINER -->
<div class="page-container">
  <!-- BEGIN EMPTY PAGE SIDEBAR -->
  
  <!-- END EMPTY PAGE SIDEBAR -->
  <!-- BEGIN CONTENT -->
  <div class="page-content-wrapper">
  <div class="page-sidebar navbar-collapse collapse">
      <ul class="page-sidebar-menu" data-slide-speed="200" data-auto-scroll="true">
        
      <?php 
        if ($_SESSION['kdjab']!='99'){
          //Awal Menu Admin Kaber   
        ?>
          <li  class="dropdown">
            <a href="#">PENGATURAN<span class="arrow"></span></a>
            <ul class="sub-menu">
              <li><a href="?content=pengguna_aplikasi">Pengguna</a></li>
              <li><a href="?content=ketentuan_peminjaman">Ketentuan Peminjaman</a></li>
              <li><a href="?content=kelas">Kelas</a></li>
              <li><a href="?content=penerbit">Penerbit</a></li>
              <li><a href="?content=kode_klasifikasi">Pengelompokan Buku</a></li>
              <li><a href="?content=kota">Kota</a></li>
              <li><a href="?content=tanggal_libur">Tanggal Libur</a></li>
              <!-- <li><a href="?content=update_jenis_buku">Update Jenis Buku</a></li> -->
            </ul> 
          </li>
          <li class="dropdown">
            <a href="#">PUSTAKA<span class="arrow"></span></a>
            <ul class="sub-menu">
              <li><a href="#" class="dropdown-item">Buku</a>
                  <ul class="sub-menu">
                    <li><a href="?content=tambahubahbuku" >Tambah[Ubah] Buku</a></li>
                    <li><a href="?content=bukurusak" >Buku Rusak/Hilang</a></li>
                    <li><a href="?content=hapusbuku" >Hapus Buku</a></li>
                    <li><a href="?content=buku" >Cari Buku</a></li>
                  </ul>
              </li>
              <li><a href="#" >CD</a>
                  <ul class="sub-menu">
                    <li><a href="#" >Tambah CD</a></li>
                    <li><a href="#" >CD Rusak</a></li>
                    <li><a href="#" >Hapus CD</a></li>
                    <li><a href="#" >Cari CD</a></li>
                  </ul>
              </li>
              <li><a href="#" >Majalah/Koran</a>
                  <ul class="sub-menu">
                    <li><a href="#" >Tambah Majalah/Koran</a></li>
                    <li><a href="#" >Hapus Majalah/Koran</a></li>
                    <li><a href="#" >Cari Majalah/Koran</a></li>
                  </ul>
              </li>  
            </ul>
          </li>
          <li class="dropdown">
              <a href="#">KEANGGOTAAN<span class="arrow"></span></a> 
              <ul class="sub-menu">
                <li><a href="?content=tambahindividu">Tambah Individu</a></li>
                <li><a href="?content=tambahmasal">Tambah Masal/Import Xlsx</a></li>
                <li><a href="?content=carianggota">Cari Anggota</a></li>
                <li><a href="?content=cetakkartu">Cetak Kartu</a></li>
              </ul>
          </li>
         
          <li class="dropdown">
            <a href="#">Peminjaman Buku<span class="arrow"></span></a>
            <ul class="sub-menu">
              <li><a href="#" >Individu</a>
                  <ul class="sub-menu">
                    <li><a href="?content=peminjamanindividu" >Peminjaman Individu</a></li>
                    <li><a href="?content=pengembalianindividu" >Pengembalian Individu</a></li>
                  </ul>
              </li>
              <li><a href="#" >Kolektif</a>
                  <ul class="sub-menu">
                    <li><a href="?content=peminjamankolektif" >Peminjaman Kolektif</a></li>
                    <li><a href="?content=pengembaliankolektif" >Pengembalian Kolektif</a></li>
                  </ul>
              </li>
            </ul>
          </li>
          <li class="dropdown">
            <a href="#">PENGUNJUNG<span class="arrow"></span></a>
            <ul class="sub-menu">
              <li><a href="?content=pencatatan">Daftar Pengunjung</a></li>
              <li><a href="?content=grafiktopten">Grafik Top 10</a></li>
              <li><a href="?content=grafikjumlahpengunjung">Grafik Jumlah Pengunjung</a></li>
              <li><a href="?content=pelaporan">Pelaporan</a></li>
            </ul>
          </li>
          <li class="dropdown">
            <a href="#">LAPORAN<span class="arrow"></span></a>
            <ul class="sub-menu">
              <li><a href="#" >Koleksi Perpustakaan</a>
                  <ul class="sub-menu">
                    <li><a href="?content=bukuteks_peminjamanindividu" >Peminjaman Individu</a></li>
                    <li><a href="?content=bukuteks_pengembalianindividu" >Pengembalian Individu</a></li>
                    <li><a href="?content=bukuteks_peminjamanperanggota" >Peminjaman per Anggota</a></li>
                    <li><a href="?content=bukuteks_peminjamanperjenis" >Peminjaman Jenis</a></li> 
                    <li><a href="?content=bukuteks_peminjamankolektif" >Peminjaman Kolektif</a></li> 
                    <li><a href="?content=bukuteks_daftarpustakaterpinjam" >Daftar Pustaka Terpinjam</a></li> 
                  </ul>
              </li>
              <li><a href="#" >Katalog & Bibliografi</a>
                  <ul class="sub-menu">
                    <li><a href="?content=cetakkatalog" >Cetak Katalog</a></li>
                    <li><a href="?content=cetakkodebukucd" >Cetak Kode (Punggung) Buku/CD</a></li>
                  </ul>
              </li>
              <li><a href="#" >Laporan Umum</a>
                  <ul class="sub-menu">
                    <li>-------------------------</li> 
                    <li><a href="?content=koleksibukulengkap" >Koleksi Buku Lengkap</a></li> 
                    <!-- <li><a href="?content=koleksibukureferensi" >Koleksi Kode klasifikasi</a></li> -->
                    <li><a href="?content=koleksicdlengkap" >Koleksi CD Lengkap</a></li>
                    <li><a href="?content=rekapkoleksibuku" >Rekap Koleksi Buku</a></li> 
                    <li>-------------------------</li> 
                    <li><a href="?content=daftaranggota" >Daftar Anggota</a></li>
                    <li><a href="?content=rekapjumlahanggota" >Rekap Jumlah Pengunjung / Anggota</a></li>
                  </ul>
              </li>
            </ul>
          </li>
          <!-- <li>
            <a href="#">DATABASE<span class="arrow"></span></a>
            <ul class="sub-menu">
              <li><a href="?content=tabel">Cari Tabel</a></li>
            </ul>
          </li> -->
          <a class="navbar-brand" style="margin-top: 0px; width: 25px;" href="admin.php">
            <img src="assets/img/DSIPustaka.png" alt="logo" style="margin-top:3px" class="img-responsive" width="25px" />
          </a>
            
        <?php 
        } // akhir menu admin kaber
        else {
          //Bea Cukai
        ?>  
          <li>
            <a href="#">Laporan<span class="arrow"></span></a>
            <ul class="sub-menu">
              <li><a href="#">Pemasukan Barang Per Dok Pabean</a></li>
              <li><a href="#">Pengeluaran Barang Per Dok Pabean</a></li>
              <li><a href="#">Pertanggungjawaban WIP</a></li>
              
              <li><a href="#">Pertanggungjawaban Bahan Baku</a></li>
              <li><a href="#">Pertanggungjawaban Barang Jadi</a></li>
              <li><a href="#">Pertanggungjawaban Scrap</a></li>
              <li><a href="#">Pertanggungjawaban Mesin dan Peralatan</a></li>
              
            </ul>
          </li> 
        <?php  
        } //Akhir Menu Bea Cukai
        ?>
      </ul>
    </div>  
    <div class="page-content">
      
        <?php 
          if (isset($_SESSION['pesan']) && $_SESSION['pesan'] <> '') {
              echo '<div class="alert alert-success alert-dismissable">
                      <button type="but ton" class="close" data-dismiss="alert" aria-hidden="true"></button>
                      <i class="fa fa-check"></i>&nbsp;'.$_SESSION['pesan'].'.
                    </div>';
            }
            $_SESSION['pesan'] = '';
              
              
          if(isset($_GET['content'])){
             include("content.php");
            }
            else{
              //include("content/home_administrator.php");
              echo "<img src='assets/img/boy-asian-lib.jpg' class='img-responsive' style='align :center'  />";
            }
          ?>
     
      <!-- END PAGE CONTENT-->
    </div>
  </div>
  <!-- END CONTENT -->
</div>
<!-- END CONTAINER -->
<!-- BEGIN FOOTER -->
<div class="footer">
  <div class="footer-inner">
     esikatERP @2023. Contact Us : office@klikdsi.com
  </div>
  <div class="footer-tools">
    <span class="go-top">
      <i class="fa fa-angle-up"></i>
    </span>
  </div>
</div>
<!-- END FOOTER -->
<!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
<!-- BEGIN CORE PLUGINS -->
<!--[if lt IE 9]>
  <script src="assets/plugins/excanvas.min.js"></script>
  <script src="assets/plugins/respond.min.js"></script>  
  <![endif]-->
  <script src="assets/plugins/jquery-1.10.2.min.js" type="text/javascript"></script>
<script src="assets/plugins/jquery-migrate-1.2.1.min.js" type="text/javascript"></script>
<script src="assets/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="assets/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
<script src="assets/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
<script src="assets/plugins/jquery.blockui.min.js" type="text/javascript"></script>
<script src="assets/plugins/jquery.cokie.min.js" type="text/javascript"></script>
<script src="assets/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>
<!-- END CORE PLUGINS -->
<script type="text/javascript" src="assets/plugins/fuelux/js/spinner.min.js"></script>
<script type="text/javascript" src="assets/plugins/bootstrap-fileinput/bootstrap-fileinput.js"></script>
<script type="text/javascript" src="assets/plugins/jquery-inputmask/jquery.inputmask.bundle.min.js"></script>
<script type="text/javascript" src="assets/plugins/jquery.input-ip-address-control-1.0.min.js"></script>
<script src="assets/plugins/jquery.pwstrength.bootstrap/src/pwstrength.js" type="text/javascript"></script>
<script src="assets/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
<script src="assets/plugins/jquery-tags-input/jquery.tagsinput.min.js" type="text/javascript"></script>
<script src="assets/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js" type="text/javascript"></script>
<script src="assets/plugins/bootstrap-touchspin/bootstrap.touchspin.js" type="text/javascript"></script>
<script src="assets/plugins/typeahead/handlebars.min.js" type="text/javascript"></script>
<script src="assets/plugins/typeahead/typeahead.min.js" type="text/javascript"></script>
<script type="text/javascript" src="assets/plugins/select2/select2.min.js"></script>
<script type="text/javascript" src="assets/plugins/data-tables/jquery.dataTables.js"></script>
<script type="text/javascript" src="assets/plugins/data-tables/DT_bootstrap.js"></script>
<script type="text/javascript" src="assets/plugins/bootstrap-select/bootstrap-select.min.js"></script>
<script type="text/javascript" src="assets/plugins/jquery-multi-select/js/jquery.multi-select.js"></script>

<script type="text/javascript" src="assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="assets/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js"></script>
<script type="text/javascript" src="assets/plugins/clockface/js/clockface.js"></script>
<script type="text/javascript" src="assets/plugins/bootstrap-daterangepicker/moment.min.js"></script>
<script type="text/javascript" src="assets/plugins/bootstrap-daterangepicker/daterangepicker.js"></script>
<script type="text/javascript" src="assets/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.js"></script>
<script type="text/javascript" src="assets/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"></script>

<!-- END PAGE LEVEL PLUGINS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="assets/scripts/core/app.js"></script>
<script src="assets/scripts/custom/components-form-tools.js"></script>
<script src="assets/scripts/custom/table-managed.js"></script>
<script src="assets/scripts/custom/components-dropdowns.js"></script>
<script src="assets/scripts/custom/components-pickers.js"></script>
<script src="assets/plugins/jstree/dist/jstree.min.js"></script>
<script src="assets/scripts/custom/ui-tree.js"></script>

<link href="assets/plugins/tree/css/dtree.css" rel="stylesheet" />
<script src="assets/plugins/tree/js/jquery-2.1.1.min.js"></script>
<script src="assets/plugins/tree/js/dtree.js"></script>
<script type="text/javascript">
  $(window).load(function() {
    $(".loader").fadeOut("slow");
  });
</script>

<script>
    jQuery(document).ready(function() {    
       App.init();
       TableManaged.init();
       ComponentsDropdowns.init();
       ComponentsPickers.init();
       UITree.init();
    });
  </script>

<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>