<?php
 //security goes here
 $dataSubyek  = (isset($_SESSION['dataSubyek'])) ? $_SESSION['dataSubyek'] : "";
 $tampil             = (isset($_SESSION['tampil'])) ? $_SESSION['tampil'] : "";

 	$aColumns = array( 'idbuku','kode', 'kodebuku','pengarang','tglentri','judul', 'desjnsbuku', 'subyek', 'pengarangnormal','pengarang2','pengarang3','namapenerbit','nmkota','thterbit','nmbahasa','nmasalbuku','jilid','edisi','cetakan','isbn','tersedia','lokasi');

	//primary key
	$sIndexColumn = "idbuku";
	
	//nama table database 
	$sTable = "vw_tbuku";

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
                $sOrder .= "judul ASC, ";
            }
        }
    
        $sOrder = rtrim($sOrder, ", ");
    }
	
	$sWhere = " WHERE kode LIKE '$dataSubyek%' AND noapk = $_SESSION[noapk] ";

    if ( $_GET['sSearch'] != "" )
    {
    $sWhere .= " and (";
    for ( $i=0 ; $i<count($aColumns) ; $i++ )
    {
        $sWhere .= $aColumns[$i]." LIKE '%".mysqli_real_escape_string( $gaSql['link'],$_GET['sSearch'] )."%' OR ";
    }
    $sWhere = substr_replace( $sWhere, "", -3 );
    $sWhere .= ')';
    }
    
    for ( $i=0 ; $i<count($aColumns) ; $i++ )
    {
		if(isset($_GET['bSearchable_'.$i])){
    if ( $_GET['bSearchable_'.$i] == "true" && $_GET['sSearch_'.$i] != '' )
    {
        if ( $sWhere == "" )
        {
            $sWhere .= " and ";
        }
        else
        {
            $sWhere .= " AND ";
        }
        $sWhere .= $aColumns[$i]." LIKE '%".mysqli_real_escape_string($gaSql['link'],$_GET['sSearch_'.$i])."%' ";
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
		$isbn		= $dataRow['isbn'];

		switch($dataRow['tersedia']){
			case '0':
				$tersedia = "DIPINJAM";
				break;
			case '1':
				$tersedia = "ADA";
				break;
			case '2':
				$tersedia = "RUSAK";
				break;
			case '3':
				$tersedia = "HILANG";
				break;
		  }

		$lokasi		= $dataRow['lokasi'];
        $tglentri   = $dataRow['tglentri'];

		$row = array($no,$idbuku,$kodebuku,$tglentri,$judul,$desjnsbuku,$kode,$subyek,$pengarang,$pengarang2,$pengarang3,$namapenerbit,$nmkota,$thterbit,$nmbahasa,$nmasalbuku,$jilid,$edisi,$cetakan,$isbn,$tersedia,$lokasi); 

		$no++; 
		$output['aaData'][] = $row;
	}
	
	echo json_encode( $output );

?>
