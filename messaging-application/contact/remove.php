<?php
require '../db.php';

session_start();

if (!isset($_SESSION['name']))
	header("Location: ../index.php");

$id = $_POST['id'];
$sql = "DELETE FROM empuk_contacts WHERE u1 = '" . $_SESSION['id'] . "' AND u2 = '" . $id ."'";

if ($conn->query($sql) === TRUE)
	echo "User was removed from your Contacts.";
else
	echo $conn->error;
?>