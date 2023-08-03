<!-- $title = "Login"; -->
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]>      <html class="no-js"> <!--<![endif]-->
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>
            Login
        </title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="../CssFiles/MCss.css">
        <link href="./images/favicon.ico" rel="icon" type="image/png" />
    </head>
    <body>
    <!--[if lt IE 7]>
        <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="#">upgrade your browser</a> to improve your experience.</p>
    <![endif]-->
<?php
session_start();
require "./headers/Mheader.html";
// include "nav";
if(isset($GET['register']) && !empty($GET['register']) && ($_GET['register']===true)){
    require "./Body/Login.php";
}else if(isset($_COOKIE[$_GET['username']]) && !empty($_COOKIE[$_GET['username']])) {
    $RjsonString = $_COOKIE["'".$Get['username']."'"];
    $usercookies = json_decode($RjsonString);
    if ($usercookies['rememberPass'] == 1) {   
        require "./Body/Main.php";
    }else if($_SESSION['loggedIn'] == 'yes'){
        require "./Body/Main.php";
    }else{
        require "./Body/Login.php";
    }
}else{
    require "./Body/login.php";
}



require "./Footer/MainFooter.html";
?>
    <script src="" async defer></script>
    </body>
    <script src="./js/all.js" crossorigin="anonymous"></script>
</html>
