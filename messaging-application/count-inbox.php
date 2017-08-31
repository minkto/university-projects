<?php
require 'db.php';

session_start();

if (isset($_SESSION['name'])) {
	$receiver = $_SESSION['id'];
	$stmt2= $conn->query("SELECT * FROM  empuk_email WHERE receiver = '" . $_SESSION['id'] . "' AND draft = FALSE");
	$row_count2 = $stmt2->num_rows;
	echo $row_count2;
}

?>