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
$aColumns = array('idbuku', 'judul', 'tglpinjam', 'tglhrskembali','nipnis','nama','isterlambat','bsudenda');
$sTable = "(SELECT  a.idjnsbuku AS idjnsbuku, a.idbuku AS idbuku, a.judul AS judul, b.tglpinjam AS tglpinjam, b.tglhrskembali AS tglhrskembali, b.isterlambat AS isterlambat, b.bsudenda AS bsudenda, b.iskembali AS iskembali, b.tglrealkembali AS tglrealkembali, c.idjnsang AS idjnsang, c.nipnis AS nipnis, c.nama AS nama
FROM tbuku a JOIN tpinbuku b ON a.idbuku = b.idbuku JOIN ranggota c ON b.nipnis = c.nipnis) AS tpinbuku";

if ($harian != "" && $pilihan == "harian") {
    $sWhere = " WHERE tglrealkembali = '" . $harian . "' ";

} elseif ($bulan != "" && $tahun != "" && $pilihan == "bulanan") {
    $sWhere = " WHERE MONTH(tglrealkembali) = '" . $bulan . "' AND YEAR(tglrealkembali) = '" . $tahun . "' ";

} elseif ($dariTanggal != "" && $sampaiTanggal != "" && $pilihan == "custom") {
    $sWhere = " WHERE tglrealkembali >= '" . $dariTanggal . "' AND tglrealkembali <= '" . $sampaiTanggal . "' ";

}

if($anggota=="Siswa"){
    $sWhere .= " AND idjnsang = 1 ";

}else if($anggota=="Guru/Karyawan"){
    $sWhere .= " AND idjnsang = 2 ";

}

$sWhere .= "AND idjnsbuku = 4 AND iskembali=1";

    //Alignment Cells
    $sheet->getStyle('A')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('B')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
    $sheet->getStyle('E')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('F')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('G')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

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
    $sheet->setCellValue('A3', "LAPORAN ".$jnslaporan); // Set kolom  
    $sheet->getStyle('A3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->mergeCells('A3:H3'); // Set Merge Cell  
    $sheet->getStyle('A3')->getFont()->setSize(13); // Set font size  

    $sheet->setCellValue('A4', "TRANSAKSI PENGEMBALIAN BUKU TEKS");  
    $sheet->getStyle('A4')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->mergeCells('A4:H4'); // Set Merge Cell pada  
    $sheet->getStyle('A4')->getFont()->setSize(13); // Set font size 

    $sheet->setCellValue('A5', "OLEH ".strtoupper($anggota));  
    $sheet->getStyle('A5')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->mergeCells('A5:H5'); // Set Merge Cell pada  
    $sheet->getStyle('A5')->getFont()->setSize(13); // Set font size 

    if ($harian != "" && $pilihan == "harian") {
        $sheet->setCellValue('A6', "Tanggal : ".IndonesiaTgl($harian));  
    }if($bulan != "" && $tahun != "" && $pilihan == "bulanan"){
        $sheet->setCellValue('A6', "Bulan : ".namaBulanIndonesia($bulan)." ".$tahun);  
    }else if($dariTanggal != "" && $sampaiTanggal != "" && $pilihan == "custom"){
        $sheet->setCellValue('A6', "Dari Tgl: ".IndonesiaTgl($dariTanggal)."  S.D.  ". IndonesiaTgl($sampaiTanggal));  
    }

    $sheet->getStyle('A6')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->mergeCells('A6:H6'); // Set Merge Cell pada  
    $sheet->getStyle('A6')->getFont()->setBold(TRUE); // Set bold  
    $sheet->getStyle('A6')->getFont()->setSize(12); // Set font size 

// Buat header tabel 
$sheet->setCellValue('A8', "NO. URUT"); 
$sheet->setCellValue('B8', "BUKU"); 
$sheet->setCellValue('E8', "TANGGAL"); 
$sheet->setCellValue('G8', "TERLAMBAT"); 
$sheet->setCellValue('H8', "DENDA RP"); 

$sheet->setCellValue('B9', "ID BUKU"); 
$sheet->setCellValue('C9', "JUDUL"); 
$sheet->setCellValue('E9', "TANGGAL PINJAM"); 
$sheet->setCellValue('F9', "TANGGAL JADWAL KEMBALI"); 

// Apply style header yang telah kita buat tadi ke masing-masing kolom header
$sheet->getStyle('A8')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('A8:A9')->applyFromArray($style_col);
$sheet->mergeCells('A8:A9'); // Set Merge Cell pada 

$sheet->getStyle('B8')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('B8:D8')->applyFromArray($style_col);
$sheet->mergeCells('B8:D8'); // Set Merge Cell pada 

$sheet->getStyle('E8')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('E8:F8')->applyFromArray($style_col);
$sheet->mergeCells('E8:F8'); // Set Merge Cell pada 

$sheet->getStyle('G8')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('G8:G9')->applyFromArray($style_col);
$sheet->mergeCells('G8:G9'); // Set Merge Cell pada 

$sheet->getStyle('H8')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('H8:H9')->applyFromArray($style_col);
$sheet->mergeCells('H8:H9'); // Set Merge Cell pada 

$sheet->getStyle('B9')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('B9')->applyFromArray($style_col); 

$sheet->getStyle('C9')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('C9:D9')->applyFromArray($style_col);
$sheet->mergeCells('C9:D9'); // Set Merge Cell pada 

$sheet->getStyle('E9')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('E9')->applyFromArray($style_col); 

$sheet->getStyle('F9')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('F9')->applyFromArray($style_col); 


// Set width kolom
$sheet->getColumnDimension('A')->setWidth(10);
$sheet->getColumnDimension('B')->setWidth(15);
$sheet->getColumnDimension('C')->setWidth(15);
$sheet->getColumnDimension('D')->setWidth(70);
$sheet->getColumnDimension('E')->setWidth(25);
$sheet->getColumnDimension('F')->setWidth(25);
$sheet->getColumnDimension('G')->setWidth(20);
$sheet->getColumnDimension('H')->setWidth(20);

$numrow = 10;  //BARUS PERTAMA UNTUK DATA, setelah header

 //table database 
 $sql = "
 SELECT ".str_replace(" , ", " ", implode(", ", $aColumns))."
 FROM $sTable
 $sWhere
";

 $stmt = mysqli_prepare($koneksidb,$sql) or die ("Gagal menyiapkan statement: " . mysqli_error($koneksidb));
 mysqli_stmt_execute($stmt);
   
        mysqli_stmt_bind_result($stmt,$dataIdBuku,$dataJudul,$dataTglPinjam,$dataTglHrsKembali,$dataNipnis,$dataNama,$dataTerlambat,$dataDenda);

 $subJumlah = 0;
 $subDenda = 0;
 $totalDenda = 0;
 $no = 0;
$current_nipnis = "";

while(mysqli_stmt_fetch($stmt)){

    switch($dataTerlambat){
        case 0:
            $dataTerlambat = "TIDAK";
            break;
        case 1:
            $dataTerlambat = "YA";
            break;
    }

    if ($current_nipnis != $dataNipnis) {
        if ($current_nipnis != "") {
            $sheet->setCellValue('B' . $numrow, "Sub Jumlah:");
            $sheet->setCellValue('C' . $numrow, $subJumlah);
            $sheet->setCellValue('D' . $numrow, "buku");
            $sheet->setCellValue('H' . $numrow, $subDenda);

            $sheet->getStyle('B' . $numrow)->getFont()->setBold(TRUE); // Set bold  
            $sheet->getStyle('C' . $numrow)->getFont()->setBold(TRUE); // Set bold  
            $sheet->getStyle('D' . $numrow)->getFont()->setBold(TRUE); // Set bold  
            $sheet->getStyle('H' . $numrow)->getFont()->setBold(TRUE); // Set bold  
            $sheet->getStyle('A'.$numrow.':H'.$numrow)->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            
            $numrow++;
            $subJumlah = 0;
        }

        $sheet->setCellValue('B' . $numrow, "Peminjam");
        $sheet->setCellValue('C' . $numrow, $dataNipnis);
        $sheet->setCellValue('D' . $numrow, $dataNama);

        $sheet->getStyle('B' . $numrow)->getFont()->setBold(TRUE); // Set bold  
        $sheet->getStyle('C' . $numrow)->getFont()->setBold(TRUE); // Set bold  
        $sheet->getStyle('D' . $numrow)->getFont()->setBold(TRUE); // Set bold  
        $sheet->getStyle('C' . $numrow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);


        $numrow++;

        $sheet->setCellValue('A' . $numrow, ++$no);
        $sheet->setCellValue('B' . $numrow, $dataIdBuku);
        $sheet->setCellValue('C' . $numrow, $dataJudul);
        $sheet->setCellValue('E' . $numrow, IndonesiaTgl($dataTglPinjam));
        $sheet->setCellValue('F' . $numrow, IndonesiaTgl($dataTglHrsKembali));
        $sheet->setCellValue('G' . $numrow, $dataTerlambat);
        $sheet->setCellValue('H' . $numrow, $dataDenda);
        
        $sheet->getStyle('H'.$numrow)->getNumberFormat()->setFormatCode('#,##0');
        $sheet->getStyle('B' . $numrow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
        $sheet->mergeCells('C'.$numrow.':D'.$numrow); // Set Merge Cell pada 

        $subJumlah++;
        $current_nipnis = $dataNipnis;
    } else {
        $sheet->setCellValue('A' . $numrow, ++$no);
        $sheet->setCellValue('B' . $numrow, $dataIdBuku);
        $sheet->setCellValue('C' . $numrow, $dataJudul);
        $sheet->setCellValue('E' . $numrow, IndonesiaTgl($dataTglPinjam));
        $sheet->setCellValue('F' . $numrow, IndonesiaTgl($dataTglHrsKembali));
        $sheet->setCellValue('G' . $numrow, $dataTerlambat);
        $sheet->setCellValue('H' . $numrow, $dataDenda);
        
        $sheet->getStyle('H'.$numrow)->getNumberFormat()->setFormatCode('#,##0');
        $sheet->getStyle('B' . $numrow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
        $sheet->mergeCells('C'.$numrow.':D'.$numrow); // Set Merge Cell pada 


        $subJumlah++;
    }  
    $numrow++;
    $subDenda = $dataDenda;
    $totalDenda += $dataDenda;
}

$sheet->setCellValue('B' . $numrow, "Sub Jumlah:");
$sheet->setCellValue('C' . $numrow, $subJumlah);
$sheet->setCellValue('D' . $numrow, "buku");
$sheet->setCellValue('H' . $numrow, $subDenda);

$sheet->getStyle('B' . $numrow)->getFont()->setBold(TRUE); // Set bold  
$sheet->getStyle('C' . $numrow)->getFont()->setBold(TRUE); // Set bold  
$sheet->getStyle('D' . $numrow)->getFont()->setBold(TRUE); // Set bold 
$sheet->getStyle('H' . $numrow)->getFont()->setBold(TRUE); // Set bold  
$sheet->getStyle('A'.$numrow.':H'.$numrow)->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

$numrow++;


    $sheet->setCellValue('B'.$numrow, "JUMLAH TOTAL");
    $sheet->getStyle('B'.$numrow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT); 
    $sheet->getStyle('B'.$numrow)->getFont()->setBold(TRUE); 
    $sheet->getStyle('B'.$numrow)->getFont()->setSize(13);

    $sheet->setCellValue('C'.$numrow, $no); 
    $sheet->getStyle('C'.$numrow)->getFont()->setBold(TRUE); 
    $sheet->getStyle('C'.$numrow)->getFont()->setSize(13);

    $sheet->setCellValue('D'.$numrow, "BUKU");
    $sheet->getStyle('D'.$numrow)->getFont()->setBold(TRUE); 
    $sheet->getStyle('D'.$numrow)->getFont()->setSize(13);

    $sheet->setCellValue('H'.$numrow, $totalDenda); 
    $sheet->getStyle('H'.$numrow)->getFont()->setBold(TRUE); 
    $sheet->getStyle('H'.$numrow)->getFont()->setSize(13);
    $sheet->getStyle('H'.$numrow)->getNumberFormat()->setFormatCode('#,##0');

    $sheet->getStyle('A'.$numrow.':H'.$numrow)->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
    $sheet->getStyle('A'.$numrow.':H'.$numrow)->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

    $numrow++;$numrow++;

    $sheet->setCellValue('G'.$numrow, "Dilaporkan Oleh");
    $sheet->getStyle('G'.$numrow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER); 
    $sheet->getStyle('G'.$numrow)->getFont()->setBold(TRUE); 
    $sheet->getStyle('G'.$numrow)->getFont()->setSize(12);

    $numrow++;$numrow++;$numrow++;

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

    header('Content-Disposition: attachment; filename="Laporan Pengembalian Individu Buku Teks.xlsx"'); // Set nama file excel nya

header('Cache-Control: max-age=0');
$writer = new Xlsx($spreadsheet);

$writer->save('php://output');
exit;
