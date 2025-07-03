<form action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" class="form-horizontal">
    <div class="portlet box <?= $_SESSION['warnabar'] ?>">
        <div class="portlet-title">
            <div class="caption">Data Buku</div>
            <!-- <button id="btnPdf" type="button" class="btn btn-sm btn-primary pull-right" style="margin-top:5px;">
                <i class="fa fa-file-pdf-o"></i> Cetak PDF
            </button> -->
        </div>

        <div class="portlet-body fieldset-form">
            <!-- Tabel untuk menampilkan daftar buku -->
            <div class="row">
                <div class="col-lg-12"> 
                <button id="btnPdf" type="button" class="btn btn-sm btn-danger pull-left" style="margin-top:5px; margin-bottom:10px;">
    <i class="fa fa-file-pdf-o"></i> Cetak PDF
</button>


                    <table class="table table-condensed table-bordered table-hover" id="sample_2" width="100%">
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
            </div>

            <hr>
            <h4 class="text-center">Detail Buku Terpilih</h4>
            <hr>
            
            <!-- ======================================================================= -->
            <!-- BAGIAN INI YANG AKAN DICETAK SEBAGAI PDF -->
            <!-- ======================================================================= -->
            <div id="detailSection">
                <div class="row">
                    <!-- Kolom untuk Cover Buku -->
                    <div class="col-xs-4">
                        <img id="txtCover" alt="Cover Buku Tidak Ada" style="width: 100%; max-width: 500px; display: block; margin: 0 auto;">
                        <img id="txtCover1" alt="Cover Buku Tidak Ada" style="width: 100%; max-width: 500px; display: block; margin: 0 auto;">
                    </div>
                    <!-- Kolom untuk Detail -->
                    <div class="col-xs-8">
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Jenis Buku</label>
                            <div class="col-sm-8">
                                <input type="text" id="txtJenisBuku" class="form-control" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Kode klasifikasi</label>
                            <div class="col-sm-8">
                                <input type="text" id="txtKlasifikasi" class="form-control sm" readonly />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Pengarang-1</label>
                            <div class="col-sm-8">
                                <input type="text" id="txtPengarang" class="form-control sm" readonly />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Pengarang-2</label>
                            <div class="col-sm-8">
                                <input type="text" id="txtPengarang2" class="form-control sm" readonly />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Pengarang-3</label>
                            <div class="col-sm-8">
                                <input type="text" id="txtPengarang3" class="form-control sm" readonly />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Penerbit</label>
                            <div class="col-sm-8">
                                <input type="text" id="txtPenerbit" class="form-control sm" readonly />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Tempat Terbit</label>
                            <div class="col-sm-8">
                                <input type="text" id="txtTempatTerbit" class="form-control sm" readonly />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Tahun Terbit</label>
                            <div class="col-sm-8">
                                <input type="text" id="txtTahunTerbit" class="form-control sm" readonly />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Bahasa</label>
                            <div class="col-sm-8">
                                <input type="text" id="txtBahasa" class="form-control sm" readonly />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Asal Buku</label>
                            <div class="col-sm-8">
                                <input type="text" id="txtAsalBuku" class="form-control sm" readonly />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Seri</label>
                            <div class="col-sm-8">
                                <input type="text" id="txtSeri" class="form-control sm" readonly />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Edisi</label>
                            <div class="col-sm-2">
                                <input type="text" id="txtEdisi" class="form-control sm" readonly />
                            </div>
                            <label class="col-sm-2 control-label">Cetakan</label>
                            <div class="col-sm-2">
                                <input type="text" id="txtCetakan" class="form-control sm" readonly />
                            </div>
                            <label class="col-sm-2 control-label">Vol</label>
                            <div class="col-sm-2">
                                <input type="text" id="txtVol" class="form-control sm" readonly />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">ISBN</label>
                            <div class="col-sm-8">
                                <input type="text" id="txtISBN" class="form-control sm" readonly />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Status</label>
                            <div class="col-sm-4">
                                <input type="text" id="txtStatus" class="form-control sm" readonly />
                            </div>
                            <label class="col-sm-2 control-label">Lokasi</label>
                            <div class="col-sm-4">
                                <input type="text" id="txtLokasi" class="form-control sm" readonly />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ======================================================================= -->
            <!-- AKHIR DARI BAGIAN YANG AKAN DICETAK -->
            <!-- ======================================================================= -->

        </div>
    </div>
</form>

<!-- Datatable & PDF Script -->
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

<script>
$(document).ready(function() {
    // Sembunyikan detail section pada awalnya
    $('#detailSection').hide();

    var table = $('#sample_2').DataTable({
        "bProcessing": true,
        "bServerSide": true,
        "searching": true,
        "bDestroy": true,
        "sAjaxSource": "action.php?act=9", // Pastikan URL ini benar
        "fnServerData": function(sSource, aoData, fnCallback) {
            $.ajax({
                dataType: 'json',
                type: 'GET',
                url: sSource,
                data: aoData,
                success: function(response) {
                    // Cek jika response dari server valid
                    if (response && response.aaData) {
                        fnCallback(response);
                    } else {
                        console.error('Format response dari server tidak valid.');
                        // Tampilkan pesan error jika perlu
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Ajax request failed. Status:', status, 'Error:', error);
                    console.log('Server Response:', xhr.responseText);
                }
            });
        },
        "fnRowCallback": function(nRow, aData, iDisplayIndex) {
            $(nRow).css('cursor', 'pointer'); // Ubah cursor agar terlihat bisa diklik
            $(nRow).on('click', function() {
                displaySeparateForm(aData);
                $('#detailSection').slideDown(); // Tampilkan detail dengan animasi
            });
        },
        "columnDefs": [
            { className: "dt-center", "targets": [0] },
            // Sembunyikan kolom data detail, karena akan ditampilkan di form terpisah
            { "targets": [4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23], "visible": false }
        ],
        "iDisplayLength": 10,
        "bInfo": true,
        "sPaginationType": 'full_numbers'
    });

    function displaySeparateForm(data) {
        // Mengisi nilai ke form detail
        $("#txtJenisBuku").val(data[4]);
        $("#txtKlasifikasi").val(data[5]);
        // $("#txtSubyek").val(data[6]); // Anda mengomentari ini di HTML, jadi saya juga
        $("#txtPengarang").val(data[7]);
        $("#txtPengarang2").val(data[8]);
        $("#txtPengarang3").val(data[9]);
        $("#txtPenerbit").val(data[10]);
        $("#txtTempatTerbit").val(data[11]);
        $("#txtTahunTerbit").val(data[12]);
        $("#txtBahasa").val(data[13]);
        $("#txtAsalBuku").val(data[14]);
        $("#txtSeri").val(data[15]);
        $("#txtEdisi").val(data[16]);
        $("#txtCetakan").val(data[17]);
        $("#txtVol").val(data[18]);
        $("#txtISBN").val(data[19]);
        $("#txtStatus").val(data[20]);
        $("#txtLokasi").val(data[21]);
        
        // Logika untuk menampilkan gambar cover
        if (data[22]) {
            $("#txtCover").attr("src", data[22]).show();
            $("#txtCover1").hide();
        } else if (data[23]) {
            $("#txtCover1").attr("src", data[23]).show();
            $("#txtCover").hide();
        } else {
            $("#txtCover").attr("src", "").hide();
            $("#txtCover1").attr("src", "").hide();
        }
    }

    // Event handler untuk tombol Cetak PDF
    $('#btnPdf').on('click', function() {
        // Cek apakah ada buku yang sudah dipilih
        if (!$('#detailSection').is(':visible')) {
            alert('Silakan pilih buku dari tabel terlebih dahulu.');
            return;
        }

        // Ambil elemen detailSection
        const element = document.getElementById('detailSection');

        // Opsi html2pdf
        const opt = {
            margin: 0.5,
            filename: 'detail-buku.pdf',
            image: { type: 'jpeg', quality: 0.98 },
            html2canvas: { scale: 2, useCORS: true }, // useCORS penting jika gambar dari domain lain
            jsPDF: { unit: 'in', format: 'a4', orientation: 'portrait' }
        };

        // Generate & download
        html2pdf().set(opt).from(element).save();
    });
});
</script>
