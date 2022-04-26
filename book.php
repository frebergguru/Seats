<?php
error_reporting(E_ALL & ~E_NOTICE);
require 'includes/config.php';
require 'includes/functions.php';
session_start();
$nickname = $_SESSION['nickname'];
$seat = filter_input(INPUT_GET, 'seatid', FILTER_VALIDATE_INT);
if(isset($nickname) && !empty($nickname) && isset($seat) && !empty($seat)) {
	$db = new mysqli($dbhost, $dbusername, $dbpassword, $database);
	$sql = $db->query("SELECT maxseats FROM config");
	$sqlresults = $sql->fetch_array(MYSQLI_ASSOC);
	$maxseats = $sqlresults["maxseats"];
	if($seat < $maxseats){
		$sql = $db->query("SELECT id FROM users WHERE nickname='$nickname'");
		$sqlresults = $sql->fetch_array(MYSQLI_ASSOC);
		$userid = $sqlresults["id"];
		$sql->free();
		$sql = $db->query("SELECT rseat FROM users WHERE nickname='$nickname'");
		$sqlresults = $sql->fetch_array(MYSQLI_ASSOC);
		if(empty($sqlresults["rseat"])){
			$sql->free();
			$db->query("UPDATE users SET rseat='".$seat."' WHERE nickname='".$_SESSION['nickname']."'");
			$db->query("INSERT INTO reservations (taken, user_id) VALUES($seat, $userid)");
			$db->close();
			header('Location: '.dirname($_SERVER['REQUEST_URI']));
			exit;
		}else{
			require 'includes/header.php';
			print '<span class="srs-header">Det har skjedd en feil</span>
<div class="srs-content">
Du kan bare reservere et sete!
</div><br><br><br>';
			require 'includes/footer.php';
		};
		$sql->free();
		$db->close();
		}else{
			require 'includes/header.php';
			print'<span class="srs-header">Det har skjedd en feil</span>
<div class="srs-content">
Sete du har valgt finnes ikke.
</div><br><br><br>';
			require 'includes/footer.php';
	}
}else{
	header("Location: ".dirname($_SERVER['REQUEST_URI']));
};
?>
