<?php
require 'db.php';
require 'header.php';
require 'footer.html';
require 'noscript.html';

//if there is existing session data (aka the user is logged in), displays a prompt telling the user that they are already logged in
//then sends them back to the index after 2 seconds.
function loggedIn() {
	echo "
	<div id='box-wrapper'>
		<div id='loginform'>
			<p>You are already logged in!</p>
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

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>EmployabilityUK [Register]</title>
	<link type="text/css" rel="stylesheet" href="style.css" />
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			//when the user clicks "Register"
			$("#register").click(function(e){
			
				//grabs the values that were entered by the user
				var name = $("#name").val(); //username
				var fname = $("#fname").val(); //first name
				var sname = $("#sname").val(); //surname
				var email = $("#email").val(); //email address
				var p = $("#password").val(); //password
				var pc = $("#password-c").val(); //confirm password
				var pl = p.length; //length of the password
				var sa = $("#s-answer").val(); //security answer
				
				//defining the criteria for a valid password.
				//in this case, the password must be between 8 to 127 characters long and must contain at least one number or symbol, and at least one uppercase letter.
				var specialChars = /^(?=.*[A-Z])(?=.*[0-9!@#$%^&*., ])[a-zA-Z0-9!@#$%^&*., ]{8,127}$/;
				
				//defining the criteria for a valid email address -> must contain an @ and a .
				var emailSpecialChars = /^(?=.*[@])(?=.*[.])[a-zA-Z0-9@.\-_]{5,60}$/;
				
				//defining the criteria for a valid username
				var userSpecialChars = /^[a-zA-Z0-9.\-_ ]{1,30}$/;
				
				if (!userSpecialChars.test(name)){ //checks to see if the username field was left blank
					alert("Please enter a valid username.\nYour username can contain letters, numbers, spaces, and .-_");
					e.preventDefault(e); //stops the form from posting (applies to all other instances of this command)
				}
				else if (fname == ""){ //checks to see if the first name field was left blank
					alert("Please enter your first name.");
					e.preventDefault(e);
				}
				else if (sname == ""){ //blank surname check
					alert("Please enter your surname.");
					e.preventDefault(e);
				}
				else if (!emailSpecialChars.test(email)){ //valid email check
					alert("Please enter a valid e-mail address.");
					e.preventDefault(e);
				}
				else if ((p == "") || (pc == "")){ //blank password check
					alert("Please enter a password.");
					e.preventDefault(e);
				}
				else if (p != pc){ //checks to see whether the entered password and confirm password values are the same or not.
					alert("The entered passwords do not match.");
					e.preventDefault(e);
				}
				else if (pl < 8){ //checks to see if the password is less than 8 characters.
					alert("Your password must be at least 8 characters long.");
					e.preventDefault(e);
				}
				else if (!specialChars.test(p)){ //checks to see if the password contains an uppercase letter and a symbol/number.
					alert("Your password must contain at least one uppercase letter and at least one number or symbol.");
					e.preventDefault(e);
				}
				else if (sa == "") { //checks to see whether the user entered a security answer or not.
					alert("Please enter a security answer.");
					e.preventDefault(e);
				}
				else { //shows the user the credentials they entered and asks for confirmation to register.
					var proceed = confirm("An account will be registered with the following details:\n\nUsername: " + name + "\nName: " + fname + " " + sname + "\nE-mail address: " + email + "\n\nProceed with registration?");
					if(proceed == false){ //stops the form from posting if the user presses cancel on the prompt.
						e.preventDefault(e);
					}
				}
				
			});
		});
	</script>
</head>

<?php
//displays the "already logged in" prompt if session data already exists.
if(isset($_SESSION['name'])){
	loggedIn();
}

else {
?>
	<body>
		<div id="box-wrapper">
			<div id="loginform">
				<h1 align="center">Create an account</h1>
				
				<form name="reg" action="reg-post.php" method=post>
					<table align=center>
						<tr>
							<td align=right><label for="name">Username: </label></td>
							<td><input type="text" name="name" id="name" maxlength=30 size=40 /></td>
						</tr>
						<tr>
							<td align=right><label for="fname">First name: </label></td>
							<td><input type="text" name="fname" id="fname" maxlength=20 size=40 /></td>
						</tr>
						<tr>
							<td align=right><label for="sname">Surname: </label></td>
							<td><input type="text" name="sname" id="sname" maxlength=20 size=40 /></td>
						</tr>
						<tr>
							<td align=right><label for="email">E-mail address: </label></td>
							<td><input type="text" name="email" id="email" maxlength=60 size=40 /></td>
						</tr>
						<tr>
							<td align=right><label for="password">Password: </label></td>
							<td><input type="password" name="password" id="password" maxlength=127 size=40 /></td>
						</tr>
						<tr>
							<td align=right><label for="password">Confirm Password: </label></td>
							<td><input type="password" name="password-c" id="password-c" maxlength=127 size=40 /></td>
						</tr>
						<tr>
							<td align=right><label for="s-question">Security question: </label></td>
							<td>
								<select name="s-question" id="s-question">
									<option value="1">Your mother's maiden name?</option>
									<option value="2">The town/city you were born in?</option>
									<option value="3">The name of your first pet?</option>
									<option value="4">Your first school?</option>
									<option value="5">Your favourite food?</option>
								</select>
							</td>
						</tr>
						<tr>
							<td align=right><label for "s-answer">Security answer: </label></td>
							<td><input type="text" name="s-answer" id="s-answer" maxlength=127 size=40 /></td>
						</tr>
					</table>
					<input type="submit" name="register" id="register" value="Register" />
				</form>
				<br>
				<p>Your password must be at least 8 characters long and must contain at least one uppercase letter and one of the following:</label><br>
				0-9 !@#$%^&*., Space</label><br><br>
				
				We recommend using a password manager such as <a href="https://lastpass.com/">LastPass</a> to generate a secure password.<br><br>
				
				[<a href="index.php">Return to the log in screen</a>]</p>	
			</div>
		</div>
	</body>
	
<?php
}
?>