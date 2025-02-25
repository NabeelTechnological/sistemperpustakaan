<?php 

require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\RichText\RichText;
use PhpOffice\PhpSpreadsheet\Style\Font;


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

    $aColumns = array('idbuku', 'judul', 'tglpinjam', 'tglhrskembali');
    $sTable = "(SELECT a.idbuku AS idbuku, a.judul AS judul, b.tglpinjam AS tglpinjam, b.tglhrskembali AS tglhrskembali,b.iskembali AS iskembali , b.nipnis AS nipnis, c.idjnsang AS idjnsang
    FROM tbuku a JOIN tpinbuku b ON a.idbuku = b.idbuku JOIN ranggota c ON b.nipnis = c.nipnis) AS tpinbuku";

    if($anggota=="Siswa"){
        $sWhere = "WHERE idjnsang = 1 ";

    }else if($anggota=="Guru/Karyawan"){
        $sWhere = "WHERE idjnsang = 2 ";
    }

    $sWhere .= "AND iskembali = 0 AND nipnis = $idAnggota";

    // query informasi data
    $sql = "SELECT a.nama, a.alamat, c.nmkota, COUNT(b.idbuku) AS jmlbuku FROM ranggota a LEFT JOIN tpinbuku b ON a.nipnis = b.nipnis AND b.iskembali = 0 LEFT JOIN rkota c ON a.idkota = c.idkota WHERE a.nipnis = $idAnggota GROUP BY a.nama, a.alamat, c.nmkota";

    $stmt = mysqli_prepare($koneksidb,$sql) or die ("Gagal menyiapkan statement: " . mysqli_error($koneksidb));
    mysqli_stmt_execute($stmt);
   
    mysqli_stmt_bind_result($stmt,$dataNama,$dataAlamat,$dataNmKota,$dataJmlBuku);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    //Alignment Cells
    $sheet->getStyle('A')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
    $sheet->getStyle('B')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
    $sheet->getStyle('E')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
    $sheet->getStyle('F')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('G')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('H')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

    // Set width kolom
    $sheet->getColumnDimension('A')->setWidth(10);
    $sheet->getColumnDimension('B')->setWidth(5);
    $sheet->getColumnDimension('C')->setWidth(10);
    $sheet->getColumnDimension('D')->setWidth(2);
    $sheet->getColumnDimension('E')->setWidth(53);
    $sheet->getColumnDimension('F')->setWidth(20);
    $sheet->getColumnDimension('G')->setWidth(20);
    $sheet->getColumnDimension('H')->setWidth(20);

// RICH TEXT
    $sheet->setCellValue('A1', getNmsekolah($koneksidb) ); 
    $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);    
    $sheet->getStyle('A1')->getFont()->setSize(12); // Set font size   

    if ($dataJmlBuku == 0) {
        $jdl = "KETERANGAN BEBAS PERPUSTAKAAN";
        $ket = "SUDAH MENGEMBALIKAN SEMUA PINJAMAN";
    }else{
        $jdl = "KETERANGAN TANGGUNGAN PERPUSTAKAAN";  
        $ket = "MASIH MEMINJAM";
    }

    $sheet->setCellValue('A3',  $jdl ); // Set kolom  
    $sheet->getStyle('A3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->mergeCells('A3:F3'); // Set Merge Cell  
    $sheet->getStyle('A3')->getFont()->setSize(13); // Set font size  
    $sheet->getStyle('A3')->getFont()->setUnderline(Font::UNDERLINE_SINGLE); // set underline

    $sheet->setCellValue('A6', "Surat keterangan ini diberikan kepada :");  
    $sheet->mergeCells('A6:F6'); // Set Merge Cell  

    $sheet->setCellValue('B7', "Nama");  
    if($anggota=="Siswa"){
        $nipnis = "NIS";

    }else if($anggota=="Guru/Karyawan"){
        $nipnis = "NIK";
    }
    $sheet->setCellValue('B8', $nipnis);  
    $sheet->setCellValue('B9', "Alamat");

    $sheet->mergeCells('B7:C7'); // Set Merge Cell  
    $sheet->mergeCells('B8:C8'); // Set Merge Cell  
    $sheet->mergeCells('B9:C9'); // Set Merge Cell  
    
    $sheet->setCellValue('D7', ":");  
    $sheet->setCellValue('D8', ":");  
    $sheet->setCellValue('D9', ":");   

    $sheet->setCellValue('E7', $dataNama);  
    $sheet->setCellValue('E8', $idAnggota);  
    $sheet->setCellValue('E9', $dataAlamat." ".$dataNmKota);   

    $richText = new RichText();

    if ($dataJmlBuku > 0) {
        $richText->createTextRun("Menerangkan bahwa yang bersangkutan ");
        $run = $richText->createTextRun($ket);
        $run->getFont()->setBold(true);
        $richText->createTextRun(" di ".getNmsekolah($koneksidb).".");
        $sheet->getCell('A10')->setValue($richText);  
        $sheet->setCellValue('A11', "Pinjaman tersebut berupa: $dataJmlBuku buku, rincian terlampir."); 

    }else{ 
        $richText->createTextRun("Menerangkan bahwa yang bersangkutan ");
        $run = $richText->createTextRun($ket);
        $run->getFont()->setBold(true);
        $sheet->getCell('A10')->setValue($richText);  
        $sheet->setCellValue('A11', "di ".getNmsekolah($koneksidb).".");  
        
    }
    $sheet->setCellValue('A12', "Demikian surat keterangan ini dibuat untuk seperlunya.");
    $sheet->mergeCells('A10:F10'); // Set Merge Cell  
    $sheet->mergeCells('A11:F11'); // Set Merge Cell  
    $sheet->mergeCells('A12:F12'); // Set Merge Cell  

    $sheet->setCellValue('F14', "Dicetak tanggal ".indonesiaTglPanjang(date("Y-m-d"))); 
    $sheet->setCellValue('F15', "Yang memberi keterangan");  
    $sheet->getStyle('F18')->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

    if ($dataJmlBuku > 0) {

    $sheet->setCellValue('A20', "PERINCIAN PINJAMAN :"); 
    $sheet->getStyle('A20')->getFont()->setUnderline(Font::UNDERLINE_SINGLE); // set underline
    $sheet->mergeCells('A20:F20'); // Set Merge Cell  
    
    $sheet->setCellValue('A21', "I. PINJAMAN BUKU"); 
    $sheet->mergeCells('A21:F21'); // Set Merge Cell  
    
    // Buat header tabel 

    $sheet->setCellValue('A22', "NO. URUT");  
    $sheet->setCellValue('B22', "BUKU");  
    $sheet->setCellValue('F22', "TANGGAL");  
    $sheet->setCellValue('H22', "TERLAMBAT");  
    $sheet->setCellValue('B23', "ID BUKU"); 
    $sheet->setCellValue('D23', "JUDUL"); 
    $sheet->setCellValue('F23', "PINJAM"); 
    $sheet->setCellValue('G23', "JADWAL KEMBALI"); 
    
    // Apply style header yang telah kita buat tadi ke masing-masing kolom header
    $sheet->getStyle('A22')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('A22:A23')->applyFromArray($style_col);
    $sheet->mergeCells('A22:A23'); // Set Merge Cell pada 

    $sheet->getStyle('B22')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('B22:E22')->applyFromArray($style_col);
    $sheet->mergeCells('B22:E22'); // Set Merge Cell pada 

    $sheet->getStyle('F22')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('F22:G22')->applyFromArray($style_col);
    $sheet->mergeCells('F22:G22'); // Set Merge Cell pada 

    $sheet->getStyle('H22')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('H22:H23')->applyFromArray($style_col);
    $sheet->mergeCells('H22:H23'); // Set Merge Cell pada 

    $sheet->getStyle('B23')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('B23:C23')->applyFromArray($style_col);
    $sheet->mergeCells('B23:C23'); // Set Merge Cell pada 

    $sheet->getStyle('D23')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('D23:E23')->applyFromArray($style_col);
    $sheet->mergeCells('D23:E23'); // Set Merge Cell pada 

    $sheet->getStyle('F23')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('F23')->applyFromArray($style_col);

    $sheet->getStyle('G23')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('G23')->applyFromArray($style_col);

    $numrow = 24;  //BARUS PERTAMA UNTUK DATA, setelah header

     //table database 
     $sql = "
 SELECT ".str_replace(" , ", " ", implode(", ", $aColumns))."
 FROM $sTable
 $sWhere
 ORDER BY tglpinjam DESC
";

 $stmt = mysqli_prepare($koneksidb,$sql) or die ("Gagal menyiapkan statement: " . mysqli_error($koneksidb));
 mysqli_stmt_execute($stmt);
   
        mysqli_stmt_bind_result($stmt,$dataIdBuku,$dataJudul,$dataTglPinjam,$dataTglHrsKembali);

     $no = 0;
        while(mysqli_stmt_fetch($stmt)){
            /***tampilkan data****/ 
            if(date("Y-m-d") > $dataTglHrsKembali){
                $dataTerlambat = "YA";
            }else{
                $dataTerlambat = "TIDAK";
            }
            $sheet->setCellValue('A'.$numrow, ++$no);
            $sheet->setCellValue('B'.$numrow, $dataIdBuku);
            $sheet->setCellValue('D'.$numrow, $dataJudul);
            $sheet->setCellValue('F'.$numrow, $dataTglPinjam);
            $sheet->setCellValue('G'.$numrow, $dataTglHrsKembali);
            $sheet->setCellValue('H'.$numrow, $dataTerlambat);
            
            $sheet->getStyle('A'.$numrow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('A'.$numrow)->applyFromArray($style_row);
            $sheet->getStyle('B'.$numrow.':C'.$numrow)->applyFromArray($style_row);
            $sheet->mergeCells('B'.$numrow.':C'.$numrow); // Set Merge Cell pada 
            $sheet->getStyle('D'.$numrow.':E'.$numrow)->applyFromArray($style_row);
            $sheet->mergeCells('D'.$numrow.':E'.$numrow); // Set Merge Cell pada 
            $sheet->getStyle('F'.$numrow)->applyFromArray($style_row);
            $sheet->getStyle('G'.$numrow)->applyFromArray($style_row);
            $sheet->getStyle('H'.$numrow)->applyFromArray($style_row);

            $numrow++;
        }

        $sheet->setCellValue('A'.$numrow, "Jumlah buku");
        $sheet->getStyle('A'.$numrow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT); 
        $sheet->getStyle('A'.$numrow)->getFont()->setBold(TRUE); 
        $sheet->getStyle('A'.$numrow)->getFont()->setSize(13);
        $sheet->mergeCells('A'.$numrow.':B'.$numrow); // Set Merge Cell pada 
    
        $sheet->setCellValue('C'.$numrow, $no);
        $sheet->getStyle('C'.$numrow)->getFont()->setBold(TRUE); 
        $sheet->getStyle('C'.$numrow)->getFont()->setSize(13);

        $sheet->getStyle('A'.$numrow.':H'.$numrow)->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $sheet->getStyle('A'.$numrow.':H'.$numrow)->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

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
header('Content-Disposition: attachment; filename="Kartu Bebas Tanggungan Perpustakaan '.$anggota.' '.$idAnggota.'.xlsx"'); // Set nama file excel nya
header('Cache-Control: max-age=0');
$writer = new Xlsx($spreadsheet);

$writer->save('php://output');
exit;
