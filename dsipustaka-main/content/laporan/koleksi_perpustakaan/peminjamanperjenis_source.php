<?php 
$dataHarian         = (isset($_SESSION['dataHarian'])) ? $_SESSION['dataHarian'] : "";
$dataBulan          = (isset($_SESSION['dataBulan'])) ? $_SESSION['dataBulan'] : "";
$dataTahun          = (isset($_SESSION['dataTahun'])) ? $_SESSION['dataTahun'] : "";
$dataDariTanggal    = (isset($_SESSION['dataDariTanggal'])) ? $_SESSION['dataDariTanggal'] : "";
$dataSampaiTanggal  = (isset($_SESSION['dataSampaiTanggal'])) ? $_SESSION['dataSampaiTanggal'] : "";
$tampil             = (isset($_SESSION['tampil'])) ? $_SESSION['tampil'] : "";
$dataPilihan             = (isset($_SESSION['dataPilihan'])) ? $_SESSION['dataPilihan'] : "";

if($dataHarian != "" && $dataPilihan == "harian"){
    $sWhereDefault = " AND b.tglpinjam = '".$dataHarian."' ";
}else if($dataBulan != "" && $dataTahun != "" && $dataPilihan == "bulanan"){
    $sWhereDefault = " AND MONTH(b.tglpinjam) = '".$dataBulan."' AND YEAR(b.tglpinjam) = '".$dataTahun."'  ";
}else if($dataDariTanggal != "" && $dataSampaiTanggal != "" && $dataPilihan == "custom"){
    $sWhereDefault = " AND b.tglpinjam >= '".$dataDariTanggal."' AND b.tglpinjam <= '".$dataSampaiTanggal."' ";
} 

$sWhereDefault .= " AND b.noapk = $_SESSION[noapk] ";

if($tampil == "tampilPerKlasifikasi"){
    for ($i=0; $i <= 9 ; $i++) { 
        $qry = "UPDATE ttemsubyek SET jumlah = (SELECT count(b.idbuku) from tbuku a join tpinbuku b on a.idbuku=b.idbuku WHERE a.kode LIKE '$i%' AND a.idjnsbuku < 4 $sWhereDefault) WHERE kode = '".$i."00' AND noapk = $_SESSION[noapk] ";
        $stmt = mysqli_prepare($koneksidb,$qry);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }

    //kolom
    $aColumns = array('kode','subyek','jumlah');
    //nama table database
    $sTable = "ttemsubyek";
    //primary key
    $sIndexColumn = "kode";

    $sWhereDefault = " WHERE noapk = $_SESSION[noapk] ";
    
}else if($tampil == "tampilPerJenis"){
    //kolom
    $aColumns = array('desjnsbuku','tglpinjam');
    //nama table database
    $sTable = " (SELECT a.desjnsbuku AS desjnsbuku, b.tglpinjam AS tglpinjam, a.idjnsbuku AS idjnsbuku FROM rjnsbuku a LEFT JOIN tbuku c ON a.idjnsbuku = c.idjnsbuku LEFT JOIN tpinbuku b ON c.idbuku = b.idbuku $sWhereDefault) AS tpinbuku ";
    //primary key
    $sIndexColumn = "desjnsbuku";
    $sGroup = "GROUP BY desjnsbuku";
}


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
            if($tampil == "tampilPerKlasifikasi"){ 
            $sOrder .= "kode ASC, ";
            }else if($tampil == "tampilPerJenis"){
            $sOrder .= "idjnsbuku ASC, ";
            }
        }
    }

    $sOrder = rtrim($sOrder, ", ");
}

if($tampil == "tampilPerKlasifikasi"){ 
    $sWhere = " ";
    if ( $_GET['sSearch'] != "" )
    {
    $sWhere = " and (";
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
            $sWhere = " and ";
        }
        else
        {
            $sWhere .= " AND ";
        }
        $sWhere .= $aColumns[$i]." LIKE '%".mysqli_real_escape_string($gaSql['link'],$_GET['sSearch_'.$i])."%' ";
    }
    }

}else if($tampil == "tampilPerJenis"){
    $sWhere = " WHERE 1 ";
    if ( $_GET['sSearch'] != "" )
    {
    $sWhere = " WHERE 1 and (";
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
            $sWhere = " WHERE 1 and ";
        }
        else
        {
            $sWhere .= " AND ";
        }
        $sWhere .= $aColumns[$i]." LIKE '%".mysqli_real_escape_string($gaSql['link'],$_GET['sSearch_'.$i])."%' ";
    }
    }
}



if($tampil == "tampilPerKlasifikasi"){ 
    $sQuery = "
    SELECT SQL_CALC_FOUND_ROWS ".str_replace(" , ", " ", implode(", ", $aColumns))." FROM   
    $sTable
    $sWhereDefault
    $sWhere
    $sOrder
    $sLimit
    ";   

}else if($tampil == "tampilPerJenis"){
    $sQuery = "
    SELECT SQL_CALC_FOUND_ROWS ".str_replace(" , ", " ", implode(", ", $aColumns)).",COUNT(tglpinjam) AS jmlpinjam FROM   
    $sTable
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

if($tampil == "tampilPerKlasifikasi"){ 
    $sQuery = "
    SELECT COUNT($sIndexColumn)
    FROM $sTable
    $sWhereDefault
    ";

}else if($tampil == "tampilPerJenis"){
    $sQuery = "
    SELECT COUNT(*)
    FROM (SELECT ".str_replace(" , ", " ", implode(", ", $aColumns))." ,COUNT(*) AS jmlpinjam
    FROM   $sTable
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
            if($tampil == "tampilPerKlasifikasi"){
                $kode 		        = $dataRow['kode'];
                $subyek 		    = $dataRow['subyek'];
                $jumlah 		    = $dataRow['jumlah'];
                $row = array($no, $kode, $subyek, $jumlah);  

            }else if($tampil == "tampilPerJenis"){
                $desjnsbuku 		= $dataRow['desjnsbuku'];
                $jmlpinjam 		    = $dataRow['jmlpinjam'];
                $row = array($no, $desjnsbuku, $jmlpinjam);  
            }

		$no++; 
		$output['aaData'][] = $row;
	}
	echo json_encode($output);
?>