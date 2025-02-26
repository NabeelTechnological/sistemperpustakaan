    <?php
    $dataHarian = isset($_SESSION['dataHarian']) ? $_SESSION['dataHarian'] : "";
    $dataBulan = isset($_SESSION['dataBulan']) ? $_SESSION['dataBulan'] : "";
    $dataTahun = isset($_SESSION['dataTahun']) ? $_SESSION['dataTahun'] : "";
    $dataDariTanggal = isset($_SESSION['dataDariTanggal']) ? $_SESSION['dataDariTanggal'] : "";
    $dataSampaiTanggal = isset($_SESSION['dataSampaiTanggal']) ? $_SESSION['dataSampaiTanggal'] : "";
    $tampil = isset($_SESSION['tampil']) ? $_SESSION['tampil'] : "";
    $dataAnggota            = (isset($_SESSION['dataAnggota'])) ? $_SESSION['dataAnggota'] : "";
    $dataBuku               = (isset($_SESSION['dataBuku'])) ? $_SESSION['dataBuku'] : "";
    $dataPilihan               = (isset($_SESSION['dataPilihan'])) ? $_SESSION['dataPilihan'] : "";


    $aColumns = array('idbuku', 'judul', 'tglpinjam', 'tglhrskembali','isterlambat','bsudenda');
    $sTable = "(SELECT a.noapk, a.idjnsbuku AS idjnsbuku, a.idbuku AS idbuku, a.judul AS judul, b.tglpinjam AS tglpinjam, b.tglhrskembali AS tglhrskembali, b.isterlambat AS isterlambat, b.bsudenda AS bsudenda, b.iskembali AS iskembali, b.tglrealkembali AS tglrealkembali, c.idjnsang AS idjnsang
    FROM tbuku a JOIN tpinbuku b ON a.idbuku = b.idbuku JOIN ranggota c ON b.nipnis = c.nipnis) AS tpinbuku";
    $sIndexColumn = "idbuku";

    $sWhereDefault = "WHERE status = 'active'";

    if ($dataHarian != "" && $dataPilihan = "harian") {
        $sWhereDefault = " WHERE tglrealkembali = '" . $dataHarian . "' ";

    } elseif ($dataBulan != "" && $dataTahun != "" && $dataPilihan = "bulanan") {
        $sWhereDefault = " WHERE MONTH(tglrealkembali) = '" . $dataBulan . "' AND YEAR(tglrealkembali) = '" . $dataTahun . "' ";

    } elseif ($dataDariTanggal != "" && $dataSampaiTanggal != ""  && $dataPilihan = "custom") {
        $sWhereDefault = " WHERE tglrealkembali >= '" . $dataDariTanggal . "' AND tglrealkembali <= '" . $dataSampaiTanggal . "' ";

    }

    if($dataAnggota=="Siswa"){
        $sWhereDefault .= " AND idjnsang = 1 ";

    }else if($dataAnggota=="Guru/Karyawan"){
        $sWhereDefault .= " AND idjnsang = 2 ";

    }

    $sWhereDefault .= "AND idjnsbuku < 4 AND iskembali=1 AND noapk = $_SESSION[noapk]";

    $gaSql['link'] = mysqli_connect($gaSql['server'], $gaSql['user'], $gaSql['password']) or die('Could not open connection to server');
    mysqli_select_db($gaSql['link'], $gaSql['db']) or die('Could not select database ' . $gaSql['db']);

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

            if ($actualColumnIndex>=0) {
                $sOrder .= $aColumns[$actualColumnIndex] . " " . mysqli_real_escape_string($gaSql['link'], $_GET['sSortDir_' . $i]) . ", ";
            }else{
                $sOrder = "";
            }
        }

        $sOrder = rtrim($sOrder, ", ");
    }

    $sWhere = $sWhereDefault;
    if ($_GET['sSearch'] != "") {
        $sWhere .= " and (";
        for ($i = 0; $i < count($aColumns); $i++) {
            $sWhere .= $aColumns[$i] . " LIKE '%" . mysqli_real_escape_string($gaSql['link'], $_GET['sSearch']) . "%' OR ";
        }
        $sWhere = substr_replace($sWhere, "", -3);
        $sWhere .= ')';
    }

    for ($i = 0; $i < count($aColumns); $i++) {
        if ($_GET['bSearchable_' . $i] == "true" && $_GET['sSearch_' . $i] != '') {
            if ($sWhere == "") {
                $sWhere .= " and ";
            } else {
                $sWhere .= " AND ";
            }
            $sWhere .= $aColumns[$i] . " LIKE '%" . mysqli_real_escape_string($gaSql['link'], $_GET['sSearch_' . $i]) . "%' ";
        }
    }

    $sQuery = "
        SELECT SQL_CALC_FOUND_ROWS " . str_replace(" , ", " ", implode(", ", $aColumns)) . " FROM   
        $sTable
        $sWhere 
        $sOrder
        $sLimit
        ";

    $rResult = mysqli_query($gaSql['link'], $sQuery) or die(mysqli_error($gaSql['link']));

    $sQuery = "SELECT FOUND_ROWS()";
    $rResultFilterTotal = mysqli_query($gaSql['link'], $sQuery) or die(mysqli_error($gaSql['link']));
    $aResultFilterTotal = mysqli_fetch_array($rResultFilterTotal);
    $iFilteredTotal = $aResultFilterTotal[0];

    $sQuery = "SELECT COUNT($sIndexColumn) FROM  $sTable $sWhereDefault";
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

            $idbuku = $dataRow['idbuku'];
            $judul = $dataRow['judul'];
            $tglpinjam = $dataRow['tglpinjam'];
            $tglhrskembali = $dataRow['tglhrskembali'];
            switch($dataRow['isterlambat']){
                case 0:
                    $isterlambat = "TIDAK";
                    break;
                case 1:
                    $isterlambat = "YA";
                    break;
            }
            $bsudenda = $dataRow['bsudenda'];

                $row = array($no, $idbuku, $judul ,$tglpinjam, $tglhrskembali, $isterlambat, $bsudenda);
            
            $no++;
            $output['aaData'][] = $row;
        
    }

    echo json_encode($output);
    ?>
