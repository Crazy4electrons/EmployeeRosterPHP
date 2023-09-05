<?php

$title = "";
$imgTitle = "";
$headDisplay = "";
$BodyDisplay = "";
$footerDisplay = "";
if (isset($_GET['redirect']) && !empty($_GET['redirect'])) {
    switch ($_GET['redirect']) {
        case 'main':
            $BodyDisplay = "dashboard/Body/LoginAndRegister/main.php";
            break;
        case 'login':
            $title = "Login";
            $imgTitle = "dashboard/images/logo.png";
            $BodyDisplay = "dashboard/Body/LoginAndRegister/Login.html";
            break;
            case 'register':
                $title = "Register";
                $imgTitle = "dashboard/images/logo.png";
            $BodyDisplay = "dashboard/Body/LoginAndRegister/register.php";
            break;
        default:
        $title = "error";
            $BodyDisplay ="<div class=\"ErrorLogin\">Theres an error in your url <br/> <b style=\"font-size:5em;\">404</b></div>";
    }
    $footerDisplay = "dashboard/Footer/MainFooter.html";
    $headDisplay = "dashboard/headers/Mheader.html";
} else {
    $title = "Log-in";
    $imgTitle = "dashboard/images/logo.png";
    $BodyDisplay = "dashboard/index.php";
    $headDisplay = "dashboard/headers/Mheader.html";
    $footerDisplay = "dashboard/Footer/MainFooter.html";
} ?>
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
        <?php htmlspecialchars($title) ?>
    </title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="dashboard/CssFiles/MCss.css">
    <link href=<?php htmlentities($imgTitle) ?> rel="icon" type="image/png" />
</head>

<body>
    <!--[if lt IE 7]>
    <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="#">upgrade your browser</a> to improve your experience.</p>
    <![endif]-->
    <?php
    require $headDisplay
    ?>
    <!-- include "nav"; -->

    <?php
    if ($title == "error") {
        echo $BodyDisplay;
    }else{
        require $BodyDisplay;
    }
    
    ?>
    <?php
    require $footerDisplay
    ?>
</body>


</html>