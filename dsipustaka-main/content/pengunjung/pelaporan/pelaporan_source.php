<?php 

$dataHarian         = (isset($_SESSION['dataHarian'])) ? $_SESSION['dataHarian'] : "";
$dataBulan          = (isset($_SESSION['dataBulan'])) ? $_SESSION['dataBulan'] : "";
$dataTahun          = (isset($_SESSION['dataTahun'])) ? $_SESSION['dataTahun'] : "";
$dataDariTanggal    = (isset($_SESSION['dataDariTanggal'])) ? $_SESSION['dataDariTanggal'] : "";
$dataSampaiTanggal  = (isset($_SESSION['dataSampaiTanggal'])) ? $_SESSION['dataSampaiTanggal'] : "";
$dataPilihan  = (isset($_SESSION['dataPilihan'])) ? $_SESSION['dataPilihan'] : "";
$tampil             = (isset($_SESSION['tampil'])) ? $_SESSION['tampil'] : "";

if($dataHarian != "" && $dataPilihan == "harian"){
    //kolom
    $aColumns = array('tglkunjung','nipnis','nama');
    //nama table database
    $sTable = " (SELECT a.noapk AS noapk, a.tglkunjung AS tglkunjung, a.stkunjung AS stkunjung, a.nipnis AS nipnis, b.nama AS nama FROM tkunjung a LEFT JOIN ranggota b ON a.nipnis = b.nipnis) AS tkunjung ";
    //kondisi utama
    $sWhereDefault = " WHERE tglkunjung = '".$dataHarian."' ";
    //primary key
    $sIndexColumn = "nipnis";
}else if($dataBulan != "" && $dataTahun != "" && $dataPilihan == "bulanan"){
    $aColumns = array('tglkunjung');
    $sTable = " tkunjung ";
    $sWhereDefault = " WHERE MONTH(tglkunjung) = '".$dataBulan."' AND YEAR(tglkunjung) = '".$dataTahun."'  ";
}else if($dataDariTanggal != "" && $dataSampaiTanggal != "" && $dataPilihan == "custom"){
    $aColumns = array('tglkunjung');
    $sTable = " tkunjung ";
    $sWhereDefault = " WHERE tglkunjung >= '".$dataDariTanggal."' AND tglkunjung <= '".$dataSampaiTanggal."' ";
} 

$sWhereDefault .= " AND noapk = $_SESSION[noapk] ";

$gaSql['link'] =  mysqli_connect( $gaSql['server'], $gaSql['user'], $gaSql['password']  ) or
die( 'Could not open connection to server' );

mysqli_select_db( $gaSql['link'], $gaSql['db']) or 
die( 'Could not select database '. $gaSql['db'] );

$sLimit = "";
if ( isset( $_GET['iDisplayStart'] ) && $_GET['iDisplayLength'] != '-1' )
{
$sLimit = "LIMIT ".mysqli_real_escape_string( $gaSql['link'],$_GET['iDisplayStart'] ).", ".
    mysqli_real_escape_string($gaSql['link'], $_GET['iDisplayLength'] );
}

if (isset($_GET['iSortCol_0'])) {
    $sOrder = "ORDER BY ";
    $sortingCols = intval($_GET['iSortingCols']); 
    for ($i = 0; $i < $sortingCols; $i++) {
        $sortableColumnIndex = intval($_GET['iSortCol_' . $i]);
        
        $actualColumnIndex = $sortableColumnIndex - 1;

        if ($actualColumnIndex>=0) {
            $sOrder .= $aColumns[$actualColumnIndex] . " " . mysqli_real_escape_string($gaSql['link'], $_GET['sSortDir_' . $i]) . ", ";
        }else{
            $sOrder = "";
        }
    }

    $sOrder = rtrim($sOrder, ", ");
}

$sGroup = " GROUP BY tglkunjung ";

if($tampil == "tampilAnggota"){
    $sWhereDefault .= " AND stkunjung = 'A' ";
}else if($tampil == "tampilTamu"){
    $sWhereDefault .= " AND stkunjung = 'T' ";
}

if ( $_GET['sSearch'] != "" )
{
$sWhere = $sWhereDefault. " and (";
for ( $i=0 ; $i<count($aColumns) ; $i++ )
{
    $sWhere .= $aColumns[$i]." LIKE '%".mysqli_real_escape_string( $gaSql['link'],$_GET['sSearch'] )."%' OR ";
}
$sWhere = substr_replace( $sWhere, "", -3 );
$sWhere .= ')';
}

for ( $i=0 ; $i<count($aColumns) ; $i++ )
{
if ( $_GET['bSearchable_'.$i] == "true" && $_GET['sSearch_'.$i] != '' )
{
    if ( $sWhere == "" )
    {
        $sWhere = $sWhereDefault. " and ";
    }
    else
    {
        $sWhere .= " AND ";
    }
    $sWhere .= $aColumns[$i]." LIKE '%".mysqli_real_escape_string($gaSql['link'],$_GET['sSearch_'.$i])."%' ";
}
}

if(!isset($sWhere)){
    $sWhere = $sWhereDefault;
}

if($dataHarian != "" && $dataPilihan == "harian"){
    $sQuery = "
    SELECT SQL_CALC_FOUND_ROWS ".str_replace(" , ", " ", implode(", ", $aColumns))." FROM   
    $sTable
    $sWhere 
    $sOrder
    $sLimit
    ";
}else{
    $sQuery = "
    SELECT SQL_CALC_FOUND_ROWS ".str_replace(" , ", " ", implode(", ", $aColumns))." ,COUNT(*) AS jmlpengunjung FROM   $sTable
    $sWhere 
    $sGroup
    $sOrder
    $sLimit
    ";
}

$rResult = mysqli_query($gaSql['link'], $sQuery ) or die(mysqli_error($gaSql['link']));

$sQuery = "
SELECT FOUND_ROWS()
";
$rResultFilterTotal = mysqli_query( $gaSql['link'],$sQuery ) or die(mysqli_error($gaSql['link']));
$aResultFilterTotal = mysqli_fetch_array($rResultFilterTotal);
$iFilteredTotal = $aResultFilterTotal[0];


if($dataHarian != "" && $dataPilihan == "harian"){
    $sQuery = "
    SELECT COUNT($sIndexColumn)
    FROM  $sTable
    $sWhereDefault
    ";
}else{
    $sQuery = "
    SELECT COUNT(*)
    FROM  (SELECT ".str_replace(" , ", " ", implode(", ", $aColumns))." ,COUNT(*) AS jmlpengunjung
    FROM   $sTable
    $sWhereDefault
    $sGroup
    ) AS subquery
    ";
}

$rResultTotal = mysqli_query( $gaSql['link'],$sQuery ) or die(mysqli_error($gaSql['link']));
$aResultTotal = mysqli_fetch_array($rResultTotal);
$iTotal = $aResultTotal[0];

$output = array(
"sEcho" => intval($_GET['sEcho']),
"iTotalRecords" => $iTotal,
"iTotalDisplayRecords" => $iFilteredTotal,
"aaData" => array()
);

$no = $_GET['iDisplayStart'] + 1;
	while ( $dataRow = mysqli_fetch_array( $rResult ) )
	{
        $tanggal 		= $dataRow['tglkunjung'];
        if($dataHarian != "" && $dataPilihan == "harian"){
            $nipnis         =  $dataRow['nipnis'];
            $nama           =  $dataRow['nama'];

            if($tampil == "tampilAnggota"){
                $row = array($no, $tanggal, $nipnis, $nama);      
            }else if($tampil == "tampilTamu"){
                $row = array($no, $tanggal, "-", $nipnis);  
            }
        }else{
            $jumlah_pengunjung = $dataRow['jmlpengunjung'];
            $row = array($no, $tanggal, $jumlah_pengunjung); 
        }
		$no++; 
		$output['aaData'][] = $row;
	}
	echo json_encode($output);
?>