<?php
error_reporting(E_ALL & ~E_NOTICE);
session_start();
$home = true;
require 'includes/config.php';
require 'includes/functions.php';
$mysqli = new mysqli($dbhost, $dbusername, $dbpassword, $database);
if($mysqli->connect_errno) {
    printf("Kunne ikke koble til database serveren: %s\n", $mysqli->connect_error);
    exit();
}
require 'includes/header.php';
?>
    <!-- SEATMAP START -->
    <div class="seatmap">
    <p class="heading">Symbol forklaring:</p>
    <p class="seat_symbols"><img src="./img/yellow.jpg" height="15" alt="Ledig sete"> Valgt sete <img src="./img/red.jpg" height="15" alt="Opptatt sete"> Opptatt sete <img src="./img/green.jpg" height="15" alt="Ledig sete"> Ledig sete</p>
    <hr>
    <?php
    if(($result = $mysqli->query("SELECT * FROM `config`"))===false)
    {
        printf("Invalid query: %s\nWhole query: %s\n", $mysqli->error, $SQL);
        exit();
    }
    $row = $result->fetch_array(MYSQLI_ASSOC);
    $maxseats = $row["maxseats"];
    $seat_width = $row["seat_width"];
    $seat_height = $row["seat_height"];
    $width = $row["width"];
    $seatmapwidth = $width * $seat_width + 40;
    print '<p class="heading">[ - FRONT - ]</p>';
    seats($maxseats,$seat_width,$seat_height,$width);
    print '</div>';
    $result->close();?>

    <!-- SEATMAP END -->
    <!-- SEAT TAKEN? START -->
    <?php
    $seatid  = filter_input(INPUT_GET, 'seatid', FILTER_VALIDATE_INT);
    if(isset($seatid)) {
        if(($result = $mysqli->query("SELECT * FROM `reservations` WHERE taken = '$seatid'"))===false)
        {
            printf("Invalid query: %s\nWhole query: %s\n", $mysqli->error, $SQL);
            exit();
        }
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            if ($row["taken"] == $seatid) {
                $occupied = 1;
                $user = $row["user_id"];
            };
        };
        $result->free();
        if(!isset($occupied)){$occupied='';};
        if($occupied == "1") {
            if(($result = $mysqli->query("SELECT * FROM `users` WHERE id = '$user'"))===false)
            {
                printf("Invalid query: %s\nWhole query: %s\n", $mysqli->error, $SQL);
                exit();
            }
            $row = $result->fetch_array(MYSQLI_ASSOC);
            $fullname = $row["fullname"];
            $result->free();
        };
        if(!empty($seatid)) {
            print '<br>
<div class="seat_registered">';
            if(!empty($occupied)) {
                print 'Sete nummer '.$seatid.' er reservert av '.$fullname.'</div>';
            }else{
                if(!isset($_SESSION['nickname'])) {
                    print 'Du m&aring; logge inn f&oslash;r du kan reservere et sete.</div>';
                }else{
                    if(($result = $mysqli->query("SELECT * FROM users WHERE nickname='".$_SESSION['nickname']."'"))===false)
                    {
                        printf("Invalid query: %s\nWhole query: %s\n", $mysqli->error, $SQL);
                        exit();
                    }
                    $row = $result->fetch_array(MYSQLI_ASSOC);
                    $rseat = $row["rseat"];
                    if(empty($rseat) OR $rseat == 0){
                        print 'Vil du reservere sete nummer '.$seatid.'? <a href="book.php?seatid='.$seatid.'">Ja</a></div>';
                        $result->free();
                    }else{
                        print '<strong>FEIL: Du kan kun reservere et sete.</strong><br><br>
Du har reservert sete nummer '.$rseat.'.</div>';
                    };
                };
            };
        };
    };
    ?>
    <!-- SEAT TAKEN? END -->
<?php
require 'includes/footer.php';
$mysqli->close()
?>
