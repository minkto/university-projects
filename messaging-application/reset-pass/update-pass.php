<link type="text/css" rel="stylesheet" href="../style.css" />

<?php
require '../db.php';
include 'header.php';
include '../footer.html';
include '../noscript.html';

if(!isset($_SESSION['name'])){
	if ($conn->connect_error)
		die("Could not connect to the server." . $conn->connect_error);
		
	$u = $_POST["user"]; //username
	$p = md5($_POST["password"]); //MD5 password

	//updating the user's password in the database.
	$sql = "UPDATE empuk_users SET password = '" . $p . "' WHERE username = '" . $u . "'";

	if ($conn->query($sql) === TRUE)
	{
		echo "
		<div id='box-wrapper'>
			<div id='loginform'><p>Your password was successfully updated.</p>
			<p>You will be automatically sent back to the login page in a few seconds...</p></div>
		</div>
		
		<script type='text/javascript'>
			window.setTimeout(function() {
				window.location.replace('../index.php')
			}, 2000);
		</script>";
		
		$conn->close();
	} 
	else 
	{
		echo "
		<div id='box-wrapper'>
			<div id='loginform'><p>Could not update password: " . $conn->error . "</p>
			<p>[<a href='../index.php'>Return to the index</a>]</p></div>
		</div>";
		
		$conn->close();
	}
	
}

else {
	header("location: ../index.php");
}

?>