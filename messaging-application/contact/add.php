<?php
require '../db.php';

session_start();

if (!isset($_SESSION['name']))
	header("Location: ../index.php");

$user = $_POST['user'];
$sql = "SELECT id FROM empuk_users WHERE username = '" . $user . "'";
$result = $conn->query($sql);

if ($result->num_rows != 1)
	echo "That user does not exist. Make sure you typed the username correctly.";
else {	
	$row = $result->fetch_assoc();
	$id = $row['id'];
	
	$sql = "SELECT u1 FROM empuk_contacts WHERE u1 = " . $_SESSION['id'] . " AND u2 = " . $id;
	$result = $conn->query($sql);
	
	if ($result->num_rows > 0)
		echo "That user is already in your Contacts list.";
	else {
		$sql = "INSERT INTO empuk_contacts (u1, u2) VALUES ('" . $_SESSION['id'] . "','" . $id . "')";
	if ($conn->query($sql) === TRUE)
		echo $user . " was successfully added to your Contacts.";
	else
		echo $conn->error;
	}
}
?>