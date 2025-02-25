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
$aColumns = array('idbuku', 'judul', 'tglpinjam', 'tglhrskembali','nipnis','nama');
$sTable = "(SELECT  a.idjnsbuku AS idjnsbuku, a.idbuku AS idbuku, a.judul AS judul, b.tglpinjam AS tglpinjam, b.tglhrskembali AS tglhrskembali, c.idjnsang AS idjnsang, c.jnskel AS jnskel, c.idkelas AS idkelas, c.nipnis AS nipnis, c.nama AS nama
FROM tbuku a JOIN tpinbuku b ON a.idbuku = b.idbuku JOIN ranggota c ON b.nipnis = c.nipnis) AS tpinbuku";

if ($harian != "" && $pilihan == "harian") {
    $sWhere = " WHERE tglpinjam = '" . $harian . "' ";

} elseif ($bulan != "" && $tahun != "" && $pilihan == "bulanan") {
    $sWhere = " WHERE MONTH(tglpinjam) = '" . $bulan . "' AND YEAR(tglpinjam) = '" . $tahun . "' ";

} elseif ($dariTanggal != "" && $sampaiTanggal != "" && $pilihan == "custom") {
    $sWhere = " WHERE tglpinjam >= '" . $dariTanggal . "' AND tglpinjam <= '" . $sampaiTanggal . "' ";

}

if($anggota=="Siswa"){
    $sWhere .= " AND idjnsang = 1 ";

}else if($anggota=="Guru/Karyawan"){
    $sWhere .= " AND idjnsang = 2 ";

}

if ($jnskelamin=="Laki-laki") {
    $sWhere .= " AND jnskel = 'L' ";

}else if ($jnskelamin=="Perempuan") {
    $sWhere .= " AND jnskel = 'P' ";
    
}

if ($cakupan == "Per Kelas") {
    $sWhere .= " AND idkelas = '$kelas' ";

}

$sWhere .= "AND idjnsbuku  <4";

    //Alignment Cells
    $sheet->getStyle('A')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('B')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
    $sheet->getStyle('E')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('F')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

$sheet->setCellValue('A1', getNmsekolah($koneksidb) ); 
$sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);    
$sheet->getStyle('A1')->getFont()->setSize(12); // Set font size

if ($harian != "" && $pilihan == "harian") {
    $jnslaporan = "HARIAN";
} else{
    $jnslaporan = "BULANAN";
}

    $sheet->setCellValue('A3', "LAPORAN ".$jnslaporan); // Set kolom  
    $sheet->getStyle('A3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->mergeCells('A3:F3'); // Set Merge Cell  
    $sheet->getStyle('A3')->getFont()->setSize(13); // Set font size  

    $sheet->setCellValue('A4', "TRANSAKSI PEMINJAMAN BUKU KOLEKSI PERPUSTAKAAN");  
    $sheet->getStyle('A4')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->mergeCells('A4:F4'); // Set Merge Cell pada  
    $sheet->getStyle('A4')->getFont()->setSize(13); // Set font size 

    $sheet->setCellValue('A5', "OLEH ".strtoupper($anggota).", JENIS KELAMIN ".strtoupper($jnskelamin));  
    $sheet->getStyle('A5')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->mergeCells('A5:F5'); // Set Merge Cell pada  
    $sheet->getStyle('A5')->getFont()->setSize(13); // Set font size 

    if ($cakupan == "Per Kelas") {
        $jdlKelas = "KELAS ".$kelas;
    
    }else{
        $jdlKelas = "SEMUA KELAS";
    }

    $sheet->setCellValue('A6', strtoupper($jdlKelas));  
    $sheet->getStyle('A6')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->mergeCells('A6:F6'); // Set Merge Cell pada  
    $sheet->getStyle('A6')->getFont()->setSize(13); // Set font size 

    if ($harian != "" && $pilihan == "harian") {
        $sheet->setCellValue('A7', "Tanggal : ".IndonesiaTgl($harian));  
    }if($bulan != "" && $tahun != "" && $pilihan == "bulanan"){
        $sheet->setCellValue('A7', "Bulan : ".namaBulanIndonesia($bulan)." ".$tahun);  
    }else if($dariTanggal != "" && $sampaiTanggal != "" && $pilihan == "custom"){
        $sheet->setCellValue('A7', "Dari Tgl: ".IndonesiaTgl($dariTanggal)."  S.D.  ". IndonesiaTgl($sampaiTanggal));  
    }

    $sheet->getStyle('A7')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->mergeCells('A7:F7'); // Set Merge Cell pada  
    $sheet->getStyle('A7')->getFont()->setBold(TRUE); // Set bold  
    $sheet->getStyle('A7')->getFont()->setSize(12); // Set font size 

// Buat header tabel 
$sheet->setCellValue('A9', "NO. URUT"); 
$sheet->setCellValue('B9', "BUKU"); 
$sheet->setCellValue('E9', "TANGGAL PINJAM"); 
$sheet->setCellValue('F9', "TANGGAL JADWAL KEMBALI"); 

$sheet->setCellValue('B10', "ID BUKU"); 
$sheet->setCellValue('C10', "JUDUL"); 

// Apply style header yang telah kita buat tadi ke masing-masing kolom header
$sheet->getStyle('A9')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('A9:A10')->applyFromArray($style_col);
$sheet->mergeCells('A9:A10'); // Set Merge Cell pada 

$sheet->getStyle('B9')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('B9:D9')->applyFromArray($style_col);
$sheet->mergeCells('B9:D9'); // Set Merge Cell pada 

$sheet->getStyle('E9')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('E9:E10')->applyFromArray($style_col);
$sheet->mergeCells('E9:E10'); // Set Merge Cell pada 

$sheet->getStyle('F9')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('F9:F10')->applyFromArray($style_col);
$sheet->mergeCells('F9:F10'); // Set Merge Cell pada 

$sheet->getStyle('B10')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('B10')->applyFromArray($style_col); 

$sheet->getStyle('C10')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('C10:D10')->applyFromArray($style_col);
$sheet->mergeCells('C10:D10'); // Set Merge Cell pada 


// Set width kolom
$sheet->getColumnDimension('A')->setWidth(10);
$sheet->getColumnDimension('B')->setWidth(15);
$sheet->getColumnDimension('C')->setWidth(15);
$sheet->getColumnDimension('D')->setWidth(70);
$sheet->getColumnDimension('E')->setWidth(25);
$sheet->getColumnDimension('F')->setWidth(25);

$numrow = 11;  //BARUS PERTAMA UNTUK DATA, setelah header

 //table database 
 $sql = "
 SELECT ".str_replace(" , ", " ", implode(", ", $aColumns))."
 FROM $sTable
 $sWhere
";

 $stmt = mysqli_prepare($koneksidb,$sql) or die ("Gagal menyiapkan statement: " . mysqli_error($koneksidb));
 mysqli_stmt_execute($stmt);
   
        mysqli_stmt_bind_result($stmt,$dataIdBuku,$dataJudul,$dataTglPinjam,$dataTglHrsKembali,$dataNipnis,$dataNama);

 $subJumlah = 0;
 $no = 0;
$current_nipnis = "";

while(mysqli_stmt_fetch($stmt)){
    if ($current_nipnis != $dataNipnis) {
        if ($current_nipnis != "") {
            $sheet->setCellValue('B' . $numrow, "Sub Jumlah:");
            $sheet->setCellValue('C' . $numrow, $subJumlah);
            $sheet->setCellValue('D' . $numrow, "buku");

            $sheet->getStyle('B' . $numrow)->getFont()->setBold(TRUE); // Set bold  
            $sheet->getStyle('C' . $numrow)->getFont()->setBold(TRUE); // Set bold  
            $sheet->getStyle('D' . $numrow)->getFont()->setBold(TRUE); // Set bold  
            $sheet->getStyle('A'.$numrow.':F'.$numrow)->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            
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

        $sheet->getStyle('B' . $numrow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
        $sheet->mergeCells('C'.$numrow.':D'.$numrow); // Set Merge Cell pada 


        $subJumlah++;
    }  
    $numrow++;
}

$sheet->setCellValue('B' . $numrow, "Sub Jumlah:");
$sheet->setCellValue('C' . $numrow, $subJumlah);
$sheet->setCellValue('D' . $numrow, "buku");

$sheet->getStyle('B' . $numrow)->getFont()->setBold(TRUE); // Set bold  
$sheet->getStyle('C' . $numrow)->getFont()->setBold(TRUE); // Set bold  
$sheet->getStyle('D' . $numrow)->getFont()->setBold(TRUE); // Set bold  
$sheet->getStyle('A'.$numrow.':F'.$numrow)->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

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

    $sheet->getStyle('A'.$numrow.':F'.$numrow)->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
    $sheet->getStyle('A'.$numrow.':F'.$numrow)->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

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

    header('Content-Disposition: attachment; filename="Laporan Peminjaman Individu Buku Koleksi Perpustakaan.xlsx"'); // Set nama file excel nya

header('Cache-Control: max-age=0');
$writer = new Xlsx($spreadsheet);

$writer->save('php://output');
exit;
