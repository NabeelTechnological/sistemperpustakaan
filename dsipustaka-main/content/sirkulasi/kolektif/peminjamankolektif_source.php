<?php
 //security goes here

$aColumns = array('idpaket', 'idbuku', 'idkelas', 'nama', 'tglpinjam', 'jampinjam', 'jmlpinjam', 'tglkembali', 'jamkembali', 'jmlkembali', 'judul');

// Primary key
$sIndexColumn = "idpaket";

// Nama table database 
$sTable = "vw_tpinjampaket";

$gaSql['link'] =  mysqli_connect($gaSql['server'], $gaSql['user'], $gaSql['password']) or
    die('Could not open connection to server');

mysqli_select_db($gaSql['link'], $gaSql['db']) or 
    die('Could not select database ' . $gaSql['db']);

// Pagination
$sLimit = "";
if (isset($_GET['iDisplayStart']) && $_GET['iDisplayLength'] != '-1') {
    $sLimit = "LIMIT " . mysqli_real_escape_string($gaSql['link'], $_GET['iDisplayStart']) . ", " .
        mysqli_real_escape_string($gaSql['link'], $_GET['iDisplayLength']);
}

// Ordering
$sOrder = "";
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

// Filtering
$sWhereDefault = " WHERE ispinjam = 1 AND noapk = " . $_SESSION['noapk'];
$sWhere = $sWhereDefault;
if ($_GET['sSearch'] != "") {
    $sWhere .= " AND (";
    foreach ($aColumns as $column) {
        $sWhere .= "$column LIKE '%" . mysqli_real_escape_string($gaSql['link'], $_GET['sSearch']) . "%' OR ";
    }
    $sWhere = substr_replace($sWhere, "", -3);
    $sWhere .= ')';
}

// Query data
$sQuery = "
    SELECT SQL_CALC_FOUND_ROWS " . implode(", ", $aColumns) . "
    FROM $sTable
    $sWhere 
    $sOrder
    $sLimit
";
$rResult = mysqli_query($gaSql['link'], $sQuery) or die(mysqli_error($gaSql['link']));

// Total data yang difilter
$sQuery = "SELECT FOUND_ROWS()";
$rResultFilterTotal = mysqli_query($gaSql['link'], $sQuery) or die(mysqli_error($gaSql['link']));
$aResultFilterTotal = mysqli_fetch_array($rResultFilterTotal);
$iFilteredTotal = $aResultFilterTotal[0];

// Total data di database
$sQuery = "SELECT COUNT($sIndexColumn) FROM $sTable $sWhereDefault";
$rResultTotal = mysqli_query($gaSql['link'], $sQuery) or die(mysqli_error($gaSql['link']));
$aResultTotal = mysqli_fetch_array($rResultTotal);
$iTotal = $aResultTotal[0];

// Output JSON
$output = array(
    "sEcho" => intval($_GET['sEcho']),
    "iTotalRecords" => $iTotal,
    "iTotalDisplayRecords" => $iFilteredTotal,
    "aaData" => array()
);

$no = $_GET['iDisplayStart'] + 1;
while ($dataRow = mysqli_fetch_array($rResult)) {
    $idpaket       = $dataRow['idpaket'];
    $idbuku        = $dataRow['idbuku'];
    $idkelas       = $dataRow['idkelas'];
    $nama        = $dataRow['nama'];
    $tglpinjam     = $dataRow['tglpinjam'];
    $jampinjam     = $dataRow['jampinjam'];
    $jmlpinjam     = $dataRow['jmlpinjam'];
    $tglkembali    = $dataRow['tglkembali'];
    $jamkembali    = $dataRow['jamkembali'];
    $jmlkembali    = $dataRow['jmlkembali'];
    $judul         = $dataRow['judul'];

    $aksi = "<button type='button' data-toggle='modal' data-target='#deleteConfirmationModal' 
                data-tglpinjam='" . $dataRow['tglpinjam'] . "' 
                data-idbuku='" . $dataRow['idbuku'] . "' 
                data-idpaket='" . $dataRow['idpaket'] . "' 
                class='delPopUp btn btn-xs " . $_SESSION['warnatombol'] . " tooltips' 
                data-placement='top' data-original-title='Delete'>
                <i class='fa fa-trash-o'></i>
             </button>";

    $row = array($no, $idkelas, $nama, $tglpinjam, $judul, $jmlpinjam, $aksi);

    $no++;
    $output['aaData'][] = $row;
}

echo json_encode($output);
?>
