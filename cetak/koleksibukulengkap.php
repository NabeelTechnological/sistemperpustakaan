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
    $aColumns = array( 'idbuku','kode','tglentri','pengarang','kodebuku','judul', 'desjnsbuku', 'subyek', 'pengarangnormal','namapenerbit','nmkota','thterbit','nmbahasa','nmasalbuku','jilid','edisi','cetakan','isbn','tersedia','lokasi');

    //nama table database
    $sTable = "vw_tbuku";
    //primary key
    $sIndexColumn = "idbuku";

    $sOrder = " ORDER BY judul";

    $sWhere = " WHERE kode LIKE '$subyek%' ";

    //Alignment Cells
    $sheet->getStyle('A')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('B')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
    $sheet->getStyle('C')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
    $sheet->getStyle('D')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
    $sheet->getStyle('E')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
    $sheet->getStyle('F')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
    $sheet->getStyle('G')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
    $sheet->getStyle('H')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
    $sheet->getStyle('I')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);


    $sheet->setCellValue('A1', getNmsekolah($koneksidb) ); 
    $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);    
    $sheet->mergeCells('A1:B1'); // Set Merge Cell pada 
    $sheet->getStyle('A1')->getFont()->setSize(12); // Set font size   
    
    $sheet->setCellValue('A3',  "LAPORAN KOLEKSI BUKU LENGKAP" ); // Set kolom  
    $sheet->getStyle('A3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->mergeCells('A3:I3'); // Set Merge Cell  
    $sheet->getStyle('A3')->getFont()->setBold(TRUE); // Set bold  
    $sheet->getStyle('A3')->getFont()->setSize(13); // Set font size  

    $sheet->setCellValue('A4', "GOLONGAN [".$subyek."00] $desSubyek"); 
    $sheet->getStyle('A4')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->mergeCells('A4:I4'); // Set Merge Cell pada  
    $sheet->getStyle('A4')->getFont()->setBold(TRUE); // Set bold  
    $sheet->getStyle('A4')->getFont()->setSize(13); // Set font size 

    $sheet->setCellValue('A5', "Tanggal ".indonesiaTglPanjang(date("Y-m-d"))); 
    $sheet->getStyle('A5')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->mergeCells('A5:I5'); // Set Merge Cell pada  
    $sheet->getStyle('A5')->getFont()->setBold(TRUE); // Set bold  
    $sheet->getStyle('A5')->getFont()->setSize(12); // Set font size

    $sheet->setCellValue('A6', "Jumlah Buku Se Golongan :"); 
    $sheet->getStyle('A6')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
    $sheet->mergeCells('A6:C6'); // Set Merge Cell pada  
    $sheet->getStyle('A6')->getFont()->setBold(TRUE); // Set bold  
    $sheet->getStyle('A6')->getFont()->setSize(12); // Set font size

    $sheet->setCellValue('D6', "$jmlGolongan"); 
    $sheet->getStyle('D6')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
    $sheet->getStyle('D6')->getFont()->setBold(TRUE); // Set bold  
    $sheet->getStyle('D6')->getFont()->setSize(12); // Set font size 

    $sheet->setCellValue('A7', "Jumlah Buku Total :"); 
    $sheet->getStyle('A7')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
    $sheet->mergeCells('A7:C7'); // Set Merge Cell pada  
    $sheet->getStyle('A7')->getFont()->setBold(TRUE); // Set bold  
    $sheet->getStyle('A7')->getFont()->setSize(12); // Set font size 

    $sheet->setCellValue('D7', "$jmlTotal"); 
    $sheet->getStyle('D7')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
    $sheet->getStyle('D7')->getFont()->setBold(TRUE); // Set bold  
    $sheet->getStyle('D7')->getFont()->setSize(12); // Set font size 

    // Buat header tabel 
    $sheet->setCellValue('A8', "NO. URUT");  
    $sheet->setCellValue('B8', "ID BUKU"); 
    $sheet->setCellValue('C8', "JUDUL"); 
    $sheet->setCellValue('E8', "JENIS BUKU"); 
    $sheet->setCellValue('F8', "SUBYEK");
    $sheet->setCellValue('G8', "PENERBIT");
    $sheet->setCellValue('H8', "TEMPAT TERBIT"); 
    $sheet->setCellValue('I8', "BAHASA"); 

    $sheet->setCellValue('B9', "KODE BUKU"); 
    $sheet->setCellValue('E9', "KLASIFIKASI"); 
    $sheet->setCellValue('F9', "PENGARANG"); 
    $sheet->setCellValue('H9', "TAHUN"); 
    $sheet->setCellValue('I9', "ASAL BUKU"); 

    // Set width kolom
    $sheet->getColumnDimension('A')->setWidth(10);
    $sheet->getColumnDimension('B')->setWidth(15);
    $sheet->getColumnDimension('C')->setWidth(25);
    $sheet->getColumnDimension('D')->setWidth(20);
    $sheet->getColumnDimension('E')->setWidth(20);
    $sheet->getColumnDimension('F')->setWidth(20);
    $sheet->getColumnDimension('G')->setWidth(30);
    $sheet->getColumnDimension('H')->setWidth(20);
    $sheet->getColumnDimension('I')->setWidth(20);
    
    // Apply style header yang telah kita buat tadi ke masing-masing kolom header
    $sheet->getStyle('A8')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('A8:A9')->applyFromArray($style_col);
    $sheet->mergeCells('A8:A9'); // Set Merge Cell pada 

    $sheet->getStyle('B8')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('B8')->applyFromArray($style_col);

    $sheet->getStyle('B9')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('B9')->applyFromArray($style_col);

    $sheet->getStyle('C8')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('C8:D9')->applyFromArray($style_col);
    $sheet->mergeCells('C8:D9'); // Set Merge Cell pada 

    $sheet->getStyle('E8')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('E8')->applyFromArray($style_col);

    $sheet->getStyle('E9')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('E9')->applyFromArray($style_col);

    $sheet->getStyle('F8')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('F8')->applyFromArray($style_col);

    $sheet->getStyle('F9')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('F9')->applyFromArray($style_col);

    $sheet->getStyle('G8')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('G8:G9')->applyFromArray($style_col);
    $sheet->mergeCells('G8:G9'); // Set Merge Cell pada  

    $sheet->getStyle('H8')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('H8')->applyFromArray($style_col);

    $sheet->getStyle('H9')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('H9')->applyFromArray($style_col);

    $sheet->getStyle('I8')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('I8')->applyFromArray($style_col);

    $sheet->getStyle('I9')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('I9')->applyFromArray($style_col);


    $numrow = 10;  //BARUS PERTAMA UNTUK DATA, setelah header

    $sql = "
    SELECT SQL_CALC_FOUND_ROWS ".str_replace(" , ", " ", implode(", ", $aColumns))." FROM   
    $sTable
    $sWhere
    $sOrder
    ";
    
     $stmt = mysqli_prepare($koneksidb,$sql) or die ("Gagal menyiapkan statement: " . mysqli_error($koneksidb));
     mysqli_stmt_execute($stmt);
     mysqli_stmt_bind_result($stmt,$dataIdBuku,$dataKode,$dataTglEntri,$dataPengarang,$dataKodeBuku,$dataJudul,$dataDesJnsBuku,$dataSubyek,$dataPengarangNormal,$dataNamaPenerbit,$dataNmKota,$dataThTerbit,$dataNmBahasa,$dataNmAsalBuku,$dataJilid,$dataEdisi,$dataCetakan,$dataIsbn,$dataTersedia,$dataLokasi);

     $total = 0;
     $no = 1;
        while(mysqli_stmt_fetch($stmt)){ 
            $numrow_merge = $numrow;

            /***tampilkan data****/ 
            $sheet->setCellValue('A'.$numrow, $no);
            $sheet->setCellValue('B'.$numrow, $dataIdBuku);
            $sheet->setCellValue('C'.$numrow, $dataJudul);
            $sheet->setCellValue('E'.$numrow, $dataDesJnsBuku);
            $sheet->setCellValue('F'.$numrow, $dataSubyek);
            $sheet->setCellValue('G'.$numrow, $dataNamaPenerbit);
            $sheet->setCellValue('H'.$numrow, $dataNmKota);
            $sheet->setCellValue('I'.$numrow, $dataNmBahasa);

            $numrow++;

            $dataKodeBuku = kodebuku($dataKode,$dataPengarang,$dataJudul)." c.$dataKodeBuku";
            $sheet->setCellValue('B'.$numrow, $dataKodeBuku);
            $sheet->setCellValue('E'.$numrow, $dataKode);
            $sheet->setCellValue('F'.$numrow, $dataPengarangNormal);
            $sheet->setCellValue('H'.$numrow, $dataThTerbit);
            $sheet->setCellValue('I'.$numrow, $dataNmAsalBuku);

            $numrow++;
            
            $dataTersedia = desTersediaBuku($dataTersedia);
            $sheet->setCellValue('B'.$numrow, "Seri : ".$dataJilid);
            $sheet->setCellValue('C'.$numrow, "Edisi : ".$dataEdisi);
            $sheet->setCellValue('D'.$numrow, "Cetakan : ".$dataCetakan);
            $sheet->setCellValue('E'.$numrow, "ISBN : ".$dataIsbn);
            $sheet->mergeCells('E'.$numrow.':F'.$numrow); // Set Merge Cell pada  
            $sheet->setCellValue('G'.$numrow, "Status : ".$dataTersedia);
            $sheet->setCellValue('H'.$numrow, "Lokasi : ".$dataLokasi);
            $sheet->setCellValue('I'.$numrow, "Tgl Entri : ".IndonesiaTgl($dataTglEntri));

            $sheet->getStyle('A'.$numrow_merge.":I".$numrow)->applyFromArray($style_row);

            $no++;
            $numrow++;
        }

        $sheet->setCellValue('B'.$numrow, "JUMLAH TOTAL:");
        $sheet->getStyle('B'.$numrow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT); 
        $sheet->getStyle('B'.$numrow)->getFont()->setBold(TRUE); 
        $sheet->getStyle('B'.$numrow)->getFont()->setSize(13);
     
        $sheet->setCellValue('C'.$numrow, $jmlGolongan);
        $sheet->getStyle('C'.$numrow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER); 
        $sheet->getStyle('C'.$numrow)->getFont()->setBold(TRUE); 
        $sheet->getStyle('C'.$numrow)->getFont()->setSize(13);
     
        $sheet->setCellValue('D'.$numrow, "BUKU");
        $sheet->getStyle('D'.$numrow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT); 
        $sheet->getStyle('D'.$numrow)->getFont()->setBold(TRUE); 
        $sheet->getStyle('D'.$numrow)->getFont()->setSize(13);
         
        $sheet->getStyle('A'.$numrow.':I'.$numrow)->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $sheet->getStyle('A'.$numrow.':I'.$numrow)->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
     
        $numrow++;$numrow++;
     
        $sheet->setCellValue('H'.$numrow, "Dilaporkan Oleh");
        $sheet->getStyle('H'.$numrow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER); 
        $sheet->getStyle('H'.$numrow)->getFont()->setBold(TRUE); 
        $sheet->getStyle('H'.$numrow)->getFont()->setSize(12);
     
        $numrow++;$numrow++;$numrow++;
     
        $sheet->getStyle('H'.$numrow)->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);


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
header('Content-Disposition: attachment; filename="Koleksi Buku Lengkap ['.$subyek.'00].xlsx"'); // Set nama file excel nya
header('Cache-Control: max-age=0');
$writer = new Xlsx($spreadsheet);

$writer->save('php://output');
exit;
