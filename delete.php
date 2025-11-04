<?php
include 'db.php';
$id = $_GET['id'];
$conn->query("DELETE FROM airlinedb WHERE ID=$id");
header("Location: index.php");
?>
