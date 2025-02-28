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

    $aColumns = array('tglrealkembali');

    $sWhere = " WHERE tglrealkembali IS NOT NULL ";
    if($dataBulan != "" && $dataTahun != "" && $pilihan == "bulanan"){
        $sWhere .= " AND MONTH(tglrealkembali) = '".$dataBulan."' AND YEAR(tglrealkembali) = '".$dataTahun."'  ";
    }else if($dataDariTanggal != "" && $dataSampaiTanggal != "" && $pilihan == "custom"){
        $sWhere .= " AND tglrealkembali >= '".$dataDariTanggal."' AND tglrealkembali <= '".$dataSampaiTanggal."' ";
    } 

    //nama table database
    $sTable = "tpinbuku";
    $sGroup = "GROUP BY tglrealkembali";
    $sOrder = " ORDER BY tglrealkembali ";

    $sheet->setCellValue('A1', getNmsekolah($koneksidb) ); 
    $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);    
    $sheet->getStyle('A1')->getFont()->setSize(12); // Set font size   
    
    $sheet->setCellValue('A3',  "LAPORAN BULANAN" ); // Set kolom  
    $sheet->getStyle('A3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->mergeCells('A3:B3'); // Set Merge Cell  
    $sheet->getStyle('A3')->getFont()->setSize(13); // Set font size  

    $sheet->setCellValue('A4', "REKAPITULASI DENDA KETERLAMBATAN PENGEMBALIAN");  
    $sheet->getStyle('A4')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->mergeCells('A4:B4'); // Set Merge Cell pada  
    $sheet->getStyle('A4')->getFont()->setSize(13); // Set font size 

    if($bulan != "" && $tahun != "" && $pilihan == "bulanan"){
        $sheet->setCellValue('A5', "Bulan : ".namaBulanIndonesia($bulan)." ".$tahun);  
    }else if($dariTanggal != "" && $sampaiTanggal != "" && $pilihan == "custom"){
        $sheet->setCellValue('A5', "Periode: ".IndonesiaTgl($dariTanggal)."  s/d  ". IndonesiaTgl($sampaiTanggal));  
    }

    $sheet->getStyle('A5')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->mergeCells('A5:B5'); // Set Merge Cell pada  
    $sheet->getStyle('A5')->getFont()->setBold(TRUE); // Set bold  
    $sheet->getStyle('A5')->getFont()->setSize(12); // Set font size 
    
    // Buat header tabel 

    $sheet->setCellValue('A7', "DENDA BUKU"); 
    $sheet->mergeCells('A7:B7'); // Set Merge Cell pada  
    $sheet->setCellValue('A8', "TANGGAL"); 
    $sheet->setCellValue('B8', "JUMLAH (Rp)"); 
    
    // Apply style header yang telah kita buat tadi ke masing-masing kolom header
    $sheet->getStyle('A7:B7')->applyFromArray($style_col);
    $sheet->getStyle('A7')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('A8')->applyFromArray($style_col);
    $sheet->getStyle('A8')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('B8')->applyFromArray($style_col);
    $sheet->getStyle('B')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('B8')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

    // Set width kolom
    $sheet->getColumnDimension('A')->setWidth(35);
    $sheet->getColumnDimension('B')->setWidth(40);

    $numrow = 9;  //BARUS PERTAMA UNTUK DATA, setelah header

     //table database 
      $sql = "
    SELECT SQL_CALC_FOUND_ROWS ".str_replace(" , ", " ", implode(", ", $aColumns)).",SUM(bsudenda) AS jmldenda FROM   
    $sTable
    $sWhere
    $sGroup 
    $sOrder
    ";
    
     $stmt = mysqli_prepare($koneksidb,$sql) or die ("Gagal menyiapkan statement: " . mysqli_error($koneksidb));
     mysqli_stmt_execute($stmt);
     mysqli_stmt_bind_result($stmt,$dataTgl,$dataJmlDenda);

     $total = 0;
        while(mysqli_stmt_fetch($stmt)){
            $total += $dataJmlDenda;

            /***tampilkan data****/ 
            $sheet->setCellValue('A'.$numrow, $dataTgl);
            $sheet->setCellValue('B'.$numrow, $dataJmlDenda);
            
            $sheet->getStyle('A'.$numrow)->applyFromArray($style_row);
            $sheet->getStyle('B'.$numrow)->applyFromArray($style_row);
            $sheet->getStyle('B'.$numrow)->getNumberFormat()->setFormatCode('#,##0');

            $numrow++;
        }

        $sheet->setCellValue('A'.$numrow, "TOTAL");
        $sheet->getStyle('A'.$numrow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER); 
        $sheet->getStyle('A'.$numrow)->getFont()->setBold(TRUE); 
        $sheet->getStyle('A'.$numrow)->getFont()->setSize(13);
    
        $sheet->setCellValue('B'.$numrow, $total);
        $sheet->getStyle('B'.$numrow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER); 
        $sheet->getStyle('B'.$numrow)->getFont()->setBold(TRUE); 
        $sheet->getStyle('B'.$numrow)->getFont()->setSize(13);
        $sheet->getStyle('B'.$numrow)->getNumberFormat()->setFormatCode('#,##0');

        $sheet->getStyle('A'.$numrow)->applyFromArray($style_row);
        $sheet->getStyle('B'.$numrow)->applyFromArray($style_row);


// Set orientasi kertas jadi LANDSCAPE
// $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
// Set skala cetak agar semua kolom muat pada satu halaman
$sheet->getPageSetup()->setFitToWidth(1);
$sheet->getPageSetup()->setFitToHeight(0);
// Set judul file excel nya
$sheet->setTitle("sheet1");

ob_end_clean();
// Proses file excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="Rekapitulasi Denda.xlsx"'); // Set nama file excel nya
header('Cache-Control: max-age=0');
$writer = new Xlsx($spreadsheet);

$writer->save('php://output');
exit;
