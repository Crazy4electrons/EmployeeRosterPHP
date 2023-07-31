<?php
if(isset($_COOKIE['Username'],$_COOKIE['HashedPassword']) && !empty($_COOKIE)) {
	header('Location: /EmployeeRosterPHP/dashboard/index.php/');
	exit;
} else {
    header('Location: /EmployeeRosterPHP/dashboard/index.php/?login=true');
	exit;
}
?>