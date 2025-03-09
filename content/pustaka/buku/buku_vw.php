<form action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" class="form-horizontal">
  <div class="portlet box <?= $_SESSION['warnabar'] ?>">
    <div class="portlet-title">
        <div class="caption">Data Buku</div>
    </div>
  
    <div class="portlet-body fieldset-form">
         
<!-- <div class="form-group">
    <label class="col-lg-3 control-label">Pencarian Berdasarkan:</label>
    <div class="col-lg-3">
      <select id="searchType" class="form-control">
        <option value="judul">Judul</option>
        <option value="kode">Kode Klasifikasi</option>
        <option value="idbuku">ID Buku</option> -->
        <!-- <option value="jenis">Jenis Buku</option>
      </select>
    </div>
    <label class="col-lg-2 control-label">PAKE INI ------ > SEARCH : </label>
    <div class="col-lg-4">
      <input type="text" id="searchVal" placeholder="Tekan [Enter] Setelah Input" class="form-control">
    </div>
</div> -->

<div class="row">
  <div class="col-lg-8">
      <table class="table table-condensed table-bordered table-hover table-condensed" id="sample_2" width="100%">
        <thead>
          <tr class="active">
                <td>NO</td>
                <td>ID BUKU</td>
                <td>KODE BUKU</td>
                <td>JUDUL</td>
          </tr>
        </thead>
      </table>
    </div>
    <div class="col-lg-4">
      <img id="txtCover" alt="Cover Buku" width="70%" height="70%">
    </div>
</div>
<div class="row" style="margin-top: 50px;">
    <div class="col-lg-4">
      <div class="form-group">
          <label class="col-lg-4 control-label">Jenis Buku</label>
          <div class="col-lg-8">
          <input type="text" id="txtJenisBuku" class="form-control" readonly>
          </div>
      </div>

      <div class="form-group">
          <label class="col-lg-4 control-label">Kode klasifikasi</label>
          <div class="col-lg-8">
            <input type="text" id="txtKlasifikasi" class="form-control sm" readonly />
          </div>
        </div>
        
        <!-- <div class="form-group">
          <label class="col-lg-4 control-label">Subyek</label>
          <div class="col-lg-8">
            <input type="text" id="txtSubyek" class="form-control sm" readonly/>
          </div>
        </div> -->

        <div class="form-group">
          <label class="col-lg-4 control-label">Pengarang-1</label>
          <div class="col-lg-8">
            <input type="text" id="txtPengarang" class="form-control sm" readonly /></span>
          </div>
        </div>

        <div class="form-group">
          <label class="col-lg-4 control-label">Pengarang-2</label>
          <div class="col-lg-8">
            <input type="text" id="txtPengarang2" class="form-control sm" readonly /></span>
          </div>
        </div>

    </div>
    <div class="col-lg-4">
        <div class="form-group">
          <label class="col-lg-4 control-label">Pengarang-3</label>
          <div class="col-lg-8">
            <input type="text" id="txtPengarang3" class="form-control sm" readonly /></span>
          </div>
        </div>
  
        <div class="form-group">
          <label class="col-lg-4 control-label">Penerbit</label>
          <div class="col-lg-8">
            <input type="text" id="txtPenerbit" class="form-control sm" readonly /></span>
          </div>
        </div>

        <div class="form-group">
          <label class="col-lg-4 control-label">Tempat Terbit</label>
          <div class="col-lg-8">
            <input type="text" id="txtTempatTerbit" class="form-control sm" readonly /></span>
          </div>
        </div>

        <div class="form-group">
          <label class="col-lg-4 control-label">Tahun Terbit</label>
          <div class="col-lg-8">
            <input type="text" id="txtTahunTerbit" class="form-control sm" readonly /></span>
          </div>
        </div>

        <div class="form-group">
          <label class="col-lg-4 control-label">Bahasa</label>
          <div class="col-lg-8">
            <input type="text" id="txtBahasa" class="form-control sm" readonly /></span>
          </div>
        </div>

    </div>
    <div class="col-lg-4">
        <div class="form-group">
            <label class="col-lg-4 control-label">Asal Buku</label>
            <div class="col-lg-8">
              <input type="text" id="txtAsalBuku" class="form-control sm" readonly /></span>
            </div>
        </div>

        <div class="form-group">
            <label class="col-lg-4 control-label">Seri</label>
            <div class="col-lg-8">
              <input type="text" id="txtSeri" class="form-control sm" readonly /></span>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-2 control-label">Edisi</label>
            <div class="col-lg-2">
              <input type="text" id="txtEdisi" class="form-control sm" readonly /></span>
            </div>
            <label class="col-lg-2 control-label">Cetakan</label>
            <div class="col-lg-2">
              <input type="text" id="txtCetakan" class="form-control sm" readonly /></span>
            </div>
            <label class="col-lg-2 control-label">Vol</label>
            <div class="col-lg-2">
              <input type="text" id="txtVol" class="form-control sm" readonly /></span>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-4 control-label">ISBN</label>
            <div class="col-lg-8">
              <input type="text" id="txtISBN" class="form-control sm" readonly /></span>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-2 control-label">Status</label>
            <div class="col-lg-4">
              <input type="text" id="txtStatus" class="form-control sm" readonly /></span>
            </div>
            <label class="col-lg-2 control-label">Lokasi</label>
            <div class="col-lg-4">
              <input type="text" id="txtLokasi" class="form-control sm" readonly /></span>
            </div>
        </div>
    </div>
</div>

	  </fieldset>
	 </div>
	</div>
  </form>  

<!-- Datatable Script -->
  <script src="plugin/datatable/jquery-3.5.1.js"></script>
  <script src="plugin/datatable/jquery.dataTables.min.js"></script>
  <link rel="stylesheet" href="plugin/datatable/jquery.dataTables.min.css">
  
  <script>
  $(document).ready(function() {

      $("#sample_2").dataTable().fnDestroy();
     
      var table = $('#sample_2').DataTable( {  
          "bProcessing": true,
          "bServerSide": true,
          "searching": true,
          "bDestroy": true,
          "bFilter": true,
          "sAjaxSource": "action.php?act=9",
          "fnServerData": function(sSource, aoData, fnCallback) {
          // Mendapatkan nilai dari input pencarian
                  var searchType = $('#searchType').val();
                  var searchVal = $('#searchVal').val();

                  // Menambahkan parameter pencarian sesuai dengan filter yang aktif
                  aoData.push({ "name": searchType, "value": searchVal });
              
                    $.ajax({
                        dataType: 'json',
                        type: 'GET',
                        url: sSource,
                        data: aoData,
                        success: function(response) {
                            console.log('Response:', response);
                            fnCallback(response);
                        },
                        error: function(xhr, status, error) {
                            console.error('Ajax request failed. Status:', status, 'Error:', error);
                            console.log('Server Response:', xhr.responseText);
                        }
                        
                    });
        }, 
          "columns": [
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null
          ],
          "fnInitComplete": function() {
            $('#searchVal').keypress(function(event) {
                if (event.which === 13) {
                  table.fnDraw();
                }
            });
          },
          "fnRowCallback": function(nRow, aData, iDisplayIndex) {
                $(nRow).on('click', function() {
                    displaySeparateForm(aData);
                });
            },
          "columnDefs": [
          { className: "dt-center", "targets": [0] }  ,
          { "targets": [4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22], "visible": false }
          ],
          "iDisplayLength": 10,
          "bInfo": true,
          "sPaginationType" : 'full_numbers'  
      } );

      function displaySeparateForm(data) {
        $("#txtJenisBuku").val(data[4]).prop("readonly", true);
        $("#txtKlasifikasi").val(data[5]).prop("readonly", true);
        $("#txtSubyek").val(data[6]).prop("readonly", true);
        $("#txtPengarang").val(data[7]).prop("readonly", true);
        $("#txtPengarang2").val(data[8]).prop("readonly", true);
        $("#txtPengarang3").val(data[9]).prop("readonly", true);
        $("#txtPenerbit").val(data[10]).prop("readonly", true);
        $("#txtTempatTerbit").val(data[11]).prop("readonly", true);
        $("#txtTahunTerbit").val(data[12]).prop("readonly", true);
        $("#txtBahasa").val(data[13]).prop("readonly", true);
        $("#txtAsalBuku").val(data[14]).prop("readonly", true);
        $("#txtSeri").val(data[15]).prop("readonly", true);
        $("#txtEdisi").val(data[16]).prop("readonly", true);
        $("#txtCetakan").val(data[17]).prop("readonly", true);
        $("#txtVol").val(data[18]).prop("readonly", true);
        $("#txtISBN").val(data[19]).prop("readonly", true);
        $("#txtStatus").val(data[20]).prop("readonly", true);
        $("#txtLokasi").val(data[21]).prop("readonly", true);
        $("#txtCover").attr('src', data[22]);
    }
  });

  $('#tabelData').DataTable({
    "searching": false  // Matikan fitur search
});


  </script>  

    	