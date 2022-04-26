<?php
require '../includes/config.php';
$db = new mysqli($dbhost, $dbusername, $dbpassword, $database);
$postpassword  = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
if(isset($postpassword)):
	if(empty($postpassword)) {
		echo "PWDEMPTY";
	}
	elseif(!filter_input(INPUT_POST,password,FILTER_VALIDATE_REGEXP,array("options" => array("regexp"=>"/^[^æøåÆØÅ]*$/")))) {
        echo "PWDINVALIDCHAR";
        exit();
    }
	elseif(!filter_input(INPUT_POST,password,FILTER_VALIDATE_REGEXP,array("options" => array("regexp"=>"/(?=.{8,}).*/")))) {
        echo "PWDTOSHORT";
        exit();
    }
	elseif(preg_match_all('/(?=^.{8,}$)(?=.*\d)(?=.*[\W]+)(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/',$postpassword)){
		echo "PWDSTRONG";
		exit();
	}else{
		echo "PWDFAIL";
		exit();
	}
endif;
?>