<?php
$db_host = "localhost";
$db_name = "nalemp";
$db_user = "root";
$db_pass = "password*#21";

try{
	
	$connect = new PDO ("mysql:host={$db_host}; dbname={$db_name}", $db_user, $db_pass);
	$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}

catch(PDOException $e) {
	echo $e->getMessage();
}

$connect->exec('SET NAMES utf8');
?>
