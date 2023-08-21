<!DOCTYPE html>
<!-- $title = "Login"; -->
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
    require "./headers/Mheader.html";
    // include "nav";
    if (isset($_GET['redirect']) && !empty($_GET['redirect'])) {
        switch ($_GET['redirect']) {
            case 'main':
                require "Body/main.php";
                break;
            case 'login':
                $_GET['login'] = true;
                require "Body/Login.php";
                break;
            case 'register':
                $_GET['login'] = false;
                require "Body/Login.php/";
                break;
            default:
                echo "Theres an error in your url";
        }
    } else {
        echo "no page specified";
    }
    require "./Footer/MainFooter.html";
    ?>
    <script src="./js/all.js" crossorigin="anonymous" async defer></script>
</body>

</html>