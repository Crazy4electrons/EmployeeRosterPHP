<?php
require "../DatabaseFunctions/DBFunctions.php";
//register user
if (isset($_GET['register']) && !empty($_GET['register']) && ($_GET['register'] === true)) {
    try {
        $chkuser = new DBaccess($_POST['username'], $_POST['password'], true);
        header("location: '/EmpoyeeRostersPHP/dashboard/index.php");
        var_dump($chkuser);
    } catch (PDOException $th) {
        echo $th;
    }
    exit;
} else {
    //login and save details
    try {
        $chkuser = new DBaccess($_POST['username'], $_POST['password']);
    } catch (PDOException $e) {
        echo $e;
    }
    if ($chkuser->userExists($_POST['username'])) {//does user exists
        if ($_POST['rememberPass']) {//should we remember pasword
            if (isset($_COOKIE["'" . $_POST['username'] . "'"]) && !empty($_COOKIE["'" . $_POST['username'] . "'"])) {
                $RjsonString = $_COOKIE["'" . $_POST['username'] . "'"];
                $usercookies = json_decode($RjsonString);
                $usercookies['rememberPass'] =  $_POST['rememberPass'];
                $SjsonString = json_encode($usercookies);
            } else {
                $usercookies['rememberPass'] = $_POST['rememberPass'];
                $usercookies['loggedIn'] = 'yes';
                $SjsonString = json_encode($usercookies);
            }
            $_SESSION[$_POST['username']] = $SjsonString;
            setcookie("'" . $_POST['username'] . "'", $SjsonString, time() + 60 * 60 * 24 * 91.25, "/dashboard/seikooc/");
            
        }else{
            header("Location: /EmployeeRosterPHP/dashboard/index.php/?username=".$_POST['username']);
        }
        var_dump($RjsonString);
        var_dump($usercookies);
        var_dump($SjsonString);
        var_dump($chkuser);
    }else{
        Echo "user dos not exist";
    }
}
