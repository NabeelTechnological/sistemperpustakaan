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
if($tampil == "tampilPerKlasifikasi"){
    //kolom
    $aColumns = array('kode','subyek','jumlah');
    //nama table database
    $sTable = "ttemsubyek";
    //primary key
    $sIndexColumn = "kode";
    $sOrder = "ORDER BY kode";  

}else if($tampil == "tampilPerJenis"){
    //kolom
    $aColumns = array('desjnsbuku');

    if($harian != "" && $pilihan == "harian"){
        $sWhereDefault = " AND c.tglpinjam = '".$harian."' ";
    }else if($bulan != "" && $tahun != "" && $pilihan == "bulanan"){
        $sWhereDefault = " AND MONTH(c.tglpinjam) = '".$bulan."' AND YEAR(c.tglpinjam) = '".$tahun."'  ";
    }else if($dariTanggal != "" && $sampaiTanggal != "" && $pilihan == "custom"){
        $sWhereDefault = " AND c.tglpinjam >= '".$dariTanggal."' AND c.tglpinjam <= '".$sampaiTanggal."' ";
    } 

    //nama table database
    $sTable = " (SELECT a.desjnsbuku AS desjnsbuku, c.tglpinjam AS tglpinjam, a.idjnsbuku AS idjnsbuku FROM rjnsbuku a LEFT JOIN tbuku b ON a.idjnsbuku = b.idjnsbuku LEFT JOIN tpinbuku c ON b.idbuku = c.idbuku $sWhereDefault) AS tpinbuku ";
    //primary key
    $sIndexColumn = "desjnsbuku";
    $sGroup = "GROUP BY desjnsbuku";
}

$sheet->setCellValue('A1', getNmsekolah($koneksidb) ); 
$sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);    
$sheet->getStyle('A1')->getFont()->setSize(12); // Set font size

if($tampil == "tampilPerKlasifikasi"){
    // LAPORAN PER KLASIIFIKASI
    if($harian != "" && $pilihan == "harian"){
        $jdl = "HARIAN"; 
    }else if($bulan != "" && $tahun != "" && $pilihan == "bulanan"){
        $jdl = "BULANAN";
    }else if($dariTanggal != "" && $sampaiTanggal != "" && $pilihan == "custom"){
        $jdl = "MASA";
    }

    $sheet->setCellValue('A3',  "LAPORAN $jdl JUMLAH PEMINJAMAN BUKU KOLEKSI PERPUSTAKAAN" ); // Set kolom  
    $sheet->getStyle('A3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->mergeCells('A3:E3'); // Set Merge Cell  
    $sheet->getStyle('A3')->getFont()->setSize(13); // Set font size  

    $sheet->setCellValue('A4', "BERDASARKAN KLASIFIKASI BUKU");  
    $sheet->getStyle('A4')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->mergeCells('A4:E4'); // Set Merge Cell pada  
    $sheet->getStyle('A4')->getFont()->setSize(13); // Set font size 


}else if($tampil == "tampilPerJenis"){
// LAPORAN PER JENIS
    $sheet->setCellValue('A3',  "REKAPITULASI PEMINJAMAN" ); // Set kolom  
    $sheet->getStyle('A3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->mergeCells('A3:D3'); // Set Merge Cell  
    $sheet->getStyle('A3')->getFont()->setSize(13); // Set font size  

    $sheet->setCellValue('A4', "PER JENIS BUKU");  
    $sheet->getStyle('A4')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->mergeCells('A4:D4'); // Set Merge Cell pada  
    $sheet->getStyle('A4')->getFont()->setSize(13); // Set font size 
}

    if($harian != "" && $pilihan == "harian"){
        $sheet->setCellValue('A5', "Tanggal : ".IndonesiaTgl($harian));  
    }else if($bulan != "" && $tahun != "" && $pilihan == "bulanan"){
        $sheet->setCellValue('A5', "Bulan : ".namaBulanIndonesia($bulan)." ".$tahun);  
    }else if($dariTanggal != "" && $sampaiTanggal != "" && $pilihan == "custom"){
        $sheet->setCellValue('A5', "Dari Tanggal: ".IndonesiaTgl($dariTanggal)."  S.D.  ". IndonesiaTgl($sampaiTanggal));  
    }
    
if($tampil == "tampilPerKlasifikasi"){

$sheet->getStyle('A5')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
$sheet->mergeCells('A5:E5'); // Set Merge Cell pada  
$sheet->getStyle('A5')->getFont()->setBold(TRUE); // Set bold  
$sheet->getStyle('A5')->getFont()->setSize(12); // Set font size 

// Buat header tabel 
$sheet->setCellValue('B7', "KD KLASIFIKASI"); 
$sheet->setCellValue('C7', "DESKRIPSI"); 
$sheet->setCellValue('D7', "JUMLAH PEMINJAMAN"); 

// Apply style header yang telah kita buat tadi ke masing-masing kolom header
$sheet->getStyle('B7')->applyFromArray($style_col);
$sheet->getStyle('B')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
$sheet->getStyle('B7')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('C7')->applyFromArray($style_col);
$sheet->getStyle('C')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
$sheet->getStyle('C7')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('D7')->applyFromArray($style_col);
$sheet->getStyle('D')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('D7')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

// Set width kolom
$sheet->getColumnDimension('B')->setWidth(15);
$sheet->getColumnDimension('C')->setWidth(40);
$sheet->getColumnDimension('D')->setWidth(35);

$numrow = 8;  //BARUS PERTAMA UNTUK DATA, setelah header

 //table database 
 $sql = "SELECT ".str_replace(" , ", " ", implode(", ", $aColumns))." FROM   
 $sTable
 $sOrder
";

 $stmt = mysqli_prepare($koneksidb,$sql) or die ("Gagal menyiapkan statement: " . mysqli_error($koneksidb));
 mysqli_stmt_execute($stmt);
 mysqli_stmt_bind_result($stmt,$dataKode,$dataSubyek,$dataJml);

 $total = 0;
    while(mysqli_stmt_fetch($stmt)){

        /***tampilkan data****/ 
        $sheet->setCellValue('B'.$numrow, $dataKode);
        $sheet->setCellValue('C'.$numrow, $dataSubyek);
        $sheet->setCellValue('D'.$numrow, $dataJml);

        $sheet->getStyle('B'.$numrow)->applyFromArray($style_row);
        $sheet->getStyle('C'.$numrow)->applyFromArray($style_row);
        $sheet->getStyle('D'.$numrow)->applyFromArray($style_row);

        $numrow++;
    }

    $numrow++;$numrow++;

    $sheet->setCellValue('E'.$numrow, "Dilaporkan Oleh");
    $sheet->getStyle('E'.$numrow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER); 
    $sheet->getStyle('E'.$numrow)->getFont()->setBold(TRUE); 
    $sheet->getStyle('E'.$numrow)->getFont()->setSize(12);

    $numrow++;$numrow++;$numrow++;

    $sheet->getStyle('E'.$numrow)->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
    $sheet->getColumnDimension('E')->setWidth(20);

}else if($tampil == "tampilPerJenis"){
    $sheet->getStyle('A5')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->mergeCells('A5:D5'); // Set Merge Cell pada  
    $sheet->getStyle('A5')->getFont()->setBold(TRUE); // Set bold  
    $sheet->getStyle('A5')->getFont()->setSize(12); // Set font size 

    // Buat header tabel 
    $sheet->setCellValue('B7', "DESKRIPSI"); 
    $sheet->setCellValue('C7', "JUMLAH PEMINJAMAN"); 
    
    // Apply style header yang telah kita buat tadi ke masing-masing kolom header
    $sheet->getStyle('B7')->applyFromArray($style_col);
    $sheet->getStyle('B')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
    $sheet->getStyle('B7')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('C7')->applyFromArray($style_col);
    $sheet->getStyle('C')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('C7')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

    // Set width kolom
    $sheet->getColumnDimension('B')->setWidth(40);
    $sheet->getColumnDimension('C')->setWidth(35);

    $numrow = 8;  //BARUS PERTAMA UNTUK DATA, setelah header

     //table database 
     $sql = "SELECT ".str_replace(" , ", " ", implode(", ", $aColumns)).",COUNT(tglpinjam) AS jmlpinjam FROM   
     $sTable
     $sGroup
 ";
    
     $stmt = mysqli_prepare($koneksidb,$sql) or die ("Gagal menyiapkan statement: " . mysqli_error($koneksidb));
     mysqli_stmt_execute($stmt);
     mysqli_stmt_bind_result($stmt,$dataDesJnsBuku,$dataJmlPinjam);

     $total = 0;
        while(mysqli_stmt_fetch($stmt)){
            $total += $dataJmlPinjam;

            /***tampilkan data****/ 
            $sheet->setCellValue('B'.$numrow, $dataDesJnsBuku);
            $sheet->setCellValue('C'.$numrow, $dataJmlPinjam);

            $sheet->getStyle('B'.$numrow)->applyFromArray($style_row);
            $sheet->getStyle('C'.$numrow)->applyFromArray($style_row);

            $numrow++;
        }

        $sheet->setCellValue('B'.$numrow, "JUMLAH");
        $sheet->getStyle('B'.$numrow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER); 
        $sheet->getStyle('B'.$numrow)->getFont()->setBold(TRUE); 
        $sheet->getStyle('B'.$numrow)->getFont()->setSize(13);
    
        $sheet->setCellValue('C'.$numrow, $total);
        $sheet->getStyle('C'.$numrow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER); 
        $sheet->getStyle('C'.$numrow)->getFont()->setBold(TRUE); 
        $sheet->getStyle('C'.$numrow)->getFont()->setSize(13);

        $sheet->getStyle('B'.$numrow)->applyFromArray($style_row);
        $sheet->getStyle('C'.$numrow)->applyFromArray($style_row);

        $numrow++;$numrow++;

        $sheet->setCellValue('D'.$numrow, "Dilaporkan Oleh");
        $sheet->getStyle('D'.$numrow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER); 
        $sheet->getStyle('D'.$numrow)->getFont()->setBold(TRUE); 
        $sheet->getStyle('D'.$numrow)->getFont()->setSize(12);
    
        $numrow++;$numrow++;$numrow++;
    
        $sheet->getStyle('D'.$numrow)->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $sheet->getColumnDimension('D')->setWidth(20);
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

if($tampil=="tampilPerKlasifikasi"){
    header('Content-Disposition: attachment; filename="Rekap Peminjaman Per Klasifikasi Koleksi Pustaka.xlsx"'); // Set nama file excel nya

}else if($tampil==="tampilPerJenis"){
    header('Content-Disposition: attachment; filename="Rekap Peminjaman Per Jenis Koleksi Pustaka.xlsx"'); // Set nama file excel nya

}
header('Cache-Control: max-age=0');
$writer = new Xlsx($spreadsheet);

$writer->save('php://output');
exit;
