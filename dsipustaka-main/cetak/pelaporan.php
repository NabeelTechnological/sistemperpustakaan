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

// LAPORAN HARIAN
if($harian != "" && $pilihan == "harian"){

    $sheet->setCellValue('A1', getNmsekolah($koneksidb) ); 
    $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);    
    $sheet->getStyle('A1')->getFont()->setSize(12); // Set font size   

    $sheet->setCellValue('A3',  "LAPORAN HARIAN" ); // Set kolom  
    $sheet->getStyle('A3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->mergeCells('A3:G3'); // Set Merge Cell  
    $sheet->getStyle('A3')->getFont()->setSize(13); // Set font size  

if($tampil == "tampilAnggota"){

    $sheet->setCellValue('A4', "LAPORAN KUNJUNGAN ANGGOTA PERPUSTAKAAN");  
    $sheet->getStyle('A4')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->mergeCells('A4:G4'); // Set Merge Cell pada  
    $sheet->getStyle('A4')->getFont()->setSize(13); // Set font size 

    $sheet->setCellValue('A5', "Tanggal : ".IndonesiaTgl($harian));  
    $sheet->getStyle('A5')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->mergeCells('A5:G5'); // Set Merge Cell pada  
    $sheet->getStyle('A5')->getFont()->setBold(TRUE); // Set bold  
    $sheet->getStyle('A5')->getFont()->setSize(12); // Set font size 

    // Buat header tabel
    $sheet->setCellValue('B7', "NO");  
    $sheet->setCellValue('C7', "ID ANGGOTA"); 
    $sheet->setCellValue('D7', "NAMA");  

    // Apply style header yang telah kita buat tadi ke masing-masing kolom header
    $sheet->getStyle('B7')->applyFromArray($style_col);
    $sheet->getStyle('B')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('C7')->applyFromArray($style_col);
    $sheet->getStyle('C')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT); 
    $sheet->getStyle('C7')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER); 
    $sheet->getStyle('D7:F7')->applyFromArray($style_col);
    $sheet->mergeCells('D7:F7');

    // Set width kolom
    $sheet->getColumnDimension('B')->setWidth(15); 
    $sheet->getColumnDimension('C')->setWidth(20);
    $sheet->getColumnDimension('D')->setWidth(30); 
    $sheet->getColumnDimension('E')->setWidth(10); 
    $sheet->getColumnDimension('F')->setWidth(20); 

    $numrow = 8;  //BARUS PERTAMA UNTUK DATA, setelah header

    //table database 
    $sql = mysqli_query($koneksidb,"SELECT a.tglkunjung AS tglkunjung, a.stkunjung AS stkunjung, a.nipnis AS nipnis, b.idjnsang AS idjnsang, b.nama AS nama FROM tkunjung a LEFT JOIN ranggota b ON a.nipnis = b.nipnis WHERE tglkunjung = '$harian' AND idjnsang=1 AND stkunjung = 'A'");

    $jmlSiswa = mysqli_num_rows($sql);
    if($jmlSiswa>0){
        $sheet->setCellValue('B'.$numrow, "Siswa");
        $sheet->getStyle('B'.$numrow)->getFont()->setBold(TRUE); 
        $sheet->getStyle('B'.$numrow)->getFont()->setSize(12); 

        $numrow++;
        $no = 1;
        while($q = mysqli_fetch_assoc($sql)){
            $id_anggota = $q['nipnis'];
            $nama       = $q['nama'];

            /***tampilkan data****/ 
            $sheet->setCellValue('B'.$numrow, $no.".");
            $sheet->setCellValue('C'.$numrow, $id_anggota);
            $sheet->setCellValue('D'.$numrow, $nama);


            $sheet->mergeCells('D'.$numrow.':F'.$numrow);
            $sheet->getStyle('B'.$numrow)->applyFromArray($style_row);
            $sheet->getStyle('C'.$numrow)->applyFromArray($style_row);
            $sheet->getStyle('D'.$numrow.':F'.$numrow)->applyFromArray($style_row); 

            $numrow++;
            $no++;
        }

        $sheet->setCellValue('D'.$numrow, "Sub Jumlah : ");
        $sheet->getStyle('D'.$numrow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT); 
        $sheet->getStyle('D'.$numrow)->getFont()->setBold(TRUE); 
        $sheet->getStyle('D'.$numrow)->getFont()->setSize(12);

        $sheet->setCellValue('E'.$numrow, $jmlSiswa);
        $sheet->getStyle('E'.$numrow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT); 
        $sheet->getStyle('E'.$numrow)->getFont()->setBold(TRUE); 
        $sheet->getStyle('E'.$numrow)->getFont()->setSize(12);

        $numrow++;
     }

    $sql = mysqli_query($koneksidb,"SELECT a.tglkunjung AS tglkunjung, a.stkunjung AS stkunjung, a.nipnis AS nipnis, b.idjnsang AS idjnsang, b.nama AS nama FROM tkunjung a LEFT JOIN ranggota b ON a.nipnis = b.nipnis WHERE tglkunjung = '$harian' AND idjnsang=2 AND stkunjung = 'A'");

    $jmlGuru = mysqli_num_rows($sql);
    if($jmlGuru>0){
        $sheet->setCellValue('B'.$numrow, "Guru/Kary");
        $sheet->getStyle('B'.$numrow)->getFont()->setBold(TRUE); 
        $sheet->getStyle('B'.$numrow)->getFont()->setSize(12); 

        $numrow++;
        $no = 1;
        while($q = mysqli_fetch_assoc($sql)){
            $id_anggota = $q['nipnis'];
            $nama       = $q['nama'];

            /***tampilkan data****/ 
            $sheet->setCellValue('B'.$numrow, $no.".");
            $sheet->setCellValue('C'.$numrow, $id_anggota);
            $sheet->setCellValue('D'.$numrow, $nama);


            $sheet->mergeCells('D'.$numrow.':F'.$numrow);
            $sheet->getStyle('B'.$numrow)->applyFromArray($style_row);
            $sheet->getStyle('C'.$numrow)->applyFromArray($style_row);
            $sheet->getStyle('D'.$numrow.':F'.$numrow)->applyFromArray($style_row); 

            $numrow++;
            $no++;
        }

        $sheet->setCellValue('D'.$numrow, "Sub Jumlah : ");
        $sheet->getStyle('D'.$numrow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT); 
        $sheet->getStyle('D'.$numrow)->getFont()->setBold(TRUE); 
        $sheet->getStyle('D'.$numrow)->getFont()->setSize(12);

        $sheet->setCellValue('E'.$numrow, $jmlGuru);
        $sheet->getStyle('E'.$numrow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT); 
        $sheet->getStyle('E'.$numrow)->getFont()->setBold(TRUE); 
        $sheet->getStyle('E'.$numrow)->getFont()->setSize(12);
 
        $numrow++;
    }

    $sheet->setCellValue('D'.$numrow, "JUMLAH TOTAL :");
    $sheet->getStyle('D'.$numrow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT); 
    $sheet->getStyle('D'.$numrow)->getFont()->setBold(TRUE); 
    $sheet->getStyle('D'.$numrow)->getFont()->setSize(13);

    $sheet->setCellValue('E'.$numrow, $jmlSiswa+$jmlGuru);
    $sheet->getStyle('E'.$numrow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT); 
    $sheet->getStyle('E'.$numrow)->getFont()->setBold(TRUE); 
    $sheet->getStyle('E'.$numrow)->getFont()->setSize(13);

    $sheet->setCellValue('F'.$numrow, "ANGGOTA");
    $sheet->getStyle('F'.$numrow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT); 
    $sheet->getStyle('F'.$numrow)->getFont()->setBold(TRUE); 
    $sheet->getStyle('F'.$numrow)->getFont()->setSize(13);
     
    $sheet->getStyle('B'.$numrow.':F'.$numrow)->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
    $sheet->getStyle('B'.$numrow.':F'.$numrow)->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

    $numrow++;$numrow++;

    $sheet->setCellValue('F'.$numrow, "Dilaporkan Oleh");
    $sheet->getStyle('F'.$numrow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER); 
    $sheet->getStyle('F'.$numrow)->getFont()->setBold(TRUE); 
    $sheet->getStyle('F'.$numrow)->getFont()->setSize(12);

    $numrow++;$numrow++;$numrow++;

    $sheet->getStyle('F'.$numrow)->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
    $sheet->getColumnDimension('F')->setWidth(20); 

}else if($tampil == "tampilTamu"){

    $sheet->setCellValue('A4', "LAPORAN KUNJUNGAN TAMU PERPUSTAKAAN");  
    $sheet->getStyle('A4')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->mergeCells('A4:G4'); // Set Merge Cell pada  
    $sheet->getStyle('A4')->getFont()->setSize(13); // Set font size 

    $sheet->setCellValue('A5', "Tanggal : ".IndonesiaTgl($harian));  
    $sheet->getStyle('A5')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->mergeCells('A5:G5'); // Set Merge Cell pada  
    $sheet->getStyle('A5')->getFont()->setBold(TRUE); // Set bold  
    $sheet->getStyle('A5')->getFont()->setSize(12); // Set font size 

    // Buat header tabel
    $sheet->setCellValue('B7', "NO");  
    $sheet->setCellValue('C7', "NAMA"); 
    $sheet->setCellValue('D7', "ALAMAT/LEMBAGA");  

    // Apply style header yang telah kita buat tadi ke masing-masing kolom header
    $sheet->getStyle('B8')->applyFromArray($style_col);
    $sheet->getStyle('B')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('C8')->applyFromArray($style_col);
    $sheet->getStyle('C8')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER); 
    $sheet->getStyle('D8:F8')->applyFromArray($style_col);
    $sheet->mergeCells('D8:F8');

    // Set width kolom
    $sheet->getColumnDimension('B')->setWidth(15); 
    $sheet->getColumnDimension('C')->setWidth(20);
    $sheet->getColumnDimension('D')->setWidth(30); 
    $sheet->getColumnDimension('E')->setWidth(10); 
    $sheet->getColumnDimension('F')->setWidth(20); 

    $numrow = 8;  //BARUS PERTAMA UNTUK DATA, setelah header

    //table database 
    $sql = mysqli_query($koneksidb,"SELECT tglkunjung, nipnis, kettamu FROM tkunjung WHERE tglkunjung = '$harian' AND stkunjung = 'T'");

    $jmlTamu = mysqli_num_rows($sql);
    if($jmlTamu>0){
        $sheet->setCellValue('B'.$numrow, "Tamu");
        $sheet->getStyle('B'.$numrow)->getFont()->setBold(TRUE); 
        $sheet->getStyle('B'.$numrow)->getFont()->setSize(12); 

        $numrow++;
        $no = 1;
        while($q = mysqli_fetch_assoc($sql)){
            $id_anggota = $q['nipnis'];
            $nama       = $q['kettamu'];

            /***tampilkan data****/ 
            $sheet->setCellValue('B'.$numrow, $no.".");
            $sheet->setCellValue('C'.$numrow, $id_anggota);
            $sheet->setCellValue('D'.$numrow, $nama);

            $sheet->mergeCells('D'.$numrow.':F'.$numrow);
            $sheet->getStyle('B'.$numrow)->applyFromArray($style_row);
            $sheet->getStyle('C'.$numrow)->applyFromArray($style_row);
            $sheet->getStyle('D'.$numrow.':F'.$numrow)->applyFromArray($style_row); 

            $numrow++;
            $no++;
        }

        // $sheet->setCellValue('D'.$numrow, "Sub Jumlah : ".$jmlTamu);
        // $sheet->getStyle('D'.$numrow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT); 
        // $sheet->getStyle('D'.$numrow)->getFont()->setBold(TRUE); 
        // $sheet->getStyle('D'.$numrow)->getFont()->setSize(12);

        // $numrow++;
     }

    $sheet->setCellValue('D'.$numrow, "JUMLAH TOTAL :");
    $sheet->getStyle('D'.$numrow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT); 
    $sheet->getStyle('D'.$numrow)->getFont()->setBold(TRUE); 
    $sheet->getStyle('D'.$numrow)->getFont()->setSize(13);

    $sheet->setCellValue('E'.$numrow, $jmlTamu);
    $sheet->getStyle('E'.$numrow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT); 
    $sheet->getStyle('E'.$numrow)->getFont()->setBold(TRUE); 
    $sheet->getStyle('E'.$numrow)->getFont()->setSize(13);

    $sheet->setCellValue('F'.$numrow, "TAMU");
    $sheet->getStyle('F'.$numrow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT); 
    $sheet->getStyle('F'.$numrow)->getFont()->setBold(TRUE); 
    $sheet->getStyle('F'.$numrow)->getFont()->setSize(13);
     
    $sheet->getStyle('B'.$numrow.':F'.$numrow)->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
    $sheet->getStyle('B'.$numrow.':F'.$numrow)->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

    $numrow++;$numrow++;

    $sheet->setCellValue('F'.$numrow, "Dilaporkan Oleh");
    $sheet->getStyle('F'.$numrow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER); 
    $sheet->getStyle('F'.$numrow)->getFont()->setBold(TRUE); 
    $sheet->getStyle('F'.$numrow)->getFont()->setSize(12);

    $numrow++;$numrow++;$numrow++;

    $sheet->getStyle('F'.$numrow)->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
    $sheet->getColumnDimension('F')->setWidth(20); 
}

// LAPORAN BULANAN DAN CUSTOM
}else{

    $sheet->setCellValue('A1', 'PERPUSTAKAAN' ); 
    $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);   
    $sheet->getStyle('A1')->getFont()->setSize(12); // Set font size 
    
    $sheet->setCellValue('A2',  "Depan Kampus | UMS, Solo" ); // Set kolom A1 
    $sheet->getStyle('A2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
    $sheet->getStyle('A2')->getFont()->setSize(12); // Set font size 15 untuk kolom A1
    
    $sheet->setCellValue('A3',  "Telp 0271-722340" ); // Set kolom A1 
    $sheet->getStyle('A3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
    $sheet->getStyle('A3')->getFont()->setSize(12); // Set font size 15 untuk kolom A1
    
    $sheet->setCellValue('A4', "LAPORAN BULANAN");  
    $sheet->getStyle('A4')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->mergeCells('A4:G4'); // Set Merge Cell pada  
    $sheet->getStyle('A4')->getFont()->setSize(13); // Set font size 15
    
    if($tampil == "tampilAnggota"){
        $sheet->setCellValue('A5', "JUMLAH KUNJUNGAN ANGGOTA PERPUSTAKAAN");      
    }else if($tampil == "tampilTamu"){
        $sheet->setCellValue('A5', "JUMLAH KUNJUNGAN TAMU PERPUSTAKAAN");      
    }
    $sheet->getStyle('A5')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->mergeCells('A5:G5'); // Set Merge Cell pada  
    $sheet->getStyle('A5')->getFont()->setSize(13); // Set font size 15

     if($bulan != "" && $tahun != "" && $pilihan == "bulanan"){
         $sheet->setCellValue('A6', "Bulan : ".namaBulanIndonesia($bulan)." ".$tahun);  
     }else if($dariTanggal != "" && $sampaiTanggal != "" && $pilihan == "custom"){
         $sheet->setCellValue('A6', "Dari Tgl: ".IndonesiaTgl($dariTanggal)."  S.D.  ". IndonesiaTgl($sampaiTanggal));  
     }
    $sheet->getStyle('A6')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->mergeCells('A6:G6'); // Set Merge Cell pada  
    $sheet->getStyle('A6')->getFont()->setBold(TRUE); 
    $sheet->getStyle('A6')->getFont()->setSize(12); // Set font size 15

    // Buat header tabel
    $sheet->setCellValue('B8', "NO");  
    $sheet->setCellValue('C8', "TANGGAL"); 
    $sheet->setCellValue('D8', "JUMLAH PENGUNJUNG");  

   // Apply style header yang telah kita buat tadi ke masing-masing kolom header
   $sheet->getStyle('B8')->applyFromArray($style_col);
   $sheet->getStyle('B')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
   $sheet->getStyle('C8')->applyFromArray($style_col);
   $sheet->getStyle('C')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT); 
   $sheet->getStyle('C8')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER); 
   $sheet->getStyle('D8:F8')->applyFromArray($style_col);
   $sheet->getStyle('D')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
   $sheet->mergeCells('D8:F8');

   // Set width kolom
   $sheet->getColumnDimension('B')->setWidth(15); 
   $sheet->getColumnDimension('C')->setWidth(20);
   $sheet->getColumnDimension('D')->setWidth(20); 
   $sheet->getColumnDimension('E')->setWidth(20); 
   $sheet->getColumnDimension('F')->setWidth(20); 

   $numrow = 9;  //BARUS PERTAMA UNTUK DATA, setelah header

   //table database 
   $stkunjung = ($tampil=="tampilAnggota") ? "A" : "T" ;
  
        if($bulan != "" && $tahun != "" && $pilihan == "bulanan"){

            $sql = mysqli_query($koneksidb,"SELECT tglkunjung, COUNT(*) AS jmlpengunjung FROM tkunjung WHERE MONTH(tglkunjung) = '$bulan' AND YEAR(tglkunjung) = '$tahun' AND stkunjung = '$stkunjung' GROUP BY tglkunjung");
    
        }else if($dariTanggal != "" && $sampaiTanggal != "" && $pilihan == "custom"){

            $sql = mysqli_query($koneksidb,"SELECT tglkunjung, COUNT(*) AS jmlpengunjung FROM tkunjung WHERE tglkunjung >= '$dariTanggal' AND tglkunjung <= '$sampaiTanggal' AND stkunjung = '$stkunjung' GROUP BY tglkunjung");     

        }

   $jmlBaris = mysqli_num_rows($sql);
   $total = 0;
   if($jmlBaris>0){
       $no = 1;
       while($q = mysqli_fetch_assoc($sql)){
           $tglkunjung          = $q['tglkunjung'];
           $jmlpengunjung       = $q['jmlpengunjung'];
           $total += $jmlpengunjung;

           /***tampilkan data****/ 
           $sheet->setCellValue('B'.$numrow, $no.".");
           $sheet->setCellValue('C'.$numrow, $tglkunjung);
           $sheet->setCellValue('D'.$numrow, $jmlpengunjung);

           $sheet->mergeCells('D'.$numrow.':F'.$numrow);
           $sheet->getStyle('B'.$numrow)->applyFromArray($style_row);
           $sheet->getStyle('C'.$numrow)->applyFromArray($style_row);
           $sheet->getStyle('D'.$numrow.':F'.$numrow)->applyFromArray($style_row); 

           $numrow++;
           $no++;
       }

    }

   $jnsPengunjung = ($tampil=="tampilAnggota") ? "Anggota" : "Tamu";

   $sheet->setCellValue('D'.$numrow, "Jumlah :");
   $sheet->getStyle('D'.$numrow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT); 
   $sheet->getStyle('D'.$numrow)->getFont()->setBold(TRUE); 
   $sheet->getStyle('D'.$numrow)->getFont()->setSize(13);

   $sheet->setCellValue('E'.$numrow, $total);
   $sheet->getStyle('E'.$numrow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER); 
   $sheet->getStyle('E'.$numrow)->getFont()->setBold(TRUE); 
   $sheet->getStyle('E'.$numrow)->getFont()->setSize(13);

   $sheet->setCellValue('F'.$numrow, $jnsPengunjung);
   $sheet->getStyle('F'.$numrow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT); 
   $sheet->getStyle('F'.$numrow)->getFont()->setBold(TRUE); 
   $sheet->getStyle('F'.$numrow)->getFont()->setSize(13);
    
   $sheet->getStyle('B'.$numrow.':F'.$numrow)->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
   $sheet->getStyle('B'.$numrow.':F'.$numrow)->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

   $numrow++;$numrow++;

   $sheet->setCellValue('F'.$numrow, "Dilaporkan Oleh");
   $sheet->getStyle('F'.$numrow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER); 
   $sheet->getStyle('F'.$numrow)->getFont()->setBold(TRUE); 
   $sheet->getStyle('F'.$numrow)->getFont()->setSize(12);

   $numrow++;$numrow++;$numrow++;

   $sheet->getStyle('F'.$numrow)->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
   $sheet->getColumnDimension('F')->setWidth(20); 



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
header('Content-Disposition: attachment; filename="Laporan Pengunjung.xlsx"'); // Set nama file excel nya
header('Cache-Control: max-age=0');
$writer = new Xlsx($spreadsheet);

$writer->save('php://output');
exit;
