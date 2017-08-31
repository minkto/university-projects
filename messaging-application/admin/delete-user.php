<link type="text/css" rel="stylesheet" href="../style.css" />

<?php
require '../db.php';
include 'header.php';
include '../footer.html';
include '../noscript.html';

if($_SESSION['role'] == "Admin"){ //only runs queries if the logged in user is an admin
	if ($conn->connect_error)
		die("Could not connect to the server." . $conn->connect_error);
	
	$id = $_POST["id"]; //user ID

	$sql = "DELETE FROM empuk_users WHERE id=" . $id; //query for deleting the user.
	
	if ($conn->query($sql) === TRUE) {
		//prompt saying that the user was deleted, followed by an auto-redirect to the admin panel.
		echo "<div id='loginform'><p>Account was successfully deleted.</p>
		<p>You will be automatically sent back to the user actions page in a few seconds...</p></div>
		<script type='text/javascript'>
			window.setTimeout(function() {
				window.location.replace('user-actions.php?id=" . $id . "')
			}, 2000);
		</script>";
	}
	
	else {
		//if the user could not be deleted, the error is shown.
		echo "<div id='loginform'><p>Unable to delete the account: " . $conn->error . "</p>
		<p>You will be automatically sent back to the user actions page in a few seconds...</p></div>
		<script type='text/javascript'>
			window.setTimeout(function() {
				window.location.replace('user-actions.php?id=" . $id . "')
			}, 2000);
		</script>";
	}

	$conn->close();
}
else {
	//if the user is not an admin, they are sent back to the index page and no queries are ran.
	//this is to prevent any user from being able to delete users from simply entering the url.
	header("Location: ../index.php");
}
?>