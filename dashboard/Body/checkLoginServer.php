<?php
require "../DatabaseFunctions/DatabaseFunction.php";
if (isset($_GET['register']) && !empty($_GET['register']) && ($_GET['register']=== true)) {
    try {
        $chkusr = new DBaccess($_POST['username'], $_POST['password'], true);
        var_dump($chkuser);
    } catch (PDOException $th) {
        echo $th;
    }
} else {
    try {
        $chkusr = new DBaccess($_POST['username'], $_POST['password'], true);
        var_dump($chkuser);
}catch(PDOException $e){
echo $e;
}
}
