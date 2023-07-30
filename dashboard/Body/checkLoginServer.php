<?php

require "./dashboard/DatabaseFunctions/DBFunctions.php";

if(isset($_POST['name'],$_POST['password']) && !empty($_POST['name'] && $_POST['password'])){
    $AdminDBAcces = new DBaccess($_POST['username'], $_POST['password']);
    if ($AdminDBAcces->UserExists) {
        # code...
    }
}