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

    //kolom
    $aColumns = array('idbuku','judul','nipnis','nama','tglpinjam','tglhrskembali');

    //nama table database
    $sTable = " (SELECT a.idbuku AS idbuku, b.judul AS judul, a.nipnis AS nipnis, c.nama AS nama, a.tglpinjam AS tglpinjam, a.tglhrskembali AS tglhrskembali, a.isterlambat AS isterlambat FROM tpinbuku a JOIN tbuku b ON a.idbuku = b.idbuku JOIN ranggota c ON a.nipnis = c.nipnis AND a.iskembali = 0 AND b.idjnsbuku < 4) AS tpinbuku ";
    //primary key
    $sIndexColumn = "idbuku";

    $sOrder = " ORDER BY tglpinjam ";

    // LAPORAN PER JENIS
    $sheet->getStyle('A')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->setCellValue('A1', getNmsekolah($koneksidb) ); 
    $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);    
    $sheet->mergeCells('A1:B1'); // Set Merge Cell pada 
    $sheet->getStyle('A1')->getFont()->setSize(12); // Set font size   
    
    $sheet->setCellValue('A3',  "LAPORAN BUKU PERPUSTAKAAN MASIH DIPINJAM" ); // Set kolom  
    $sheet->getStyle('A3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->mergeCells('A3:H3'); // Set Merge Cell  
    $sheet->getStyle('A3')->getFont()->setBold(TRUE); // Set bold  
    $sheet->getStyle('A3')->getFont()->setSize(13); // Set font size  


    $sheet->setCellValue('A4', "Per Tanggal ".IndonesiaTgl(date("Y-m-d"))); 
    $sheet->getStyle('A4')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->mergeCells('A4:H4'); // Set Merge Cell pada  
    $sheet->getStyle('A4')->getFont()->setBold(TRUE); // Set bold  
    $sheet->getStyle('A4')->getFont()->setSize(12); // Set font size 
    
    // Buat header tabel 
    $sheet->setCellValue('A6', "NO");  
    $sheet->setCellValue('B6', "BUKU"); 
    $sheet->setCellValue('D6', "PEMINJAM"); 
    $sheet->setCellValue('F6', "TANGGAL");
    $sheet->setCellValue('H6', "TERLAMBAT"); 
    $sheet->setCellValue('B7', "ID BUKU"); 
    $sheet->setCellValue('C7', "JUDUL"); 
    $sheet->setCellValue('D7', "NIS / NIP"); 
    $sheet->setCellValue('E7', "NAMA"); 
    $sheet->setCellValue('F7', "PINJAM"); 
    $sheet->setCellValue('G7', "JADWAL KEMBALI"); 
    
    // Apply style header yang telah kita buat tadi ke masing-masing kolom header
    $sheet->getStyle('A6')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('A6:A7')->applyFromArray($style_col);
    $sheet->mergeCells('A6:A7'); // Set Merge Cell pada 

    $sheet->getStyle('B')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
    $sheet->getStyle('B6')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('B6:C6')->applyFromArray($style_col);
    $sheet->mergeCells('B6:C6'); // Set Merge Cell pada  

    $sheet->getStyle('D')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
    $sheet->getStyle('D6')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('D6:E6')->applyFromArray($style_col);
    $sheet->mergeCells('D6:E6'); // Set Merge Cell pada  

    $sheet->getStyle('F6')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('F6:G6')->applyFromArray($style_col);
    $sheet->mergeCells('F6:G6'); // Set Merge Cell pada  

    $sheet->getStyle('H6')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('H6:H7')->applyFromArray($style_col);
    $sheet->mergeCells('H6:H7'); // Set Merge Cell pada  

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

    // Set width kolom
    $sheet->getColumnDimension('A')->setWidth(10);
    $sheet->getColumnDimension('B')->setWidth(20);
    $sheet->getColumnDimension('C')->setWidth(50);
    $sheet->getColumnDimension('D')->setWidth(20);
    $sheet->getColumnDimension('E')->setWidth(50);
    $sheet->getColumnDimension('F')->setWidth(20);
    $sheet->getColumnDimension('G')->setWidth(20);
    $sheet->getColumnDimension('H')->setWidth(15);

    $numrow = 8;  //BARUS PERTAMA UNTUK DATA, setelah header

    $sql = "
    SELECT SQL_CALC_FOUND_ROWS ".str_replace(" , ", " ", implode(", ", $aColumns))." FROM   
    $sTable
    $sOrder
    ";
    
     $stmt = mysqli_prepare($koneksidb,$sql) or die ("Gagal menyiapkan statement: " . mysqli_error($koneksidb));
     mysqli_stmt_execute($stmt);
     mysqli_stmt_bind_result($stmt,$dataIdBuku,$dataJudul,$dataNipnis,$dataNama,$dataTglPinjam,$dataTglHrsKembali);

     $total = 0;
     $no = 1;
        while(mysqli_stmt_fetch($stmt)){

            /***tampilkan data****/ 
            $sheet->setCellValue('A'.$numrow, $no);
            $sheet->setCellValue('B'.$numrow, $dataIdBuku);
            $sheet->setCellValue('C'.$numrow, $dataJudul);
            $sheet->setCellValue('D'.$numrow, $dataNipnis);
            $sheet->setCellValue('E'.$numrow, $dataNama);
            $sheet->setCellValue('F'.$numrow, IndonesiaTgl($dataTglPinjam));
            $sheet->setCellValue('G'.$numrow, IndonesiaTgl($dataTglHrsKembali));
            if ($dataTglHrsKembali < date("Y-m-d")){
                $sheet->setCellValue('H'.$numrow, "YA");
            }else{
                $sheet->setCellValue('H'.$numrow, "TIDAK");
            }
            
            $sheet->getStyle('A'.$numrow)->applyFromArray($style_row);
            $sheet->getStyle('B'.$numrow)->applyFromArray($style_row);
            $sheet->getStyle('C'.$numrow)->applyFromArray($style_row);
            $sheet->getStyle('D'.$numrow)->applyFromArray($style_row);
            $sheet->getStyle('E'.$numrow)->applyFromArray($style_row);
            $sheet->getStyle('F'.$numrow)->applyFromArray($style_row);
            $sheet->getStyle('G'.$numrow)->applyFromArray($style_row);
            $sheet->getStyle('H'.$numrow)->applyFromArray($style_row);

            $no++;
            $numrow++;
        }


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
header('Content-Disposition: attachment; filename="Daftar Pustaka Terpinjam Koleksi Pustaka.xlsx"'); // Set nama file excel nya
header('Cache-Control: max-age=0');
$writer = new Xlsx($spreadsheet);

$writer->save('php://output');
exit;
