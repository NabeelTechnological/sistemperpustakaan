<?php
ob_start();
session_start();
require __DIR__ . '/../../../vendor/autoload.php';
require_once '../../../config/inc.connection.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Chart\Chart;
use PhpOffice\PhpSpreadsheet\Chart\Legend;
use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
use PhpOffice\PhpSpreadsheet\Chart\Title;
use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
use PhpOffice\PhpSpreadsheet\Chart\Layout;

// Validasi Input
if (!isset($_GET['tahun']) || empty($_GET['tahun'])) {
    echo "<script>alert('Tahun harus dipilih!'); window.history.back();</script>";
    exit;
}

$tahun = filter_var($_GET['tahun'], FILTER_SANITIZE_NUMBER_INT);

if (!isset($_SESSION['noapk']) || empty($_SESSION['noapk'])) {
    echo "<script>alert('Session noapk tidak tersedia!'); window.history.back();</script>";
    exit;
}

$noapk = $_SESSION['noapk'];

// Query Ambil Data Peminjaman
$qry = "SELECT DATE_FORMAT(tglpinjam, '%Y-%m') AS bulan, COUNT(*) AS total_peminjaman 
        FROM tpinjampaket 
        WHERE YEAR(tglpinjam) = ? AND noapk = ?
        GROUP BY bulan ORDER BY bulan";

$stmt = $koneksidb->prepare($qry);
$stmt->bind_param("si", $tahun, $noapk);
$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = [
        'bulan' => $row['bulan'],
        'total_peminjaman' => $row['total_peminjaman']
    ];
}
$stmt->close();

// Jika tidak ada data
if (empty($data)) {
    echo "<script>alert('Data tidak ditemukan!'); window.history.back();</script>";
    exit;
}

// Buat File Excel
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle("Peminjaman $tahun");

// Header Kolom
$headers = ["No", "Bulan", "Jumlah Peminjaman"];
$columnIndex = 'A';
foreach ($headers as $header) {
    $sheet->setCellValue($columnIndex . "1", $header);
    $columnIndex++;
}

// Gaya Header
$headerStyle = [
    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4F81BD']],
    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]]
];
$sheet->getStyle("A1:C1")->applyFromArray($headerStyle);

// Isi Data
$row = 2;
$no = 1;
foreach ($data as $item) {
    $sheet->setCellValue("A$row", $no++);
    $sheet->setCellValue("B$row", $item['bulan']);
    $sheet->setCellValue("C$row", $item['total_peminjaman']);
    $row++;
}
$dataRowEnd = $row - 1;

// Gaya Data
$sheet->getStyle("A2:C$dataRowEnd")->applyFromArray([
    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]],
    'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT, 'vertical' => Alignment::VERTICAL_CENTER]
]);

foreach (range('A', 'C') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// Tambahkan Grafik
$dataSeriesLabels = [new DataSeriesValues('String', "'Peminjaman $tahun'!C1", null, 1)];
$xAxisTickValues = [new DataSeriesValues('String', "'Peminjaman $tahun'!B2:B$dataRowEnd", null, $dataRowEnd - 1)];
$dataSeriesValues = [new DataSeriesValues('Number', "'Peminjaman $tahun'!C2:C$dataRowEnd", null, $dataRowEnd - 1)];

$series = new DataSeries(
    DataSeries::TYPE_BARCHART,
    DataSeries::GROUPING_CLUSTERED,
    range(0, count($dataSeriesValues) - 1),
    $dataSeriesLabels,
    $xAxisTickValues,
    $dataSeriesValues
);
$series->setPlotDirection(DataSeries::DIRECTION_VERTICAL);

$plotArea = new PlotArea(null, [$series]);
$chart = new Chart(
    'chart_peminjaman',
    new Title("Grafik Peminjaman Buku Tahun $tahun"),
    new Legend(Legend::POSITION_RIGHT, null, false),
    $plotArea
);

$chart->setTopLeftPosition('E2');
$chart->setBottomRightPosition('M15');
$sheet->addChart($chart);

// Buat File Excel dengan Grafik
$writer = new Xlsx($spreadsheet);
$writer->setIncludeCharts(true);

if (ob_get_length()) ob_end_clean();

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="peminjaman buku kolektif_' . $tahun . '.xlsx"');
header('Cache-Control: max-age=0');

$writer->save('php://output');
exit;
?>
