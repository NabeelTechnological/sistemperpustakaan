<?php
 //security goes here

$aColumns = array('tgllibur', 'deslibur', 'keterangan'); // Tambahkan keterangan

//primary key
$sIndexColumn = "tgllibur";

//nama table database 
$sTable = "rtgllibur";

$gaSql['link'] = mysqli_connect($gaSql['server'], $gaSql['user'], $gaSql['password']) or
    die('Could not open connection to server');

mysqli_select_db($gaSql['link'], $gaSql['db']) or 
    die('Could not select database '. $gaSql['db']);

$sLimit = "";
if (isset($_GET['iDisplayStart']) && $_GET['iDisplayLength'] != '-1') {
    $sLimit = "LIMIT " . mysqli_real_escape_string($gaSql['link'], $_GET['iDisplayStart']) . ", " .
        mysqli_real_escape_string($gaSql['link'], $_GET['iDisplayLength']);
}

if (isset($_GET['iSortCol_0'])) {
    $sOrder = "ORDER BY ";
    $sortingCols = intval($_GET['iSortingCols']); 
    for ($i = 0; $i < $sortingCols; $i++) {
        $sortableColumnIndex = intval($_GET['iSortCol_' . $i]);
        $actualColumnIndex = $sortableColumnIndex - 1;

        if ($actualColumnIndex >= 0) {
            $sOrder .= $aColumns[$actualColumnIndex] . " " . mysqli_real_escape_string($gaSql['link'], $_GET['sSortDir_' . $i]) . ", ";
        } else {
            $sOrder = "";
        }
    }
    $sOrder = rtrim($sOrder, ", ");
}

$sWhere = " WHERE noapk = $_SESSION[noapk] ";
if ($_GET['sSearch'] != "") {
    $sWhere = " WHERE noapk = $_SESSION[noapk] AND (";
    for ($i = 0; $i < count($aColumns); $i++) {
        $sWhere .= $aColumns[$i] . " LIKE '%" . mysqli_real_escape_string($gaSql['link'], $_GET['sSearch']) . "%' OR ";
    }
    $sWhere = substr_replace($sWhere, "", -3);
    $sWhere .= ')';
}

for ($i = 0; $i < count($aColumns); $i++) {
    if ($_GET['bSearchable_' . $i] == "true" && $_GET['sSearch_' . $i] != '') {
        if ($sWhere == "") {
            $sWhere = " WHERE noapk = $_SESSION[noapk] AND ";
        } else {
            $sWhere .= " AND ";
        }
        $sWhere .= $aColumns[$i] . " LIKE '%" . mysqli_real_escape_string($gaSql['link'], $_GET['sSearch_' . $i]) . "%' ";
    }
}

$sQuery = "
    SELECT SQL_CALC_FOUND_ROWS " . str_replace(" , ", " ", implode(", ", $aColumns)) . "
    FROM $sTable
    $sWhere 
    $sOrder
    $sLimit
";
$rResult = mysqli_query($gaSql['link'], $sQuery) or die(mysqli_error($gaSql['link']));

$sQuery = "SELECT FOUND_ROWS()";
$rResultFilterTotal = mysqli_query($gaSql['link'], $sQuery) or die(mysqli_error($gaSql['link']));
$aResultFilterTotal = mysqli_fetch_array($rResultFilterTotal);
$iFilteredTotal = $aResultFilterTotal[0];

$sQuery = "SELECT COUNT($sIndexColumn) FROM $sTable";
$rResultTotal = mysqli_query($gaSql['link'], $sQuery) or die(mysqli_error($gaSql['link']));
$aResultTotal = mysqli_fetch_array($rResultTotal);
$iTotal = $aResultTotal[0];

$output = array(
    "sEcho" => intval($_GET['sEcho']),
    "iTotalRecords" => $iTotal,
    "iTotalDisplayRecords" => $iFilteredTotal,
    "aaData" => array()
);

$no = $_GET['iDisplayStart'] + 1;
while ($dataRow = mysqli_fetch_array($rResult)) {
    $tgllibur = $dataRow['tgllibur'];

    switch ($dataRow['deslibur']) {
        case '0':
            $deslibur = "Minggu";
            break;
        case '1':
            $deslibur = "Hari Besar";
            break;
    }

    $keterangan = !empty($dataRow['keterangan']) ? $dataRow['keterangan'] : '-';

    $aksi = "<button data-toggle='modal' data-target='#deleteConfirmationModal' data-id='$tgllibur' class='delPopUp btn btn-xs " . $_SESSION['warnatombol'] . " tooltips' data-placement='top' data-original-title='Delete'><i class='fa fa-trash-o'></i></button>";

    // Menambahkan keterangan ke dalam output JSON
    $row = array($no, $tgllibur, $deslibur, $keterangan, $aksi);
    
    $no++;
    $output['aaData'][] = $row;
}

echo json_encode($output);

?>
