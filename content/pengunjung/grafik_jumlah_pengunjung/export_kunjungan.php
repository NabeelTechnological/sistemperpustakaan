<?php
session_start();
require __DIR__ . '/../../../vendor/autoload.php';
require_once '../../../config/inc.connection.php';
require_once '../../../config/inc.library.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Chart\Chart;
use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
use PhpOffice\PhpSpreadsheet\Chart\Legend;
use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
use PhpOffice\PhpSpreadsheet\Chart\Title;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

// Ambil data dari URL
$tahun = isset($_GET['tahun']) ? $_GET['tahun'] : date('Y');
$bulan = isset($_GET['bulan']) ? $_GET['bulan'] : "";
$noapk = $_SESSION['noapk'];

if (!empty($bulan)) {
    $bulanNumber = date('m', strtotime($bulan));
    $qry = "SELECT DAY(tglkunjung) as tgl, count(*) as jmlkunjung FROM vw_tkunjung 
            WHERE MONTH(tglkunjung) = ? AND YEAR(tglkunjung) = ? AND noapk = ?
            GROUP BY tgl ORDER BY tgl";
    $stmt = $koneksidb->prepare($qry);
    $stmt->bind_param("sss", $bulanNumber, $tahun, $noapk);
} else {
    $qry = "SELECT MONTH(tglkunjung) as bulan, count(*) as jmlkunjung FROM vw_tkunjung 
            WHERE YEAR(tglkunjung) = ? AND noapk = ?
            GROUP BY bulan ORDER BY bulan";
    $stmt = $koneksidb->prepare($qry);
    $stmt->bind_param("ss", $tahun, $noapk);
}

$stmt->execute();
$result = $stmt->get_result();
$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}
$stmt->close();

if (empty($data)) {
    die('Tidak ada data kunjungan.');
}

// Buat file Excel
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle("Grafik Kunjungan");

// Header data untuk grafik
$sheet->setCellValue('A1', 'Bulan');
$sheet->setCellValue('B1', 'Jumlah Pengunjung');

// Format Header
$headerStyle = [
    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4F81BD']],
    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]]
];
$sheet->getStyle("A1:B1")->applyFromArray($headerStyle);

// Isi data ke Excel
$row = 2;
foreach ($data as $entry) {
    $sheet->setCellValue('A' . $row, $entry['tgl'] ?? $entry['bulan']);
    $sheet->setCellValue('B' . $row, $entry['jmlkunjung']);
    $row++;
}
$dataRowEnd = $row - 1;

// Format isi tabel
$bodyStyle = [
    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]],
    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER]
];
$sheet->getStyle("A2:B$dataRowEnd")->applyFromArray($bodyStyle);

// Auto-size kolom agar rapi
foreach (range('A', 'B') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// Buat Grafik
$dataSeriesLabels = [new DataSeriesValues('String', "'Grafik Kunjungan'!B1", null, 1)];
$xAxisTickValues = [new DataSeriesValues('String', "'Grafik Kunjungan'!A2:A$dataRowEnd", null, 4)];
$dataSeriesValues = [new DataSeriesValues('Number', "'Grafik Kunjungan'!B2:B$dataRowEnd", null, 4)];

$series = new DataSeries(
    DataSeries::TYPE_BARCHART, // Bisa diganti ke TYPE_LINECHART atau TYPE_PIECHART
    DataSeries::GROUPING_CLUSTERED,
    range(0, count($dataSeriesValues) - 1),
    $dataSeriesLabels,
    $xAxisTickValues,
    $dataSeriesValues
);
$series->setPlotDirection(DataSeries::DIRECTION_COL);

$plotArea = new PlotArea(null, [$series]);
$legend = new Legend(Legend::POSITION_RIGHT, null, false);
$title = new Title('Grafik Kunjungan Perpustakaan');

$chart = new Chart(
    'chart1',
    $title,
    $legend,
    $plotArea
);

// Menentukan posisi grafik di Excel
$chart->setTopLeftPosition('D2');
$chart->setBottomRightPosition('M20');
$sheet->addChart($chart);

// Simpan dan unduh file Excel
$writer = new Xlsx($spreadsheet);
$writer->setIncludeCharts(true); // Wajib agar grafik muncul
$fileName = "grafik_kunjungan_perpustakaan.xlsx";

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="' . $fileName . '"');
header('Cache-Control: max-age=0');

$writer->save('php://output');
exit;
?>
