<?php
if (isset($_POST["name"]) && !empty($_POST["name"])) {
    $name = $_POST["name"];
    if (!preg_match("/^[a-zA-Z ]*$/",$name)){
        echo "Name: Only letters and whitespace allowed";
    }else{
        echo "Name: ".$_POST["name"]."\n";
    }
}
if (isset($_POST["password"]) && !empty($_POST["password"])) {
    $password = $_POST["password"];
    if(strlen($password)<= 8) {
        echo "password must be more than 8 characters";
    }
}
?>