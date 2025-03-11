<?php
include "inc.connection.php";
date_default_timezone_set("Asia/Jakarta");

function getnmsekolah($koneksidb){
    $sistem = mysqli_prepare($koneksidb,"SELECT nmsekolah FROM rsekolah WHERE noapk = $_SESSION[noapk]");
    mysqli_stmt_execute($sistem);
    mysqli_stmt_bind_result($sistem,$nmsekolah);
    mysqli_stmt_fetch($sistem);
    mysqli_stmt_close($sistem);

    return $nmsekolah;
}

function logTransaksi($Iduser, $Tanggal, $Aktivitas, $noapk)
{
    global $koneksidb;

    $sql = "INSERT INTO tlogtransaksi (iduser, tanggal, aktivitas, noapk) VALUES (?, ?, ?,?)";
    $stmt = mysqli_prepare($koneksidb, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "sssi", $Iduser, $Tanggal, $Aktivitas, $noapk);

        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            
        } else {
            die("Kesalahan saat menjalankan pernyataan: " . mysqli_error($koneksidb));
        }
    } else {
        die("Kesalahan saat mempersiapkan pernyataan: " . mysqli_error($koneksidb));
    }
}


function persentase($pembilang,$penyebut){
    try {
        $persentase = ($pembilang/$penyebut) * 100;
        $hasil = str_replace('.',',',number_format($persentase, 2))."%";
    } catch (DivisionByZeroError $e) {
        $hasil = "-";
    }

    return $hasil;
}

function desTersediaBuku($tersedia){
  switch($tersedia){
    case '0':
      return "DIPINJAM";
      break;
    case '1':
      return "ADA";
      break;
    case '2':
      return "RUSAK";
      break;
    case '3':
      return "HILANG";
      break;
    }
}

function isBukuIersedia($koneksidb,$dataIdBuku){
  $qry = "SELECT tersedia FROM tbuku WHERE idbuku = ? AND noapk = $_SESSION[noapk]";
  $stmt = mysqli_prepare($koneksidb,$qry) or die("Gagal menyiapkan statement: " . mysqli_error($koneksidb));
  mysqli_stmt_bind_param($stmt,"s",$dataIdBuku);
  mysqli_stmt_execute($stmt) or die("Gagal Query Cek Buku : " . mysqli_error($koneksidb));
  mysqli_stmt_bind_result($stmt,$dataTersedia);
  mysqli_stmt_fetch($stmt);
  mysqli_stmt_close($stmt);
  return $dataTersedia;
}

function isIdBuku($koneksidb,$dataIdBuku){
  $qry = "SELECT COUNT(*) as count FROM tbuku WHERE idbuku = ? AND noapk = $_SESSION[noapk]";
  $stmt = mysqli_prepare($koneksidb,$qry) or die("Gagal menyiapkan statement: " . mysqli_error($koneksidb));
  mysqli_stmt_bind_param($stmt,"s",$dataIdBuku);
  mysqli_stmt_execute($stmt) or die("Gagal Query Cek Buku : " . mysqli_error($koneksidb));
  mysqli_stmt_bind_result($stmt,$cek);
  mysqli_stmt_fetch($stmt);
  mysqli_stmt_close($stmt);
  return $cek;
}

function getIdBukuTerakhir($koneksidb){
    $dataSql = "SELECT idbuku FROM tbuku WHERE noapk = $_SESSION[noapk] ORDER BY idbuku DESC LIMIT 1";
    $dataQry = mysqli_query($koneksidb, $dataSql) or die("Gagal Query" . mysqli_error($koneksidb));
    $dataRow = mysqli_fetch_assoc($dataQry);
    return $dataRow['idbuku']; 
}

function getBerlaku($koneksidb,$id){
  $txtID = $id;

  $qry = "SELECT berlaku FROM ranggota WHERE nipnis = ? AND noapk = $_SESSION[noapk]";
  $stmt = mysqli_prepare($koneksidb, $qry) or die("Gagal menyiapkan statement: " . mysqli_error($koneksidb));
  mysqli_stmt_bind_param($stmt, "s", $txtID);
  mysqli_stmt_execute($stmt) or die("Gagal Query Cek Berlaku : " . mysqli_error($koneksidb));
  mysqli_stmt_bind_result($stmt,$berlaku);
  mysqli_stmt_fetch($stmt);
  mysqli_stmt_close($stmt);
 
  return $berlaku;
}

function isPencatatan($koneksidb,$id){
  $txtID = $id;

  $qry = "SELECT tglkunjung FROM tkunjung WHERE nipnis = ? AND tglkunjung = CURDATE() AND noapk = $_SESSION[noapk]";
  $stmt = mysqli_prepare($koneksidb, $qry) or die("Gagal menyiapkan statement: " . mysqli_error($koneksidb));
  mysqli_stmt_bind_param($stmt, "s", $txtID);
  mysqli_stmt_execute($stmt) or die("Gagal Query Cek Jika Sudah Pencatatan Hari Ini : " . mysqli_error($koneksidb));
  mysqli_stmt_bind_result($stmt,$tglkunjung);
  mysqli_stmt_fetch($stmt);
  mysqli_stmt_close($stmt);
 
  return $tglkunjung;
}

function uploadFoto($file){
  $dataPhoto = strtotime(date('y-m-d H:i')) . '_' . $_FILES[$file]['name'];
  move_uploaded_file($_FILES[$file]['tmp_name'], 'photo/' . $dataPhoto);
  return "photo/".$dataPhoto;
}

 function kodebuku($kode,$pengarang,$judul){
    return $kode." / ".strtoupper(substr($pengarang,0,3))." / ".strtolower(substr($judul,0,1));
 }

 function indonesiaTglPanjang($tanggal){
  $tgllengkap = $tanggal;
  if (substr($tanggal,2,1)!="-"){
    $tgl=substr($tanggal,8,2);
    $bln=substr($tanggal,5,2);
    $thn=substr($tanggal,0,4);
    $tgllengkap = $tgl." ".namaBulanIndonesia(intval($bln))." ".$thn;
  }
return $tgllengkap;
}


// FUNGSI-FUNGSI DEFAULT

function namaBulanIndonesia($pangkabulan){
    $hasil ="";
    if ($pangkabulan==1) {$hasil = "Januari";}
    else  if ($pangkabulan==2) {$hasil = "Februari";}   
    else  if ($pangkabulan==3) {$hasil = "Maret";}
    else  if ($pangkabulan==4) {$hasil = "April";} 
    else  if ($pangkabulan==5) {$hasil = "Mei";} 
    else  if ($pangkabulan==6) {$hasil = "Juni";} 
    else  if ($pangkabulan==7) {$hasil = "Juli";} 
    else  if ($pangkabulan==8) {$hasil = "Agustus";} 
    else  if ($pangkabulan==9) {$hasil = "September";} 
    else  if ($pangkabulan==10) {$hasil = "Oktober";} 
    else  if ($pangkabulan==11) {$hasil = "November";} 
    else  if ($pangkabulan==12) {$hasil = "Desember";}  

    return $hasil;    
}
 

function jin_gfile($txt) {
	$txt = preg_replace("/[^a-zA-Z0-9s.]/", "_", trim($txt));
	return $txt;
}

function buatKode($length)
{
    $str        = "";
    $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ123456789';
    $max        = strlen($characters) - 1;
    for ($i = 0; $i < $length; $i++) {
        $rand = mt_rand(0, $max);
        $str .= $characters[$rand];
    }
    return $str;
}

# Fungsi untuk membalik tanggal dari format Indo -> English
function InggrisTgl($tanggal){
    $awal = $tanggal;
    //cek apakah sudah inggris 
    if (substr($tanggal,4,1)!="-"){
        $tgl=substr($tanggal,0,2);
        $bln=substr($tanggal,3,2);
        $thn=substr($tanggal,6,4);
        $awal="$thn-$bln-$tgl";
        
    }
	return $awal;
}
 

# Fungsi untuk membalik tanggal dari format English -> Indo
function IndonesiaTgl($tanggal){
    $awal = $tanggal;
    if (substr($tanggal,2,1)!="-"){
    	$tgl=substr($tanggal,8,2);
    	$bln=substr($tanggal,5,2);
    	$thn=substr($tanggal,0,4);
    	$awal="$tgl-$bln-$thn";
    }
	return $awal;
}

function format_tgl($tanggal){
  $tgl=substr($tanggal,0,2);
  $bln=substr($tanggal,3,2);
  $thn=substr($tanggal,6,4);
  $time=substr($tanggal,11,5);
  $awal="$thn-$bln-$tgl $time";
  return $awal;
}

# Fungsi untuk membuat format rupiah pada angka (uang)
function format_angka_desimal($angka,$desimal) {
    $hasil =  number_format($angka,$desimal, ".",",");
    return $hasil;
}

function format_angka($angka) {
	$hasil =  number_format($angka,0, ",",".");
	return $hasil;
}

function format_angka2($angka) {
	$hasil =  number_format($angka,0, ",",",");
	return $hasil;
}

# Fungsi untuk menghitung umur
function umur($birthday){
	date_default_timezone_set("Asia/Jakarta");

	list($year,$month,$day) = explode("-",$birthday);
	$year_diff = date("Y") - $year;
	$month_diff = date("m") - $month;
	$day_diff = date("d") - $day;
	if ($month_diff < 0) $year_diff--;
	elseif (($month_diff==0) && ($day_diff < 0)) $year_diff--;
	return $year_diff;
}

function dateDiff($time1, $time2, $precision = 6) {
    if (!is_int($time1)) {
      $time1 = strtotime($time1);
    }
    if (!is_int($time2)) {
      $time2 = strtotime($time2);
    }

    if ($time1 > $time2) {
      $ttime = $time1;
      $time1 = $time2;
      $time2 = $ttime;
    }

    $intervals = array('Year','Month','Day','Hour','Minute','Second');
    $diffs = array();

    foreach ($intervals as $interval) {
      $diffs[$interval] = 0;
      $ttime = strtotime("+1 " . $interval, $time1);
      while ($time2 >= $ttime) {
$time1 = $ttime;
$diffs[$interval]++;
$ttime = strtotime("+1 " . $interval, $time1);
      }
    }

    $count = 0;
    $times = array();
    foreach ($diffs as $interval => $value) {
      if ($count >= $precision) {
break;
      }
      if ($value > 0) {
if ($value != 1) {
 $interval .= "s";
}
$times[] = $value . " " . $interval;
$count++;
      }
    }

    return implode(", ", $times);
  }

function get_age($birth_date){
date_default_timezone_set("Asia/Jakarta");
return floor((time() - strtotime($birth_date))/31556926);
}
function sekianLama($format, $wkt) {
    $sekarang = date("Y-m-d");
    return date($format, strtotime(date("Y-m-d", strtotime($sekarang)) . " " . $wkt));
}
function restore($file) {
	global $rest_dir;
	$koneksi=($GLOBALS["___mysqli_ston"] = mysqli_connect("localhost", "root", "root"));
	mysqli_select_db($koneksi, artikel);
	
	$nama_file	= $file['name'];
	$ukrn_file	= $file['size'];
	$tmp_file	= $file['tmp_name'];
	
	if ($nama_file == "")
	{
		echo "Fatal Error";
	}
	else
	{
		$alamatfile	= $rest_dir.$nama_file;
		$templine	= array();
		
		if (move_uploaded_file($tmp_file , $alamatfile))
		{
			
			$templine	= '';
			$lines		= file($alamatfile);

			foreach ($lines as $line)
			{
				if (substr($line, 0, 2) == '--' || $line == '')
					continue;
			 
				$templine .= $line;

				if (substr(trim($line), -1, 1) == ';'){
					mysqli_query($GLOBALS["___mysqli_ston"], $templine); 
					$templine = '';
				}
			}
			echo "<div class='alert alert-success alert-dismissable'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
					<i class='icon-ok'></i> &nbsp;Berhasil Restore Database</div>";
		
		}else{
			echo "Proses upload gagal, kode error = " . $file['error'];
		}	
	}
	
}

function paginate_one($reload, $hal, $tpages) {
	
	$firstlabel = "<i class='fa fa-angle-double-left'></i>";
	$prevlabel  = "<i class='fa fa-angle-left'></i>";
	$nextlabel  = "<i class='fa fa-angle-right'></i>";
	$lastlabel  = "<i class='fa fa-angle-double-right'></i>";
	
	$out = "<div class='btn-group'>";
	
	// first
	if($hal>1) {
		$out.= "<a class='btn btn-default' href=\"" . $reload . "\">" . $firstlabel . "</a>";
	}
	else {
		$out.= "<span class='btn btn-default'>" . $firstlabel . "</span>";
	}
	
	// previous
	if($hal==1) {
		$out.= "<span class='btn btn-default'>" . $prevlabel . "</span>";
	}
	elseif($hal==2) {
		$out.= "<a class='btn btn-default' href=\"" . $reload . "\">" . $prevlabel . "</a>";
	}
	else {
		$out.= "<a class='btn btn-default' href=\"" . $reload . "&amp;hal=" . ($hal-1) . "\">" . $prevlabel . "</a>";
	}
	
	// current
	$out.= "<span class='btn btn-default' class=\"current\">Page " . $hal . " From " . $tpages ."</span>";
	
	// next
	if($hal<$tpages) {
		$out.= "<a class='btn btn-default' href=\"" . $reload . "&amp;hal=" .($hal+1) . "\">" . $nextlabel . "</a>";
	}
	else {
		$out.= "<span class='btn btn-default'>" . $nextlabel . "</span></li>";
	}
	
	// last
	if($hal<$tpages) {
		$out.= "<a class='btn btn-default' href=\"" . $reload . "&amp;hal=" . $tpages . "\">" . $lastlabel . "</a>";
	}
	else {
		$out.= "<span class='btn btn-default'>" . $lastlabel . "</span>";
	}
	
	$out.= "</div>";
	
	return $out;
}

function validate_email($email) {

   //check for all the non-printable codes in the standard ASCII set,
   //including null bytes and newlines, and exit immediately if any are found.
   if (preg_match("/[\\000-\\037]/",$email)) {
      return false;
   }
   $pattern = "/^[-_a-z0-9\'+*$^&%=~!?{}]++(?:\.[-_a-z0-9\'+*$^&%=~!?{}]+)*+@(?:(?![-.])[-a-z0-9.]+(?<![-.])\.[a-z]{2,6}|\d{1,3}(?:\.\d{1,3}){3})(?::\d++)?$/iD";
   if(!preg_match($pattern, $email)){
      return false;
   }
   // Validate the domain exists with a DNS check
   // if the checks cannot be made (soft fail over to true)
   list($user,$domain) = explode('@',$email);
   if( function_exists('checkdnsrr') ) {
      if( !checkdnsrr($domain,"MX") ) { // Linux: PHP 4.3.0 and higher & Windows: PHP 5.3.0 and higher
         return false;
      }
   }
   else if( function_exists("getmxrr") ) {
      if ( !getmxrr($domain, $mxhosts) ) {
         return false;
      }
   }
   return true;
} // end function validate_email

function rand_string( $length ) {
	$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";	

	$size = strlen( $chars );
	for( $i = 0; $i < $length; $i++ ) {
		$str .= $chars[ rand( 0, $size - 1 ) ];
	}

	return $str;
}

function satuan($inp)
{
 if ($inp == 1)
 {
  return "SATU ";
 }
 else if ($inp == 2)
 {
  return "DUA ";
 }
 else if ($inp == 3)
 {
  return "TIGA ";
 }
 else if ($inp == 4)
 {
  return "EMAPAT ";
 }
 else if ($inp == 5)
 {
  return "LIMA ";
 }
 else if ($inp == 6)
 {
  return "EMAM ";
 }
 else if ($inp == 7)
 {
  return "TUJUH ";
 }
 else if ($inp == 8)
 {
  return "DELAPAN ";
 }
 else if ($inp == 9)
 {
  return "SEMBILAN ";
 }
 else
 {
  return "";
 }
}


function belasan($inp)
{
 $proses = $inp; //substr($inp, -1);
 if ($proses == '11')
 {
  return "SEBELAS ";
 }
 else
 {
  $proses = substr($proses,1,1);
  return satuan($proses)."BELAS ";
 }
}



function puluhan($inp)
{
 $proses = $inp; //substr($inp, 0, -1);
 if ($proses == 1)
 {
  return "SEPULUH ";
 }
 else if ($proses == 0)
 {
  return '';
 }
 else
 {
  return satuan($proses)."PULUH ";
 }
}


function ratusan($inp)
{
 $proses = $inp; //substr($inp, 0, -2);
 if ($proses == 1)
 {
  return "SERATUS ";
 }
 else if ($proses == 0)
 {
  return '';
 }
 else
 {
  return satuan($proses)."RATUS ";
 }
}


function ribuan($inp)
{
 $proses = $inp; //substr($inp, 0, -3);
 if ($proses == 1)
 {
  return "SERIBU ";
 }
 else if ($proses == 0)
 {
  return '';
 }
 else
 {
  return satuan($proses)."RIBU ";
 }
}


function jutaan($inp)
{
 $proses = $inp; //substr($inp, 0, -6);
 if ($proses == 0)
 {
  return '';
 }
 else
 {
  return satuan($proses)."JUTA ";
 }
}


function milyaran($inp)
{
 $proses = $inp; //substr($inp, 0, -9);
 if ($proses == 0)
 {
  return '';
 }
 else
 {
  return satuan($proses)."MILYAR ";
 }
}


function terbilang($rp)
{
 $kata = "";
 $rp = trim($rp);
 if (strlen($rp) >= 10)
 {
  $angka = substr($rp, strlen($rp)-10, -9);
  $kata = $kata.milyaran($angka);
 }
 $tambahan = "";
 if (strlen($rp) >= 9)
 {
  $angka = substr($rp, strlen($rp)-9, -8);
  $kata = $kata.ratusan($angka);
  if ($angka > 0) { $tambahan = "JUTA "; }
 }
 if (strlen($rp) >= 8)
 {
  $angka = substr($rp, strlen($rp)-8, -7);
  $angka1 = substr($rp, strlen($rp)-7, -6);
  if (($angka == 1) && ($angka1 > 0))
  {
   $angka = substr($rp, strlen($rp)-8, -6);
   //echo " belasan".($angka)." ";
   $kata = $kata.belasan($angka)."JUTA ";
  }
  else
  {
   $angka = substr($rp, strlen($rp)-8, -7);
   //echo " puluhan".($angka)." ";
   $kata = $kata.puluhan($angka);
   if ($angka > 0) { $tambahan = "JUTA "; }
   
   $angka = substr($rp, strlen($rp)-7, -6);
   //echo " ribuan".($angka)." ";
   $kata = $kata.ribuan($angka);
   if ($angka == 0) { $kata = $kata.$tambahan; }
  } 
 }
 if (strlen($rp) == 7)
 {
  $angka = substr($rp, strlen($rp)-7, -6);
  $kata = $kata.jutaan($angka);
  if ($angka == 0) { $kata = $kata.$tambahan; }
 }
 $tambahan = "";
 if (strlen($rp) >= 6)
 {
  $angka = substr($rp, strlen($rp)-6, -5);
  $kata = $kata.ratusan($angka);
  if ($angka > 0) { $tambahan = "RIBU "; }
 }
 if (strlen($rp) >= 5)
 {
  $angka = substr($rp, strlen($rp)-5, -4);
  $angka1 = substr($rp, strlen($rp)-4, -3);
  if (($angka == 1) && ($angka1 > 0))
  {
   $angka = substr($rp, strlen($rp)-5, -3);
   //echo " belasan".($angka)." ";
   $kata = $kata.belasan($angka)."RIBU ";
  }
  else
  {
   $angka = substr($rp, strlen($rp)-5, -4);
   //echo " puluhan".($angka)." ";
   $kata = $kata.puluhan($angka);
   if ($angka > 0) { $tambahan = "RIBU "; }
   
   $angka = substr($rp, strlen($rp)-4, -3);
   //echo " ribuan".($angka)." ";
   $kata = $kata.ribuan($angka);
   if ($angka == 0) { $kata = $kata.$tambahan; }
  }
 }
 if (strlen($rp) == 4)
 {
  $angka = substr($rp, strlen($rp)-4, -3);
  //echo " ribuan".($angka)." ";
  $kata = $kata.ribuan($angka);
  if ($angka == 0) { $kata = $kata.$tambahan; }
 }
 if (strlen($rp) >= 3)
 {
  $angka = substr($rp, strlen($rp)-3, -2);
  //echo " ratusan".($angka)." ";
  $kata = $kata.ratusan($angka);
 }
 if (strlen($rp) >= 2)
 {
  $angka = substr($rp, strlen($rp)-2, -1);
  $angka1 = substr($rp, strlen($rp)-1);
  if (($angka == 1) && ($angka1 > 0))
  {
   $angka = substr($rp, strlen($rp)-2);
   //echo " belasan".($angka)." ";
   $kata = $kata.belasan($angka);
  }
  else
  {
   //echo " puluhan".($angka)." ";
   $kata = $kata.puluhan($angka);
   
   $angka = substr($rp, strlen($rp)-1);
   //echo " satuan".($angka)." ";
   $kata = $kata.satuan($angka);
  }
 }
 if (strlen($rp) == 1)
 {
  $angka = substr($rp, strlen($rp)-1);
  //echo " satuan".($angka)." ";
  $kata = $kata.satuan($angka);
 }
 return $kata;
}



?>