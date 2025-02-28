<?php 

require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Buat sebuah variabel untuk menampung pengaturan style dari header tabel
$style_col = [
    'font' => ['bold' => true], // Set font nya jadi bold
    'alignment' => [
        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, // Set text jadi ditengah secara horizontal (center)
        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
    ],
    'borders' => [
        'top' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], // Set border top dengan garis tipis
        'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],  // Set border right dengan garis tipis
        'bottom' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], // Set border bottom dengan garis tipis
        'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN] // Set border left dengan garis tipis
    ]
];
// Buat sebuah variabel untuk menampung pengaturan style dari isi tabel
$style_row = [
    'alignment' => [
        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
    ],
    'borders' => [
        'top' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], // Set border top dengan garis tipis
        'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],  // Set border right dengan garis tipis
        'bottom' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], // Set border bottom dengan garis tipis
        'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN] // Set border left dengan garis tipis
    ]
];

// DATABASE PREPARATION
$aColumns = array( 'desjenisang', 'tdkberlaku', 'masihberlaku');
$sTable = "(SELECT desjenisang, 
COUNT(CASE WHEN berlaku < CURDATE() THEN 1 END) AS tdkberlaku,
COUNT(CASE WHEN berlaku >= CURDATE() THEN 1 END) AS masihberlaku
FROM vw_ranggota
GROUP BY desjenisang
ORDER BY idjnsang) as ranggota";

$sheet->setCellValue('A1', getNmsekolah($koneksidb) ); 
$sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);    
$sheet->getStyle('A1')->getFont()->setSize(12); // Set font size

    $sheet->setCellValue('A3', "REKAPITULASI JUMLAH ANGGOTA");  
    $sheet->getStyle('A3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->mergeCells('A3:F3'); // Set Merge Cell pada  
    $sheet->getStyle('A3')->getFont()->setSize(13); // Set font size 

    $sheet->setCellValue('A4', "Dicetak tanggal ".indonesiaTglPanjang(date("Y-m-d")));  
    $sheet->getStyle('A4')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->mergeCells('A4:F4'); // Set Merge Cell pada  
    $sheet->getStyle('A4')->getFont()->setBold(TRUE); // Set bold  
    $sheet->getStyle('A4')->getFont()->setSize(12); // Set font size 

// Buat header tabel 
$sheet->setCellValue('B6', "NO. URUT"); 
$sheet->setCellValue('C6', "URAIAN"); 
$sheet->setCellValue('D6', "JUMLAH"); 
$sheet->setCellValue('D7', "KARTU BERLAKU"); 
$sheet->setCellValue('E7', "KARTU TDK BERLAKU"); 
$sheet->setCellValue('F7', "TOTAL"); 

// Apply style header yang telah kita buat tadi ke masing-masing kolom header
$sheet->getStyle('B6')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('B6:B7')->applyFromArray($style_col);
$sheet->mergeCells('B6:B7'); // Set Merge Cell pada 

$sheet->getStyle('C6')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('C6:C7')->applyFromArray($style_col);
$sheet->mergeCells('C6:C7'); // Set Merge Cell pada 

$sheet->getStyle('D6')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('D6:F6')->applyFromArray($style_col);
$sheet->mergeCells('D6:F6'); // Set Merge Cell pada 

$sheet->getStyle('D7')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('D7')->applyFromArray($style_col); 

$sheet->getStyle('E7')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('E7')->applyFromArray($style_col);

$sheet->getStyle('F7')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('F7')->applyFromArray($style_col);

// Set width kolom
$sheet->getColumnDimension('B')->setWidth(10);
$sheet->getColumnDimension('C')->setWidth(30);
$sheet->getColumnDimension('D')->setWidth(20);
$sheet->getColumnDimension('E')->setWidth(20);
$sheet->getColumnDimension('F')->setWidth(20);

$numrow = 8;  //BARUS PERTAMA UNTUK DATA, setelah header

 //table database 
 $sql = "
 SELECT ".str_replace(" , ", " ", implode(", ", $aColumns))."
 FROM $sTable
";

 $stmt = mysqli_prepare($koneksidb,$sql) or die ("Gagal menyiapkan statement: " . mysqli_error($koneksidb));
 mysqli_stmt_execute($stmt);
   
        mysqli_stmt_bind_result($stmt,$dataDesJenisang,$dataTdkBerlaku,$dataMasihBerlaku);

 $totalBerlaku = 0;
 $totalTdkBerlaku = 0;
 $jmlTotal = 0;
 $no = 1;
    while(mysqli_stmt_fetch($stmt)){

        /***tampilkan data****/ 
        $sheet->setCellValue('B'.$numrow, $no++);
        $sheet->setCellValue('C'.$numrow, $dataDesJenisang);
        $sheet->setCellValue('D'.$numrow, $dataMasihBerlaku);
        $sheet->setCellValue('E'.$numrow, $dataTdkBerlaku);
        $dataTotal = $dataMasihBerlaku + $dataTdkBerlaku;
        $sheet->setCellValue('F'.$numrow, $dataTotal);

        $sheet->getStyle('B'.$numrow)->applyFromArray($style_row);
        $sheet->getStyle('C'.$numrow)->applyFromArray($style_row);
        $sheet->getStyle('D'.$numrow)->applyFromArray($style_row);
        $sheet->getStyle('E'.$numrow)->applyFromArray($style_row);
        $sheet->getStyle('F'.$numrow)->applyFromArray($style_row);

        $numrow++;
        $totalBerlaku += $dataMasihBerlaku;
        $totalTdkBerlaku += $dataTdkBerlaku;
        $jmlTotal += $dataTotal;
    }

    $sheet->setCellValue('C'.$numrow, "JUMLAH TOTAL");
    $sheet->getStyle('C'.$numrow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT); 
    $sheet->getStyle('C'.$numrow)->getFont()->setBold(TRUE); 
    $sheet->getStyle('C'.$numrow)->getFont()->setSize(13);

    $sheet->setCellValue('D'.$numrow, $totalBerlaku); 
    $sheet->getStyle('D'.$numrow)->getFont()->setBold(TRUE); 
    $sheet->getStyle('D'.$numrow)->getFont()->setSize(13);

    $sheet->setCellValue('E'.$numrow, $totalTdkBerlaku);
    $sheet->getStyle('E'.$numrow)->getFont()->setBold(TRUE); 
    $sheet->getStyle('E'.$numrow)->getFont()->setSize(13);

    $sheet->setCellValue('F'.$numrow, $jmlTotal);
    $sheet->getStyle('F'.$numrow)->getFont()->setBold(TRUE); 
    $sheet->getStyle('F'.$numrow)->getFont()->setSize(13);

    $sheet->getStyle('B'.$numrow.':F'.$numrow)->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
    $sheet->getStyle('B'.$numrow.':F'.$numrow)->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

    $numrow++;$numrow++;

    $sheet->setCellValue('F'.$numrow, "Dilaporkan Oleh");
    $sheet->getStyle('F'.$numrow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER); 
    $sheet->getStyle('F'.$numrow)->getFont()->setBold(TRUE); 
    $sheet->getStyle('F'.$numrow)->getFont()->setSize(12);

    $numrow++;$numrow++;$numrow++;

    $sheet->getStyle('F'.$numrow)->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);


// Set orientasi kertas jadi LANDSCAPE
$sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
// Set skala cetak agar semua kolom muat pada satu halaman
$sheet->getPageSetup()->setFitToWidth(1);
$sheet->getPageSetup()->setFitToHeight(0);
// Set judul file excel nya
$sheet->setTitle("sheet1");

ob_end_clean();
// Proses file excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

    header('Content-Disposition: attachment; filename="Rekap Jumlah Anggota.xlsx"'); // Set nama file excel nya

header('Cache-Control: max-age=0');
$writer = new Xlsx($spreadsheet);

$writer->save('php://output');
exit;
