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

    $aColumns = array('idbuku', 'judul', 'tglpinjam', 'tglrealkembali','pengarangnormal');
    $sTable = "(SELECT  a.idjnsbuku AS idjnsbuku, a.idbuku AS idbuku, a.judul AS judul, a.pengarangnormal AS pengarangnormal, b.tglpinjam AS tglpinjam, b.tglrealkembali AS tglrealkembali, c.nipnis AS nipnis
    FROM tbuku a JOIN tpinbuku b ON a.idbuku = b.idbuku JOIN ranggota c ON b.nipnis = c.nipnis) AS tpinbuku";

    if ($harian != "" && $pilihan == "harian") {
        $sWhere = " WHERE tglpinjam = '" . $harian . "' ";

    } elseif ($bulan != "" && $tahun != "" && $pilihan == "bulanan") {
        $sWhere = " WHERE MONTH(tglpinjam) = '" . $bulan . "' AND YEAR(tglpinjam) = '" . $tahun . "' ";

    } elseif ($dariTanggal != "" && $sampaiTanggal != "" && $pilihan == "custom") {
        $sWhere = " WHERE tglpinjam >= '" . $dariTanggal . "' AND tglpinjam <= '" . $sampaiTanggal . "' ";

    }

    $sWhere .= "AND idjnsbuku = 4 AND nipnis=$anggota";

    //Alignment Cells
    $sheet->getStyle('A')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('D')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);

    $sheet->setCellValue('A1', getNmsekolah($koneksidb) ); 
    $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);    
    $sheet->getStyle('A1')->getFont()->setSize(12); // Set font size   
    
    if ($harian != "" && $pilihan == "harian") {
        $jnslaporan = "HARIAN";
    
    } elseif ($bulan != "" && $tahun != "" && $pilihan == "bulanan") {
        $jnslaporan = "BULANAN";
    
    } elseif ($dariTanggal != "" && $sampaiTanggal != "" && $pilihan == "custom") {
        $jnslaporan = "MASA";
    
    }

    $sheet->setCellValue('A3',  "LAPORAN ".$jnslaporan." PEMINJAMAN BUKU TEKS" ); // Set kolom  
    $sheet->getStyle('A3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->mergeCells('A3:H3'); // Set Merge Cell  
    $sheet->getStyle('A3')->getFont()->setSize(13); // Set font size  

    $dataSql = "SELECT nama FROM ranggota WHERE nipnis = ?";
    $stmt = mysqli_prepare($koneksidb,$dataSql) or die ("Gagal menyiapkan statement: " . mysqli_error($koneksidb));
    mysqli_stmt_bind_param($stmt,"s",$anggota);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt,$dataNama);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    $sheet->setCellValue('A4', "OLEH : [$anggota] $dataNama");  
    $sheet->getStyle('A4')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->mergeCells('A4:H4'); // Set Merge Cell pada  
    $sheet->getStyle('A4')->getFont()->setSize(13); // Set font size 

    if ($harian != ""  && $pilihan == "harian") {
        $sheet->setCellValue('A5', "Tanggal : ".IndonesiaTgl($harian));  
    }if($bulan != "" && $tahun != ""  && $pilihan == "bulanan"){
        $sheet->setCellValue('A5', "Bulan : ".namaBulanIndonesia($bulan)." ".$tahun);  
    }else if($dariTanggal != "" && $sampaiTanggal != ""  && $pilihan == "custom"){
        $sheet->setCellValue('A5', "Dari Tanggal: ".IndonesiaTgl($dariTanggal)."  S.D.  ". IndonesiaTgl($sampaiTanggal));  
    }

    $sheet->getStyle('A5')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->mergeCells('A5:H5'); // Set Merge Cell pada  
    $sheet->getStyle('A5')->getFont()->setBold(TRUE); // Set bold  
    $sheet->getStyle('A5')->getFont()->setSize(12); // Set font size 
    
    // Buat header tabel 

    $sheet->setCellValue('A7', "NO");  
    $sheet->setCellValue('B7', "TGL PINJAM"); 
    $sheet->setCellValue('C7', "TGL KEMBALI"); 
    $sheet->setCellValue('D7', "ID BUKU"); 
    $sheet->setCellValue('E7', "JUDUL BUKU"); 
    $sheet->setCellValue('H7', "PENGARANG"); 
    
    // Apply style header yang telah kita buat tadi ke masing-masing kolom header
    $sheet->getStyle('A7')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('A7')->applyFromArray($style_col);

    $sheet->getStyle('B7')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('B7')->applyFromArray($style_col);

    $sheet->getStyle('C7')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('C7')->applyFromArray($style_col);

    $sheet->getStyle('D7')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('D7')->applyFromArray($style_col);

    $sheet->getStyle('E7')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('E7:G7')->applyFromArray($style_col);
    $sheet->mergeCells('E7:G7'); // Set Merge Cell pada 

    $sheet->getStyle('H7')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('H7')->applyFromArray($style_col);

    // Set width kolom
    $sheet->getColumnDimension('A')->setWidth(10);
    $sheet->getColumnDimension('B')->setWidth(20);
    $sheet->getColumnDimension('C')->setWidth(20);
    $sheet->getColumnDimension('D')->setWidth(20);
    $sheet->getColumnDimension('E')->setWidth(20);
    $sheet->getColumnDimension('F')->setWidth(20);
    $sheet->getColumnDimension('G')->setWidth(45);
    $sheet->getColumnDimension('H')->setWidth(30);

    $numrow = 8;  //BARUS PERTAMA UNTUK DATA, setelah header

     //table database 
     $sql = "
 SELECT ".str_replace(" , ", " ", implode(", ", $aColumns))."
 FROM $sTable
 $sWhere
";

 $stmt = mysqli_prepare($koneksidb,$sql) or die ("Gagal menyiapkan statement: " . mysqli_error($koneksidb));
 mysqli_stmt_execute($stmt);
   
        mysqli_stmt_bind_result($stmt,$dataIdBuku,$dataJudul,$dataTglPinjam,$dataTglRealKembali,$dataPengarangNormal);

     $no = 0;
        while(mysqli_stmt_fetch($stmt)){
            /***tampilkan data****/ 
            $sheet->setCellValue('A'.$numrow, ++$no);
            $sheet->setCellValue('B'.$numrow, $dataTglPinjam);
            $dataTglRealKembali = ($dataTglRealKembali!="") ? $dataTglRealKembali : "-";
            $sheet->setCellValue('C'.$numrow, $dataTglRealKembali);
            $sheet->setCellValue('D'.$numrow, $dataIdBuku);
            $sheet->setCellValue('E'.$numrow, $dataJudul);
            $sheet->setCellValue('H'.$numrow, $dataPengarangNormal);
            
            $sheet->getStyle('A'.$numrow)->applyFromArray($style_row);
            $sheet->getStyle('B'.$numrow)->applyFromArray($style_row);
            $sheet->getStyle('C'.$numrow)->applyFromArray($style_row);
            $sheet->getStyle('D'.$numrow)->applyFromArray($style_row);
            $sheet->getStyle('E'.$numrow.':G'.$numrow)->applyFromArray($style_row);
            $sheet->mergeCells('E'.$numrow.':G'.$numrow); // Set Merge Cell pada 
            $sheet->getStyle('H'.$numrow)->applyFromArray($style_row);

            $numrow++;
        }

        $sheet->setCellValue('E'.$numrow, "JUMLAH TOTAL:");
        $sheet->getStyle('E'.$numrow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT); 
        $sheet->getStyle('E'.$numrow)->getFont()->setBold(TRUE); 
        $sheet->getStyle('E'.$numrow)->getFont()->setSize(13);
    
        $sheet->setCellValue('F'.$numrow, $no);
        $sheet->getStyle('F'.$numrow)->getFont()->setBold(TRUE); 
        $sheet->getStyle('F'.$numrow)->getFont()->setSize(13);
        
        $sheet->setCellValue('G'.$numrow, "PEMINJAMAN");
        $sheet->getStyle('G'.$numrow)->getFont()->setBold(TRUE); 
        $sheet->getStyle('G'.$numrow)->getFont()->setSize(13);

        $sheet->getStyle('A'.$numrow.':H'.$numrow)->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $sheet->getStyle('A'.$numrow.':H'.$numrow)->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        $numrow++;$numrow++;

        $sheet->setCellValue('H'.$numrow, "Dilaporkan Oleh");
        $sheet->getStyle('H'.$numrow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER); 
        $sheet->getStyle('H'.$numrow)->getFont()->setBold(TRUE); 
        $sheet->getStyle('H'.$numrow)->getFont()->setSize(12);

        $numrow++;$numrow++;$numrow++;

        $sheet->getStyle('H'.$numrow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER); 
        $sheet->getStyle('H'.$numrow)->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);


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
header('Content-Disposition: attachment; filename="Laporan Peminjaman Per Anggota Buku Teks.xlsx"'); // Set nama file excel nya
header('Cache-Control: max-age=0');
$writer = new Xlsx($spreadsheet);

$writer->save('php://output');
exit;
