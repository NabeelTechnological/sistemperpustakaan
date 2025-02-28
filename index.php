<?php
session_start();
include_once "config/inc.connection.php";
include_once "config/inc.library.php";
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));

?>

<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Login Page</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <script>
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        }
    </script>
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- Left Image Section -->
        <div class="hidden lg:block lg:w-3/5">
            <img alt="cover left" class="w-full h-full object-cover" height="600" src="assets/img/pustaka.png" width="800"/>
        </div>
        <!-- Right Form Section -->
        <div class="flex flex-col justify-center items-center w-full lg:w-2/5 bg-white p-8 relative top-[-20px]">
        <h1 class="text-3xl font-bold mb-4">
        <span class="text-red-500">D</span>
        <span class="text-yellow-500">S</span>
        <span class="text-green-500">I</span> 
        <span class="text-grey-500">Pustaka</span>
        </h1>
            <!-- <img alt="Dsi Pustaka logo" class="mb-4" height="20" src="assets/img/logo pt.dsi.png" width="100"/> -->
            <h2 class="text-xl mb-6">Halaman DSI Pustaka Login</h2>
            <div class="content">
  <!-- BEGIN LOGIN FORM -->
  <form class="login-form w-[400px] mx-auto" action="masuk.php" method="post">
    <!-- <h4 class="form-title">DSI Pustaka Login</h4> -->
    <?php
      if (isset($_SESSION['pesan']) && $_SESSION['pesan'] <> '') {
          echo '<div class="alert alert-danger alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'.$_SESSION['pesan'].'</div>';
        }
      $_SESSION['pesan'] = '';
      ?>
    <div class="mb-4">
      <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
      <label class="block text-black text-lg  mb-2">Username</label>
      <div class="input-icon">
        <!-- <i class="fa fa-user"></i> <br> -->
        <input class="rounded-md w-full border-b-2 border-transparent py-2 px-1 text-gray-700 focus:outline-none focus:border-blue-500" type="text" autocomplete="off" placeholder="Masukkan Username" name="username" id="username"/>
      </div>
    </div>
    <div class="mb-6">
        <label class="block text-black text-lg  mb-2" for="password">Password</label>
        <div class="relative">
          <i class="fas fa-eye absolute right-3 top-3 text-gray-500 cursor-pointer" id="eye-icon" onclick="togglePasswordVisibility()"></i>
          <input class="rounded-md w-full border-b-2 border-transparent py-2 px-1 text-gray-700 focus:outline-none focus:border-blue-500" id="password" type="password" autocomplete="off" placeholder="Masukkan Password" name="password"/>
        </div>
    </div>
    <div class="form-actions">
      <label class="checkbox">
      <!-- <input type="checkbox" name="txtAdmin" value="admin"/> Remember </label> -->
      <button type="submit" class="relative flex items-center justify-center mx-auto shadow-2xl border-2 border-blue-500 text-white font-bold py-2 px-20 w-full rounded focus:outline-none focus:shadow-outline bg-blue-500 hover:bg-transparent hover:text-blue-500 active:bg-transparent active:text-blue-500">
    login
</button>

    </div> <br>
    <div class="forget-password">
      <h5>Lupa password anda ?</h5>
      <p>
         Silahkan hubungi administrator program
      </p>
    </div>
  </form>
</div>
            <!-- <div class="mt-8 text-center">
                <p class="text-gray-500">Powered by :</p>
                <img alt="Dsi Pustaka logo" class="mx-auto my-2" height="50" src="assets/img/logo_esikat.png" width="150"/>
                <p class="text-gray-500"><i class="fas fa-envelope"></i> office@klikdsi.com</p>
                <p class="text-gray-500"><i class="fas fa-phone"></i>0857-4000-8282 (Reza)</p>
            </div> -->

            <div class="text-center mt-10">
   <p class="text-lg font-semibold">
    Powered by :
   </p>
   <div class="flex items-center justify-center mt-2">
    <img alt="esikatERP logo with text 'esikatERP by DSi'" class="border p-1 mr-4" height="50" src="assets/img/logo_esikat.png" width="120"/>
    <div class="text-left">
     <div class="flex items-center mt-1">
      <i class="fas fa-envelope mr-1">
      </i>
      <span>
       office@klikdsi.com
      </span>
     </div>
     <div class="flex items-center mt-1">
      <i class="fas fa-phone mr-1">
      </i>
      <span>
       0857-4000-8282 (Reza)
      </span>
     </div>
        </div>
    </div>
</body>
</html>