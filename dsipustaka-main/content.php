<?php
 
$pg=$_GET['content'];
if($pg=="home"){ include "content/home_administrator.php"; } 
// DATABASE
	//Tabel
		// elseif($pg=="tabel"){ include "content/database/tabel_vw.php"; }

//KONFIGURASI
		elseif($pg=="konfigurasiphoto"){ include "content/konfigurasi/photo/konfigurasiphoto_ubah.php"; }
		elseif($pg=="konfigurasipassword"){ include "content/konfigurasi/password/konfigurasipassword_ubah.php"; }
		elseif($pg=="konfigurasiprofil"){ include "content/konfigurasi/profil/konfigurasiprofil_ubah.php"; }

// PENGATURAN
	// Pengguna
		elseif($pg=="pengguna_aplikasi"){ include "content/pengaturan/pengguna/penggunaaplikasi_vw.php"; }
		elseif($pg=="tambahpengguna"){ include "content/pengaturan/pengguna/penggunaaplikasi_tambah.php"; }  
		elseif($pg=="ubahpengguna"){ include "content/pengaturan/pengguna/penggunaaplikasi_ubah.php"; }
		elseif($pg=="hakaksespengguna"){ include "content/pengaturan/pengguna/penggunaaplikasi_hakakses.php"; } 
		elseif($pg=="hakaksespenggunabc"){ include "content/pengaturan/pengguna/penggunaaplikasi_hakakses_bc.php"; } 

	//Ketentuan Peminjaman
		elseif($pg=="ketentuan_peminjaman"){ include "content/pengaturan/ket_pinjam/ketentuanpeminjaman_vw.php"; }
		elseif($pg=="tambahketpinjam"){ include "content/pengaturan/ket_pinjam/ketentuanpeminjaman_tambah.php"; }  
		elseif($pg=="ubahketpinjam"){ include "content/pengaturan/ket_pinjam/ketentuanpeminjaman_ubah.php"; }

	//Kelas
		elseif($pg=="kelas"){ include "content/pengaturan/kelas/kelas_vw.php"; }
		elseif($pg=="tambahkelas"){ include "content/pengaturan/kelas/kelas_tambah.php"; }  
		elseif($pg=="ubahkelas"){ include "content/pengaturan/kelas/kelas_ubah.php"; }

	//Penerbit
		elseif($pg=="penerbit"){ include "content/pengaturan/penerbit/penerbit_vw.php"; }
		elseif($pg=="tambahpenerbit"){ include "content/pengaturan/penerbit/penerbit_tambah.php"; }  
		elseif($pg=="ubahpenerbit"){ include "content/pengaturan/penerbit/penerbit_ubah.php"; }

	//Klasifikasi
		elseif($pg=="kode_klasifikasi"){ include "content/pengaturan/klasifikasi/klasifikasi_vw.php"; }
		elseif($pg=="tambahklasifikasi"){ include "content/pengaturan/klasifikasi/klasifikasi_tambah.php"; }  
		elseif($pg=="ubahklasifikasi"){ include "content/pengaturan/klasifikasi/klasifikasi_ubah.php"; }

	//Kota
		elseif($pg=="kota"){ include "content/pengaturan/kota/kota_vw.php"; }
		elseif($pg=="tambahkota"){ include "content/pengaturan/kota/kota_tambah.php"; }  
		elseif($pg=="ubahkota"){ include "content/pengaturan/kota/kota_ubah.php"; }

	//Tgl Libur
		elseif($pg=="tanggal_libur"){ include "content/pengaturan/tgllibur/tgllibur_vw.php"; }
		elseif($pg=="tambahtgllibur"){ include "content/pengaturan/tgllibur/tgllibur_tambah.php"; }  
		elseif($pg=="ubahtgllibur"){ include "content/pengaturan/tgllibur/tgllibur_ubah.php"; }

	//Update Jenis Buku
		elseif($pg=="update_jenis_buku"){ include "content/pengaturan/jenisbuku/jenisbuku_vw.php"; }
		elseif($pg=="tambahjenisbuku"){ include "content/pengaturan/jenisbuku/jenisbuku_tambah.php"; }  
		elseif($pg=="ubahjenisbuku"){ include "content/pengaturan/jenisbuku/jenisbuku_ubah.php"; }

//PUSTAKA
	//Buku
		elseif($pg=="buku"){ include "content/pustaka/buku/buku_vw.php"; }
		elseif($pg=="tambahubahbuku"){ include "content/pustaka/buku/buku_tambah_ubah.php"; }  
		elseif($pg=="hapusbuku"){ include "content/pustaka/buku/buku_hapus.php"; }
		elseif($pg=="bukurusak"){ include "content/pustaka/buku/buku_rusakhilang_ubah.php"; }


//KEANGGOTAAN
	//Tambah Masal
		elseif($pg=="tambahindividu"){ include "content/keanggotaan/tambah_individu/tambahindividu_vw.php"; }
		elseif($pg=="tambahmasal"){ include "content/keanggotaan/tambah_masal/tambahmasal_vw.php"; }

	//Cetak Kartu
		elseif($pg=="cetakkartu"){ include "content/keanggotaan/cetak_kartu/cetakkartu_vw.php"; }
		elseif($pg=="cetakkartudepan"){ include "content/keanggotaan/cetak_kartu/cetakkartudepan.php"; }
		elseif($pg=="cetakkartubelakang"){ include "content/keanggotaan/cetak_kartu/cetakkartubelakang.php"; }
		elseif($pg=="carianggota"){ include "content/keanggotaan/cari_anggota/anggota_vw.php"; }
		elseif($pg=="ubahanggota"){ include "content/keanggotaan/cari_anggota/anggota_ubah.php"; }

// SIRKULASI
	//Individu
		elseif($pg=="peminjamanindividu"){ include "content/sirkulasi/individu/peminjamanindividu_vw.php"; }
		elseif($pg=="pengembalianindividu"){ include "content/sirkulasi/individu/pengembalianindividu_vw.php"; }

	//Kolektif
		elseif($pg=="peminjamankolektif"){ include "content/sirkulasi/kolektif/peminjamankolektif_vw.php"; }
		elseif($pg=="pengembaliankolektif"){ include "content/sirkulasi/kolektif/pengembaliankolektif_vw.php"; }

//PENGUNJUNG
	//Pelaporan
		elseif($pg=="pencatatan"){ include "content/pengunjung/pencatatan/pencatatan_vw.php"; }
		elseif($pg=="grafiktopten"){ include "content/pengunjung/grafik_top_ten/grafiktopten_vw.php"; }
		elseif($pg=="grafikjumlahpengunjung"){ include "content/pengunjung/grafik_jumlah_pengunjung/grafikjumlahpengunjung_vw.php"; }
		elseif($pg=="pelaporan"){ include "content/pengunjung/pelaporan/pelaporan_vw.php"; }

//LAPORAN
	//Koleksi Perpustakaan
		elseif($pg=="koleksipustaka_peminjamanindividu"){ include "content/laporan/koleksi_perpustakaan/peminjamanindividu_vw.php"; }
		elseif($pg=="koleksipustaka_pengembalianindividu"){ include "content/laporan/koleksi_perpustakaan/pengembalianindividu_vw.php"; }
		elseif($pg=="koleksipustaka_peminjamanperanggota"){ include "content/laporan/koleksi_perpustakaan/peminjamanperanggota_vw.php"; }
		elseif($pg=="koleksipustaka_peminjamanperjenis"){ include "content/laporan/koleksi_perpustakaan/peminjamanperjenis_vw.php"; }
		elseif($pg=="koleksipustaka_daftarpustakaterpinjam"){ include "content/laporan/koleksi_perpustakaan/daftarpustakaterpinjam_vw.php"; }

	//Buku Teks
		elseif($pg=="bukuteks_peminjamanindividu"){ include "content/laporan/buku_teks/peminjamanindividu_vw.php"; }
		elseif($pg=="bukuteks_pengembalianindividu"){ include "content/laporan/buku_teks/pengembalianindividu_vw.php"; }
		elseif($pg=="bukuteks_peminjamanperanggota"){ include "content/laporan/buku_teks/peminjamanperanggota_vw.php"; }
		elseif($pg=="bukuteks_peminjamanperjenis"){ include "content/laporan/buku_teks/peminjamanperjenis_vw.php"; }
		elseif($pg=="bukuteks_peminjamankolektif"){ include "content/laporan/buku_teks/peminjamankolektif_vw.php"; }
		elseif($pg=="bukuteks_daftarpustakaterpinjam"){ include "content/laporan/buku_teks/daftarpustakaterpinjam_vw.php"; }

	//Katalog & Bibliografi
		elseif($pg=="cetakkatalog"){ include "content/laporan/katalog_bibliografi/cetakkatalog_vw.php"; }
		elseif($pg=="printkatalog"){ include "content/laporan/katalog_bibliografi/cetakkatalog.php"; }
		elseif($pg=="printbarcode"){ include "content/laporan/katalog_bibliografi/cetakbarcode.php"; }
		elseif($pg=="cetakkodebukucd"){ include "content/laporan/katalog_bibliografi/cetakkodebukucd_vw.php"; }
		elseif($pg=="printpunggung"){ include "content/laporan/katalog_bibliografi/cetakkodebuku.php"; }
		elseif($pg=="printpunggungbarcode"){ include "content/laporan/katalog_bibliografi/cetakkodebarcode.php"; }
		elseif($pg=="cetakbibliografi"){ include "content/laporan/katalog_bibliografi/cetakbibliografi_vw.php"; }
		elseif($pg=="printbibliografi"){ include "content/laporan/katalog_bibliografi/cetakbibliografi.php"; }

	//Laporan Umum
		elseif($pg=="rekapdenda"){ include "content/laporan/laporan_umum/rekapdenda_vw.php"; }
		elseif($pg=="kartubebastanggungan"){ include "content/laporan/laporan_umum/kartubebastanggungan_vw.php"; }
		elseif($pg=="koleksibukulengkap"){ include "content/laporan/laporan_umum/koleksibukulengkap_vw.php"; }
		elseif($pg=="koleksibukureferensi"){ include "content/laporan/laporan_umum/koleksibukureferensi_vw.php"; }
		elseif($pg=="koleksicdlengkap"){ include "content/laporan/laporan_umum/koleksicdlengkap_vw.php"; }
		elseif($pg=="rekapkoleksibuku"){ include "content/laporan/laporan_umum/rekapkoleksibuku_vw.php"; }
		elseif($pg=="perkembanganbuku"){ include "content/laporan/laporan_umum/perkembanganbuku_vw.php"; }
		elseif($pg=="daftaranggota"){ include "content/laporan/laporan_umum/daftaranggota_vw.php"; }
		elseif($pg=="rekapjumlahanggota"){ include "content/laporan/laporan_umum/rekapjumlahanggota_vw.php"; }
		
	else {
	echo "	<div class='alert alert-danger alert-dismissable'>
				<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
				Anda tidak berhak mengakses data / modul belum lengkap
			</div>";
	}
 
 
?>
		
		