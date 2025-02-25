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
$aColumnsTambahan = ", COUNT(DISTINCT judul) AS judul, COUNT(idbuku) as eksemplar";
 
 if ($rekap == "rekapjenisbuku") {
     $aColumns = array( 'desjnsbuku');
     $sGroup = "GROUP BY desjnsbuku";
     $sTable = "vw_tbuku";
     $sOrder = "ORDER BY idjnsbuku";
}else if($rekap == "rekapkodeklasifikasi"){
    $aColumns = array('kode','subyek');
    $sGroup = "GROUP BY kode, subyek";
    $sTable = "(SELECT a.kode AS kode, a.subyek AS subyek, b.judul AS judul, b.idbuku AS idbuku FROM ttemsubyek a LEFT JOIN tbuku b ON b.kode LIKE CONCAT(SUBSTRING(a.kode, 1, 1), '%')) AS tbuku";
    $sOrder = "";
}else if ($rekap == "rekapbukureferensi") {
    $aColumns = array('kode','subyek');
    $sGroup = "GROUP BY kode, subyek";
    $sWhereDefault = "AND b.idjnsbuku = 3";
    $sTable = "(SELECT a.kode AS kode, a.subyek AS subyek, b.judul AS judul, b.idbuku AS idbuku FROM ttemsubyek a LEFT JOIN tbuku b ON b.kode LIKE CONCAT(SUBSTRING(a.kode, 1, 1), '%') $sWhereDefault) AS tbuku";
    $sOrder = "";
}

    //Alignment Cells
    $sheet->getStyle('F')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

$sheet->setCellValue('A1', getNmsekolah($koneksidb) ); 
$sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);    
$sheet->getStyle('A1')->getFont()->setSize(12); // Set font size

    if ($rekap == "rekapjenisbuku") {
        $jdl = "REKAPITULASI BUKU BERDASARKAN JENIS BUKU";
    }else if($rekap == "rekapkodeklasifikasi"){
        $jdl = "REKAPITULASI SEMUA BUKU BERDASARKAN KODE KLASIFIKASI";
    }else if ($rekap == "rekapbukureferensi") {
        $jdl = "REKAPITULASI BUKU REFERENSI BERDASARKAN KODE KLASIFIKASI";
    }

    $sheet->setCellValue('A3', $jdl); // Set kolom  
    $sheet->getStyle('A3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->mergeCells('A3:E3'); // Set Merge Cell  
    $sheet->getStyle('A3')->getFont()->setSize(13); // Set font size  

    $sheet->setCellValue('A4', "KOLEKSI PERPUSTAKAAN");  
    $sheet->getStyle('A4')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->mergeCells('A4:E4'); // Set Merge Cell pada  
    $sheet->getStyle('A4')->getFont()->setSize(13); // Set font size 

    $sheet->setCellValue('A5', "Dicetak tanggal ".indonesiaTglPanjang(date("Y-m-d")));  
    $sheet->getStyle('A5')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->mergeCells('A5:E5'); // Set Merge Cell pada  
    $sheet->getStyle('A5')->getFont()->setBold(TRUE); // Set bold  
    $sheet->getStyle('A5')->getFont()->setSize(12); // Set font size 

// Buat header tabel 
$sheet->setCellValue('B7', "NO. URUT"); 
$sheet->setCellValue('C7', "URAIAN"); 
$sheet->setCellValue('D7', "JUMLAH"); 
$sheet->setCellValue('D8', "BERDASARKAN JUDUL"); 
$sheet->setCellValue('E8', "BERDASARKAN EKSEMPLAR"); 

// Apply style header yang telah kita buat tadi ke masing-masing kolom header
$sheet->getStyle('B7')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('B7:B8')->applyFromArray($style_col);
$sheet->mergeCells('B7:B8'); // Set Merge Cell pada 

$sheet->getStyle('C7')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('C7:C8')->applyFromArray($style_col);
$sheet->mergeCells('C7:C8'); // Set Merge Cell pada 

$sheet->getStyle('D7')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('D7:E7')->applyFromArray($style_col);
$sheet->mergeCells('D7:E7'); // Set Merge Cell pada 

$sheet->getStyle('D8')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('D8')->applyFromArray($style_col); 

$sheet->getStyle('E8')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('E8')->applyFromArray($style_col);

// Set width kolom
$sheet->getColumnDimension('B')->setWidth(10);
$sheet->getColumnDimension('C')->setWidth(40);
$sheet->getColumnDimension('D')->setWidth(25);
$sheet->getColumnDimension('E')->setWidth(25);

$numrow = 9;  //BARUS PERTAMA UNTUK DATA, setelah header

 //table database 
 $sql = "
 SELECT ".str_replace(" , ", " ", implode(", ", $aColumns))."
 $aColumnsTambahan
 FROM $sTable
 $sGroup
 $sOrder
";

 $stmt = mysqli_prepare($koneksidb,$sql) or die ("Gagal menyiapkan statement: " . mysqli_error($koneksidb));
 mysqli_stmt_execute($stmt);
    if ($rekap == "rekapjenisbuku") {
        mysqli_stmt_bind_result($stmt,$dataDesJnsBuku,$dataJudul,$dataEksemplar);
    }else {
        mysqli_stmt_bind_result($stmt,$dataKode,$dataSubyek,$dataJudul,$dataEksemplar);
    }

 $totalJudul = 0;
 $totalEksemplar = 0;
 $no = 1;
    while(mysqli_stmt_fetch($stmt)){
        $totalJudul += $dataJudul;
        $totalEksemplar += $dataEksemplar;

        if ($rekap == "rekapjenisbuku") {
            $dataUraian = $dataDesJnsBuku;
        }else { 
            $dataUraian = "[$dataKode] $dataSubyek";
        }

        /***tampilkan data****/ 
        $sheet->setCellValue('B'.$numrow, $no++);
        $sheet->setCellValue('C'.$numrow, $dataUraian);
        $sheet->setCellValue('D'.$numrow, $dataJudul);
        $sheet->setCellValue('E'.$numrow, $dataEksemplar);

        $sheet->getStyle('B'.$numrow)->applyFromArray($style_row);
        $sheet->getStyle('C'.$numrow)->applyFromArray($style_row);
        $sheet->getStyle('D'.$numrow)->applyFromArray($style_row);
        $sheet->getStyle('E'.$numrow)->applyFromArray($style_row);

        $numrow++;
    }

    $sheet->setCellValue('C'.$numrow, "JUMLAH TOTAL");
    $sheet->getStyle('C'.$numrow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT); 
    $sheet->getStyle('C'.$numrow)->getFont()->setBold(TRUE); 
    $sheet->getStyle('C'.$numrow)->getFont()->setSize(13);

    $sheet->setCellValue('D'.$numrow, $totalJudul); 
    $sheet->getStyle('D'.$numrow)->getFont()->setBold(TRUE); 
    $sheet->getStyle('D'.$numrow)->getFont()->setSize(13);

    $sheet->setCellValue('E'.$numrow, $totalEksemplar);
    $sheet->getStyle('E'.$numrow)->getFont()->setBold(TRUE); 
    $sheet->getStyle('E'.$numrow)->getFont()->setSize(13);

    $sheet->getStyle('B'.$numrow.':E'.$numrow)->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
    $sheet->getStyle('B'.$numrow.':E'.$numrow)->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

    $numrow++;$numrow++;

    $sheet->setCellValue('F'.$numrow, "Dilaporkan Oleh");
    $sheet->getStyle('F'.$numrow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER); 
    $sheet->getStyle('F'.$numrow)->getFont()->setBold(TRUE); 
    $sheet->getStyle('F'.$numrow)->getFont()->setSize(12);

    $numrow++;$numrow++;$numrow++;

    $sheet->getStyle('F'.$numrow)->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
    $sheet->getColumnDimension('F')->setWidth(20);



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

if($rekap=="rekapjenisbuku"){
    header('Content-Disposition: attachment; filename="Rekap Koleksi Buku Jenis Buku.xlsx"'); // Set nama file excel nya

}else if($rekap==="rekapkodeklasifikasi"){
    header('Content-Disposition: attachment; filename="Rekap Koleksi Buku Lengkap.xlsx"'); // Set nama file excel nya

}else if($rekap==="rekapbukureferensi"){
    header('Content-Disposition: attachment; filename="Rekap Koleksi Buku Referensi.xlsx"'); // Set nama file excel nya
}

header('Cache-Control: max-age=0');
$writer = new Xlsx($spreadsheet);

$writer->save('php://output');
exit;
