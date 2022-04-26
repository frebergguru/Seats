<?php
error_reporting(E_ALL & ~E_NOTICE);
include 'includes/config.php';
include 'includes/functions.php';
$pwdwrong = false;
$nickname = filter_input(INPUT_POST, 'nickname', FILTER_SANITIZE_STRING);
$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
if(isset($nickname) && !empty($nickname) && isset($password) && !empty($password)) {
	$db = new mysqli($dbhost, $dbusername, $dbpassword, $database);
	$sql = $db->query("SELECT password FROM users WHERE nickname='$nickname'");
	$sqlresults = $sql->fetch_array(MYSQLI_ASSOC);
	if(password_verify($password, $sqlresults["password"])){
		session_start();
		$_SESSION['nickname'] = $nickname;
		$sql->free();
		$db->close();
		$left = false;
		header('Location: '.dirname($_SERVER['REQUEST_URI']));
		exit;
	}{
			include 'includes/header.php';
			print'<span class="srs-header">Feil brukernavn eller passord!</span><br><br><br>';
		$pwdwrong=true;
	}
	$sql->free();
	$db->close();
}{
    if($pwdwrong == false){
    include 'includes/header.php';
};
    print'<form class="srs-container" method="POST" action="'.$_SERVER["PHP_SELF"].'">
        <span class="srs-header">Logg inn</span>

        <div class="srs-content">
            <label for="fullname" class="srs-lb">Kallenavn</label><input name="nickname" value="'.$nickname.'" id="nickname" class="srs-tb"><br>
            <span id="statusfullname"></span><br>
            <label for="password" class="srs-lb">Passord</label><input name="password" id="password" type="password" class="srs-tb"><br>
        </div>
        <div class="srs-footer">
            <div class="srs-button-container">
                <input type="submit" class="submit" value="Logg inn">
            </div>
            <div class="srs-slope"></div>
        </div>
    </form>';
};
?>
<br>
<?php
include 'includes/footer.php';
?>
