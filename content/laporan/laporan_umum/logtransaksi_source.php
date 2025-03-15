<?php
// session_start(); // Wajib dimulai di awal

// Pastikan $_SESSION['noapk'] tersedia
if (!isset($_SESSION['noapk']) || empty($_SESSION['noapk'])) {
    die(json_encode(["error" => "Noapk tidak ditemukan di sesi"]));
}

$aColumns = array('iduser', 'tanggal', 'aktivitas', 'noapk'); // Tambahkan 'noapk'
$sIndexColumn = "iduser";
$sTable = "vw_tlogtransaksi";

$gaSql['link'] =  mysqli_connect($gaSql['server'], $gaSql['user'], $gaSql['password'], $gaSql['db']) or 
    die(json_encode(["error" => "Koneksi database gagal"]));

$noapk = intval($_SESSION['noapk']); // Pastikan integer

// WHERE clause
$sWhere = " WHERE noapk = $noapk ";
if (!empty($_GET['sSearch'])) {
    $search = mysqli_real_escape_string($gaSql['link'], $_GET['sSearch']);
    $sWhere .= " AND (";
    foreach ($aColumns as $column) {
        $sWhere .= "$column LIKE '%$search%' OR ";
    }
    $sWhere = substr($sWhere, 0, -4) . ")"; // Hapus "OR " terakhir
}

// ORDER clause
$sOrder = "";
if (isset($_GET['iSortCol_0'])) {
    $sOrder = "ORDER BY ";
    $sortingCols = intval($_GET['iSortingCols']);
    for ($i = 0; $i < $sortingCols; $i++) {
        $sortableColumnIndex = intval($_GET['iSortCol_' . $i]);
        if ($sortableColumnIndex >= 0) {
            $sOrder .= $aColumns[$sortableColumnIndex] . " " . 
                mysqli_real_escape_string($gaSql['link'], $_GET['sSortDir_' . $i]) . ", ";
        }
    }
    $sOrder = rtrim($sOrder, ", ");
}

// LIMIT clause
$sLimit = "";
if (isset($_GET['iDisplayStart']) && $_GET['iDisplayLength'] != '-1') {
    $sLimit = "LIMIT " . intval($_GET['iDisplayStart']) . ", " . intval($_GET['iDisplayLength']);
}

// Query utama dengan WHERE, ORDER, dan LIMIT
$sQuery = "SELECT " . implode(", ", $aColumns) . " FROM $sTable $sWhere $sOrder $sLimit";
$rResult = mysqli_query($gaSql['link'], $sQuery) or die(mysqli_error($gaSql['link']));

// Mengubah hasil query menjadi array untuk DataTables
$output = array("aaData" => array());
while ($dataRow = mysqli_fetch_assoc($rResult)) {
    $output['aaData'][] = array_values($dataRow);
}

echo json_encode($output);
?>
