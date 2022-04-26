<?php
error_reporting(E_ALL & ~E_NOTICE);
require 'includes/config.php';
require 'includes/functions.php';
$register_page = true;
require 'includes/header.php';
$db = new mysqli($dbhost, $dbusername, $dbpassword, $database);
$regsubmit = filter_input(INPUT_POST, 'regsubmit', FILTER_SANITIZE_STRING);
$fullname = filter_input(INPUT_POST, 'fullname', FILTER_SANITIZE_STRING);
$nickname = filter_input(INPUT_POST, 'nickname', FILTER_SANITIZE_STRING);
$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
$password2 = filter_input(INPUT_POST, 'password2', FILTER_SANITIZE_STRING);
if(isset($regsubmit) && !empty($regsubmit)){
    if(isset($password) && !empty($password) && !preg_match_all('/(?=^.{8,}$)(?=.*\d)(?=.*[\W]+)(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/',$password)){
        echo '<div class="regerror">FEIL: Passordet er ugyldig, pr&oslash;v igjen!</div><br><br>';
        $formstatus = 'FEIL';
    }
    if(isset($password2) && !empty($password2) && $password !== $password2) {
        echo '<div class="regerror">FEIL: Passordene er ikke like, pr&oslash;v igjen!</div><br><br>';
        $formstatus = 'FEIL';
    }
    if(isset($password) && empty($password)){
        echo '<div class="regerror">FEIL: Du m&aring; skrive inn et passord!</div><br><br>';
        $formstatus = 'FEIL';
    }
    if(isset($password2) && empty($password2)){
        echo '<div class="regerror">FEIL: Du m&aring; skrive inn et bekreftelses passord!</div><br><br>';
        $formstatus = 'FEIL';
    }
    if(isset($fullname) && empty($fullname)){
        echo '<div class="regerror">FEIL: Du m&aring; skrive inn et navn!</div><br><br>';
        $formstatus = 'FEIL';
    }
    if(isset($fullname) && !empty($fullname) && !preg_match_all('/([a-zA-ZæøåÆØÅ])(\s+([a-zA-ZæøåÆØÅ]{2,}))+/',$fullname)){
        echo '<div class="regerror">FEIL: Du m&aring; skrive inn hele navnet ditt!</div><br><br>';
        $formstatus = 'FEIL';
    }
    if(isset($email) && !empty($email)) {
        $sql = $db->query("SELECT id FROM users WHERE email='$email'");
        if(mysqli_num_rows($sql)) {
            echo '<div class="regerror">FEIL: E-mail adressen finnes fra f&oslash;r!</div><br><br>';
            $formstatus = 'FEIL';
        }
        $sql->free();
    }
    if(isset($email) && empty($email)){
        echo '<div class="regerror">FEIL: Du m&aring; skrive inn en gyldig e-post adresse!</div><br><br>';
        $formstatus = 'FEIL';
    }
    if(isset($nickname) && !empty($nickname)){
        $sql = $db->query("SELECT id FROM users WHERE nickname='$nickname'");
        if(mysqli_num_rows($sql)) {
            echo '<div class="regerror">FEIL: Kallenavnet finnes fra f&oslash;r!</div><br><br>';
            $formstatus = 'FEIL';
        }
        $sql->free();
    }
    if(isset($nickname) && !empty($nickname) && strlen($nickname) < 4) {
        echo '<div class="regerror">FEIL: Kallenavnet m&aring; v&aelig;re p&aring; minst 4 tegn!</div><br><br>';
        $formstatus = 'FEIL';
    }
    if(!isset($nickname) || empty($nickname)){
        echo '<div class="regerror">FEIL: Du m&aring; skrive inn et kallenavn!</div><br><br>';
        $formstatus = 'FEIL';
    }
}
if($formstatus !== 'FEIL' && isset($regsubmit) && !empty($regsubmit)){
$options = [
    'cost' => 12,
];
$pwdhash = password_hash($password, PASSWORD_DEFAULT, $options);
if($db->query("INSERT INTO users (fullname, nickname, password, email) VALUES ('$fullname', '$nickname', '$pwdhash', '$email')") === TRUE){
	echo '<span class="srs-header">Brukeren ble laget!</span>
<div class="srs-content">
Du kan n&aring; logge inn og registrere et sete.
</div><br><br>';
}
    $formstatus = True;
}
if($formstatus !== True){
    print'<form class="srs-container" method="POST" action="'.$_SERVER["PHP_SELF"].'">
        <span class="srs-header">Ny bruker</span>

        <div class="srs-content">
            <label for="fullname" class="srs-lb">Fullt navn</label><input name="fullname" value="'.$fullname.'" id="fullname" class="srs-tb"><br>
            <span id="statusfullname"></span><br>
            <label for="nickname" class="srs-lb">Kallenavn</label><input name="nickname" value="'.$nickname.'" id="nickname" class="srs-tb"><br>
            <span id="status"></span><br>
            <label for="email" class="srs-lb">E-post</label><input name="email" value="'.$email.'" id="email" class="srs-tb"><br>
            <span id="statusemail"></span><br>
            <label for="password" class="srs-lb">Passord</label><input name="password" id="password" type="password" class="srs-tb"><br>
            <span id="pwstatus"></span><br>
            <label for="password2" class="srs-lb">Gjenta passord</label><input name="password2" id="password2" type="password" class="srs-tb"><br>
            <span id="pwstatus2"></span><br>
        </div>
        <div class="srs-footer">
            <div class="srs-button-container">
                <input type="submit" class="submit" name="regsubmit" value="Registrer">
            </div>
            <div class="srs-slope"></div>
        </div>
    </form>
    <script src="./js/formcheck.js"></script>
    <script src="./js/pwdcheck.js"></script>';
};
$db->close();
?>
<br>
<?php
require 'includes/footer.php';
?>
