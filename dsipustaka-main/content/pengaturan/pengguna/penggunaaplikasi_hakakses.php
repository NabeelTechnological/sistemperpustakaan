<?php	
//Security goes here

$dataModul = isset($_GET['id1']) ? $_GET['id1'] : "";

if(isset($_POST['cmbModul'])){
    $selectedValue = $_POST['cmbModul'];
    $dataModul     = $_POST['cmbModul'];
} 
//declare variable  

//hapus hak akses
if (isset($_GET['act'])){
	if ($_GET['act']=='del'){ 
		$kdmodul = $_GET['id1'];
		$iduser  = $_GET['id'];
		$kdmenu  = $_GET['id3'];
		$dataModul     = $kdmodul;

		//del rhakmenu
		$sql2 = "delete from rhakmenu  where kdmodul='$kdmodul' and iduser='$iduser' and kdmenu='$kdmenu'";
		$qry = mysqli_query($koneksidb,$sql2);
	}
} 

//tambah hak akses 
if (isset($_GET['act'])){
	if ($_GET['act']=='add'){ 
		$kdmodul = $_GET['id1'];
		$iduser  = $_GET['id'];
		$kdmenu  = $_GET['id3'];

		//isi rakses (untuk modul)
		$sql1 = "replace into rakses (iduser,kdmodul) 
				values ('$iduser','$kdmodul')";
		$qry = mysqli_query($koneksidb,$sql1); 

		//isi rhakmenu
		$sql2 = "replace into rhakmenu (kdmodul,iduser,kdmenu) 
				values ('$kdmodul','$iduser','$kdmenu')";
		$qry = mysqli_query($koneksidb,$sql2);
	}
}


//view 
$txtID    = isset($_GET['id']) ? $_GET['id'] : "";
$qryCek   = mysqli_query($koneksidb, "SELECT iduser,nama,passwd, kdjab, kddep, idatasan FROM ruser  WHERE iduser='".$txtID."'	") or die('Gagal Query Cek.'. mysqli_error());
if (mysqli_num_rows($qryCek)>0){
	  $rs = mysqli_fetch_array($qryCek);
    $dataNama					= $rs['nama'];
    $dataPassword			= $rs['passwd'];
		$isaktif					= $rs['isaktif'];
		$dataJabatan    	= $rs['kdjab']; 
		$dataAtasan    		= $rs['idatasan'];
		$dataDepartemen   = $rs['kddep'];
		$dataIdUser   	  = $txtID;
} 

$dataCariA = isset($_POST['txtCariA']) ? $_POST['txtCariA'] : "";
$dataCariB = isset($_POST['txtCariB']) ? $_POST['txtCariB'] : "";
?> 

<!-- Scroll and Fix header table-->
<style type="text/css">
	html {
	  font-family: verdana;
	  font-size: 10pt;
	  line-height: 10px;
	}

	table {
	  border-collapse: collapse;
	  width: 400px;
	  overflow-x: scroll;
	  display: block;
	}

	thead {
	  background-color: #EFEFEF;
	}

	thead,
	tbody {
	  display: block;
	}

	tbody {
	  overflow-y: scroll;
	  overflow-x: hidden;
	  height: 300px;
	}

	td,
	th {
	  min-width: 40px;
	  height: 10px;
	  border: dashed 1px lightblue;
	  overflow: hidden;
	  text-overflow: ellipsis;
/*	  max-width: 100px;*/
	}
</style>

<style>
* {
  box-sizing: border-box;
}

.row {
  display: flex;
  margin-left:-5px;
  margin-right:-5px;
}

.column {
  flex: 10%;
  padding: 5px;
}

/*table {
  border-collapse: collapse;
  border-spacing: 0;
  width: 100%;
  border: 1px solid #ddd;
}*/

th, td {
  text-align: left;
  padding: 4px;
}

tr:nth-child(even) {
  background-color: #f2f2f2;
}
</style>

<div class="portlet box <?= $_SESSION['warnabar'] ?>">
	<div class="portlet-title">
		<div class="caption">Form Kelola Hak Akses Pengguna</div>
		<div class="tools">
			<a href="javascript:;" class="collapse"></a>
			<a href="javascript:;" class="reload"></a>
			<a href="javascript:;" class="remove"></a>
		</div>
	</div>
	<div class="portlet-body form">
	<form action="<?php $_SERVER['PHP_SELF']; ?>" method="post" class="form-horizontal" role="form" autocomplete="off" name="form1" id="form1">
		 <div class="form-body">
			<div class="form-group">
				<label class="col-lg-2 control-label">ID User</label>
				<div class="col-lg-3">
					<input type="text" id="txtIdUser" name="txtIdUser" value="<?php echo $dataIdUser; ?>"   class="form-control sm" disabled/></span>
				</div>	
		    
				<label class="col-lg-2 control-label">Jenis User</label>
				<div class="col-lg-3">
					<select name="cmbJenis"   data-placeholder="- Pilih Jenis -" class="select2me form-control" disabled>
						<option value="0">Internal</option>
					</select>
		    	</div>
			</div>
			<div class="form-group">
				<label class="col-lg-2 control-label">Nama</label>
				<div class="col-lg-3">
					<input type="text" id="txtNama" name="txtNama" value="<?php echo $dataNama; ?>"  class="form-control sm" disabled/></span>
		    	</div>
				 
				<label class="col-lg-2 control-label">Jabatan</label>
				<div class="col-lg-3">
					<select name="txtJabatan"   data-placeholder="- Pilih Jabatan -" class="select2me form-control" disabled>
						<option value=""></option> 
						<?php
							  $dataSql = "SELECT kdjab, nmjab  FROM rjabatan ORDER BY nmjab ";
							  $dataQry = mysqli_query( $koneksidb, $dataSql) or die ("Gagal Query jab".mysqli_error());
							  while ($dataRow = mysqli_fetch_array($dataQry)) {
								if ($dataJabatan == $dataRow['kdjab']) {
									$cek = " selected";
								} else { $cek=""; }
								echo "<option value='$dataRow[kdjab]' $cek>$dataRow[nmjab]</option>";
							  }
							  $sqlData ="";
						?>
					</select>
	      		</div>
			</div>
			 
			<div class="batas"></div>
		 
			<div class="form-group">
				<label class="col-lg-2 control-label">Modul</label>
				<div class="col-lg-3">
					<select name="cmbModul" id="cmbModul"   data-placeholder="- Pilih Modul -" class="select2me form-control"   >
						<option value=""></option>
						<option value="1" <?php if($dataModul=='1'){echo "selected";} ?>>IT Inventory</option> 
						<option value="5" <?php if($dataModul=='5'){echo "selected";} ?>>Accounting</option> 
					</select>
		      	</div>
			</div>
			<br> 
			<div class="form-group">
				 
			    <label class="col-lg-2 control-label">&nbsp;</label>
				<div class="row">
					<div class="column">  
					<!-- <div class="col-lg-4"  style="height:300px;overflow:scroll;"> -->
						<table  id="samplea">
							<thead> 
								<tr class="active">
					                <td width="40"><div align='center'>NO</div></td>
					                <td width="320"><div align='center'>MENU TERSEDIA</div></td> 
					                <td width="40"><div align="center"><div align='center'>PILIH</div></div></td>
					            </tr>	
							</thead>
							<tbody>
							<?php
							$kdmodul = $dataModul;
							$qry1 = mysqli_query($koneksidb, "SELECT * FROM rmenu WHERE kdmodul='$dataModul' and nmmenu like '%$dataCariA%' order by nmmenu");
							$no = 1;
							while ($rs=mysqli_fetch_array($qry1)){
								$kdmenu = $rs['kdmenu'];
								?>
								<tr> 
									<td><?= $no; ?></td>
									<td><?= $rs['nmmenu']; ?></td>
									<td><?php echo "<div align='center'>
										<a href=?content=hakaksespengguna&act=add&id1=".$dataModul."&id=".$dataIdUser."&id3=".$kdmenu." class='btn btn-xs ".$_SESSION['warnatombol']." tooltips' data-placement='top' data-original-title='Pilih'><i class='fa fa-check'></i></a></div>"  ?></td>		
								</tr>	
								<?php
								$no++;
							} 
							?>	
							</tbody>
						</table>
					<!-- </div> -->
				    </div>
				    <div class="column">
					<!-- <div class="col-lg-4" style="height:300px;overflow:scroll;"> -->
						<table  id="sampleb"  >
							<thead> 
								<tr class="active">
					                <td width="40"><div align="center">NO</div></td>
					                <td width="320"><div align="center">MENU HASIL PILIHAN</div></td> 
					                <td width="40"><div align="center">BATAL</div></td>
					            </tr>		
							</thead>	
							<tbody>
							<?php
							$kdmodul = $dataModul;
							$iduser  = $_GET['id']; 
							$qry2 = mysqli_query($koneksidb, "SELECT * FROM rhakmenu a inner join rmenu b on a.kdmenu=b.kdmenu and a.kdmodul and b.kdmodul   WHERE a.kdmodul='$dataModul' and a.iduser='$iduser' and b.nmmenu like '%$dataCariB%' order by b.nmmenu");
							$no = 1;
							while ($rs2=mysqli_fetch_array($qry2)){
								$kdmenu = $rs2['kdmenu'];
								?>
								<tr> 
									<td><?= $no; ?></td>
									<td><?= $rs2['nmmenu']; ?></td>
									<td><?php echo "<div align='center' id='batal'>
										<a href=?content=hakaksespengguna&act=del&id1=".$dataModul."&id=".$dataIdUser."&id3=".$kdmenu." class='btn btn-xs red tooltips' data-placement='top' data-original-title='Batal'><i class='fa fa-trash-o'></i></a></div>"  ?></td>		
								</tr>	
								<?php
								$no++;
							} 
							?>	
							</tbody>
						</table>
					<!-- </div>  -->
					</div> <!-- div column -->
				</div> <!-- div row -->
			</div>	
		 
			<footer class="panel-footer">
			    <div class="row">
			        <div class="form-group">
			            <div class="col-lg-offset-2 col-lg-10">
			                
			                <a href="?content=penggunaaplikasi" class="btn <?= $_SESSION['warnabar'] ?>"><i class="fa fa-undo"></i> Kembali</a>
			            </div>
			        </div>
			    </div>
			</footer>
		</form>
	</div>
</div>
<!--
	 Parameter Datatable 
   $('#example').dataTable({
    "bProcessing": true,
    "sAutoWidth": false,
    "bDestroy":true,
    "sPaginationType": "bootstrap", // full_numbers
    "iDisplayStart ": 10,
    "iDisplayLength": 10,
    "bPaginate": false, //hide pagination
    "bFilter": false, //hide Search bar
    "bInfo": false, // hide showing entries
}) -->

<!-- Datatable Script -->
  <script src="plugin/datatable/jquery-3.5.1.js"></script>
  <script src="plugin/datatable/jquery.dataTables.min.js"></script>
  <link rel="stylesheet" href="plugin/datatable/jquery.dataTables.min.css">
  <script>
  	$(document).ready(function () {
  		$("#samplea").dataTable().fnDestroy();
	    $('#samplea').dataTable({ 
            "bInfo":     false,
       		"bPaginate": false,
	    });
	});
  </script>  
    <script>
  	$(document).ready(function () {
  		$("#sampleb").DataTable().fnDestroy();
	    $('#sampleb').DataTable({ 
            "bInfo":     false,
       		"bPaginate": false,
	    });
	});
  </script> 
 

<script type="text/javascript" language="javascript">
	document.getElementById('txtCariA').onkeypress=function(){
	    document.getElementById('form1').submit();
	}    
	document.getElementById('txtCariB').onkeypress=function(){
	    document.getElementById('form1').submit();
	}
</script>

<script type="text/javascript" language="javascript">
document.getElementById('cmbModul').onchange=function(){
    document.getElementById('form1').submit();
}


function setSelected(value){
    var filtro = document.getElementById('cmbModul');
    var options = filtro.options;
    for(var i=0;i<options.length;i++){
        if(options[i].value == value){
            filtro.selectedIndex = i;
        }
    }   
}
</script>