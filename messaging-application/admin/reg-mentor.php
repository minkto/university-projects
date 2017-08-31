<link type="text/css" rel="stylesheet" href="../style.css" />

<?php
require '../db.php';
include 'header.php';
include '../footer.html';
include '../noscript.html';

if($_SESSION['role'] == "Admin"){
	if ($conn->connect_error)
		die("Could not connect to the server." . $conn->connect_error);
		
	$u = $_POST["name"]; //username
	$fn = $_POST["fname"]; //first name
	$sn = $_POST["sname"]; //surname
	$em = $_POST["email"]; //e-mail address
	$p = md5($_POST["password"]); //MD5 password
	$sq = $_POST["s-question"]; //security question
	$sa = md5($_POST["s-answer"]); //security answer

	//checks to see if the username already exists.
	$sql = "SELECT * FROM empuk_users WHERE username='" . $u . "'";
	
	if ($conn->query($sql)->num_rows > 0)
	{
		echo "<div id='loginform'><p>That username already exists!</p>
		<p><a href='../admin.php'>Return to the admin panel</a></p></div>";
	}
	else
	{
		//adds the mentor to the database, then sends the user back to the admin after 2 seconds.
		$sql = "INSERT INTO empuk_users (username, password, forename, surname, email, role, question, answer) VALUES ('" . $u . "','" . $p . "','" . $fn . "','" . $sn . "','" . $em . "','Mentor','" . $sq . "','" .  $sa. "')";

		if ($conn->query($sql) === TRUE)
		{
			echo "<div id='loginform'><p>Account <strong>[Mentor]" . $u . "</strong> was successfully registered!</p>
			<p>You will be automatically sent back to the admin panel in a few seconds...</p></div>
			<script type='text/javascript'>
				window.setTimeout(function() {
					window.location.replace('../admin.php')
				}, 2000);
			</script>";
		} 
		else 
		{
			echo "<div id='loginform'><p>Could not register the account: " . $conn->error . "</p>
			<p><a href='../admin.php'>Return</a></p></div>";
		}
	
	}

	$conn->close();
}

else {
	//kicks the user out to the index page if they are not an admin, without running any fo the queries above.
	header("Location: ../index.php");
}
?>