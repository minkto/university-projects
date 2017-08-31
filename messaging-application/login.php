<link type="text/css" rel="stylesheet" href="style.css" />

<?php
require 'db.php';
require 'header.php';
require 'footer.html';
require 'noscript.html';

//if no session is already set, attempts to log in the specified user.
if (!isset($_SESSION['name']))
{
	if ($conn->connect_error)
		die("Could not connect to the server." . $conn->connect_error);
	
	$u=stripslashes($_POST['name']); //username
	$p=md5(stripslashes($_POST['password'])); //password MD5 (it isn't transferred/stored in plaintext!)
	
	//checking to see if the user exists.
	$sql = "SELECT username, password, forename, surname, attempts, time_locked FROM empuk_users WHERE username='" . $u . "'";
	$result = $conn->query($sql);
			
	if ($result->num_rows == 1)
	{
		$row = $result->fetch_assoc();
		
		if ($row['password'] == $p) { //checking to see if the password is correct.
			//preventing the user from logging in if they are locked of their account.
			if(date('Y-m-d H:i:s') < $row['time_locked']) {
				echo "
				<div id='box-wrapper'>
					<div id='loginform'>
						<p>For security reasons, your account has been temporarily locked.</p>
						<p>You can try to log in again at <b>" . $row['time_locked'] . "</b>.</p>
						<p><a href='index.php'>Return to the login page</a></p>
					</div>
				</div>";	
			}
			
			else{
				$_SESSION['name'] = stripslashes(htmlspecialchars($u)); //strips backslashes to prevent code injection.
				
				//fetches some user details and stores them in the session for later use.
				$sql = 'SELECT id, forename, surname, role FROM empuk_users WHERE username = "' . $u . '"';
				$result = $conn->query($sql);
				$row = $result->fetch_assoc();
				$id = $row["id"];
				$fn = $row["forename"];
				$sn = $row["surname"];
				$role = $row["role"];
				$_SESSION['id'] = $id;
				$_SESSION['fname'] = $fn;
				$_SESSION['sname'] = $sn;
				$_SESSION['role'] = $role;
				$date = date('Y-m-d H:i:s');
				
				//sets user's status to Online and updates the "last active" time to the current time.
				$sql = 'UPDATE empuk_users SET online = TRUE, last_active = "' . $date . '", attempts = 0 WHERE username = "' . $u . '"';
				$result = $conn->query($sql);
					
				if ($conn->query($sql) === TRUE)
				{
					//shows a "login successful" prompt and redirects the user to the inbox page.
					if ($role == "Admin")
					{
						echo "
						<div id='box-wrapper'>
							<div id='loginform'>
								<p>Successfully logged in as <strong>[Admin] " . $fn . " " . $sn . " (" .  $u . ")</strong>.</p>";
					}
					else if($role == "Mentor")
					{
						echo "
						<div id='box-wrapper'>
							<div id='loginform'>
								<p>Successfully logged in as <strong>[Mentor] " . $fn . " " . $sn . " (" .  $u . ")</strong>.</p>";
					}
					else
					{
						echo "
						<div id='box-wrapper'>
							<div id='loginform'>
								<p>Successfully logged in as <strong>" . $fn . " " . $sn . " (" .  $u . ")</strong>.</p>";
					}
					
					echo "
							<p>You will be automatically sent to your inbox in a few seconds...</p>
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
					//destroys the session if there was a problem logging in.
					session_destroy();
					
					echo "
					<div id='box-wrapper'>
						<div id='loginform'>
							<p>Unable to login: " . $conn->error . "</p>
							<p>You will be automatically sent back to the login page in a few seconds...</p>
						</div>
					</div>
					
					<script type='text/javascript'>
						window.setTimeout(function() {
						window.location.replace('index.php')
						}, 2000);
					</script>";
				}
			}
		}

		else { //incorrect password entered.
			//checking how many times the user has incorrectly entered their password in a row,
			//as well as whether or not they are locked out of their account.
			$sql = "SELECT time_locked, attempts FROM empuk_users WHERE username = '" . $u . "'";
			$result = $conn->query($sql);
			$row = $result->fetch_assoc();
			$locked = $row['time_locked'];
			$att = $row['attempts'];
			
			if(date('Y-m-d H:i:s') < $locked) {
				echo "
				<div id='box-wrapper'>
					<div id='loginform'>
						<p>For security reasons, your account has been temporarily locked.</p>
						<p>You can try to log in again at <b>" . $locked . "</b>.</p>
						<p><a href='index.php'>Return to the login page</a></p>
					</div>
				</div>";	
			}
			
			else {
				//resets the attempts counter to 0 if the user's account is no longer locked.
				if (($att > 2) && (date('Y-m-d H:i:s') > $locked)) {
					$conn->query("UPDATE empuk_users SET attempts = 0 WHERE username = '" . $u . "'");
				}
				
				//adds 1 to the user's attempt counter.
				$sql = "SELECT attempts FROM empuk_users WHERE username = '" . $u . "'";
				$result = $conn->query($sql);
				$row = $result->fetch_assoc();
				$att = $row['attempts'] + 1;
				
				$sql = "UPDATE empuk_users SET attempts = " . $att . " WHERE username = '" . $u . "'";
				$result = $conn->query($sql);
				
				if ($att > 2) {
					//if the user enters the incorrect password three times in a row,
					//they are locked out of their account for 20 minutes.
					$date = date('Y-m-d H:i:s', time() + 1200); //the date/time 20 minutes from now (which is when the user can access their account again)
					
					if (date('Y-m-d H:i:s') > $locked) { //prevents the time_locked value from updating itself if the user is already locked out.
						$conn->query("UPDATE empuk_users SET time_locked = '" . $date . "' WHERE username = '" . $u . "'");
					}
					
					//a prompt is displayed showing the user when they can attempt to log in again.
					echo "
					<div id='box-wrapper'>
						<div id='loginform'>
							<p>For security reasons, your account has been temporarily locked.</p>
							<p>You can try to log in again at <b>" . $date . "</b>.</p>
							<p><a href='index.php'>Return to the login page</a></p>
						</div>
					</div>";	
				}
				
				else {
					//displays a prompt telling the user how many more attempts they have to enter their password.
					echo "
					<div id='box-wrapper'>
						<div id='loginform'>
							<p>Invalid password; make sure you typed your username and password correctly.</p>
							<p>You have <b>" . (3 - $att) . "</b> more attempt(s) remaining.</p>
							<p>You will be automatically sent back to the login page in a few seconds...</p>
						</div>
					</div>
					
					<script type='text/javascript'>
						window.setTimeout(function() {
							window.location.replace('index.php')
						}, 2000);
					</script>";	
				}
			}
		}
	}
	
	else //if the user does not exist
	{
		echo "
		<div id='box-wrapper'>
			<div id='loginform'>
				<p>That user does not exist. Make sure you typed your username correctly.</p>
				<p>You will be automatically sent back to the login page in a few seconds...</p>
			</div>
		</div>
		
		<script type='text/javascript'>
			window.setTimeout(function() {
				window.location.replace('index.php')
			}, 2000);
		</script>";	
	}
}
else
{
	//if the user is already logged in, displays a prompt and sends the user back to the login screen.
	echo "
	<div id='box-wrapper'>
		<div id='loginform'>
			<p>You are already logged in as <strong>" . $_SESSION['fname'] . " " . $_SESSION['sname'] . " (" . $_SESSION['name'] . ")</strong>.</p>
			<p>You will be automatically sent back to your inbox in a few seconds...</p>
		</div>
	</div>
	
	<script type='text/javascript'>
		window.setTimeout(function() {
			window.location.replace('index.php')
		}, 2000);
	</script>";
}
?>