<?php
function seats($maxseats, $seat_width, $seat_height, $width) {
    $seatid  = filter_input(INPUT_GET, 'seatid', FILTER_VALIDATE_INT);
    require 'config.php';
    $mysqli = new mysqli($dbhost, $dbusername, $dbpassword, $database);
    if($mysqli->connect_errno) {
        printf("Kunne ikke koble til database serveren: %s\n", $mysqli->connect_error);
        exit();
    }
    $i = 0;
    while ($i < $maxseats) {
        $i++;

        if(($result = $mysqli->query("SELECT * FROM `reservations` WHERE taken = '$i'"))===false)
        {
            printf("Invalid query: %s\nWhole query: %s\n", $mysqli->error, $SQL);
            exit;
        }
        $row = $result->fetch_array(MYSQLI_ASSOC);
        if($row["taken"] == $i) {
            print '<a href="?seatid='.$i.'"><img src="./img/red.jpg" width="'.$seat_width.'" height="'.$seat_height.'" alt="Sete nummer: '.$i.' - Opptatt sete"></a> ';
        }elseif($seatid == $i){
            print '<img src="./img/yellow.jpg" width="'.$seat_width.'" height="'.$seat_height.'" alt="Sete nummer: '.$i.' - Valgt sete"> ';
        } else {
            print '<a href="?seatid='.$i.'"><img src="./img/green.jpg" width="'.$seat_width.'" height="'.$seat_height.'" alt="Sete nummer: '.$i.' - Ledig sete"></a> ';
        };
        if(!isset($width2)){$width2='';};
        $width2 = $width2 + 1;
        if ($width2 == $width) {
            $width2 = 0;
            print "<br>\n";
        };
        $result->free();
    };
    $mysqli->close();
};
function get_client_ip() {
        $ipaddress = '';
        if ($_SERVER['HTTP_CLIENT_IP'])
                $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if($_SERVER['HTTP_X_FORWARDED_FOR'])
                $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if($_SERVER['HTTP_X_FORWARDED'])
                $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if($_SERVER['HTTP_FORWARDED_FOR'])
                $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if($_SERVER['HTTP_FORWARDED'])
                $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if($_SERVER['REMOTE_ADDR'])
                $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
                $ipaddress = 'UNKNOWN';
        return $ipaddress;
};
function random_string() {
	$chars = str_split('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%<>&*()');
	shuffle($chars);
	$random='';
	foreach (array_rand($chars, 16) as $r) $random .= $chars[$r];
	return $random;
}
