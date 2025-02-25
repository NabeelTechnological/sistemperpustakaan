<?php

error_reporting(E_ALL);

require_once '../plugin/excel/PHPExcel.php';
require_once '../config/inc.connection.php';
require_once '../config/inc.library.php';

if(isset($_POST['btnSave'])){

$dataBulan			= $_POST['txtBulan'];
$dataTahun			= $_POST['txtTahun'];
$dataCabang			= $_POST['txtCabang'];
$dataBagian			= $_POST['txtBagian'];
$dataNama			= $_POST['txtNama'];
$dataTanggal		= date('Y-m-d');

$objPHPExcel = new PHPExcel();

$query="SELECT * FROM t_pembayaran_gaji
		INNER JOIN t_pegawai ON t_pembayaran_gaji.nik=t_pegawai.nik 
		INNER JOIN t_perpajakan ON t_pegawai.id_perpajakan=t_perpajakan.id_perpajakan
		INNER JOIN t_bagian ON t_pegawai.id_bagian=t_bagian.id_bagian
		INNER JOIN t_cabang ON t_pegawai.id_cabang=t_cabang.id_cabang
		INNER JOIN t_jabatan ON t_pegawai.id_jabatan=t_jabatan.id_jabatan
		WHERE t_pembayaran_gaji.priode_gajian LIKE '%$dataBulan-$dataTahun%'
		AND t_pegawai.id_cabang LIKE '%$dataCabang%'
		AND t_pegawai.nama_pegawai LIKE '%$dataNama%'
		AND t_pegawai.id_bagian LIKE '%$dataBagian%'";
$hasil = mysqli_query($GLOBALS["___mysqli_ston"], $query);
 
// Set properties
$objPHPExcel->getProperties()->setCreator("Farhan Alawi, ST")
      ->setLastModifiedBy("Seventh Media")
      ->setTitle("Office 2007 XLSX Test Document")
      ->setSubject("Office 2007 XLSX Test Document")
       ->setDescription("Laporan Data Pajak.")
       ->setKeywords("office 2007 openxml php")
       ->setCategory("MIS 2013");
 
// Add some data
$objPHPExcel->setActiveSheetIndex(0)
	   ->setCellValue('A1', 'Bulan')
	   ->setCellValue('B1', $dataBulan)
	   ->setCellValue('A2', 'Tahun')
	   ->setCellValue('B2', $dataTahun)
       ->setCellValue('A4', 'NO')
       ->setCellValue('B4', 'NO NPWP')
	   ->setCellValue('C4', 'NAMA PEGAWAI')
	   ->setCellValue('D4', 'STATUS')
	   ->setCellValue('E4', 'PRIODE')
	   ->setCellValue('F4', 'PH BRUTO')
	   ->setCellValue('G4', 'PH NETTO')
	   ->setCellValue('H4', 'PTKP')
	   ->setCellValue('I4', 'PKP')
	   ->setCellValue('J4', 'PPH21 TERUTANG')
	   ->setCellValue('K4', 'PPH21 BULANAN');
 
$baris = 5;
$no = 0;			
while($row=mysqli_fetch_array($hasil)){
$no = $no +1;

$objPHPExcel->setActiveSheetIndex(0)
     ->setCellValue("A$baris", $no)
     ->setCellValue("B$baris", $row['no_npwp'])
	 ->setCellValue("C$baris", $row['nama_pegawai'])
	 ->setCellValue("D$baris", $row['status'])
	 ->setCellValue("E$baris", $row['priode_gajian'])
	 ->setCellValue("F$baris", $row['penghasilan_bruto'])
	 ->setCellValue("G$baris", $row['penghasilan_netto'])
	 ->setCellValue("H$baris", $row['penghasilan_tidak_kena_pajak'])
	 ->setCellValue("I$baris", $row['penghasilan_kena_pajak'])
	 ->setCellValue("J$baris", $row['pph21_terutang'])
	 ->setCellValue("K$baris", $row['potongan_pajak']);
$baris = $baris + 1;
}
} 
// Rename sheet
$objPHPExcel->getActiveSheet()->setTitle('laporan_pajak');
 
// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);
 
// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="LaporanPajak.xls"');
header('Cache-Control: max-age=0');
 
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;
?>
 