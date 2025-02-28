<?php
 //security goes here
 
 	$aColumns = array('nipnis','nama');

	//primary key
	$sIndexColumn = "nipnis";
	
	//nama table database 
	$sTable = "ranggota";

	$gaSql['link'] =  mysqli_connect( $gaSql['server'], $gaSql['user'], $gaSql['password']  ) or
		die( 'Could not open connection to server' );
	
	mysqli_select_db( $gaSql['link'], $gaSql['db']) or 
		die( 'Could not select database '. $gaSql['db'] );
	

	$sLimit = "";
	if ( isset( $_GET['iDisplayStart'] ) && $_GET['iDisplayLength'] != '-1' )
	{
		$sLimit = "LIMIT ".mysqli_real_escape_string( $gaSql['link'], $_GET['iDisplayStart'] ).", ".
			mysqli_real_escape_string( $gaSql['link'], $_GET['iDisplayLength'] );
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
	
	$sWhere = " where noapk = $_SESSION[noapk] ";

if(isset($_GET['nama'])){
	$search = $_GET['nama'];
	if(empty($search)){
		$sWhere = " where noapk = $_SESSION[noapk] ";
	}else{
		$sWhere .= " AND ( $aColumns[1] LIKE '%".mysqli_real_escape_string( $gaSql['link'], $search )."%' ) ";
	}
}else if(isset($_GET['noindividu'])){
	$search = $_GET['noindividu'];
	if(empty($search)){
		$sWhere = " where noapk = $_SESSION[noapk] ";
	}else{
		$sWhere .= " AND ( $aColumns[0] LIKE '".mysqli_real_escape_string( $gaSql['link'], $search )."' ) ";
	}
}else if($_GET['searchFrom']!="" && $_GET['searchTo']!=""){
	$search1 = $_GET['searchFrom'];
	$search2 = $_GET['searchTo'];
	$sWhere = " where noapk = $_SESSION[noapk] ";
	if(!empty($search2)){
		$sWhere .= " AND ( CAST($aColumns[0] AS SIGNED) >= '".mysqli_real_escape_string( $gaSql['link'], $search1 )."' AND CAST($aColumns[0] AS SIGNED) <= '".mysqli_real_escape_string( $gaSql['link'], $search2 )."' )";
	}
}
	
	$sQuery = "
		SELECT SQL_CALC_FOUND_ROWS ".str_replace(" , ", " ", implode(", ", $aColumns))."
		FROM $sTable
		$sWhere 
		$sOrder
		$sLimit
	";
	$rResult = mysqli_query( $gaSql['link'], $sQuery ) or die(mysqli_error($gaSql['link']));
	
	$sQuery = "
		SELECT FOUND_ROWS()
	";
	$rResultFilterTotal = mysqli_query( $gaSql['link'], $sQuery ) or die(mysqli_error($gaSql['link']));
	$aResultFilterTotal = mysqli_fetch_array($rResultFilterTotal);
	$iFilteredTotal = $aResultFilterTotal[0];
	
	$sQuery = "
		SELECT COUNT(".$sIndexColumn.")
		FROM   $sTable
	";
	$rResultTotal = mysqli_query( $gaSql['link'], $sQuery ) or die(mysqli_error($gaSql['link']));
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
    $nipnis = $dataRow['nipnis'];
    $nama   = $dataRow['nama'];

	  $aksi 	  = "<input type='checkbox' name='selected_data[]' value='$nipnis'>"; 
	     
	    //CEK Hari Libur
		$row = array( $no, $nipnis, $nama, $aksi); 

		$no++; 
		$output['aaData'][] = $row;
	}
	
	echo json_encode( $output );
?>