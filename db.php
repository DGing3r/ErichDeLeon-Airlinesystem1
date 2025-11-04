<?php
$host = "sql104.infinityfree.com";
$user = "if0_40326646";
$pass = "MSajXNbag61";
$db = "if0_40326646_airlinepo";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
