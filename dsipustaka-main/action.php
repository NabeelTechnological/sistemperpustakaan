<?php 
// security goes here

session_start();
//error_reporting(0);

//informasi koneksi ke database
// include "parser-php-version.php"; 
include "config/inc.connection.php";
include "config/inc.library.php";

ini_set("memory_limit", "4096M");
ini_set('max_execution_time', 6000);
set_time_limit(0);


if (isset($_GET['act'])){
	// PENGATURAN
	if ($_GET['act']=="1"){
		//ruser
		include "content/pengaturan/pengguna/penggunaaplikasi_source.php";
	}
	else if($_GET['act']=="2"){
		//rreftrans
		include "content/pengaturan/ket_pinjam/ketentuanpeminjaman_source.php";
	}
	else if($_GET['act']=="3"){
		//rkelas
		include "content/pengaturan/kelas/kelas_source.php";
	}
	else if($_GET['act']=="4"){
		//rpenerbit
		include "content/pengaturan/penerbit/penerbit_source.php";
	}
	else if($_GET['act']=="5"){
		//rklasifikasi
		include "content/pengaturan/klasifikasi/klasifikasi_source.php";
	}
	else if($_GET['act']=="6"){
		//rkota
		include "content/pengaturan/kota/kota_source.php";
	}
	else if($_GET['act']=="7"){
		//rtgllibur
		include "content/pengaturan/tgllibur/tgllibur_source.php";
	}
	else if($_GET['act']=="8"){
		//tbuku - updatejenisbuku
		include "content/pengaturan/jenisbuku/jenisbuku_source.php";
	}
	


	// PUSTAKA
	else if($_GET['act']=="9"){
		//tbuku
		include "content/pustaka/buku/buku_source.php";
	}
	else if($_GET['act']=="9a"){
		//tbuku
		include "content/pustaka/buku/buku_subyek_ajax.php";
	}
	else if($_GET['act']=="9b"){
		//tbuku
		include "content/pustaka/buku/buku_kdbuku_ajax.php";
	}
	else if($_GET['act']=="9c"){
		//tbuku
		include "content/pustaka/buku/buku_idbuku_ajax.php";
	}
	else if($_GET['act']=="9d"){
		//tbuku
		include "content/pustaka/buku/buku_tersedia_ajax.php";
	}
	else if($_GET['act']=="10"){
		//cd
		include "content/pustaka/cd/cd_source.php";
	}
	else if($_GET['act']=="11"){
		//majalah koran
		include "content/pustaka/majalah_koran/majalah_koran_source.php";
	}
	

	// KEANGGOTAAN
	else if($_GET['act']=="12"){
		//tambah mssal
		include "content/keanggotaan/tambah_masal/tambahmasal_source.php";
	}
	else if($_GET['act']=="13"){
		//cari anggota
		include "content/keanggotaan/cari_anggota/anggota_source.php";
	}
	else if($_GET['act']=="14"){
		//cetak kartu
		include "content/keanggotaan/cetak_kartu/cetakkartu_source.php";
	}
	
	// SIRKULASI
	else if($_GET['act']=="15"){
		//peminjaman individu
		include "content/sirkulasi/individu/peminjamanindividu_source.php";
	}
	else if($_GET['act']=="16"){
		//pengembalian individu
		include "content/sirkulasi/individu/pengembalianindividu_source.php";
	}
	else if($_GET['act']=="17"){
		//peminjaman kolektif
		include "content/sirkulasi/kolektif/peminjamankolektif_source.php";
	}
	else if($_GET['act']=="18"){
		//pengembalian kolektif
		include "content/sirkulasi/kolektif/pengembaliankolektif_source.php";
	}

	//Pengunjung
	else if($_GET['act']=="19"){
		//pencatatan
		include "content/pengunjung/pencatatan/pencatatan_source.php";
	}
	else if($_GET['act']=="20"){
		//grafiktopten
		include "content/pengunjung/grafiktopten/grafiktopten_source.php";
	}
	else if($_GET['act']=="21"){
		//grafikjumlah pengunjung
		include "content/pengunjung/grafikjumlahpengunjung/grafikjumlahpengunjung_source.php";
	}
	else if($_GET['act']=="22"){
		//pelaporan
		include "content/pengunjung/pelaporan/pelaporan_source.php";
	}

//LAPORAN
	// KOLEKSI PERPUSTAKAAN
	else if($_GET['act']=="23"){
		//peminjaman individu
		include "content/laporan/koleksi_perpustakaan/peminjamanindividu_source.php";
	}
	else if($_GET['act']=="24"){
		//pengembalian individu
		include "content/laporan/koleksi_perpustakaan/pengembalianindividu_source.php";
	}
	else if($_GET['act']=="25"){
		//peminjaman per anggota
		include "content/laporan/koleksi_perpustakaan/peminjamanperanggota_source.php";
	}
	else if($_GET['act']=="26"){
		//peminjaman perjenis
		include "content/laporan/koleksi_perpustakaan/peminjamanperjenis_source.php";
	}
	else if($_GET['act']=="27"){
		//daftar pustaka terpinjam
		include "content/laporan/koleksi_perpustakaan/daftarpustakaterpinjam_source.php";
	}

	//BUKU TEKS
	else if($_GET['act']=="28"){
		//peminjaman individu
		include "content/laporan/buku_teks/peminjamanindividu_source.php";
	}
	else if($_GET['act']=="29"){
		//pengembalian individu
		include "content/laporan/buku_teks/pengembalianindividu_source.php";
	}
	else if($_GET['act']=="30"){
		//peminjaman per anggota
		include "content/laporan/buku_teks/peminjamanperanggota_source.php";
	}
	else if($_GET['act']=="31"){
		//peminjaman perjenis
		include "content/laporan/buku_teks/peminjamanperjenis_source.php";
	}
	else if($_GET['act']=="32"){
		//peminjaman kolektif
		include "content/laporan/buku_teks/peminjamankolektif_source.php";
	}
	else if($_GET['act']=="33"){
		//daftar pustaka terpinjam
		include "content/laporan/buku_teks/daftarpustakaterpinjam_source.php";
	}	

	// KATALOG & BIBLIOGRAFI
	else if($_GET['act']=="34"){
		//cetak katalog
		include "content/laporan/katalog_bibliografi/cetakkatalog_source.php";
	}
	else if($_GET['act']=="34a"){
		//cetak katalog
		include "content/laporan/katalog_bibliografi/cetakbarcode_source.php";
	}
	else if($_GET['act']=="35"){
		//cetak kode buku/cd
		include "content/laporan/katalog_bibliografi/cetakkodebukucd_source.php";
	}
	else if($_GET['act']=="35a"){
		//cetak kode buku/cd
		include "content/laporan/katalog_bibliografi/cetakkodebarcode_source.php";
	}
	else if($_GET['act']=="36"){
		//cetak bibliografi
		include "content/laporan/katalog_bibliografi/cetakbibliografi_source.php";
	}

	// LAPORAN UMUM
	else if($_GET['act']=="37"){
		//rekap denda
		include "content/laporan/laporan_umum/rekapdenda_source.php";
	}
	else if($_GET['act']=="38"){
		//kartu bebas tanggungan
		include "content/laporan/laporan_umum/kartubebastanggungan_source.php";
	}
	else if($_GET['act']=="39"){
		//koleksi buku lengkap
		include "content/laporan/laporan_umum/koleksibukulengkap_source.php";
	}
	else if($_GET['act']=="40"){
		//koleksi buku referensi
		include "content/laporan/laporan_umum/koleksibukureferensi_source.php";
	}
	else if($_GET['act']=="41"){
		//koleksi cd lengkap
		include "content/laporan/laporan_umum/koleksicdlengkap_source.php";
	}
	else if($_GET['act']=="42"){
		//rekap koleksi buku
		include "content/laporan/laporan_umum/rekapkoleksibuku_source.php";
	}
	else if($_GET['act']=="43"){
		//perkembangan buku
		include "content/laporan/laporan_umum/perkembanganbuku_source.php";
	}
	else if($_GET['act']=="44"){
		//daftar anggota
		include "content/laporan/laporan_umum/daftaranggota_source.php";
	}
	else if($_GET['act']=="45"){
		//rekap jumlah anggota
		include "content/laporan/laporan_umum/rekapjumlahanggota_source.php";
	}

}
?>