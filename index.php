<?php
$title = "";
$imgTitle = "";
$headName = "";
$Mnav = "";
$BodyDisplay = "";
$footerDisplay = "";
$loginStatus = "dashboard/modules/loginStatus/loginstatus.php";
if (isset($_GET['redirect']) && !empty($_GET['redirect'])) {
    switch ($_GET['redirect']) {
        case 'main':
            $title = "Main";
            $BodyDisplay = "dashboard/BodyPages/main/Main.php";
            break;
        case 'login':
            $title = "Login";
            $imgTitle = "dashboard/images/logo.png";
            $BodyDisplay = "dashboard/BodyPages/LoginAndRegister/Login.php";
            break;
        case 'register':
            $title = "Register";
            $imgTitle = "dashboard/images/logo.png";
            $BodyDisplay = "dashboard/BodyPages/LoginAndRegister/register.php";
            break;
        case 'testpage';
            $title = "Testpage";
            $BodyDisplay = "dashboard/BodyPages/Testpage.php";
            break;
        default:
            $title = "error";
            $BodyDisplay = "<div class=\"ErrorLogin\">Theres an error in your url <br/> <b style=\"font-size:5em;\">404</b></div>";
    }
    $footerDisplay = "dashboard/Footer/MainFooter.html";
    $headName = "dashboard/headers/MainHeader.php";
} else {
    $title = "Log-in";
    $imgTitle = "dashboard/images/logo.png";
    $BodyDisplay = "dashboard/index.php";
    $headName = "dashboard/headers/MainHeader.php";
    $footerDisplay = "dashboard/Footer/MainFooter.html";
}


?>
<!DOCTYPE html>
<!-- $title = "Login"; -->
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]>      <html class="no-js"> <!<![endif]-->
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>
        <?php htmlspecialchars($title) ?>
    </title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href=<?php htmlentities($imgTitle) ?> rel="icon" type="image/png" />
</head>

<body>
<!--[if lt IE 7]>
<p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="#">upgrade your browser</a> to improve your experience.</p>
<![endif]-->
<header class="header">
    <?php
    include $headName;
    include $loginStatus;

    ?>
</header>
<nav class="MNav">
    <?php
    if ($Mnav != null) {
        include $Mnav;
    };
    ?>
</nav>

    <section class="Body">
        <?php
        if ($title == "error" or null) {
            echo $BodyDisplay;
        } else {
            require $BodyDisplay;
        }
        ?>
    </section>
    <footer class="footer">
        <?php include $footerDisplay; ?>
    </footer>
    
    <link rel="stylesheet" href="dashboard/CssFiles/MCss.css" media="print" onload="this.media='all'">
    <script src="dashboard/js/FAIcons/all.js" async="false" crossorigin="anonymous"></script>
</body>


</html>