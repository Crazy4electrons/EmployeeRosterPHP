<?php
require "../DatabaseFunctions/DBFunctions.php";
//register user
if (isset($_GET['register']) && !empty($_GET['register']) && ($_GET['register'] === true)) {
    try {
        $chkuser = new DBaccess($_POST['username'], $_POST['password'], true);
        header("location: ./index.php");
        var_dump($chkuser);
    } catch (PDOException $th) {
        echo $th;
    }
} else {
    //login and save details
    try {
        $chkuser = new DBaccess($_POST['username'], $_POST['password']);
        if ($_POST['rememberPass']) {
        setcookie()}
        var_dump($chkuser);
    } catch (PDOException $e) {
        echo $e;
    }
}
