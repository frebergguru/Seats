<?php
require '../includes/config.php';
$db = new mysqli($dbhost, $dbusername, $dbpassword, $database);
$postnickname  = filter_input(INPUT_POST, 'nickname', FILTER_SANITIZE_STRING);
if(isset($postnickname)):
    $sql = $db->query("SELECT id FROM users WHERE nickname='$postnickname'");
    if(mysqli_num_rows($sql))
    {
        echo 'NICKFAIL';
    }
    elseif(strlen($postnickname) < 4)
    {
        echo 'LENGTHFAIL';
    }
    else
    {
        echo "NICKOK";
    }
endif;
?>
