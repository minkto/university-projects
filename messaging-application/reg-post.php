<link type="text/css" rel="stylesheet" href="style.css" />

<?php
require 'db.php';
require 'header.php';
require 'footer.html';
require 'noscript.html';

if(!isset($_SESSION['name'])){
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
		echo "
		<div id='box-wrapper'>
			<div id='loginform'>
				<p>That username already exists!</p>
				<p><a href='reg.php'>Return to the registration page</a></p>
			</div>
		</div>";
	}
	else
	{
		//adds the user to the database, then sends the user back to the login screen after 2 seconds.
		$sql = "INSERT INTO empuk_users (username, password, forename, surname, email, role, question, answer) VALUES ('" . $u . "','" . $p . "','" . $fn . "','" . $sn . "','" . $em . "','User','" . $sq . "','" . $sa . "')";

		if ($conn->query($sql) === TRUE)
		{
			echo "
			<div id='box-wrapper'>
				<div id='loginform'>
					<p>Account <strong>" . $u . "</strong> was successfully registered!</p>
					<p>You will be automatically sent back to the login page in a few seconds...</p>
				</div>
			</div>
			
			<script type='text/javascript'>
				window.setTimeout(function() {
					window.location.replace('index.php')
				}, 2000);
			</script>";
		} 
		else 
		{
			echo "
			<div id='box-wrapper'>
				<div id='loginform'>
					<p>Could not register the account: " . $conn->error . "</p>
					<p><a href='reg.php'>Return</a></p>
				</div>
			</div>";
		}
	
	}

	$conn->close();
}

else {
	header("location: ../index.php");
}

?>