<?php

require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();


// Ambil noapk dari sesi atau request
$noapk = $_SESSION['noapk'] ?? $_GET['noapk'] ?? null;
if (!$noapk) {
    die("Noapk tidak ditemukan.");
}

// Style header tabel
$style_col = [
    'font' => ['bold' => true],
    'alignment' => [
        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
    ],
    'borders' => [
        'allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]
    ]
];

// Style isi tabel
$style_row = [
    'alignment' => ['vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER],
    'borders' => [
        'allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]
    ]
];

// Header tabel
$sheet->setCellValue('B6', "NO. URUT"); 
$sheet->setCellValue('C6', "URAIAN"); 
$sheet->setCellValue('D6', "JUMLAH"); 
$sheet->setCellValue('D7', "KARTU BERLAKU"); 
$sheet->setCellValue('E7', "KARTU TDK BERLAKU"); 
$sheet->setCellValue('F7', "TOTAL"); 
$sheet->mergeCells('D6:F6');

// Set style header
$sheet->getStyle('B6:F7')->applyFromArray($style_col);

// Query database
$sql = "
    SELECT desjenisang, 
           COUNT(CASE WHEN berlaku >= CURDATE() THEN 1 END) AS masihberlaku,
           COUNT(CASE WHEN berlaku < CURDATE() THEN 1 END) AS tdkberlaku
    FROM vw_ranggota 
    WHERE noapk = ?
    GROUP BY desjenisang
    ORDER BY idjnsang
";

$stmt = mysqli_prepare($koneksidb, $sql);
mysqli_stmt_bind_param($stmt, "s", $noapk);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $dataDesJenisang, $dataMasihBerlaku, $dataTdkBerlaku);

$numrow = 8;
$no = 1;
$totalBerlaku = $totalTdkBerlaku = $jmlTotal = 0;

while (mysqli_stmt_fetch($stmt)) {
    $dataTotal = $dataMasihBerlaku + $dataTdkBerlaku;
    $sheet->setCellValue('B' . $numrow, $no++)
          ->setCellValue('C' . $numrow, $dataDesJenisang)
          ->setCellValue('D' . $numrow, $dataMasihBerlaku)
          ->setCellValue('E' . $numrow, $dataTdkBerlaku)
          ->setCellValue('F' . $numrow, $dataTotal);
    $sheet->getStyle('B' . $numrow . ':F' . $numrow)->applyFromArray($style_row);
    $numrow++;
    $totalBerlaku += $dataMasihBerlaku;
    $totalTdkBerlaku += $dataTdkBerlaku;
    $jmlTotal += $dataTotal;
}

// Total
$sheet->setCellValue('C' . $numrow, "JUMLAH TOTAL")->getStyle('C' . $numrow)->getFont()->setBold(true);
$sheet->setCellValue('D' . $numrow, $totalBerlaku)->getStyle('D' . $numrow)->getFont()->setBold(true);
$sheet->setCellValue('E' . $numrow, $totalTdkBerlaku)->getStyle('E' . $numrow)->getFont()->setBold(true);
$sheet->setCellValue('F' . $numrow, $jmlTotal)->getStyle('F' . $numrow)->getFont()->setBold(true);

// Konfigurasi file
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="Rekap_Jumlah_Anggota.xlsx"');
header('Cache-Control: max-age=0');
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;