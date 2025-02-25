<?php
 //security goes here

if($_GET['table']=="sample_2"){
	// if($_GET['jenis']=="buku"){
	
		$aColumns = array('ispaket', 'idbuku','judul','desjnsbuku','tglpinjam','tglhrskembali', 'isterlambat');
	    //    $aColumns = array('id','idbuku','ispaket','','judul','desjnsbuku','tglpinjam','tglhrskembali','isterlambat');
		//primary key
		$sIndexColumn = "idbuku";
		
		//nama table database 
		$sTable = "vw_tpinbuku";
	
	// }else if($_GET['jenis']=="cd"){
	
	// 	$aColumns = array('idcd', 'judul','tglpinjam','tglhrskembali','isterlambat');
	
	// 	//primary key
	// 	$sIndexColumn = "idcd";
		
	// 	//nama table database 
	// 	$sTable = "vw_tpincd";
	// }

	$sWhereDefault = " WHERE nipnis ='$_GET[id]' AND iskembali = 0 AND noapk = $_SESSION[noapk]";

}else if($_GET['table']=="sample_3"){
	// if($_GET['jenis']=="buku"){
	
		$aColumns = array('ispaket', 'idbuku','judul','desjnsbuku','tglpinjam','tglhrskembali', 'isterlambat','bsudenda');
	
		//primary key
		$sIndexColumn = "idbuku";
		
		//nama table database 
		$sTable = "vw_tpinbuku";
	
	// }else if($_GET['jenis']=="cd"){
	
	// 	$aColumns = array('idcd', 'judul','tglpinjam','tglhrskembali','isterlambat','bsudenda');
	
	// 	//primary key
	// 	$sIndexColumn = "idcd";
		
	// 	//nama table database 
	// 	$sTable = "vw_tpincd";
	// }

	$sWhereDefault = " WHERE nipnis ='$_GET[id]' AND iskembali = 1 AND tglrealkembali = CURDATE() AND noapk = $_SESSION[noapk]";
}


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

    $sWhere = $sWhereDefault;
	if ( $_GET['sSearch'] != "" )
	{
		$sWhere .= " and (";
		for ( $i=0 ; $i<count($aColumns) ; $i++ )
		{
			$sWhere .= $aColumns[$i]." LIKE '%".mysqli_real_escape_string( $gaSql['link'], $_GET['sSearch'] )."%' OR ";
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
				$sWhere = " $sWhereDefault and ";
			}
			else
			{
				$sWhere .= " AND ";
			}
			$sWhere .= $aColumns[$i]." LIKE '%".mysqli_real_escape_string( $gaSql['link'],$_GET['sSearch_'.$i])."%' ";
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
        $sWhereDefault
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

		$judul = $dataRow['judul'];
		$tglpinjam   = $dataRow['tglpinjam'];
		$tglhrskembali   = $dataRow['tglhrskembali'];
		switch($dataRow['isterlambat']){
			case '0':
				$terlambat = "TIDAK";
				break;
			case '1':
				$terlambat = "YA";
				break;
		}

		if($_GET['table']=="sample_3"){
			$denda = $dataRow['bsudenda'];
		}

		// if($_GET['jenis']=="buku"){
			switch($dataRow['ispaket']){
				case '0':
					$ispaket = "NON PAKET";
					break;
				case '1':
					$ispaket = "PAKET";
					break;
			}
			$idbuku   = $dataRow['idbuku'];
			// $kode = $dataRow['kode'];
			$jenis = $dataRow['desjnsbuku'];

			if($_GET['table']=="sample_2"){
				$row = array( $no, $idbuku, $ispaket, $judul, $tglpinjam, $tglhrskembali, $terlambat); 
	
			} else if($_GET['table']=="sample_3"){
				$row = array( $no, $idbuku, $ispaket, $judul, $tglpinjam, $tglhrskembali, $terlambat, $denda); 
	
			}
	
	
		// } else if($_GET['jenis']=="cd"){
		// 	$idcd   = $dataRow['idcd'];
	
		// 	if($_GET['table']=="sample_2"){
		// 		$row = array( $no, $idcd, $judul, $tglpinjam, $tglhrskembali, $terlambat); 
	
		// 	} else if($_GET['table']=="sample_3"){
		// 		$row = array( $no, $idcd, $judul, $tglpinjam, $tglhrskembali, $terlambat, $denda); 
	
		// 	}
		// }

		$no++; 
		$output['aaData'][] = $row;
	}
	
   
        echo json_encode( $output );

?>
