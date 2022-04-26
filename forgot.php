<?php
error_reporting(E_ALL & ~E_NOTICE);
require 'includes/config.php';
require 'includes/functions.php';
$nickname = filter_input(INPUT_GET, 'nickname', FILTER_SANITIZE_STRING);
$key = filter_input(INPUT_GET, 'key', FILTER_SANITIZE_STRING);
$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING);
$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
$password2 = filter_input(INPUT_POST, 'password2', FILTER_SANITIZE_STRING);
if(isset($password) && !empty($password) && !preg_match_all('/(?=^.{8,}$)(?=.*\d)(?=.*[\W]+)(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/',$password)){
    echo '<div class="regerror">FEIL: Passordet er ugyldig, pr&oslash;v igjen!</div><br><br>';
    $formstatus = 'FEIL';
}
if(isset($password2) && !empty($password2) && $password !== $password2) {
    echo '<div class="regerror">FEIL: Passordene er ikke like, pr&oslash;v igjen!</div><br><br>';
    $formstatus = 'FEIL';
}
if(isset($password) && !empty($password) && isset($password2) && !empty($password2) && isset($key) && !empty($key) && $formstatus != "FEIL"){
	require 'includes/header.php';
	print '<span class="srs-header">Nytt passord</span>
<div class="srs-content">
Passordet ditt er n&aring; endret, du kan logge inn ved &aring; trykke <a href="login.php">her</a>.
</div><br><br><br>';
	require 'includes/footer.php';
	$db = new mysqli($dbhost, $dbusername, $dbpassword, $database);
	$options = [
		'cost' => 12,
	];
	$pwdhash = password_hash($password, PASSWORD_DEFAULT, $options);
	$db->query("UPDATE users SET password='".$pwdhash."', forgotkey='', ip='".$clientip."' WHERE nickname='".$nickname."'");
	$db->close();
	$pwdchanged = true;
}
if(isset($nickname) && !empty($nickname) && isset($key) && !empty($key) && $pwdchanged !=true) {
	$db = new mysqli($dbhost, $dbusername, $dbpassword, $database);
	$sql = $db->query("SELECT forgotkey FROM users WHERE nickname = '".$nickname."'");
	$sqlresults = $sql->fetch_array(MYSQLI_ASSOC);
	$forgotkey = $sqlresults["forgotkey"];
	$sql->free();
	$db->close();
	if($key == $forgotkey){
	require 'includes/header.php';
	print '<form class="srs-container" method="POST" action="'.$_SERVER["PHP_SELF"].'?nickname='.$nickname.'&key='.$forgotkey.'">
<span class="srs-header">Glemt passord</span>
<div class="srs-content">
	<label for="password" class="srs-lb">Passord</label><input name="password" id="password" type="password" class="srs-tb"><br>
	<span id="pwstatus"></span><br>
	<label for="password2" class="srs-lb">Gjenta passord</label><input name="password2" id="password2" type="password" class="srs-tb"><br>
	<span id="pwstatus2"></span><br>
</div>
<div class="srs-footer">
	<div class="srs-button-container">
		<input type="submit" class="submit" name="regsubmit" value="Bytt passord">
	</div>
	<div class="srs-slope"></div>
</div>
</form>
<script src="./js/pwdcheck.js"></script><br>';
	require 'includes/footer.php';
		exit;
	}else{
		require 'includes/header.php';
		print '<span class="srs-header">Nytt passord - FEIL</span>
<div class="srs-content">
Feil kallenavn eller verifikasjonsn&oslash;kkel.
</div><br><br><br>';
		require 'includes/footer.php';
		exit;
	};
}elseif(!empty($email)){
	require 'includes/header.php';
	print '<span class="srs-header">Nytt passord - E-post</span>
<div class="srs-content">
Du har n&aring; f&aring;tt tilsendt en e-post med instruksjoner hvis adressen du har skrevet inn finnes.
</div><br><br><br>';
	require 'includes/footer.php';
	$db = new mysqli($dbhost, $dbusername, $dbpassword, $database);
	$sql = $db->query("SELECT nickname FROM users WHERE email='$email'");
	$sqlresults = $sql->fetch_array(MYSQLI_ASSOC);
	if($sql->num_rows == 1){
		$nickname = $sqlresults['nickname'];
		$sql->close();
		$randomkey = random_string();
		$clientip = get_client_ip();
		$db->query("UPDATE users SET forgotkey='".$randomkey."', ip='".$clientip."' WHERE nickname='".$nickname."'");
		$db->close();
		$mailheaders = 'From: Sete reservering <noreply@'.$_SERVER["SERVER_NAME"].'>'."\r\n".
	'X-Mailer: Seat Reservation/1.0';
		$mailmsg = "Trykk p&aring; linken under for å bytte passordet ditt.\n\n https://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']."?nickname=".$nickname."&key=".$randomkey;
		$mailsubject = "Sete reservering";
		mail($email, $mailsubject, $mailmsg, $mailheaders);
		exit;
	}
}else{
	if($pwdchanged != true) {
		require 'includes/header.php';
		print '<form class="srs-container" method="POST" action="'.$_SERVER["PHP_SELF"].'">
<span class="srs-header">Glemt passord</span>
<div class="srs-content">
	<label for="email" class="srs-lb">E-post</label><input name="email" value="" id="email" class="srs-tb"><br>
</div>
<div class="srs-footer">
	<div class="srs-button-container">
		<input type="submit" class="submit" name="regsubmit" value="Fortsett">
	</div>
	<div class="srs-slope"></div>
</div>
</form><br>';
		require 'includes/footer.php';
	};
};
?>
