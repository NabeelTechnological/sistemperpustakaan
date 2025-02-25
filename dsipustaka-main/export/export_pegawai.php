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
$dataAgama			= $_POST['txtAgama'];
$dataKelamin		= $_POST['txtKelamin'];
$dataTanggal		= date('Y-m-d');

$objPHPExcel = new PHPExcel();

$query="SELECT * FROM t_pegawai 
		INNER JOIN t_bagian ON t_pegawai.id_bagian=t_bagian.id_bagian
		INNER JOIN t_cabang ON t_pegawai.id_cabang=t_cabang.id_cabang
		INNER JOIN t_jabatan ON t_pegawai.id_jabatan=t_jabatan.id_jabatan
		WHERE tanggal_masuk BETWEEN '$dataAwal' AND '$dataAkhir'
		AND t_pegawai.id_cabang LIKE '%$dataCabang%'
		AND t_pegawai.agama LIKE '%$dataAgama%'
		AND t_pegawai.id_bagian LIKE '%$dataBagian%'
		AND t_pegawai.jenis_kelamin LIKE '%$dataKelamin%'";
$hasil = mysqli_query($GLOBALS["___mysqli_ston"], $query);
 
// Set properties
$objPHPExcel->getProperties()->setCreator("Farhan Alawi, ST")
      ->setLastModifiedBy("Management Information System")
      ->setTitle("Office 2007 XLSX Test Document")
      ->setSubject("Office 2007 XLSX Test Document")
       ->setDescription("Laporan Data Pegawai.")
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
	   ->setCellValue('C4', 'NPWP')
	   ->setCellValue('D4', 'NAMA PEGAWAI')
	   ->setCellValue('E4', 'TGL LAHIR')
	   ->setCellValue('F4', 'ALAMAT')
	   ->setCellValue('G4', 'NO TELP')
	   ->setCellValue('H4', 'JENIS KELAMIN')
	   ->setCellValue('I4', 'PENDIDIKAN')
	   ->setCellValue('J4', 'PERKAWINAN')
	   ->setCellValue('K4', 'AGAMA')
	   ->setCellValue('L4', 'DIVISI')
	   ->setCellValue('M4', 'BAGIAN')
	   ->setCellValue('N4', 'JABATAN')
	   ->setCellValue('O4', 'TGL MASUK')
	   ->setCellValue('P4', 'TGL BERAKHIR')
	   ->setCellValue('Q4', 'STATUS PEGAWAI')
	   ->setCellValue('R4', 'STATUS KERJA')
	   ->setCellValue('S4', 'NAMA BANK')
	   ->setCellValue('T4', 'NO. REKENING');
 
$baris = 5;
$no = 0;			
while($row=mysqli_fetch_array($hasil)){
$no = $no +1;

if($row['tanggal_berakhir']<=$dataTanggal && $row['status_pegawai']=='Kontrak'){
	$txtStatus = "Habis Kontrak"; 
	}
else {
	$txtStatus = "Aktif";
	}
$objPHPExcel->setActiveSheetIndex(0)
     ->setCellValue("A$baris", $no)
     ->setCellValue("B$baris", $row['nik'])
	 ->setCellValue("C$baris", $row['no_npwp'])
	 ->setCellValue("D$baris", $row['nama_pegawai'])
	 ->setCellValue("E$baris", $row['tanggal_lahir'])
	 ->setCellValue("F$baris", $row['alamat_pegawai'])
	 ->setCellValue("G$baris", $row['telp_pegawai'])
	 ->setCellValue("H$baris", $row['jenis_kelamin'])
	 ->setCellValue("I$baris", $row['pendidikan'])
	 ->setCellValue("J$baris", $row['status_perkawinan'])
	 ->setCellValue("K$baris", $row['agama'])
	 ->setCellValue("L$baris", $row['nama_cabang'])
	 ->setCellValue("M$baris", $row['nama_bagian'])
	 ->setCellValue("N$baris", $row['nama_jabatan'])
	 ->setCellValue("O$baris", IndonesiaTgl($row['tanggal_masuk']))
	 ->setCellValue("P$baris", IndonesiaTgl($row['tanggal_berakhir']))
	 ->setCellValue("Q$baris", $row['status_pegawai'])
	 ->setCellValue("R$baris", $txtStatus)
	 ->setCellValue("S$baris", $row['nama_bank'])
	 ->setCellValue("T$baris", $row['no_rekening']);
$baris = $baris + 1;
}
} 
// Rename sheet
$objPHPExcel->getActiveSheet()->setTitle('laporan_pegawai');
 
// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);
 
// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="LaporanPegawai.xls"');
header('Cache-Control: max-age=0');
 
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;
?>
 