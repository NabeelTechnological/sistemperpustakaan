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

if ($pilihan == "triwulanan") {
    switch ($triWulan) {
        case 1:
            $bulan1 = 1;
            $bulan2 = 3;
            break;
        case 2:
            $bulan1 = 4;
            $bulan2 = 6;
            break;
        case 3:
            $bulan1 = 7;
            $bulan2 = 9;
            break;
        case 4:
            $bulan1 = 10;
            $bulan2 = 12;
            break;
    }

    if ($bulan1 == 1) {
        $sKondisi1 = "YEAR(tglentri) < $tahun1";
    }else{
        $sKondisi1 = "(MONTH(tglentri) < $bulan1 AND YEAR(tglentri) = $tahun1) OR (YEAR(tglentri) < $tahun1)";
    }
    $sKondisi2 = "MONTH(tglentri) >= $bulan1 AND MONTH(tglentri) <= $bulan2 AND YEAR(tglentri) = $tahun1";
}else if($pilihan == "tahunan"){
    $sKondisi1 = "YEAR(tglentri) < $tahun2";
    $sKondisi2 = "YEAR(tglentri) = $tahun2";
}

    $aColumns = array('kode','subyek','judul1','judul2','idbuku1','idbuku2');
    $sIndexColumn = "kode";
    $sSubQuery1 = "SELECT kode, COUNT(DISTINCT judul) AS judul, COUNT(idbuku) AS idbuku FROM tbuku WHERE $sKondisi1 GROUP BY kode";
    $sSubQuery2 = "SELECT kode, COUNT(DISTINCT judul) AS judul, COUNT(idbuku) AS idbuku FROM tbuku WHERE $sKondisi2 GROUP BY kode";
    $sTable = "(SELECT a.kode AS kode, a.subyek AS subyek, COALESCE(b.judul,0) AS judul1, COALESCE(c.judul,0) AS judul2, COALESCE(b.idbuku,0) AS idbuku1, COALESCE(c.idbuku,0) AS idbuku2 FROM ttemsubyek a LEFT JOIN ($sSubQuery1) b ON b.kode = a.kode LEFT JOIN ($sSubQuery2) c ON c.kode = a.kode GROUP BY a.kode, a.subyek ORDER BY a.kode) AS tbuku";

    //Alignment Cells
    $sheet->getStyle('A')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('F')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('J')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

    // LAPORAN PER JENIS
    $sheet->getStyle('A')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->setCellValue('A1', getNmsekolah($koneksidb) ); 
    $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);    
    $sheet->mergeCells('A1:B1'); // Set Merge Cell pada 
    $sheet->getStyle('A1')->getFont()->setSize(12); // Set font size   

    $jdl = strtoupper($pilihan);
    $sheet->setCellValue('A3',  "LAPORAN $jdl" ); // Set kolom  
    $sheet->getStyle('A3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->mergeCells('A3:K3'); // Set Merge Cell  
    $sheet->getStyle('A3')->getFont()->setSize(13); // Set font size  

    $sheet->setCellValue('A4',  "JUMLAH JUDUL DAN BUKU PERPUSTAKAAN" ); // Set kolom  
    $sheet->getStyle('A4')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->mergeCells('A4:K4'); // Set Merge Cell  
    $sheet->getStyle('A4')->getFont()->setSize(13); // Set font size  

    if ($pilihan == "triwulanan") {
        $bln1 = substr(namaBulanIndonesia($bulan1),0,3);
        $bln2 = substr(namaBulanIndonesia($bulan2),0,3);
        $bln1upper = strtoupper($bln1);
        $bln2upper = strtoupper($bln2);
        $thn1 = substr($tahun1,-2);
        $sheet->setCellValue('A5',  "MASA : $bln1upper-$bln2upper $tahun1" ); // Set kolom  

    }else if($pilihan == "tahunan"){
        $thn2 = substr($tahun1,-2);
        $sheet->setCellValue('A5',  "TAHUN : ".$tahun2 ); // Set kolom  
        
    }
    $sheet->getStyle('A5')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->mergeCells('A5:K5'); // Set Merge Cell  
    $sheet->getStyle('A5')->getFont()->setSize(13); // Set font size  

    $sheet->setCellValue('A6', "Tanggal Cetak ".indonesiaTglPanjang(date("Y-m-d"))); 
    $sheet->getStyle('A6')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->mergeCells('A6:K6'); // Set Merge Cell pada  
    $sheet->getStyle('A6')->getFont()->setBold(TRUE); // Set bold  
    $sheet->getStyle('A6')->getFont()->setSize(12); // Set font size 
    
    // Buat header tabel 
    $sheet->setCellValue('A8', "NO. URUT");  
    $sheet->setCellValue('B8', "GOLONGAN / KODE BUKU"); 
    $sheet->setCellValue('D8', "JUMLAH JUDUL"); 
    $sheet->setCellValue('H8', "JUMLAH BUKU"); 
    if ($pilihan == "triwulanan") {
        $sheet->setCellValue('D9', "Sebelum $bln1 $thn1"); 
        $sheet->setCellValue('E9', "$bln1-$bln2 $thn1"); 
        $sheet->setCellValue('F9', "Persentase"); 
        $sheet->setCellValue('G9', "Keseluruhan"); 
        $sheet->setCellValue('H9', "Sebelum $bln1 $thn1"); 
        $sheet->setCellValue('I9', "$bln1-$bln2 $thn1"); 
        $sheet->setCellValue('J9', "Persentase"); 
        $sheet->setCellValue('K9', "Keseluruhan"); 

    }else if($pilihan == "tahunan"){
        $sheet->setCellValue('D9', "S.d. 31 Des ".intval($thn2)-1); 
        $sheet->setCellValue('E9', "Tahun ini ($thn2)"); 
        $sheet->setCellValue('F9', "Persentase"); 
        $sheet->setCellValue('G9', "Keseluruhan"); 
        $sheet->setCellValue('H9', "S.d. 31 Des ".intval($thn2)-1); 
        $sheet->setCellValue('I9', "Tahun ini ($thn2)"); 
        $sheet->setCellValue('J9', "Persentase"); 
        $sheet->setCellValue('K9', "Keseluruhan"); 
    }

    // Apply style header yang telah kita buat tadi ke masing-masing kolom header
    $sheet->getStyle('A8')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('A8:A9')->applyFromArray($style_col);
    $sheet->mergeCells('A8:A9'); // Set Merge Cell pada 

    $sheet->getStyle('B8')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('B8:C9')->applyFromArray($style_col);
    $sheet->mergeCells('B8:C9'); // Set Merge Cell pada 

    $sheet->getStyle('D8')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('D8:G8')->applyFromArray($style_col);
    $sheet->mergeCells('D8:G8'); // Set Merge Cell pada 

    $sheet->getStyle('H8')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('H8:K8')->applyFromArray($style_col);
    $sheet->mergeCells('H8:K8'); // Set Merge Cell pada 

    $sheet->getStyle('D9')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('D9')->applyFromArray($style_col); 

    $sheet->getStyle('E9')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('E9')->applyFromArray($style_col);

    $sheet->getStyle('F9')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('F9')->applyFromArray($style_col); 

    $sheet->getStyle('G9')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('G9')->applyFromArray($style_col);

    $sheet->getStyle('H9')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('H9')->applyFromArray($style_col); 

    $sheet->getStyle('I9')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('I9')->applyFromArray($style_col);

    $sheet->getStyle('J9')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('J9')->applyFromArray($style_col); 

    $sheet->getStyle('K9')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('K9')->applyFromArray($style_col);


    // Set width kolom
    $sheet->getColumnDimension('A')->setWidth(10);
    $sheet->getColumnDimension('B')->setWidth(30);
    $sheet->getColumnDimension('C')->setWidth(15);
    $sheet->getColumnDimension('D')->setWidth(15);
    $sheet->getColumnDimension('E')->setWidth(15);
    $sheet->getColumnDimension('F')->setWidth(15);
    $sheet->getColumnDimension('G')->setWidth(15);
    $sheet->getColumnDimension('H')->setWidth(15);
    $sheet->getColumnDimension('I')->setWidth(15);
    $sheet->getColumnDimension('J')->setWidth(15);
    $sheet->getColumnDimension('K')->setWidth(15);

    $numrow = 10;  //BARUS PERTAMA UNTUK DATA, setelah header

    $sql = "
    SELECT ".str_replace(" , ", " ", implode(", ", $aColumns))." FROM   
    $sTable
    ";
    
     $stmt = mysqli_prepare($koneksidb,$sql) or die ("Gagal menyiapkan statement: " . mysqli_error($koneksidb));
     mysqli_stmt_execute($stmt);
     mysqli_stmt_bind_result($stmt,$dataKode,$dataSubyek,$dataJudul1,$dataJudul2,$dataIdBuku1,$dataIdBuku2);

     $jmlJudul1 = 0;
     $jmlJudul2 = 0;
     $totalJudul = 0;
     $jmlBuku1 = 0;
     $jmlBuku2 = 0;
     $totalBuku = 0;
     $no = 1;
        while(mysqli_stmt_fetch($stmt)){
            $persenJudul 		    = persentase($dataJudul2,$dataJudul1);
            $persenBuku 		    = persentase($dataIdBuku2,$dataIdBuku1);
            $jumlahJudul 		    = $dataJudul1+$dataJudul2;
            $jumlahBuku 		    = $dataIdBuku1+$dataIdBuku2;
            $golongan               = "[$dataKode] $dataSubyek";

            /***tampilkan data****/ 
            $sheet->setCellValue('A'.$numrow, $no);
            $sheet->setCellValue('B'.$numrow, $golongan);
            $sheet->mergeCells('B'.$numrow.':C'.$numrow); // Set Merge Cell pada 
            $sheet->setCellValue('D'.$numrow, $dataJudul1);
            $sheet->setCellValue('E'.$numrow, $dataJudul2);
            $sheet->setCellValue('F'.$numrow, $persenJudul);
            $sheet->setCellValue('G'.$numrow, $jumlahJudul);
            $sheet->setCellValue('H'.$numrow, $dataIdBuku1);
            $sheet->setCellValue('I'.$numrow, $dataIdBuku2);
            $sheet->setCellValue('J'.$numrow, $persenBuku);
            $sheet->setCellValue('K'.$numrow, $jumlahBuku);

            $sheet->getStyle('A'.$numrow)->applyFromArray($style_row);
            $sheet->getStyle('B'.$numrow.':C'.$numrow)->applyFromArray($style_row);
            $sheet->getStyle('D'.$numrow)->applyFromArray($style_row);
            $sheet->getStyle('E'.$numrow)->applyFromArray($style_row);
            $sheet->getStyle('F'.$numrow)->applyFromArray($style_row);
            $sheet->getStyle('G'.$numrow)->applyFromArray($style_row);
            $sheet->getStyle('H'.$numrow)->applyFromArray($style_row);
            $sheet->getStyle('I'.$numrow)->applyFromArray($style_row);
            $sheet->getStyle('J'.$numrow)->applyFromArray($style_row);
            $sheet->getStyle('K'.$numrow)->applyFromArray($style_row);

            $no++;
            $numrow++;
            $jmlJudul1 += $dataJudul1;
            $jmlJudul2 += $dataJudul2;
            $jmlBuku1 += $dataIdBuku1;
            $jmlBuku2 += $dataIdBuku2;
            $totalJudul += $jumlahJudul;
            $totalBuku += $jumlahBuku;
        }

    $sheet->setCellValue('B'.$numrow, "Jumlah");
    $sheet->getStyle('B'.$numrow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER); 
    $sheet->mergeCells('B'.$numrow.':C'.$numrow); // Set Merge Cell pada 
    $sheet->getStyle('B'.$numrow)->getFont()->setBold(TRUE); 
    $sheet->getStyle('B'.$numrow)->getFont()->setSize(13);

    $sheet->setCellValue('D'.$numrow, $jmlJudul1); 
    $sheet->getStyle('D'.$numrow)->getFont()->setBold(TRUE); 
    $sheet->getStyle('D'.$numrow)->getFont()->setSize(13);

    $sheet->setCellValue('E'.$numrow, $jmlJudul2);
    $sheet->getStyle('E'.$numrow)->getFont()->setBold(TRUE); 
    $sheet->getStyle('E'.$numrow)->getFont()->setSize(13);

    $persenTotalJudul = persentase($jmlJudul2,$jmlJudul1);
    $sheet->setCellValue('F'.$numrow, $persenTotalJudul); 
    $sheet->getStyle('F'.$numrow)->getFont()->setBold(TRUE); 
    $sheet->getStyle('F'.$numrow)->getFont()->setSize(13);

    $sheet->setCellValue('G'.$numrow, $totalJudul);
    $sheet->getStyle('G'.$numrow)->getFont()->setBold(TRUE); 
    $sheet->getStyle('G'.$numrow)->getFont()->setSize(13);

    $sheet->setCellValue('H'.$numrow, $jmlBuku1); 
    $sheet->getStyle('H'.$numrow)->getFont()->setBold(TRUE); 
    $sheet->getStyle('H'.$numrow)->getFont()->setSize(13);

    $sheet->setCellValue('I'.$numrow, $jmlBuku2);
    $sheet->getStyle('I'.$numrow)->getFont()->setBold(TRUE); 
    $sheet->getStyle('I'.$numrow)->getFont()->setSize(13);

    $persenTotalBuku = persentase($jmlBuku2,$jmlBuku1);
    $sheet->setCellValue('J'.$numrow,$persenTotalBuku); 
    $sheet->getStyle('J'.$numrow)->getFont()->setBold(TRUE); 
    $sheet->getStyle('J'.$numrow)->getFont()->setSize(13);

    $sheet->setCellValue('K'.$numrow, $totalBuku);
    $sheet->getStyle('K'.$numrow)->getFont()->setBold(TRUE); 
    $sheet->getStyle('K'.$numrow)->getFont()->setSize(13);

    $sheet->getStyle('A'.$numrow.':K'.$numrow)->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
    $sheet->getStyle('G'.$numrow)->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
    $sheet->getStyle('A'.$numrow.':K'.$numrow)->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

    $numrow++;$numrow++;

    $sheet->setCellValue('A'.$numrow, "Catatan Kondisi Buku :" ); // Set kolom  
    $sheet->getStyle('A'.$numrow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
    $sheet->mergeCells('A'.$numrow.':C'.$numrow); 
    $sheet->getStyle('A'.$numrow)->getFont()->setBold(TRUE); // Set bold  
    $sheet->getStyle('A'.$numrow)->getFont()->setSize(13); // Set font size  

    $numrow++;

    $sheet->setCellValue('A'.$numrow, "(Per Tanggal ".indonesiaTglPanjang(date("Y-m-d")).")"); // Set kolom  
    $sheet->getStyle('A'.$numrow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
    $sheet->mergeCells('A'.$numrow.':C'.$numrow); 
    $sheet->getStyle('A'.$numrow)->getFont()->setSize(12); // Set font size

    $numrow++;

    $sql = "SELECT CASE WHEN tersedia = 0 THEN 1 ELSE tersedia END AS kondisi, COUNT(idbuku) as jumlah FROM tbuku GROUP BY kondisi ORDER BY kondisi";
    
     $stmt = mysqli_prepare($koneksidb,$sql) or die ("Gagal menyiapkan statement: " . mysqli_error($koneksidb));
     mysqli_stmt_execute($stmt);
     mysqli_stmt_bind_result($stmt,$dataTersedia,$dataJumlah);

     $no = 1;
     $total = 0;
     while(mysqli_stmt_fetch($stmt)){
        $sheet->setCellValue('A'.$numrow, $no++);

        switch($dataTersedia){
            case '1':
                $dataTersedia = "Baik";
              break;
            case '2':
                $dataTersedia = "Rusak";
              break;
            case '3':
                $dataTersedia = "Hilang";
              break;
            }
        $sheet->setCellValue('B'.$numrow, "Jumlah Buku ".$dataTersedia);
        $sheet->setCellValue('C'.$numrow, $dataJumlah);
        
        $sheet->getStyle('A'.$numrow)->applyFromArray($style_row);
        $sheet->getStyle('B'.$numrow)->applyFromArray($style_row);
        $sheet->getStyle('C'.$numrow)->applyFromArray($style_row);
        $numrow++;
        $total += $dataJumlah;
    }

    $sheet->setCellValue('B'.$numrow, "Jumlah Keseluruhan"); // Set kolom  
    $sheet->getStyle('B'.$numrow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('B'.$numrow)->getFont()->setBold(TRUE); // Set bold  
    $sheet->getStyle('B'.$numrow)->getFont()->setSize(12); // Set font size  

    $sheet->setCellValue('C'.$numrow, $total); // Set kolom  
    $sheet->getStyle('C'.$numrow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('C'.$numrow)->getFont()->setBold(TRUE); // Set bold  
    $sheet->getStyle('C'.$numrow)->getFont()->setSize(12); // Set font size  

    $sheet->getStyle('A'.$numrow.':C'.$numrow)->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
    $sheet->getStyle('A'.$numrow.':C'.$numrow)->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

    $numrow++;$numrow++;

    $sheet->setCellValue('J'.$numrow, "Dilaporkan Oleh");
    $sheet->getStyle('J'.$numrow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER); 
    $sheet->mergeCells('J'.$numrow.':K'.$numrow); // Set Merge Cell pada 
    $sheet->getStyle('J'.$numrow)->getFont()->setBold(TRUE); 
    $sheet->getStyle('J'.$numrow)->getFont()->setSize(12);

    $numrow++;$numrow++;$numrow++;

    $sheet->getStyle('J'.$numrow.':K'.$numrow)->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
    $sheet->mergeCells('J'.$numrow.':K'.$numrow); // Set Merge Cell pada 


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
if ($pilihan=="triwulanan") {
    header('Content-Disposition: attachment; filename="Laporan Perkembangan Buku Triwulanan.xlsx"'); // Set nama file excel nya
}else if($pilihan=="tahunan"){
    header('Content-Disposition: attachment; filename="Laporan Perkembangan Buku Tahunan.xlsx"'); // Set nama file excel nya
}
header('Cache-Control: max-age=0');
$writer = new Xlsx($spreadsheet);

$writer->save('php://output');
exit;
