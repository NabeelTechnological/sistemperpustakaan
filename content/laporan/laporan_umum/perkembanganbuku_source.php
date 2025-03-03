<?php 
$dataPilihan         = (isset($_SESSION['dataPilihan'])) ? $_SESSION['dataPilihan'] : "";
$dataTriWulan          = (isset($_SESSION['dataTriWulan'])) ? $_SESSION['dataTriWulan'] : "";
$dataTahun1          = (isset($_SESSION['dataTahun1'])) ? $_SESSION['dataTahun1'] : "";
$dataTahun2          = (isset($_SESSION['dataTahun2'])) ? $_SESSION['dataTahun2'] : "";
$tampil             = (isset($_SESSION['tampil'])) ? $_SESSION['tampil'] : "";

// kunci join jika berdasarkan kode% :
// a.kode diganti LIKE CONCAT(SUBSTRING(a.kode, 1, 1), '%')

if ($dataPilihan == "triwulanan") {
    switch ($dataTriWulan) {
        case 1:
            $bulan1 = 1;
            $bulan2 = 3;
            break;
        case 2:
            $bulan1 = 4;
            $bulan2 = 6;
            break;
        case 3:
            $bulan1 = 7;
            $bulan2 = 9;
            break;
        case 4:
            $bulan1 = 10;
            $bulan2 = 12;
            break;
    }

    if ($bulan1 == 1) {
        $sKondisi1 = "YEAR(tglentri) < $dataTahun1";
    }else{
        $sKondisi1 = "(MONTH(tglentri) < $bulan1 AND YEAR(tglentri) = $dataTahun1) OR (YEAR(tglentri) < $dataTahun1)";
    }
    $sKondisi2 = "MONTH(tglentri) >= $bulan1 AND MONTH(tglentri) <= $bulan2 AND YEAR(tglentri) = $dataTahun1";
}else if($dataPilihan == "tahunan"){
    $sKondisi1 = "YEAR(tglentri) < $dataTahun2";
    $sKondisi2 = "YEAR(tglentri) = $dataTahun2";
}

    $aColumns = array('kode','subyek','judul1','judul2','idbuku1','idbuku2');
    $sIndexColumn = "kode";
    $sSubQuery1 = "SELECT kode, COUNT(DISTINCT judul) AS judul, COUNT(idbuku) AS idbuku FROM tbuku WHERE $sKondisi1 GROUP BY kode";
    $sSubQuery2 = "SELECT kode, COUNT(DISTINCT judul) AS judul, COUNT(idbuku) AS idbuku FROM tbuku WHERE $sKondisi2 GROUP BY kode";
    $sTable = "(SELECT a.kode AS kode, a.subyek AS subyek, COALESCE(b.judul,0) AS judul1, COALESCE(c.judul,0) AS judul2, COALESCE(b.idbuku,0) AS idbuku1, COALESCE(c.idbuku,0) AS idbuku2 FROM ttemsubyek a LEFT JOIN ($sSubQuery1) b ON b.kode = a.kode LEFT JOIN ($sSubQuery2) c ON c.kode = a.kode WHERE a.noapk = $_SESSION[noapk] GROUP BY a.kode, a.subyek ORDER BY a.kode) AS tbuku";


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


$sWhereDefault = " WHERE 1 ";
$sWhere = "";
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

    $sQuery = "
    SELECT SQL_CALC_FOUND_ROWS ".str_replace(" , ", " ", implode(", ", $aColumns))." FROM   
    $sTable
    $sWhere
    $sOrder
    $sLimit
    ";   

$rResult = mysqli_query($gaSql['link'], $sQuery ) or die(mysqli_error($gaSql['link']));

$sQuery = "
SELECT FOUND_ROWS()
";
$rResultFilterTotal = mysqli_query( $gaSql['link'],$sQuery ) or die(mysqli_error($gaSql['link']));
$aResultFilterTotal = mysqli_fetch_array($rResultFilterTotal);
$iFilteredTotal = $aResultFilterTotal[0];

    $sQuery = "
    SELECT COUNT($sIndexColumn)
    FROM $sTable
    ";

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

        $kode 		            = $dataRow['kode'];
        $subyek 		        = $dataRow['subyek'];
        $judul1 		        = $dataRow['judul1'];
        $judul2 		        = $dataRow['judul2'];
        $idbuku1 		        = $dataRow['idbuku1'];
        $idbuku2 		        = $dataRow['idbuku2'];
        $persenJudul 		    = persentase($judul2,$judul1);
        $persenBuku 		    = persentase($idbuku2,$idbuku1);
        $jumlahJudul 		    = $judul1+$judul2;
        $jumlahBuku 		    = $idbuku1+$idbuku2;
        $golongan               = "[$kode] $subyek";

        $row = array($no, $golongan, $judul1,$judul2,$persenJudul,$jumlahJudul,$idbuku1,$idbuku2,$persenBuku,$jumlahBuku);  

		$no++; 
		$output['aaData'][] = $row;

	}
	echo json_encode($output);
?>