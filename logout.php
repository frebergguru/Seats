<?php
session_start();
if(isset($_SESSION['nickname'])){
	session_destroy();
	$left = true;
	header("Location: ".dirname($_SERVER['REQUEST_URI']));
	exit;
};
header("Location: ".dirname($_SERVER['REQUEST_URI']));
?>
