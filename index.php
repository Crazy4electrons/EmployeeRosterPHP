<?php
if(isset($_COOKIE['Username'],$_COOKIE['HashedPassword']) && !empty($_COOKIE)) {
	header('Location: '.$_SERVER['HTTP_HOST'].'/EmployeeRosterPHP/dashboard/index.php/');
	exit;
} else {
    header('Location: '.$_SERVER['HTTP_HOST'].'/EmployeeRosterPHP/dashboard/index.php/?login=true');
	exit;
}
?>