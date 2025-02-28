<?php 
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

if(isset($_FILES['file']['name'])) {
    $tmp_name = $_FILES['file']['tmp_name'];
    
    $file_name = $_FILES['file']['name'];
    
    $ext = pathinfo($file_name, PATHINFO_EXTENSION);
    
    $valid_extensions = array('xls', 'xlsx');
    
    if(in_array($ext, $valid_extensions)) {
            $excel = IOFactory::load($tmp_name);
            
            $sheet = $excel->getActiveSheet();
            
            $data = [];
            foreach ($sheet->getRowIterator() as $row) {
                $rowData = [];
                foreach ($row->getCellIterator() as $cell) {
                    $rowData[] = $cell->getValue();
                }
                $data[] = $rowData;
            }

            if($_POST['cek']=="siswa" ){
            }else if($_POST['cek']=="guru"){
            }else{
                $no=1;
                foreach($data as $row){
                    echo "<tr>
                    <td>".$no++."</td>
                    <td>$row[0]</td>
                    <td>$row[1]</td>
                    <td>$row[2]</td>
                    <td>$row[3]</td>
                </tr>";
                }
            }
    } else {
        echo 'Error: File yang diupload tidak valid';
    }

    if($_POST['cek']=="siswa"){
        $dataKelas = $_POST['txtKelas'];
        $dataKota = $_POST['txtKota'];
        $dataBerlaku = $_POST['txtBerlaku'];
        if(empty($dataKelas) || empty($dataKota) || empty($dataBerlaku) ){
            echo "<div class='alert alert-danger alert-dismissable'>
            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
            <strong><i class='fa fa-times'></i>&nbsp; Data Tidak Boleh Ada yang Kosong </strong>
            </div>";
        }else{
            foreach ($data as $row) {
                if (count($row) < 4) continue;
            $insQry = "insert into ranggota (nipnis,idjnsang,idkota,idkelas,tgldaftar,nama,alamat,berlaku,jnskel,noapk) values (?,1,?,?,CURDATE(),?,?,?,?,$_SESSION[noapk])";
            $stmt = mysqli_prepare($koneksidb,$insQry);
            mysqli_stmt_bind_param($stmt,'sssssss',$row[0],$dataKota,$dataKelas,$row[1],$row[2],$dataBerlaku,$row[3]);
            mysqli_stmt_execute($stmt) or die ("Gagal Query Insert Siswa Masal : " . mysqli_error($koneksidb));
            mysqli_stmt_close($stmt);
            }

            echo "<div class='alert alert-success alert-dismissable'>
            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
            <strong><i class='fa fa-check'></i>&nbsp;</strong>Data Siswa Sukses insert. 
            </div>";
        }
    }else if($_POST['cek']=="guru"){
        $dataKota = $_POST['txtKota'];
        $dataBerlaku = $_POST['txtBerlaku'];
        if( empty($dataKota) || empty($dataBerlaku) ){
            echo "<div class='alert alert-danger alert-dismissable'>
            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
            <strong><i class='fa fa-times'></i>&nbsp; Data Tidak Boleh Ada yang Kosong </strong>
            </div>";
        }else{
            foreach ($data as $row) {

            $insQry = "insert into ranggota (nipnis,idjnsang,idkota,idkelas,tgldaftar,nama,alamat,berlaku,jnskel,noapk) values (?,2,?,'NA',CURDATE(),?,?,?,?,$_SESSION[noapk])";
            $stmt = mysqli_prepare($koneksidb,$insQry);
            mysqli_stmt_bind_param($stmt,'ssssss',$row[0],$dataKota,$row[1],$row[2],$dataBerlaku,$row[3]);
            mysqli_stmt_execute($stmt) or die ("Gagal Query Insert Guru/Karyawan Masal : " . mysqli_error($koneksidb));
            mysqli_stmt_close($stmt);
            }

            echo "<div class='alert alert-success alert-dismissable'>
            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
            <strong><i class='fa fa-check'></i>&nbsp;</strong>Data Guru Sukses insert. 
            </div>";
        }
    }

    
}
