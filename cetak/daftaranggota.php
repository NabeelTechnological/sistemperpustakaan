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
    $aColumns = array( 'nipnis', 'nama', 'jnskel','telp','berlaku','alamat','kota','alamat2','kotaalt');

    //nama table database
    $sTable = "vw_ranggota";

    $sOrder = " ORDER BY nama";

    $sWhere = " where idjnsang = ".mysqli_real_escape_string($koneksidb, $idjnsang );
    if ($idjnsang==1) {
        $sWhere .= " AND idkelas = '".mysqli_real_escape_string($koneksidb, $kelas )."'";
    }

    //Alignment Cells
    $sheet->getStyle('A')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('B')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
    $sheet->getStyle('C')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
    $sheet->getStyle('D')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
    $sheet->getStyle('E')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
    $sheet->getStyle('F')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);


    $sheet->setCellValue('A1', getNmsekolah($koneksidb) ); 
    $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);    
    $sheet->mergeCells('A1:B1'); // Set Merge Cell pada 
    $sheet->getStyle('A1')->getFont()->setSize(12); // Set font size   
    
    $sheet->setCellValue('A3',  "LAPORAN ANGGOTA LENGKAP" ); // Set kolom  
    $sheet->getStyle('A3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->mergeCells('A3:F3'); // Set Merge Cell  
    $sheet->getStyle('A3')->getFont()->setBold(TRUE); // Set bold  
    $sheet->getStyle('A3')->getFont()->setSize(13); // Set font size  

    $sheet->setCellValue('A4', "Tanggal ".indonesiaTglPanjang(date("Y-m-d"))); 
    $sheet->getStyle('A4')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->mergeCells('A4:F4'); // Set Merge Cell pada  
    $sheet->getStyle('A4')->getFont()->setBold(TRUE); // Set bold  
    $sheet->getStyle('A4')->getFont()->setSize(12); // Set font size

    $sheet->setCellValue('A6', "Jumlah Anggota $jnsang : "); 
    $sheet->getStyle('A6')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
    $sheet->mergeCells('A6:B6'); // Set Merge Cell pada  
    $sheet->getStyle('A6')->getFont()->setBold(TRUE); // Set bold  
    $sheet->getStyle('A6')->getFont()->setSize(12); // Set font size

    $sheet->setCellValue('C6', "$jmlGolongan"); 
    $sheet->getStyle('C6')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
    $sheet->getStyle('C6')->getFont()->setBold(TRUE); // Set bold  
    $sheet->getStyle('C6')->getFont()->setSize(12); // Set font size 

    $sheet->setCellValue('A7', "Jumlah Anggota Total :"); 
    $sheet->getStyle('A7')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
    $sheet->mergeCells('A7:B7'); // Set Merge Cell pada  
    $sheet->getStyle('A7')->getFont()->setBold(TRUE); // Set bold  
    $sheet->getStyle('A7')->getFont()->setSize(12); // Set font size 

    $sheet->setCellValue('C7', "$jmlTotal"); 
    $sheet->getStyle('C7')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
    $sheet->getStyle('C7')->getFont()->setBold(TRUE); // Set bold  
    $sheet->getStyle('C7')->getFont()->setSize(12); // Set font size 

    // Buat header tabel 
    $sheet->setCellValue('A8', "NO. URUT");  
    $sheet->setCellValue('B8', "ID ANGGOTA"); 
    $sheet->setCellValue('C8', "NAMA ANGGOTA"); 
    $sheet->setCellValue('D8', "ALAMAT 1"); 
    $sheet->setCellValue('F8', "TELEPON");

    $sheet->setCellValue('B9', "BERLAKU S.D."); 
    $sheet->setCellValue('D9', "ALAMAT 2"); 
    $sheet->setCellValue('F9', "JNS KELAMIN"); 

    // Set width kolom
    $sheet->getColumnDimension('A')->setWidth(10);
    $sheet->getColumnDimension('B')->setWidth(23);
    $sheet->getColumnDimension('C')->setWidth(50);
    $sheet->getColumnDimension('D')->setWidth(50);
    $sheet->getColumnDimension('E')->setWidth(20);
    $sheet->getColumnDimension('F')->setWidth(20);
    
    // Apply style header yang telah kita buat tadi ke masing-masing kolom header
    $sheet->getStyle('A8')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('A8:A9')->applyFromArray($style_col);
    $sheet->mergeCells('A8:A9'); // Set Merge Cell pada 

    $sheet->getStyle('B8')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('B8')->applyFromArray($style_col);

    $sheet->getStyle('B9')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('B9')->applyFromArray($style_col);

    $sheet->getStyle('C8')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('C8:C9')->applyFromArray($style_col);
    $sheet->mergeCells('C8:C9'); // Set Merge Cell pada 

    $sheet->getStyle('D8')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('D8:E8')->applyFromArray($style_col);
    $sheet->mergeCells('D8:E8'); // Set Merge Cell pada 

    $sheet->getStyle('D9')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('D9:E9')->applyFromArray($style_col);
    $sheet->mergeCells('D9:E9'); // Set Merge Cell pada 

    $sheet->getStyle('F8')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('F8')->applyFromArray($style_col);

    $sheet->getStyle('F9')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('F9')->applyFromArray($style_col);


    $numrow = 10;  //BARUS PERTAMA UNTUK DATA, setelah header

    $sql = "
    SELECT SQL_CALC_FOUND_ROWS ".str_replace(" , ", " ", implode(", ", $aColumns))." FROM   
    $sTable
    $sWhere
    $sOrder
    ";
    
     $stmt = mysqli_prepare($koneksidb,$sql) or die ("Gagal menyiapkan statement: " . mysqli_error($koneksidb));
     mysqli_stmt_execute($stmt);

     mysqli_stmt_bind_result($stmt,$dataNipnis,$dataNama,$dataJnskel,$dataTelp,$dataBerlaku,$dataAlamat,$dataKota,$dataAlamat2,$dataKotaalt);

     $no = 1;
        while(mysqli_stmt_fetch($stmt)){ 
            $numrow_merge = $numrow;

            /***tampilkan data****/ 
            $sheet->setCellValue('A'.$numrow, $no);
            $sheet->setCellValue('B'.$numrow, $dataNipnis);
            $sheet->setCellValue('C'.$numrow, $dataNama);
            $sheet->setCellValue('D'.$numrow, $dataAlamat);
            $sheet->setCellValue('E'.$numrow, $dataKota);
            $sheet->setCellValue('F'.$numrow, $dataTelp);

            $numrow++;

            $sheet->setCellValue('B'.$numrow, $dataBerlaku);
            $sheet->setCellValue('D'.$numrow, $dataAlamat2);
            $sheet->setCellValue('E'.$numrow, $dataKotaalt);
            $sheet->setCellValue('F'.$numrow, $dataJnskel);
            $sheet->getStyle('F'.$numrow)->getFont()->setBold(TRUE); // Set bold  
            $sheet->getStyle('F'.$numrow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

            $sheet->getStyle('A'.$numrow_merge.":F".$numrow)->applyFromArray($style_row);

            $no++;
            $numrow++;
        }
        
        $numrow++;
     
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
header('Content-Disposition: attachment; filename="Daftar Anggota Lengkap.xlsx"'); // Set nama file excel nya
header('Cache-Control: max-age=0');
$writer = new Xlsx($spreadsheet);

$writer->save('php://output');
exit;
