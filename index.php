<?php
require_once "/dashboard/functions/SessionCntrl.php";
$CntrlSession = new SessionFunctions();
if($CntrlSession->isKeyExistsInSession("username")){
    setcookie('username',$CntrlSession->getSessionValue("username"),time()+60*60*24);
    header("location: /dashboard/index.php/?redirect=main");
    exit;
}else{
    header("location: /dashboard/index.php");
    exit;
}
