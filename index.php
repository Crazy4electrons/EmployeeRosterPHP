<?php

//go to dashboard
// if (!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])) {
//     $uri = 'http://';
// } else {
//     $uri = '/EmployeeRosterPHP/dashboard/DatabaseFunctions/chkdb.html';
// }
// $url = $_SERVER['HTTP_HOST'];
// // $url .= $uri;
// header('Location: '.$uri);
// exit;

// if(isset($_COOKIE['AHostname'],$_COOKIE['AUsername'],$_COOKIE['ADatabase'],$_COOKIE['AHashedPassword']) && !empty($_COOKIE)) {
// 	header('Location: '.$_SERVER['HTTTP_HOST'].'/EmployeeRosterPHP/index.php');
// } else {
//     header('Location: '.$_SERVER['HTTP_HOST'].'/EmployeeRosterPHP/dashboard/DatabaseFunctions/chkdb.html');
// 	exit;
// }
$Cput = array('john' => 'bob','ali'=>'skop','bak'=>'gat');
$keyV = key()
file_put_contents('./chkwrking.txt',$Cput,1,null);