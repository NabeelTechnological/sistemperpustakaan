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
$dataKeterangan		= $_POST['txtKeterangan'];
$dataTanggal		= date('Y-m-d');

$objPHPExcel = new PHPExcel();

$query="SELECT * FROM t_pembayaran_gaji
		INNER JOIN t_pegawai ON t_pembayaran_gaji.nik=t_pegawai.nik 
		INNER JOIN t_bagian ON t_pegawai.id_bagian=t_bagian.id_bagian
		INNER JOIN t_cabang ON t_pegawai.id_cabang=t_cabang.id_cabang
		INNER JOIN t_jabatan ON t_pegawai.id_jabatan=t_jabatan.id_jabatan
		WHERE t_pembayaran_gaji.priode_gajian LIKE '%$dataBulan-$dataTahun%'
		AND t_pegawai.id_cabang LIKE '%$dataCabang%'
		AND t_pegawai.nama_pegawai LIKE '%$dataNama%'
		AND t_pegawai.id_bagian LIKE '%$dataBagian%'
		AND t_pegawai.pembayaran_gajian LIKE '%$dataKeterangan%'";
$hasil = mysqli_query($GLOBALS["___mysqli_ston"], $query);
 
// Set properties
$objPHPExcel->getProperties()->setCreator("Farhan Alawi, ST")
      ->setLastModifiedBy("Seventh Media")
      ->setTitle("Office 2007 XLSX Test Document")
      ->setSubject("Office 2007 XLSX Test Document")
       ->setDescription("Laporan Data Penggajian.")
       ->setKeywords("office 2007 openxml php")
       ->setCategory("MIS 2013");
 
// Add some data
$objPHPExcel->setActiveSheetIndex(0)
	   ->setCellValue('A1', 'Bulan')
	   ->setCellValue('B1', $dataBulan)
	   ->setCellValue('A2', 'Tahun')
	   ->setCellValue('B2', $dataTahun)
       ->setCellValue('A4', 'NO')
       ->setCellValue('B4', 'NIK')
	   ->setCellValue('C4', 'NAMA PEGAWAI')
	   ->setCellValue('D4', 'DIVISI')
	   ->setCellValue('E4', 'BAGIAN')
	   ->setCellValue('F4', 'JABATAN')
	   ->setCellValue('G4', 'PRIODE')
	   ->setCellValue('H4', 'JUMLAH HARI')
	   ->setCellValue('I4', 'TOTAL PENERIMAAN')
	   ->setCellValue('J4', 'TOTAL POTONGAN')
	   ->setCellValue('K4', 'GAJI DITERIMA')
	   ->setCellValue('L4', 'PEMBAYARAN');
 
$baris = 5;
$no = 0;			
while($row=mysqli_fetch_array($hasil)){
$no = $no +1;

$objPHPExcel->setActiveSheetIndex(0)
     ->setCellValue("A$baris", $no)
     ->setCellValue("B$baris", $row['nik'])
	 ->setCellValue("C$baris", $row['nama_pegawai'])
	 ->setCellValue("D$baris", $row['nama_cabang'])
	 ->setCellValue("E$baris", $row['nama_bagian'])
	 ->setCellValue("F$baris", $row['nama_jabatan'])
	 ->setCellValue("G$baris", $row['priode_gajian'])
	 ->setCellValue("H$baris", $row['jumlah_hari'])
	 ->setCellValue("I$baris", $row['total_penerimaan'])
	 ->setCellValue("J$baris", $row['total_potongan'])
	 ->setCellValue("K$baris", $row['total_gaji_diterima'])
	 ->setCellValue("L$baris", $row['pembayaran_gajian']);
$baris = $baris + 1;
}
} 
// Rename sheet
$objPHPExcel->getActiveSheet()->setTitle('laporan_penggajian');
 
// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);
 
// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="LaporanPenggajian.xls"');
header('Cache-Control: max-age=0');
 
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;
?>
 