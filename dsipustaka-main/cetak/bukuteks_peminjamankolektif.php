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

$aColumns = array('idbuku','idkelas','nipnis','nama','judul','jmlpinjam','tglpinjam','tglkembali');
$sTable = "(SELECT a.judul AS judul, b.idbuku AS idbuku, b.tglpinjam AS tglpinjam, b.tglkembali AS tglkembali, b.idkelas AS idkelas, b.nipnis AS nipnis, b.jmlpinjam AS jmlpinjam, c.nama AS nama
FROM tbuku a JOIN tpinjampaket b ON a.idbuku = b.idbuku JOIN ranggota c ON b.nipnis = c.nipnis) AS tpinjampaket";

if ($harian != "" && $pilihan == "harian") {
    $sWhere = " WHERE tglpinjam = '" . $harian . "' ";
    $jnslaporan = "HARIAN";

} elseif ($bulan != "" && $tahun != "" && $pilihan == "bulanan") {
    $sWhere = " WHERE MONTH(tglpinjam) = '" . $bulan . "' AND YEAR(tglpinjam) = '" . $tahun . "' ";
    $jnslaporan = "BULANAN";

} elseif ($dariTanggal != "" && $sampaiTanggal != "" && $pilihan == "custom") {
    $sWhere = " WHERE tglpinjam >= '" . $dariTanggal . "' AND tglpinjam <= '" . $sampaiTanggal . "' ";
    $jnslaporan = "MASA";

}

$sOrder = "ORDER BY tglpinjam";

//Alignment Cells
$sheet->getStyle('A')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
$sheet->getStyle('C')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
$sheet->getStyle('G')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('H')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

    // LAPORAN PER JENIS
    $sheet->setCellValue('A1', getNmsekolah($koneksidb) ); 
    $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);    
    $sheet->mergeCells('A1:B1'); // Set Merge Cell pada 
    $sheet->getStyle('A1')->getFont()->setSize(12); // Set font size   
    
    $sheet->setCellValue('A3',  "LAPORAN $jnslaporan" ); // Set kolom  
    $sheet->getStyle('A3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->mergeCells('A3:H3'); // Set Merge Cell   
    $sheet->getStyle('A3')->getFont()->setSize(13); // Set font size  

    $sheet->setCellValue('A4',  "PEMINJAMAN BUKU PAKET" ); // Set kolom  
    $sheet->getStyle('A4')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->mergeCells('A4:H4'); // Set Merge Cell   
    $sheet->getStyle('A4')->getFont()->setSize(13); // Set font size  

    if ($harian != "" && $pilihan == "harian") {
        $sheet->setCellValue('A5', "Tanggal : ".IndonesiaTgl($harian));  
    }if($bulan != "" && $tahun != "" && $pilihan == "bulanan"){
        $sheet->setCellValue('A5', "Bulan : ".namaBulanIndonesia($bulan)." ".$tahun);  
    }else if($dariTanggal != "" && $sampaiTanggal != "" && $pilihan == "custom"){
        $sheet->setCellValue('A5', "Mulai Tanggal: ".IndonesiaTgl($dariTanggal)."  s.d.  ". IndonesiaTgl($sampaiTanggal));  
    }
    $sheet->getStyle('A5')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->mergeCells('A5:H5'); // Set Merge Cell pada  
    $sheet->getStyle('A5')->getFont()->setBold(TRUE); // Set bold  
    $sheet->getStyle('A5')->getFont()->setSize(12); // Set font size 
    
    // Buat header tabel 
    $sheet->setCellValue('A7', "IDBUKU");  
    $sheet->setCellValue('B7', "KELAS"); 
    $sheet->setCellValue('C7', "ID ANGGOTA"); 
    $sheet->setCellValue('D7', "NAMA PEMINJAM"); 
    $sheet->setCellValue('E7', "JUDUL BUKU"); 
    $sheet->setCellValue('F7', "JUMLAH");
    $sheet->setCellValue('G7', "PINJAM");
    $sheet->setCellValue('H7', "KEMBALI"); 

    // Apply style header yang telah kita buat tadi ke masing-masing kolom header
    $sheet->getStyle('A7')->applyFromArray($style_col);
    $sheet->getStyle('A7')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('B7')->applyFromArray($style_col);
    $sheet->getStyle('B7')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('C7')->applyFromArray($style_col);
    $sheet->getStyle('C7')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('D7')->applyFromArray($style_col);
    $sheet->getStyle('D7')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('E7')->applyFromArray($style_col);
    $sheet->getStyle('E7')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('F7')->applyFromArray($style_col);
    $sheet->getStyle('F7')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('G7')->applyFromArray($style_col);
    $sheet->getStyle('G7')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('H7')->applyFromArray($style_col);
    $sheet->getStyle('H7')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

    // Set width kolom
    $sheet->getColumnDimension('A')->setWidth(10);
    $sheet->getColumnDimension('B')->setWidth(8);
    $sheet->getColumnDimension('C')->setWidth(20);
    $sheet->getColumnDimension('D')->setWidth(40);
    $sheet->getColumnDimension('E')->setWidth(50);
    $sheet->getColumnDimension('F')->setWidth(10);
    $sheet->getColumnDimension('G')->setWidth(20);
    $sheet->getColumnDimension('H')->setWidth(20);

    $numrow = 8;  //BARUS PERTAMA UNTUK DATA, setelah header

    $sql = "SELECT ".str_replace(" , ", " ", implode(", ", $aColumns))." FROM   
    $sTable
    $sWhere
    $sOrder
    ";
    
     $stmt = mysqli_prepare($koneksidb,$sql) or die ("Gagal menyiapkan statement: " . mysqli_error($koneksidb));
     mysqli_stmt_execute($stmt);
     mysqli_stmt_bind_result($stmt,$dataIdBuku,$dataKelas,$dataNipnis,$dataNama,$dataJudul,$dataJmlPinjam,$dataTglPinjam,$dataTglKembali);

        while(mysqli_stmt_fetch($stmt)){

            /***tampilkan data****/ 
            $sheet->setCellValue('A'.$numrow, $dataIdBuku);
            $sheet->setCellValue('B'.$numrow, $dataKelas);
            $sheet->setCellValue('C'.$numrow, $dataNipnis);
            $sheet->setCellValue('D'.$numrow, $dataNama);
            $sheet->setCellValue('E'.$numrow, $dataJudul);
            $sheet->setCellValue('F'.$numrow, $dataJmlPinjam);
            $sheet->setCellValue('G'.$numrow, IndonesiaTgl($dataTglPinjam));
            $sheet->setCellValue('H'.$numrow, IndonesiaTgl($dataTglKembali));
            
            $sheet->getStyle('A'.$numrow)->applyFromArray($style_row);
            $sheet->getStyle('B'.$numrow)->applyFromArray($style_row);
            $sheet->getStyle('C'.$numrow)->applyFromArray($style_row);
            $sheet->getStyle('D'.$numrow)->applyFromArray($style_row);
            $sheet->getStyle('E'.$numrow)->applyFromArray($style_row);
            $sheet->getStyle('F'.$numrow)->applyFromArray($style_row);
            $sheet->getStyle('G'.$numrow)->applyFromArray($style_row);
            $sheet->getStyle('H'.$numrow)->applyFromArray($style_row);

            $numrow++;
        }

        $numrow++;

        $sheet->setCellValue('G'.$numrow, "Dilaporkan Oleh");
        $sheet->getStyle('G'.$numrow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER); 
        $sheet->getStyle('G'.$numrow)->getFont()->setBold(TRUE); 
        $sheet->getStyle('G'.$numrow)->getFont()->setSize(12);

        $numrow++;$numrow++;$numrow++;

        $sheet->getStyle('G'.$numrow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER); 
        $sheet->getStyle('G'.$numrow)->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

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
header('Content-Disposition: attachment; filename="Laporan Peminjaman Buku Kolektif.xlsx"'); // Set nama file excel nya
header('Cache-Control: max-age=0');
$writer = new Xlsx($spreadsheet);

$writer->save('php://output');
exit;
