<div class="portlet box  <?= $_SESSION['warnabar'] ?>">
<div class="portlet-title">
		<div class="caption">DATABASE</div>
		<div class="tools">
			<a href="javascript:;" class="collapse"></a>
			<a href="javascript:;" class="reload"></a>
			<a href="javascript:;" class="remove"></a>
		</div>
	</div>
<div class="portlet-body">

<div class="form-group">
    <label for="tabel">Pilih Tabel : </label>
    <select name="tabel" id="tabel">
        <option value=""></option>
        <?php 
        $qry = mysqli_query($koneksidb,"SHOW TABLES");
        while($row = mysqli_fetch_row($qry)){
?>
<option value="<?=$row[0]?>"><?=$row[0]?></option>
        <?php } ?>
    </select>
</div>


    <?php if(isset($_GET['tabel'])){
         $tabel = $_GET['tabel'];
         $qry = mysqli_query($koneksidb,"EXPLAIN $tabel");
        ?>
        <div>       
            <h3>TABEL <?= $tabel ?></h3>
            <table class="table">
                <tr>
                    <th>No</th>
                    <th>Field</th>
                    <th>Type</th>
                    <th>Null</th>
                    <th>Key</th>
                    <th>Default</th>
                    <th>Extra</th>
                </tr>
                <?php 
                $no=1;
                while($row = mysqli_fetch_row($qry)){ ?>
                    <tr>
                    <td><?=$no++;?></td>
                    <td><?=$row[0]?></td>
                    <td><?=$row[1]?></td>
                    <td><?=$row[2]?></td>
                    <td><?=$row[3]?></td>
                    <td><?=$row[4]?></td>
                    <td><?=$row[5]?></td>
                    </tr>
                <?php  } ?>
            </table>
</div>
<?php } ?>

</div>
</div>
<script>
    document.getElementById('tabel').addEventListener('change', function () {
      var selectedType = this.value;
      
      var currentUrl = window.location.href;
      var newUrl;

      var regex = /[?&]tabel=[^&]*/g;
      var newUrl = currentUrl.replace(regex, '');

      if (newUrl.includes('?')) {
        newUrl += '&tabel=' + selectedType;
      } else {
        newUrl += '?tabel=' + selectedType;
      }

      window.history.pushState({ path: newUrl }, '', newUrl);
      window.location.reload();
    });
  </script>