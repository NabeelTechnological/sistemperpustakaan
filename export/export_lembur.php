<?php

error_reporting(E_ALL);

require_once '../plugin/excel/PHPExcel.php';
require_once '../config/inc.connection.php';
require_once '../config/inc.library.php';

if(isset($_POST['btnSave'])){

$dataAwal			= InggrisTgl($_POST['txtAwal']);
$dataAkhir			= InggrisTgl($_POST['txtAkhir']);
$dataCabang			= $_POST['txtCabang'];
$dataBagian			= $_POST['txtBagian'];
$dataNama			= $_POST['txtNama'];
$dataKeterangan		= $_POST['txtKeterangan'];
$dataTanggal		= date('Y-m-d');

$objPHPExcel = new PHPExcel();

$query="SELECT * FROM t_lembur
		INNER JOIN t_pegawai ON t_lembur.nik=t_pegawai.nik 
		INNER JOIN t_bagian ON t_pegawai.id_bagian=t_bagian.id_bagian
		INNER JOIN t_cabang ON t_pegawai.id_cabang=t_cabang.id_cabang
		INNER JOIN t_jabatan ON t_pegawai.id_jabatan=t_jabatan.id_jabatan
		WHERE t_lembur.tanggal_lembur BETWEEN '$dataAwal' AND '$dataAkhir'
		AND t_pegawai.id_cabang LIKE '%$dataCabang%'
		AND t_pegawai.nama_pegawai LIKE '%$dataNama%'
		AND t_pegawai.id_bagian LIKE '%$dataBagian%'
		AND t_lembur.kategori_lembur LIKE '%$dataKeterangan%'";
$hasil = mysqli_query($GLOBALS["___mysqli_ston"], $query);
 
// Set properties
$objPHPExcel->getProperties()->setCreator("Farhan Alawi, ST")
      ->setLastModifiedBy("Seventh Media")
      ->setTitle("Office 2007 XLSX Test Document")
      ->setSubject("Office 2007 XLSX Test Document")
       ->setDescription("Laporan Data Lembur.")
       ->setKeywords("office 2007 openxml php")
       ->setCategory("MIS 2013");
 
// Add some data
$objPHPExcel->setActiveSheetIndex(0)
	   ->setCellValue('A1', 'Dari Tanggal')
	   ->setCellValue('B1', IndonesiaTgl($dataAwal))
	   ->setCellValue('A2', 'Sampai Tanggal')
	   ->setCellValue('B2', IndonesiaTgl($dataAkhir))
       ->setCellValue('A4', 'NO')
       ->setCellValue('B4', 'NIK')
	   ->setCellValue('C4', 'NAMA PEGAWAI')
	   ->setCellValue('D4', 'DIVISI')
	   ->setCellValue('E4', 'BAGIAN')
	   ->setCellValue('F4', 'JABATAN')
	   ->setCellValue('G4', 'TGL LEMBUR')
	   ->setCellValue('H4', 'UPAH LEMBUR')
	   ->setCellValue('I4', 'JENIS LEMBUR');
 
$baris = 5;
$no = 0;			
while($row=mysqli_fetch_array($hasil)){
$no = $no +1;

if($row['kategori_lembur']=='1'){
	$dataJenis="Terusan"; 
	}
elseif($row['kategori_lembur']=='2') {
	$dataJenis="Hari Libur";
	}
elseif($row['kategori_lembur']=='3') {
	$dataJenis="Libur Nasional";
	}

$objPHPExcel->setActiveSheetIndex(0)
     ->setCellValue("A$baris", $no)
     ->setCellValue("B$baris", $row['nik'])
	 ->setCellValue("C$baris", $row['nama_pegawai'])
	 ->setCellValue("D$baris", $row['nama_cabang'])
	 ->setCellValue("E$baris", $row['nama_bagian'])
	 ->setCellValue("F$baris", $row['nama_jabatan'])
	 ->setCellValue("G$baris", $row['tanggal_lembur'])
	 ->setCellValue("H$baris", format_angka($row['upah_lembur']))
	 ->setCellValue("I$baris", $dataJenis);
$baris = $baris + 1;
}
} 
// Rename sheet
$objPHPExcel->getActiveSheet()->setTitle('laporan_lembur');
 
// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);
 
// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="LaporanLembur.xls"');
header('Cache-Control: max-age=0');
 
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;
?>
 