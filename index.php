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
    <style>
        @media (max-width: 640px) {
            .login-form {
                width: 90% !important;
                max-width: 400px;
            }
        }
    </style>
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
    <div class="flex flex-col lg:flex-row h-screen">
        <div class="hidden lg:block lg:w-3/5">
            <img alt="cover left" class="w-full h-full object-cover" src="assets/img/pustaka.png"/>
        </div>
        <div class="flex flex-col justify-center items-center w-full lg:w-2/5 bg-white p-4 sm:p-8">
            <h1 class="text-3xl font-bold mb-4 text-center">
                <span class="text-red-500">D</span>
                <span class="text-yellow-500">S</span>
                <span class="text-green-500">I</span>
                <span class="text-black-500">Pustaka</span>
            </h1>
            <h2 class="text-xl mb-6 text-center">Halaman DSI Pustaka Login</h2>
            <div class="content w-full">
                <form class="login-form w-11/12 sm:w-[400px] mx-auto" action="masuk.php" method="post">
                    <?php
                        if (isset($_SESSION['pesan']) && $_SESSION['pesan'] <> '') {
                            echo '<div class="alert alert-danger alert-dismissable bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                                    <strong class="font-bold">Error!</strong>
                                    <span class="block sm:inline">'.$_SESSION['pesan'].'</span>
                                    <span class="absolute top-0 bottom-0 right-0 px-4 py-3 cursor-pointer" onclick="this.parentElement.style.display=\'none\';">
                                        <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Close</title><path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/></svg>
                                    </span>
                                </div>';
                        }
                        $_SESSION['pesan'] = '';
                    ?>
                    <div class="mb-4">
                        <label class="block text-black text-lg mb-2" for="username">Username</label>
                        <div class="input-icon">
                            <input class="rounded-md w-full border-b-2 border-transparent py-2 px-1 text-gray-700 focus:outline-none focus:border-blue-500" type="text" autocomplete="off" placeholder="Masukkan Username" name="username" id="username"/>
                        </div>
                    </div>
                    <div class="mb-6">
                        <label class="block text-black text-lg mb-2" for="password">Password</label>
                        <div class="relative">
                            <i class="fas fa-eye absolute right-3 top-3 text-gray-500 cursor-pointer" id="eye-icon" onclick="togglePasswordVisibility()"></i>
                            <input class="rounded-md w-full border-b-2 border-transparent py-2 px-1 text-gray-700 focus:outline-none focus:border-blue-500" id="password" type="password" autocomplete="off" placeholder="Masukkan Password" name="password"/>
                        </div>
                    </div>
                    <div class="form-actions">
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
            <div class="text-center mt-10">
                <p class="text-lg font-semibold">
                    Powered by :
                </p>
                <div class="flex flex-col sm:flex-row items-center justify-center mt-2">
                    <img alt="esikatERP logo with text 'esikatERP by DSi'" class="border p-1 mb-4 sm:mb-0 sm:mr-4" height="50" src="assets/img/logo_esikat.png" width="120"/>
                    <div class="text-center sm:text-left">
                        <div class="flex items-center justify-center sm:justify-start mt-1">
                            <i class="fas fa-envelope mr-1"></i>
                            <span>office@klikdsi.com</span>
                        </div>
                        <div class="flex items-center justify-center sm:justify-start mt-1">
                            <i class="fas fa-phone mr-1"></i>
                            <span>0857-4000-8282 (Reza)</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>