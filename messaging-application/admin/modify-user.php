<link type="text/css" rel="stylesheet" href="../style.css" />

<?php
require '../db.php';
require 'header.php';
require '../footer.html';
require '../noscript.html';

if($_SESSION['role'] == "Admin"){ //only runs the queries if the user is logged in as an admin.
	if ($conn->connect_error)
		die("Could not connect to the server." . $conn->connect_error);
	
	$id = $_POST["id"]; //user ID
	$u = $_POST["name"]; //username
	$fn = $_POST["fname"]; //first name
	$sn = $_POST["sname"]; //surname
	$em = $_POST["email"]; //e-mail address
	
	$sql = "SELECT username FROM empuk_users WHERE username = '" . $u . "'";
	$result = $conn->query($sql);
	
	if ($result->num_rows == 0) {
		$sql = "UPDATE empuk_users SET username = '" . $u . "', forename = '" . $fn . "', surname = '" . $sn . "', email = '" . $em . "' WHERE id = " . $id; //updates the user's details with those that the admin entered.
	
		if ($conn->query($sql) === TRUE) {
			//shows a prompt if the changes were successful, and then returns the admin to the user actions page automatically.
			echo "
			<div id='box-wrapper'>
				<div id='loginform'>
					<p>Account <strong>" . $u . "</strong> was successfully modified.</p>
					<p>You will be automatically sent back to the user actions page in a few seconds...</p>
				</div>
			</div>
			
			<script type='text/javascript'>
				window.setTimeout(function() {
					window.location.replace('user-actions.php?id=" . $id . "')
				}, 2000);
			</script>";
		}
		else {
			//shows an error if the changes were unsuccessful.
			echo "
			<div id='box-wrapper'>
				<div id='loginform'>
					<p>Unable to modify: " . $conn->error . "</p>
					<p>You will be automatically sent back to the user actions page in a few seconds...</p>
				</div>
			</div>
			
			<script type='text/javascript'>
				window.setTimeout(function() {
					window.location.replace('user-actions.php?id=" . $id . "')
				}, 2000);
			</script>";
		}
	}
	
	else { //if a user already exists with the specified username
		echo "
		<div id='box-wrapper'>
			<div id='loginform'>
				<p>A user already exists with that username.</p>
				<p>You will be automatically sent back to the user actions page in a few seconds...</p>
			</div>
		</div>
		
		<script type='text/javascript'>
			window.setTimeout(function() {
				window.location.replace('user-actions.php?id=" . $id . "')
			}, 2000);
		</script>";
	}

	

	$conn->close();
}
else {
	//kicks the user out to the index page if they are not an admin, without running any queries.
	header("Location: ../index.php");
}
?>