<?php
require '../db.php';
require 'header.php';
require '../footer.html';
require '../noscript.html';

//if there is existing session data (aka the user is logged in), displays a prompt telling the user that they are already logged in
//then sends them back to the index after 2 seconds.
function loggedIn() {
	echo "<p>You are already logged in!</p>
	<p>You will be automatically sent back to Messaging in a few seconds...</p>
	<script type='text/javascript'>
		window.setTimeout(function() {
			window.location.replace('../index.php')
		}, 2000);
	</script>";
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>EmployabilityUK [Reset password]</title>
	<link type="text/css" rel="stylesheet" href="../style.css" />
</head>

<?php
//displays the "already logged in" prompt if session data already exists.
if(isset($_SESSION['name'])){
	loggedIn();
}

else {
?>
	<body>
		<div id='box-wrapper'>
			<div id="loginform">
				<h1 align="center">Reset your password</h1>
				
				<form name="pass" action="stage2.php" method=post>
					<table align=center>
						<tr>
							<td>
								<select name="type" id="type">
									<option value="user">Username</option>
									<option value="email">E-mail address</option>
								</select>
							</td>
							<td><input type="text" name="id" id="id" maxlength=60 size=40 /></td>
						</tr>
					</table>
					<input type="submit" name="submit" id="submit" value="Submit" />
				</form>
				<br>
				
				<p>[<a href="../index.php">Return to the log in screen</a>]</p>	
			</div>
		</div>
	</body>
	
<?php
}
?>