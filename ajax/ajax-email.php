<?php
require '../includes/config.php';
$db = new mysqli($dbhost, $dbusername, $dbpassword, $database);
if(!filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL)) {
    echo 'EMAILFAIL';
    exit();
}
$postemail  = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
if(isset($postemail)):
    $sql = $db->query("SELECT id FROM users WHERE email='$postemail'");
    if(mysqli_num_rows($sql))
    {
        echo 'EMAILINUSE';
    }
    else
    {
        echo "EMAILOK";
    }
endif;
?>
