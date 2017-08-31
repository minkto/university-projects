<link type="text/css" rel="stylesheet" href="../style.css" />

<?php
require '../db.php';

session_start();

if($_SESSION['role'] == "Admin"){ //only runs queries if the logged in user is an admin
	if ($conn->connect_error)
		die("Could not connect to the server." . $conn->connect_error);
	
	$id = $_POST ['id'];
	$type = $_POST['type'];
	
	if ($type == "minutes") {
		$lockdate = date('Y-m-d H:i:s', time() + ($_POST["mins"] * 60));
		
		$sql = "UPDATE empuk_users SET time_locked = '" . $lockdate . "' WHERE id = " . $id;
		$conn->query($sql);
	}
	else if ($type == "date") {
		$strdate = strtotime($_POST['year'] . "-" . $_POST['month'] . "-" . $_POST['day'] . " 00:00:00");
		$lockdate = date('Y-m-d H:i:s', $strdate);
		
		$sql = "UPDATE empuk_users SET time_locked = '" . $lockdate . "' WHERE id = " . $id;
		$conn->query($sql);
	}
	else if ($type == "permanent") {
		$strdate = strtotime("9999-12-31 23:59:59");
		$lockdate = date('Y-m-d H:i:s', $strdate);
		
		$sql = "UPDATE empuk_users SET time_locked = '" . $lockdate . "' WHERE id = " . $id;
		$conn->query($sql);
	}
	else if ($type == "unlock") {
		$strdate = strtotime("0000-00-00 00:00:00");
		$lockdate = date('Y-m-d H:i:s', $strdate);
		
		$sql = "UPDATE empuk_users SET time_locked = '" . $lockdate . "' WHERE id = " . $id;
		$conn->query($sql);
	}
	
	$conn->close();
}
else {
	//if the user is not an admin, they are sent back to the index page and no queries are ran.
	//this is to prevent any user from being able to delete users from simply entering the url.
	header("Location: ../index.php");
}
?>