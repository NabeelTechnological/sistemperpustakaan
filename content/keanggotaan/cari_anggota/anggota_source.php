<?php
//security goes here

$aColumns = array('nipnis', 'nama', 'jnskel', 'idjnsang', 'pinjambuku', 'pinjamcd', 'alamat', 'alamat2');

//primary key
$sIndexColumn = "nipnis";

//nama table database 
$sTable = "vw_ranggota";

$gaSql['link'] =  mysqli_connect($gaSql['server'], $gaSql['user'], $gaSql['password']) or
    die('Could not open connection to server');

mysqli_select_db($gaSql['link'], $gaSql['db']) or
    die('Could not select database ' . $gaSql['db']);


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

// --- TIDAK ADA PERUBAHAN DI ATAS SINI ---

$sWhere = " where noapk = $_SESSION[noapk] ";
if ($_GET['sSearch'] != "") {
    $sWhere = " WHERE noapk = $_SESSION[noapk] and (";
    for ($i = 0; $i < count($aColumns); $i++) {
        $sWhere .= $aColumns[$i] . " LIKE '%" . mysqli_real_escape_string($gaSql['link'], $_GET['sSearch']) . "%' OR ";
    }
    $sWhere = substr_replace($sWhere, "", -3);
    $sWhere .= ')';
}

for ($i = 0; $i < count($aColumns); $i++) {
    if (isset($_GET['bSearchable_' . $i])) {
        if ($_GET['bSearchable_' . $i] == "true" && $_GET['sSearch_' . $i] != '') {
            if ($sWhere == "") {
                $sWhere = " WHERE noapk = $_SESSION[noapk] and ";
            } else {
                $sWhere .= " AND ";
            }
            $sWhere .= $aColumns[$i] . " LIKE '%" . mysqli_real_escape_string($gaSql['link'], $_GET['sSearch_' . $i]) . "%' ";
        }
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

$sQuery = "
    SELECT FOUND_ROWS()
";
$rResultFilterTotal = mysqli_query($gaSql['link'], $sQuery) or die(mysqli_error($gaSql['link']));
$aResultFilterTotal = mysqli_fetch_array($rResultFilterTotal);
$iFilteredTotal = $aResultFilterTotal[0];

$sQuery = "
    SELECT COUNT(" . $sIndexColumn . ")
    FROM   $sTable
";
$rResultTotal = mysqli_query($gaSql['link'], $sQuery) or die(mysqli_error($gaSql['link']));
$aResultTotal = mysqli_fetch_array($rResultTotal);
$iTotal = $aResultTotal[0];

$output = array(
    "sEcho" => intval($_GET['sEcho']),
    "iTotalRecords" => $iTotal,
    "iTotalDisplayRecords" => $iFilteredTotal,
    "aaData" => array()
);


// --- PERUBAHAN DIMULAI DARI SINI ---

$no = $_GET['iDisplayStart'] + 1;
while ($dataRow = mysqli_fetch_array($rResult)) {
    $nipnis = $dataRow['nipnis'];
    $nama   = $dataRow['nama'];
    $jnskel = $dataRow['jnskel'];
    $idjnsang   = $dataRow['idjnsang'];
    $pinjambuku = $dataRow['pinjambuku'];
    $alamat = $dataRow['alamat'];
    $alamatalt = $dataRow['alamat2'];

    // 1. Buat HTML untuk tombol hapus
    $tombol_hapus = '<button type="button" class="btn btn-danger btn-xs delPopUp" data-id="' . $nipnis . '">Hapus</button>';

    // 2. Tambahkan tombol hapus sebagai elemen terakhir di array $row
    // Pastikan urutan kolom sesuai dengan yang diharapkan oleh DataTables di halaman utama
    $row = array($no, $nipnis, $nama, $jnskel, $idjnsang, $alamat, $alamatalt, $tombol_hapus);

    $no++;
    $output['aaData'][] = $row;
}

echo json_encode($output);
?>
