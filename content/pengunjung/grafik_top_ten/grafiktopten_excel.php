<?php
ob_start(); // Mulai output buffering
session_start();
require __DIR__ . '/../../../vendor/autoload.php';
require_once '../../../config/inc.connection.php';
require_once '../../../config/inc.library.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Chart\Chart;
use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
use PhpOffice\PhpSpreadsheet\Chart\Legend;
use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
use PhpOffice\PhpSpreadsheet\Chart\Title;

// Validasi input
if (!isset($_GET['tahun'], $_GET['jenis']) || empty($_GET['tahun']) || empty($_GET['jenis'])) {
    die("Tahun dan jenis harus dipilih!");
}

$tahun = filter_var($_GET['tahun'], FILTER_SANITIZE_NUMBER_INT);
$jenis = filter_var($_GET['jenis'], FILTER_SANITIZE_STRING);
$bulan = isset($_GET['bulan']) ? trim($_GET['bulan']) : null;

if (!isset($_SESSION['noapk']) || empty($_SESSION['noapk'])) {
    die("Session noapk tidak tersedia!");
}
$noapk = $_SESSION['noapk'];

if (!empty($bulan) && strtolower($bulan) !== "semua bulan") {
    $bulan = date('m', strtotime($bulan));
} else {
    $bulan = null;
}

$qry = "SELECT nipnis, nama, COUNT(*) AS jmlkunjung FROM vw_tkunjung WHERE desjenisang = ? AND YEAR(tglkunjung) = ? AND noapk = ?";
$params = [$jenis, $tahun, $noapk];
$types = "ssi";

if (!empty($bulan)) {
    $qry .= " AND MONTH(tglkunjung) = ?";
    $params[] = $bulan;
    $types .= "i";
}
$qry .= " GROUP BY nipnis, nama ORDER BY jmlkunjung DESC LIMIT 10";

$stmt = $koneksidb->prepare($qry);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = [
        'anggota' => "[$row[nipnis]] " . $row['nama'],
        'jmlkunjung' => $row['jmlkunjung']
    ];
}
$stmt->close();

if (empty($data)) {
    die("Data tidak ditemukan!");
}

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle("Top 10 Pengunjung");

$headers = ["No", "Anggota", "Jumlah Kunjungan"];
$columnIndex = 'A';
foreach ($headers as $header) {
    $sheet->setCellValue($columnIndex . "1", $header);
    $columnIndex++;
}

$headerStyle = [
    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4F81BD']],
    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]]
];
$sheet->getStyle("A1:C1")->applyFromArray($headerStyle);

$row = 2;
$no = 1;
foreach ($data as $item) {
    $sheet->setCellValue("A$row", $no++);
    $sheet->setCellValue("B$row", $item['anggota']);
    $sheet->setCellValue("C$row", $item['jmlkunjung']);
    $row++;
}
$dataRowEnd = $row - 1;

$sheet->getStyle("A2:C$dataRowEnd")->applyFromArray([
    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]],
    'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT, 'vertical' => Alignment::VERTICAL_CENTER]
]);

foreach (range('A', 'C') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// Menambahkan filter di header
$sheet->setAutoFilter("A1:C1");

// Menambahkan Grafik
$kategoriX = new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'Top 10 Pengunjung'!\$B\$2:\$B\$$dataRowEnd", null, count($data));
$dataY = new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, "'Top 10 Pengunjung'!\$C\$2:\$C\$$dataRowEnd", null, count($data));

$series = new DataSeries(
    DataSeries::TYPE_BARCHART,
    DataSeries::GROUPING_CLUSTERED,
    range(0, count($data) - 1),
    [],
    [$kategoriX],
    [$dataY]
);
$series->setPlotDirection(DataSeries::DIRECTION_COL);

$plotArea = new PlotArea(null, [$series]);
$chart = new Chart(
    'chart1',
    new Title('Top 10 Pengunjung Perpustakaan'),
    new Legend(Legend::POSITION_RIGHT, null, false),
    $plotArea
);
$chart->setTopLeftPosition('E2');
$chart->setBottomRightPosition('M15');
$sheet->addChart($chart);

$writer = new Xlsx($spreadsheet);
$writer->setIncludeCharts(true);
$fileName = "top_10_pengunjung.xlsx";

// Pastikan tidak ada output sebelum mengirim file Excel
ob_end_clean();

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="' . $fileName . '"');
header('Cache-Control: max-age=0');

$writer->save('php://output');
exit;
?>
