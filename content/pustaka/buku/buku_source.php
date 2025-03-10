<?php
 //security goes here
 	$aColumns = array( 'idbuku','kode', 'kodebuku','pengarang', 'judul', 'desjnsbuku', 'subyek', 'pengarangnormal','pengarang2','pengarang3','namapenerbit','nmkota','thterbit','nmbahasa','nmasalbuku','jilid','edisi','cetakan','vol','isbn','tersedia','lokasi','Cover','Cover1');

	//primary key
	$sIndexColumn = "idbuku";
	
	//nama table database 
	 $sTable = "vw_tbuku";
	// $sTable = "rjnsbuku";
	// $sTable = "vw_tbuku INNER JOIN rjnsbuku ON vw_tbuku.id_jnsbuku = rjnsbuku.id_jnsbuku";


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

	if(isset($_GET['judul'])){
		$search = $_GET['judul'];
		if(empty($search)){
			$sWhere = " where noapk = $_SESSION[noapk] ";
		}else{
		$sWhere .= " AND ( $aColumns[4] LIKE '%".mysqli_real_escape_string( $gaSql['link'], $search )."%' ) ";
		}
	}else if(isset($_GET['kode'])){
		$search = $_GET['kode'];
		if(empty($search)){
			$sWhere = " where noapk = $_SESSION[noapk] ";
		}else{
		$sWhere .= " AND ( $aColumns[1] LIKE '%".mysqli_real_escape_string( $gaSql['link'], $search )."%' ) ";
		}
	}else if(isset($_GET['idbuku'])){
		$search = $_GET['idbuku'];
		if(empty($search)){
			$sWhere = " where noapk = $_SESSION[noapk] ";
		}else{
		$sWhere .= " AND ( $aColumns[0] LIKE '%".mysqli_real_escape_string( $gaSql['link'], $search )."%' ) ";
		}
	}else if(isset($_GET['jenis'])){
		$search = $_GET['jenis'];
		if(empty($search)){
			$sWhere = " where noapk = $_SESSION[noapk] ";
		}else{
		$sWhere .= " AND ( $aColumns[5] = '".mysqli_real_escape_string( $gaSql['link'], $search )."') ";
		}
	}
	
	$sWhere = " where noapk = $_SESSION[noapk] ";
	if ( $_GET['sSearch'] != "" )
	{
		$sWhere = " WHERE noapk = $_SESSION[noapk] and (";
		for ( $i=0 ; $i<count($aColumns) ; $i++ )
		{
			$sWhere .= $aColumns[$i]." LIKE '%".mysqli_real_escape_string( $gaSql['link'], $_GET['sSearch'] )."%' OR ";
		}
		$sWhere = substr_replace( $sWhere, "", -3 );
		$sWhere .= ')';
	}
	
	for ( $i=0 ; $i<count($aColumns) ; $i++ )
	{
		if (isset($GET['bSearchable'.$i])) {
		if ( $GET['bSearchable'.$i] == "true" && $GET['sSearch'.$i] != '' )
		{
			if ( $sWhere == "" )
			{
				$sWhere = " WHERE noapk = $_SESSION[noapk] and ";
			}
			else
			{
				$sWhere .= " AND ";
			}
			$sWhere .= $aColumns[$i]." LIKE '%".mysqli_real_escape_string( $gaSql['link'],$GET['sSearch'.$i])."%' ";
		}
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
		$idbuku		= $dataRow['idbuku'];
		$kodebuku	= kodebuku($dataRow['kode'],$dataRow['pengarang'],$dataRow['judul'])."&nbsp;&nbsp; c.".$dataRow['kodebuku'];
		$judul		= $dataRow['judul'];
		$desjnsbuku		= $dataRow['desjnsbuku'];
		$kode		= $dataRow['kode'];
		$subyek		= $dataRow['subyek'];
		$pengarangnormal = $dataRow['pengarangnormal'];
		$pengarang		= $dataRow['pengarang'];
		$pengarang2		= $dataRow['pengarang2'];
		$pengarang3		= $dataRow['pengarang3'];
		$namapenerbit		= $dataRow['namapenerbit'];
		$nmkota		= $dataRow['nmkota'];
		$thterbit		= $dataRow['thterbit'];
		$nmbahasa		= $dataRow['nmbahasa'];
		$nmasalbuku		= $dataRow['nmasalbuku'];
		$jilid		= $dataRow['jilid'];
		$edisi		= $dataRow['edisi'];
		$cetakan		= $dataRow['cetakan'];
		$vol		= $dataRow['vol'];
		$isbn		= $dataRow['isbn'];
		$tersedia = desTersediaBuku($dataRow['tersedia']);

		$lokasi		= $dataRow['lokasi'];
		// $cover      =$dataRow['Cover'];
		$cover = !empty($dataRow['Cover']) 
		? "data:image/jpeg;base64," . base64_encode($dataRow['Cover']) 
		: $dataRow['Cover1'];
		
		$cover1 = ['']; 
	

		

		// $path = pathinfo($dataRow['Cover'],PATHINFO_EXTENSION);
		// $data = file_get_contents($path);
		// $cover = !empty($data) ? "data:image/jpeg;base64," . base64_encode($data) : "";
 



		$row = array($no,$idbuku,$kodebuku,$judul,$desjnsbuku,$kode,$pengarangnormal,$pengarang,$pengarang2,$pengarang3,$namapenerbit,$nmkota,$thterbit,$nmbahasa,$nmasalbuku,$jilid,$edisi,$cetakan,$vol,$isbn,$tersedia,$lokasi,$cover,$cover1); 

		$no++; 
		$output['aaData'][] = $row;
	}
	
	
	echo json_encode( $output );

?>
