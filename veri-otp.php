<?php
/*security*/
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
if (! isset($_SESSION['isLogIndsiOBEbelumOk'])) {
  echo "<script>window.location.href='index.php';</script>";
} else {
  if ($_SESSION['isLogIndsiOBEbelumOk'] <> '2ktuYZ639OIs') {
    echo "<script>window.location.href='index.php';</script>";
  }
}
include_once "config/inc.connection.php";
include_once "config/inc.library.php";
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));


?>


<!DOCTYPE html>

<html lang="en" class="no-js">
<!--<![endif]-->
<!-- BEGIN HEAD -->

<head>
  <meta charset="utf-8" />
  <title>esikatERP - Panel Login</title>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta content="width=device-width, initial-scale=1.0" name="viewport" />
  <meta content="" name="description" />
  <meta content="" name="author" />
  <!-- BEGIN GLOBAL MANDATORY STYLES -->
  <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
  <link href="assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
  <link href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
  <link href="assets/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css" />
  <!-- END GLOBAL MANDATORY STYLES -->
  <!-- BEGIN PAGE LEVEL STYLES -->
  <link rel="stylesheet" type="text/css" href="assets/plugins/select2/select2.css" />
  <link rel="stylesheet" type="text/css" href="assets/plugins/select2/select2-metronic.css" />
  <!-- END PAGE LEVEL SCRIPTS -->
  <!-- BEGIN THEME STYLES -->
  <link href="assets/css/style-metronic.css" rel="stylesheet" type="text/css" />
  <link href="assets/css/style.css" rel="stylesheet" type="text/css" />
  <link href="assets/css/style-responsive.css" rel="stylesheet" type="text/css" />
  <link href="assets/css/plugins.css" rel="stylesheet" type="text/css" />
  <link href="assets/css/themes/default.css" rel="stylesheet" type="text/css" id="style_color" />
  <link href="assets/css/pages/login-soft.css" rel="stylesheet" type="text/css" />
  <link href="assets/css/custom.css" rel="stylesheet" type="text/css" />
  <!-- END THEME STYLES -->
  <link rel="shortcut icon" href="favicon.ico" />

  <script type="text/javascript">
    function stopRKey(evt) {
      var evt = (evt) ? evt : ((event) ? event : null);
      var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null);
      if ((evt.keyCode == 13) && (node.type == "text")) {
        return false;
      }
      if ((evt.keyCode == 13) && (node.type == "number")) {
        return false;
      }
    }
    document.onkeypress = stopRKey;
  </script>
</head>

<!-- BEGIN BODY -->

<body class="login" onLoad="document.frmotp.otp.focus()">
  <!-- BEGIN LOGO -->
  <div class="logo">
    <a>
      <h1 id="kaber" class="form-title">
        <font class="f-lato fz-24 fbold tc-white">DSI Pustaka</font>
      </h1>
    </a>
  </div>
  <!-- END LOGO -->
  <div class="content ronded">
    <?php
    if (isset($_SESSION['alert'])) {
      echo "<div class='alert alert-{$_SESSION['alert']['type']} alert-dismissable'>
              <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>{$_SESSION['alert']['message']}</div>";
      $_SESSION['alert'] = null;
      unset($_SESSION['alert']);
    }
    ?>
    <div class="col-md-12 pd-0">
      <a href="index.php" class="f-lato fz-14"><i class="fa fa-chevron-left"></i> Kembali</a>
    </div>
    <br>
    <div class="col-md-12 mbt-10">
      <div class="f-lato fz-20 fbold tc-black flex-center">Verifikasi OTP</div>
    </div>
    <form action="veri-otp-proses.php" method="post" name="frmotp" id="frmotp">
      <div class="input-group wd-100">
        <input type="hidden" name="otp" id="otp" required>
        <div class="otp-container">
          <input type="text" class="otp-input" id="focus" maxlength="1" required>
          <input type="text" class="otp-input" maxlength="1" required>
          <input type="text" class="otp-input" maxlength="1" required>
          <input type="text" class="otp-input" maxlength="1" required>
          <input type="text" class="otp-input" maxlength="1" required>
          <input type="text" class="otp-input" maxlength="1" required onkeypress="if (event.keyCode==13) {csubmit.click();}">
        </div>
      </div>

      <br>
      <div class="row">
        <div class="col-md-12 flex-center">
          <button type="submit" id="csubmit" class="btn btn-blue btn-block ronded" style="width: 100px;" onclick="verifyOTP()">Verify</button>
        </div><!-- /.col -->
      </div>
    </form>
  </div>
  <br>
  <div class="copyright text-center">
    <a href="https://klikdsi.com" target="_blank">
      <div class="f-lato tc-white fz-12 flex-center gep-10">
        <img src="assets/img/logo_esikat.png" alt="" width="100px" />
        office@klikdsi.com
      </div>
    </a>
  </div>
  <script src="assets/plugins/jquery-1.10.2.min.js" type="text/javascript"></script>
  <script src="assets/plugins/jquery-migrate-1.2.1.min.js" type="text/javascript"></script>
  <script src="assets/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
  <script src="assets/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
  <script src="assets/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
  <script src="assets/plugins/jquery.blockui.min.js" type="text/javascript"></script>
  <script src="assets/plugins/jquery.cokie.min.js" type="text/javascript"></script>
  <script src="assets/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>
  <!-- END CORE PLUGINS -->
  <!-- BEGIN PAGE LEVEL PLUGINS -->
  <script type="text/javascript" src="assets/plugins/select2/select2.min.js"></script>
  <!-- END PAGE LEVEL PLUGINS -->
  <!-- BEGIN PAGE LEVEL SCRIPTS -->
  <script src="assets/scripts/core/app.js" type="text/javascript"></script>
  <!-- END PAGE LEVEL SCRIPTS -->
  <script>
    $(document).ready(function() {
      // Fungsi untuk focus input ketika halaman ditampilkan
      // Khasan 2024-11-02
      $('#focus').focus();
    });
  </script>
  <script>
    jQuery(document).ready(function() {
      App.init();
    });

    // Fungsi untuk handle paste
    document.querySelectorAll('.otp-input').forEach(input => {
      input.addEventListener('paste', function(event) {
        const pastedData = event.clipboardData.getData('text').slice(0, 6); // Ambil maksimal 6 karakter
        const inputs = document.querySelectorAll('.otp-input');

        for (let i = 0; i < pastedData.length; i++) {
          if (i < inputs.length) {
            inputs[i].value = pastedData[i];
          }
        }
        // Fokus pada input terakhir
        inputs[pastedData.length - 1].focus();
        event.preventDefault();
      });

      // Fungsi untuk handle input
      input.addEventListener('input', function(e) {
        const target = e.target
        const val = target.value;

        if (isNaN(val)) {
          target.value = "";
          return;
        }

        if (val != "") {
          const next = target.nextElementSibling;
          if (next) {
            next.focus()
          }
        }
      })

      // Fungsi untuk handle backspace
      input.addEventListener('keyup', function(e) {
        const target = e.target;
        const key = e.key.toLowerCase();

        if (key == "backspace" || key == "delete") {
          target.value = "";
          const prev = target.previousElementSibling;
          if (prev) {
            prev.focus();
          }
          return;
        }
      })
    });

    // Fungsi untuk verifikasi OTP
    function verifyOTP() {
      const inputs = document.querySelectorAll('.otp-input');
      let otp = '';
      inputs.forEach(input => {
        otp += input.value;
      });

      document.getElementById('otp').value = otp; // Set value ke input tersembunyi
    }
  </script>
  <!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->

</html>