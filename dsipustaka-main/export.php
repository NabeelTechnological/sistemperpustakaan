<?php 
session_start();   
include_once "config/inc.connection.php";
include_once "config/inc.library.php";

if (isset($_GET['lap'])){

	// PELAPORAN
	if ($_GET['lap']=="pelaporan"){
		$harian			= $_POST['txtHarian'];
		$bulan			= $_POST['txtBulan'];
		$tahun			= $_POST['txtTahun'];
		$dariTanggal	= $_POST['txtDariTanggal'];
		$sampaiTanggal	= $_POST['txtSampaiTanggal'];	
		$pilihan		= $_POST['txtPilihan'];	

		if(empty($harian) && (empty($bulan) || empty($tahun)) && (empty($dariTanggal) || empty($sampaiTanggal))){
			$_SESSION['pesanKesalahan'] = "<div class='alert alert-danger alert-dismissable'>
			<button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
			<strong><i class='fa fa-times'></i>&nbsp; Data Tidak Boleh Kosong</strong>
			</div>";
			
			$pagename 		= $_GET['page'];
			echo "<script>window.location='".$pagename."?content=pelaporan'</script>";	
		}else{
			if ($_POST['btnSave']=="cetak"){
				$tampil	= $_POST['jnsPengunjung'];
				include "cetak/pelaporan.php";

			}else if ($_POST['btnSave']=="tampilAnggota" || $_POST['btnSave']=="tampilTamu"){
				$tampil			= $_POST['btnSave'];
				$pagename 		= $_GET['page'];

				$_SESSION['dataHarian'] = $harian;
				$_SESSION['dataBulan'] = $bulan;
				$_SESSION['dataTahun'] = $tahun;
				$_SESSION['dataDariTanggal'] = $dariTanggal;
				$_SESSION['dataSampaiTanggal'] = $sampaiTanggal;
				$_SESSION['dataPilihan'] = $pilihan;
				$_SESSION['tampil'] = $tampil;

				echo "<script>window.location='".$pagename."?content=pelaporan&btnsave=tampil'</script>";	 	
				
			}
		}
	}

	
	// KOLEKSI PUSTAKA
else if ($_GET['lap']=="koleksipustaka_peminjamanindividu") {
	$harian			= $_POST['txtHarian'];
	$bulan			= $_POST['txtBulan'];
	$tahun			= $_POST['txtTahun'];
	$dariTanggal	= $_POST['txtDariTanggal'];
	$sampaiTanggal	= $_POST['txtSampaiTanggal'];	
	$anggota		= $_POST['txtAnggota'];
	// $buku			= $_POST['txtBuku'];
	$jnskelamin		= $_POST['txtJnskelamin'];
	$cakupan		= $_POST['txtCakupan'];
	$kelas		= $_POST['txtKelas'];
	$tampil			= $_POST['btnSave'];
	$pilihan			= $_POST['txtPilihan'];

	if(empty($harian) && (empty($bulan) || empty($tahun)) && (empty($dariTanggal) || empty($sampaiTanggal)) || empty($anggota) || empty($jnskelamin) || empty($cakupan)){
		$_SESSION['pesanKesalahan'] = "<div class='alert alert-danger alert-dismissable'>
		<button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
		<strong><i class='fa fa-times'></i>&nbsp; Data Tidak Boleh Kosong</strong>
		</div>";
		
		$pagename 		= $_GET['page'];
		echo "<script>window.location='".$pagename."?content=koleksipustaka_peminjamanindividu'</script>";	
	}else{

		if ($_POST['btnSave']=="cetak"){
			include "cetak/koleksipustaka_peminjamanindividu.php";

		}else if ($_POST['btnSave']=="tampil"){
			$pagename 		= $_GET['page'];
			$_SESSION['dataHarian'] =  $harian;
			$_SESSION['dataBulan'] = $bulan;
			$_SESSION['dataTahun'] = $tahun;
			$_SESSION['dataDariTanggal'] = $dariTanggal;
			$_SESSION['dataSampaiTanggal'] = $sampaiTanggal;
			$_SESSION['dataAnggota'] = $anggota;
			// $_SESSION['dataBuku'] = $buku;
			$_SESSION['dataJnskelamin'] = $jnskelamin;
			$_SESSION['dataCakupan'] = $cakupan;
			$_SESSION['dataKelas'] = $kelas;
			$_SESSION['dataPilihan'] = $pilihan;
			$_SESSION['tampil'] = $tampil;


			echo "<script>window.location='".$pagename."?content=koleksipustaka_peminjamanindividu&btnsave=tampil'</script>";	 	


		}
	}
	}

if ($_GET['lap']=="koleksipustaka_pengembalianindividu") {
	$harian			= $_POST['txtHarian'];
	$bulan			= $_POST['txtBulan'];
	$tahun			= $_POST['txtTahun'];
	$dariTanggal	= $_POST['txtDariTanggal'];
	$sampaiTanggal	= $_POST['txtSampaiTanggal'];	
	$anggota		= $_POST['txtAnggota'];
	$buku			= $_POST['txtBuku'];
	$pilihan			= $_POST['txtPilihan'];
	$tampil			= $_POST['btnSave'];

	if(empty($harian) && (empty($bulan) || empty($tahun)) && (empty($dariTanggal) || empty($sampaiTanggal)) || empty($anggota) ){
		$_SESSION['pesanKesalahan'] = "<div class='alert alert-danger alert-dismissable'>
		<button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
		<strong><i class='fa fa-times'></i>&nbsp; Data Tidak Boleh Kosong</strong>
		</div>";
		
		$pagename 		= $_GET['page'];
		echo "<script>window.location='".$pagename."?content=koleksipustaka_pengembalianindividu'</script>";	
	}else{


		if ($_POST['btnSave']=="cetak"){
			$tampil	= $_POST['jnsPengunjung'];
			include "cetak/koleksipustaka_pengembalianindividu.php";

		}else if ($_POST['btnSave']=="tampil"){
			$pagename 		= $_GET['page'];
			$_SESSION['dataHarian'] =  $harian;
			$_SESSION['dataBulan'] = $bulan;
			$_SESSION['dataTahun'] = $tahun;
			$_SESSION['dataDariTanggal'] = $dariTanggal;
			$_SESSION['dataSampaiTanggal'] = $sampaiTanggal;
			$_SESSION['dataAnggota'] = $anggota;
			$_SESSION['dataBuku'] = $buku;
			$_SESSION['dataPilihan'] = $pilihan;
			$_SESSION['tampil'] = $tampil;

			
			echo "<script>window.location='".$pagename."?content=koleksipustaka_pengembalianindividu&btnsave=tampil'</script>";	 	
			
			
		}
	}
	}	

else if ($_GET['lap'] == "koleksipustaka_peminjamanperanggota") {
	$harian         = $_POST['txtHarian'];
	$bulan          = $_POST['txtBulan'];
	$tahun          = $_POST['txtTahun'];
	$dariTanggal    = $_POST['txtDariTanggal'];
	$sampaiTanggal  = $_POST['txtSampaiTanggal'];
	$anggota        = $_POST['txtAnggota'];
	$pilihan        = $_POST['txtPilihan'];


	if(empty($harian) && (empty($bulan) || empty($tahun)) && (empty($dariTanggal) || empty($sampaiTanggal)) || empty($anggota)){
		$_SESSION['pesanKesalahan'] = "<div class='alert alert-danger alert-dismissable'>
		<button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
		<strong><i class='fa fa-times'></i>&nbsp; Data Tidak Boleh Kosong</strong>
		</div>";
		
		$pagename 		= $_GET['page'];
		echo "<script>window.location='".$pagename."?content=koleksipustaka_peminjamanperanggota'</script>";	
	}else{
	if ($_POST['btnSave'] == "cetak") {
		$tampil = $_POST['jnsPengunjung'];
		include "cetak/koleksipustaka_peminjamanperanggota.php";

	} else if ($_POST['btnSave'] == "tampil") {
		$tampil                 = $_POST['btnSave'];
		$pagename               = $_GET['page'];

		$_SESSION['dataHarian']         = $harian;
		$_SESSION['dataBulan']          = $bulan;
		$_SESSION['dataTahun']          = $tahun;
		$_SESSION['dataDariTanggal']    = $dariTanggal;
		$_SESSION['dataSampaiTanggal']  = $sampaiTanggal;
		$_SESSION['dataAnggota']        = $anggota;
		$_SESSION['tampil']             = $tampil;
		$_SESSION['dataPilihan']             = $pilihan;

		echo "<script>window.location='" . $pagename . "?content=koleksipustaka_peminjamanperanggota&btnsave=tampil'</script>";
	}
}
}

else if ($_GET['lap']=="koleksipustaka_peminjamanperjenis") {
	$harian			= $_POST['txtHarian'];
		$bulan			= $_POST['txtBulan'];
		$tahun			= $_POST['txtTahun'];
		$dariTanggal	= $_POST['txtDariTanggal'];
		$sampaiTanggal	= $_POST['txtSampaiTanggal'];	
		$pilihan	= $_POST['txtPilihan'];	

		if(empty($harian) && (empty($bulan) || empty($tahun)) && (empty($dariTanggal) || empty($sampaiTanggal))){
			$_SESSION['pesanKesalahan'] = "<div class='alert alert-danger alert-dismissable'>
			<button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
			<strong><i class='fa fa-times'></i>&nbsp; Data Tidak Boleh Kosong</strong>
			</div>";
			
			$pagename 		= $_GET['page'];
			echo "<script>window.location='".$pagename."?content=koleksipustaka_peminjamanperjenis'</script>";	
		}else{
			if ($_POST['btnSave']=="cetak"){
				$tampil	= $_POST['tampil'];
				include "cetak/koleksipustaka_peminjamanperjenis.php";

			}else if ($_POST['btnSave']=="tampilPerKlasifikasi" || $_POST['btnSave']=="tampilPerJenis"){
				$tampil			= $_POST['btnSave'];
				$pagename 		= $_GET['page'];

				$_SESSION['dataHarian'] = $harian;
				$_SESSION['dataBulan'] = $bulan;
				$_SESSION['dataTahun'] = $tahun;
				$_SESSION['dataDariTanggal'] = $dariTanggal;
				$_SESSION['dataSampaiTanggal'] = $sampaiTanggal;
				$_SESSION['tampil'] = $tampil;
				$_SESSION['dataPilihan'] = $pilihan;

				echo "<script>window.location='".$pagename."?content=koleksipustaka_peminjamanperjenis&btnsave=tampil'</script>";	 	
				
			}
		}
}

else if ($_GET['lap']=="koleksipustaka_daftarpustakaterpinjam") {
		
	if ($_POST['btnSave']=="cetak"){
		$tampil	= $_POST['tampil'];
		include "cetak/koleksipustaka_daftarpustakaterpinjam.php";

	}else if ($_POST['btnSave']=="tampil"){
		$tampil			= $_POST['btnSave'];
		$pagename 		= $_GET['page'];
		$_SESSION['tampil'] = $tampil;

		echo "<script>window.location='".$pagename."?content=koleksipustaka_daftarpustakaterpinjam&btnsave=tampil'</script>";	 	
		
	}
		
}

// BUKU TEKS
if ($_GET['lap']=="bukuteks_peminjamanindividu"){
	$harian			= $_POST['txtHarian'];
	$bulan			= $_POST['txtBulan'];
	$tahun			= $_POST['txtTahun'];
	$dariTanggal	= $_POST['txtDariTanggal'];
	$sampaiTanggal	= $_POST['txtSampaiTanggal'];	
	$anggota			= $_POST['txtAnggota'];
	// $buku			= $_POST['txtBuku'];
	$jnskelamin			= $_POST['txtJnskelamin'];
	$cakupan			= $_POST['txtCakupan'];
	$kelas			= $_POST['txtKelas'];
	$pilihan			= $_POST['txtPilihan'];
	

	if(empty($harian) && (empty($bulan) || empty($tahun)) && (empty($dariTanggal) || empty($sampaiTanggal)) || empty($anggota) || empty($jnskelamin) || empty($cakupan)){
		$_SESSION['pesanKesalahan'] = "<div class='alert alert-danger alert-dismissable'>
		<button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
		<strong><i class='fa fa-times'></i>&nbsp; Data Tidak Boleh Kosong</strong>
		</div>";
		
		$pagename 		= $_GET['page'];
		echo "<script>window.location='".$pagename."?content=bukuteks_peminjamanindividu'</script>";	
	}else{
		if ($_POST['btnSave']=="cetak"){
			include "cetak/bukuteks_peminjamanindividu.php";

		}else if ($_POST['btnSave']=="tampil"){
			$tampil			= $_POST['btnSave'];
			$pagename 		= $_GET['page'];

			$_SESSION['dataHarian'] = $harian;
			$_SESSION['dataBulan'] = $bulan;
			$_SESSION['dataTahun'] = $tahun;
			$_SESSION['dataDariTanggal'] = $dariTanggal;
			$_SESSION['dataSampaiTanggal'] = $sampaiTanggal;
			$_SESSION['dataAnggota'] = $anggota;
			// $_SESSION['dataBuku'] = $buku;
			$_SESSION['dataJnskelamin'] = $jnskelamin;			
			$_SESSION['dataCakupan'] = $cakupan;
			$_SESSION['dataKelas'] = $kelas;
			$_SESSION['tampil'] = $tampil;
			$_SESSION['dataPilihan'] = $pilihan;
			
			echo "<script>window.location='".$pagename."?content=bukuteks_peminjamanindividu&btnsave=tampil'</script>";	 	
			
		}
	}
}

if ($_GET['lap']=="bukuteks_pengembalianindividu"){
	$harian			= $_POST['txtHarian'];
	$bulan			= $_POST['txtBulan'];
	$tahun			= $_POST['txtTahun'];
	$dariTanggal	= $_POST['txtDariTanggal'];
	$sampaiTanggal	= $_POST['txtSampaiTanggal'];	
	$anggota			= $_POST['txtAnggota'];
	// $buku			= $_POST['txtBuku'];
	$pilihan			= $_POST['txtPilihan'];
	

	if(empty($harian) && (empty($bulan) || empty($tahun)) && (empty($dariTanggal) || empty($sampaiTanggal)) || empty($anggota)){
		$_SESSION['pesanKesalahan'] = "<div class='alert alert-danger alert-dismissable'>
		<button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
		<strong><i class='fa fa-times'></i>&nbsp; Data Tidak Boleh Kosong</strong>
		</div>";
		
		$pagename 		= $_GET['page'];
		echo "<script>window.location='".$pagename."?content=bukuteks_pengembalianindividu'</script>";	
	}else{
		if ($_POST['btnSave']=="cetak"){
			include "cetak/bukuteks_pengembalianindividu.php";

		}else if ($_POST['btnSave']=="tampil"){
			$tampil			= $_POST['btnSave'];
			$pagename 		= $_GET['page'];

			$_SESSION['dataHarian'] = $harian;
			$_SESSION['dataBulan'] = $bulan;
			$_SESSION['dataTahun'] = $tahun;
			$_SESSION['dataDariTanggal'] = $dariTanggal;
			$_SESSION['dataSampaiTanggal'] = $sampaiTanggal;
			$_SESSION['dataAnggota'] = $anggota;
			// $_SESSION['dataBuku'] = $buku;
			$_SESSION['tampil'] = $tampil;
			$_SESSION['dataPilihan'] = $pilihan;
			
			echo "<script>window.location='".$pagename."?content=bukuteks_pengembalianindividu&btnsave=tampil'</script>";	 	
			
		}
	}
}

if ($_GET['lap']=="bukuteks_peminjamanperanggota"){
	$harian			= $_POST['txtHarian'];
	$bulan			= $_POST['txtBulan'];
	$tahun			= $_POST['txtTahun'];
	$dariTanggal	= $_POST['txtDariTanggal'];
	$sampaiTanggal	= $_POST['txtSampaiTanggal'];	
	$anggota			= $_POST['txtAnggota'];
	// $buku			= $_POST['txtBuku'];
	$pilihan			= $_POST['txtPilihan'];
	

	if(empty($harian) && (empty($bulan) || empty($tahun)) && (empty($dariTanggal) || empty($sampaiTanggal)) || empty($anggota)){
		$_SESSION['pesanKesalahan'] = "<div class='alert alert-danger alert-dismissable'>
		<button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
		<strong><i class='fa fa-times'></i>&nbsp; Data Tidak Boleh Kosong</strong>
		</div>";
		
		$pagename 		= $_GET['page'];
		echo "<script>window.location='".$pagename."?content=bukuteks_peminjamanperanggota'</script>";	
	}else{
		if ($_POST['btnSave']=="cetak"){
			include "cetak/bukuteks_peminjamanperanggota.php";

		}else if ($_POST['btnSave']=="tampil"){
			$tampil			= $_POST['btnSave'];
			$pagename 		= $_GET['page'];

			$_SESSION['dataHarian'] = $harian;
			$_SESSION['dataBulan'] = $bulan;
			$_SESSION['dataTahun'] = $tahun;
			$_SESSION['dataDariTanggal'] = $dariTanggal;
			$_SESSION['dataSampaiTanggal'] = $sampaiTanggal;
			$_SESSION['dataAnggota'] = $anggota;
			// $_SESSION['dataBuku'] = $buku;
			$_SESSION['dataPilihan'] = $pilihan;
			$_SESSION['tampil'] = $tampil;
			
			echo "<script>window.location='".$pagename."?content=bukuteks_peminjamanperanggota&btnsave=tampil'</script>";	 	
			
		}
	}
}

else if ($_GET['lap']=="bukuteks_peminjamanperjenis") {
	$harian			= $_POST['txtHarian'];
		$bulan			= $_POST['txtBulan'];
		$tahun			= $_POST['txtTahun'];
		$dariTanggal	= $_POST['txtDariTanggal'];
		$sampaiTanggal	= $_POST['txtSampaiTanggal'];	
		$pilihan	= $_POST['txtPilihan'];	

		if(empty($harian) && (empty($bulan) || empty($tahun)) && (empty($dariTanggal) || empty($sampaiTanggal))){
			$_SESSION['pesanKesalahan'] = "<div class='alert alert-danger alert-dismissable'>
			<button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
			<strong><i class='fa fa-times'></i>&nbsp; Data Tidak Boleh Kosong</strong>
			</div>";
			
			$pagename 		= $_GET['page'];
			echo "<script>window.location='".$pagename."?content=bukuteks_peminjamanperjenis'</script>";	
		}else{
			if ($_POST['btnSave']=="cetak"){
				$tampil	= $_POST['tampil'];
				include "cetak/bukuteks_peminjamanperjenis.php";

			}else if ($_POST['btnSave']=="tampilPerKlasifikasi" || $_POST['btnSave']=="tampilPerJenis"){
				$tampil			= $_POST['btnSave'];
				$pagename 		= $_GET['page'];

				$_SESSION['dataHarian'] = $harian;
				$_SESSION['dataBulan'] = $bulan;
				$_SESSION['dataTahun'] = $tahun;
				$_SESSION['dataDariTanggal'] = $dariTanggal;
				$_SESSION['dataSampaiTanggal'] = $sampaiTanggal;
				$_SESSION['dataPilihan'] = $pilihan;
				$_SESSION['tampil'] = $tampil;

				echo "<script>window.location='".$pagename."?content=bukuteks_peminjamanperjenis&btnsave=tampil'</script>";	 	
				
			}
		}
}

if ($_GET['lap']=="bukuteks_peminjamankolektif"){
	$harian			= $_POST['txtHarian'];
	$bulan			= $_POST['txtBulan'];
	$tahun			= $_POST['txtTahun'];
	$dariTanggal	= $_POST['txtDariTanggal'];
	$sampaiTanggal	= $_POST['txtSampaiTanggal'];	
	$pilihan	= $_POST['txtPilihan'];	
	

	if(empty($harian) && (empty($bulan) || empty($tahun)) && (empty($dariTanggal) || empty($sampaiTanggal))){
		$_SESSION['pesanKesalahan'] = "<div class='alert alert-danger alert-dismissable'>
		<button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
		<strong><i class='fa fa-times'></i>&nbsp; Data Tidak Boleh Kosong</strong>
		</div>";
		
		$pagename 		= $_GET['page'];
		echo "<script>window.location='".$pagename."?content=bukuteks_peminjamankolektif'</script>";	
	}else{
		if ($_POST['btnSave']=="cetak"){
			include "cetak/bukuteks_peminjamankolektif.php";

		}else if ($_POST['btnSave']=="tampil"){
			$tampil			= $_POST['btnSave'];
			$pagename 		= $_GET['page'];

			$_SESSION['dataHarian'] = $harian;
			$_SESSION['dataBulan'] = $bulan;
			$_SESSION['dataTahun'] = $tahun;
			$_SESSION['dataDariTanggal'] = $dariTanggal;
			$_SESSION['dataSampaiTanggal'] = $sampaiTanggal;
			$_SESSION['dataPilihan'] = $pilihan;
			$_SESSION['tampil'] = $tampil;
			
			echo "<script>window.location='".$pagename."?content=bukuteks_peminjamankolektif&btnsave=tampil'</script>";	 	
			
		}
	}
}

else if ($_GET['lap']=="bukuteks_daftarpustakaterpinjam") {
		
	if ($_POST['btnSave']=="cetak"){
		$tampil	= $_POST['tampil'];
		include "cetak/bukuteks_daftarpustakaterpinjam.php";

	}else if ($_POST['btnSave']=="tampil"){
		$tampil			= $_POST['btnSave'];
		$pagename 		= $_GET['page'];
		$_SESSION['tampil'] = $tampil;

		echo "<script>window.location='".$pagename."?content=bukuteks_daftarpustakaterpinjam&btnsave=tampil'</script>";	 	
		
	}
		
}

// LAPORAN UMUM
else if ($_GET['lap']=="rekapdenda") {
		$bulan			= $_POST['txtBulan'];
		$tahun			= $_POST['txtTahun'];
		$dariTanggal	= $_POST['txtDariTanggal'];
		$sampaiTanggal	= $_POST['txtSampaiTanggal'];	
		$pilihan	= $_POST['txtPilihan'];	

		if(empty($bulan) && empty($tahun) && (empty($dariTanggal) || empty($sampaiTanggal))){
			$_SESSION['pesanKesalahan'] = "<div class='alert alert-danger alert-dismissable'>
			<button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
			<strong><i class='fa fa-times'></i>&nbsp; Data Tidak Boleh Kosong</strong>
			</div>";
			
			$pagename 		= $_GET['page'];
			echo "<script>window.location='".$pagename."?content=rekapdenda'</script>";	
		}else{
			if ($_POST['btnSave']=="cetak"){
				$tampil	= $_POST['tampil'];
				include "cetak/rekapdenda.php";

			}else if ($_POST['btnSave']=="tampil"){
				$tampil			= $_POST['btnSave'];
				$pagename 		= $_GET['page'];

				$_SESSION['dataBulan'] = $bulan;
				$_SESSION['dataTahun'] = $tahun;
				$_SESSION['dataDariTanggal'] = $dariTanggal;
				$_SESSION['dataSampaiTanggal'] = $sampaiTanggal;
				$_SESSION['dataPilihan'] = $pilihan;
				$_SESSION['tampil'] = $tampil;

				echo "<script>window.location='".$pagename."?content=rekapdenda&btnsave=tampil'</script>";	 	
				
			}
		}
}


if ($_GET['lap']=="kartubebastanggungan"){	
	$anggota			= $_POST['txtAnggota'];
	$idAnggota			= $_POST['txtIdAnggota'];
	

	if(empty($anggota) || empty($idAnggota)){
		$_SESSION['pesanKesalahan'] = "<div class='alert alert-danger alert-dismissable'>
		<button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
		<strong><i class='fa fa-times'></i>&nbsp; Data Tidak Boleh Kosong</strong>
		</div>";
		
		$pagename 		= $_GET['page'];
		echo "<script>window.location='".$pagename."?content=kartubebastanggungan'</script>";	
	}else{
		if ($_POST['btnSave']=="cetak"){
			include "cetak/kartubebastanggungan.php";

		}else if ($_POST['btnSave']=="tampil"){
			$tampil			= $_POST['btnSave'];
			$pagename 		= $_GET['page'];

			$_SESSION['dataAnggota'] = $anggota;
			$_SESSION['dataIdAnggota'] = $idAnggota;
			$_SESSION['tampil'] = $tampil;
			
			echo "<script>window.location='".$pagename."?content=kartubebastanggungan&btnsave=tampil'</script>";	 	
			
		}
	}
}

else if ($_GET['lap']=="koleksibukulengkap") {
		
	$subyek					= $_POST['txtSubyek'];
	$desSubyek				= (@$_POST['txtDesSubyek']) ? $_POST['txtDesSubyek'] : "";
	$jmlGolongan			= (@$_POST['txtJmlGolongan']) ? $_POST['txtJmlGolongan'] : "";
	$jmlTotal				= (@$_POST['txtJmlTotal']) ? $_POST['txtJmlTotal'] : "";

		if($subyek===""){
			$_SESSION['pesanKesalahan'] = "<div class='alert alert-danger alert-dismissable'>
			<button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
			<strong><i class='fa fa-times'></i>&nbsp; Golongan Kode Buku Belum Dipilih</strong>
			</div>";
			
			$pagename 		= $_GET['page'];
			echo "<script>window.location='".$pagename."?content=koleksibukulengkap'</script>";	
		}else{

	if ($_POST['btnSave']=="cetak"){
		$tampil	= $_POST['btnSave'];
		if($jmlGolongan==0){
			$_SESSION['pesanKesalahan'] = "<div class='alert alert-danger alert-dismissable'>
			<button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
			<strong><i class='fa fa-times'></i>&nbsp; Maaf, Buku Golongan Tersebut Tidak Ada</strong>
			</div>";
			
			$pagename 		= $_GET['page'];
			echo "<script>window.location='".$pagename."?content=koleksibukulengkap'</script>";

		}else{
			include "cetak/koleksibukulengkap.php";

		}

	}else if ($_POST['btnSave']=="tampil"){
		$tampil			= $_POST['btnSave'];
		$pagename 		= $_GET['page'];

		$_SESSION['dataSubyek'] = $subyek;
		$_SESSION['tampil'] = $tampil;

		echo "<script>window.location='".$pagename."?content=koleksibukulengkap&btnsave=tampil'</script>";	 	
		
	}
}
		
}

else if ($_GET['lap']=="koleksibukureferensi") {
		
	$subyek					= $_POST['txtSubyek'];
	$desSubyek				= (@$_POST['txtDesSubyek']) ? $_POST['txtDesSubyek'] : "";
	$jmlGolongan			= (@$_POST['txtJmlGolongan']) ? $_POST['txtJmlGolongan'] : "";
	$jmlTotal				= (@$_POST['txtJmlTotal']) ? $_POST['txtJmlTotal'] : "";

		if($subyek===""){
			$_SESSION['pesanKesalahan'] = "<div class='alert alert-danger alert-dismissable'>
			<button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
			<strong><i class='fa fa-times'></i>&nbsp; Golongan Kode Buku Belum Dipilih</strong>
			</div>";
			
			$pagename 		= $_GET['page'];
			echo "<script>window.location='".$pagename."?content=koleksibukureferensi'</script>";

		}else{

	if ($_POST['btnSave']=="cetak"){
		$tampil	= $_POST['btnSave'];
		if($jmlGolongan==0){
			$_SESSION['pesanKesalahan'] = "<div class='alert alert-danger alert-dismissable'>
			<button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
			<strong><i class='fa fa-times'></i>&nbsp; Maaf, Buku Golongan Tersebut Tidak Ada</strong>
			</div>";
			
			$pagename 		= $_GET['page'];
			echo "<script>window.location='".$pagename."?content=koleksibukureferensi'</script>";

		}else{
			include "cetak/koleksibukureferensi.php";

		}

	}else if ($_POST['btnSave']=="tampil"){
		$tampil			= $_POST['btnSave'];
		$pagename 		= $_GET['page'];

		$_SESSION['dataSubyek'] = $subyek;
		$_SESSION['tampil'] = $tampil;

		echo "<script>window.location='".$pagename."?content=koleksibukureferensi&btnsave=tampil'</script>";	 	
		
	}
}
		
}

else if ($_GET['lap']=="rekapkoleksibuku") {
		
	$rekap					= $_POST['txtRekap'];

		if($rekap===""){
			$_SESSION['pesanKesalahan'] = "<div class='alert alert-danger alert-dismissable'>
			<button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
			<strong><i class='fa fa-times'></i>&nbsp; Rekap Berdasarkan Belum Dipilih</strong>
			</div>";
			
			$pagename 		= $_GET['page'];
			echo "<script>window.location='".$pagename."?content=rekapkoleksibuku'</script>";

		}else{

	if ($_POST['btnSave']=="cetak"){
		$tampil	= $_POST['btnSave'];
			include "cetak/rekapkoleksibuku.php";

	}else if ($_POST['btnSave']=="tampil"){
		$tampil			= $_POST['btnSave'];
		$pagename 		= $_GET['page'];

		$_SESSION['dataRekap'] = $rekap;
		$_SESSION['tampil'] = $tampil;

		echo "<script>window.location='".$pagename."?content=rekapkoleksibuku&btnsave=tampil'</script>";	 	
		
	}
}
		
}

else if ($_GET['lap']=="perkembanganbuku") {
	$pilihan				= $_POST['txtPilihan'];
	$triWulan				= $_POST['txtTriWulan'];
	$tahun1					= $_POST['txtTahun1'];
	$tahun2					= $_POST['txtTahun2'];
	//rumus bulan pertama triwulan
	$batasTriWulan			= 3*$triWulan-2;

		if($pilihan=="" || ($tahun1=="" && $tahun2=="")){
			$_SESSION['pesanKesalahan'] = "<div class='alert alert-danger alert-dismissable'>
			<button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
			<strong><i class='fa fa-times'></i>&nbsp; Data Tidak Boleh Kosong</strong>
			</div>";
			
			$pagename 		= $_GET['page'];
			echo "<script>window.location='".$pagename."?content=perkembanganbuku'</script>";

		}else if($pilihan=="triwulanan" AND (date("m") < $batasTriWulan OR date("Y") < $tahun1)){
			$_SESSION['pesanKesalahan'] = "<div class='alert alert-danger alert-dismissable'>
			<button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
			<strong><i class='fa fa-times'></i>&nbsp; Tanggal hari ini belum memasuki masa laporan yang dikehendaki. <br> ".IndonesiaTgl(date("Y-m-d"))." < 01-".sprintf("%02d", $batasTriWulan)."-$tahun1 </strong>
			</div>";
			
			$pagename 		= $_GET['page'];
			echo "<script>window.location='".$pagename."?content=perkembanganbuku'</script>";
		}else if($pilihan=="tahunan" AND date("Y") < $tahun2){
			$_SESSION['pesanKesalahan'] = "<div class='alert alert-danger alert-dismissable'>
			<button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
			<strong><i class='fa fa-times'></i>&nbsp; Tanggal hari ini belum memasuki masa laporan yang dikehendaki. <br> ".IndonesiaTgl(date("Y-m-d"))." < 01-01-$tahun2 </strong>
			</div>";
			
			$pagename 		= $_GET['page'];
			echo "<script>window.location='".$pagename."?content=perkembanganbuku'</script>";
		}else{

	if ($_POST['btnSave']=="cetak"){
		$tampil	= $_POST['btnSave'];
			include "cetak/perkembanganbuku.php";

	}else if ($_POST['btnSave']=="tampil"){
		$tampil			= $_POST['btnSave'];
		$pagename 		= $_GET['page'];

		$_SESSION['dataPilihan'] = $pilihan;
		$_SESSION['dataTriWulan'] = $triWulan;
		$_SESSION['dataTahun1'] = $tahun1;
		$_SESSION['dataTahun2'] = $tahun2;
		$_SESSION['tampil'] = $tampil;

		echo "<script>window.location='".$pagename."?content=perkembanganbuku&btnsave=tampil'</script>";	 	
		
	}
}
		
}

else if ($_GET['lap']=="daftaranggota") {
	$idjnsang = $_POST['txtIdjnsang'];
	$kelas = $_POST['txtKelas'];
	$jmlGolongan			= (@$_POST['txtJmlGolongan']) ? $_POST['txtJmlGolongan'] : "";
	$jmlperkelas			= (@$_POST['txtJmlPerKelas']) ? $_POST['txtJmlPerKelas'] : "";
	$jmlTotal				= (@$_POST['txtJmlTotal']) ? $_POST['txtJmlTotal'] : "";
    $jnsang = ($idjnsang==1) ? "Siswa" : "Guru/Karyawan";

	if($idjnsang===""){
		$_SESSION['pesanKesalahan'] = "<div class='alert alert-danger alert-dismissable'>
		<button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
		<strong><i class='fa fa-times'></i>&nbsp; Jenis Anggota Belum Dipilih</strong>
		</div>";
		
		$pagename 		= $_GET['page'];
		echo "<script>window.location='".$pagename."?content=daftaranggota'</script>";	
	}else{	
		
	if ($_POST['btnSave']=="cetak"){
		$tampil	= $_POST['btnSave'];
		if ($idjnsang == 1 AND $jmlperkelas == 0) {
			$_SESSION['pesanKesalahan'] = "<div class='alert alert-danger alert-dismissable'>
			<button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
			<strong><i class='fa fa-times'></i>&nbsp; Maaf, Data Anggota Siswa Belum Ada</strong>
			</div>";
			
			$pagename 		= $_GET['page'];
			echo "<script>window.location='".$pagename."?content=daftaranggota'</script>";

		}else if($jmlGolongan==0){
			$_SESSION['pesanKesalahan'] = "<div class='alert alert-danger alert-dismissable'>
			<button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
			<strong><i class='fa fa-times'></i>&nbsp; Maaf, Data Anggota $jnsang Belum Ada</strong>
			</div>";
			
			$pagename 		= $_GET['page'];
			echo "<script>window.location='".$pagename."?content=daftaranggota'</script>";

		}else{
			include "cetak/daftaranggota.php";
		}
	}else if ($_POST['btnSave']=="tampil"){
		$tampil			= $_POST['btnSave'];
		$pagename 		= $_GET['page'];

		$_SESSION['tampil'] = $tampil;
		$_SESSION['dataIdjnsang'] = $idjnsang;
		$_SESSION['dataKelas'] = $kelas;
		$_SESSION['dataJnsang'] = $jnsang;

		echo "<script>window.location='".$pagename."?content=daftaranggota&btnsave=tampil'</script>";	 	
		
	}
}
}

else if ($_GET['lap']=="rekapjumlahanggota") {
		
	if ($_POST['btnSave']=="cetak"){
		$tampil	= $_POST['btnSave'];
			include "cetak/rekapjumlahanggota.php";

	}else if ($_POST['btnSave']=="tampil"){
		$tampil			= $_POST['btnSave'];
		$pagename 		= $_GET['page'];

		$_SESSION['tampil'] = $tampil;

		echo "<script>window.location='".$pagename."?content=rekapjumlahanggota&btnsave=tampil'</script>";	 	
		
	}
		
}

} 


?>
