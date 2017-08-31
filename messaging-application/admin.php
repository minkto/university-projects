<?php
require 'db.php';
require 'header.php';
require 'footer.html';
require 'noscript.html';

//sends the user back to index.php if they are not an admin (or not logged in).
function returnToIndex(){
	header("Location: index.php");
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>EmployabilityUK [Admin Panel]</title>
		<link type="text/css" rel="stylesheet" href="style.css" />
	</head>

	<body>
		<?php
			//returns the user to the index if they are not an admin/logged in.
			if($_SESSION["role"] != "Admin") {returnToIndex();}

			//displays the admin panel.
			else {
		?>
		
		<div id="box-wrapper">
			<div id="loginform">
				<h1 align=center>Admin panel</h1>
				<p align=center><a href="#" id="view-flag">View flagged messages</a> || <a href="#" id="create-mentor">Create a mentor account</a> || <a href="#" id="view-users">View users</a></p>
				
				<div id="view-usermsg" style="float:left"></div>
				<div id="view-fullmsg"></div>
				
				<div id="mentor">
					<h1 align="center">Create a mentor account</h1>
				
					<form name="reg" action="admin/reg-mentor.php" method=post>
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
				</div>
				
				<div id="users"></div>
				
			</div>
		</div>

		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
		<script type="text/javascript">
			$(document).ready(function(){
				
				//hides all of the panels until the user clicks on an option.
				$('#view-usermsg').hide();
				$('#view-fullmsg').hide();
				$('#mentor').hide();
				$('#users').hide();
				
				$('#loginform').click(function(e){
					e.stopPropagation();
				})
				
				//shows the flagged messages panel if the user clicks on "View flagged messages"
				$('#view-flag').click(function(e){
					$('#view-usermsg').show();
					$('#view-fullmsg').show();
					$('#mentor').hide();
					$('#users').hide();
					$.ajax({
						url: "admin/view-messages.php?flag=1",
						cache: true,
						success: function(html){
							$('#view-usermsg').html(html); //inserts the messages in to the div.				
						},
					});
				})
				
				//shows the mentor registration panel if the user clicks on "Create a mentor account"
				$('#create-mentor').click(function(e){
					$('#mentor').show();
					$('#view-usermsg').hide();
					$('#view-fullmsg').hide();
					$('#users').hide();
				})
				
				//shows the users panel if the user clicks on "View users"
				$('#view-users').click(function(e){
					$('#users').show();
					$('#view-usermsg').hide();
					$('#view-fullmsg').hide();
					$('#mentor').hide();
					$.ajax({ //AJAX request for showing the list of users.
						url: "admin/view-users.php",
						cache: false,
						success: function(html){		
							$("#users").html(html); //inserts the list of users in to the div.
						}
					});
				})
				
				//(Mentor registration panel) when the user clicks "Register"
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
						alert("Please enter a valid username.\nUsernames can contain letters, numbers, spaces, and .-_");
						e.preventDefault(e); //stops the form from posting (applies to all other instances of this command)
					}
					else if (fname == ""){ //checks to see if the first name field was left blank
						alert("Please enter the mentor's first name.");
						e.preventDefault(e);
					}
					else if (sname == ""){ //blank surname check
						alert("Please enter the mentor's surname.");
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
						alert("Passwords must be at least 8 characters long.");
						e.preventDefault(e);
					}
					else if (!specialChars.test(p)){ //checks to see if the password contains an uppercase letter and a symbol/number.
						alert("Passwords must contain at least one uppercase letter and at least one number or symbol.");
						e.preventDefault(e);
					}
					else if (sa == "") { //checks to see whether the user entered a security answer or not.
						alert("Please enter a security answer.");
						e.preventDefault(e);
					}
					else { //shows the user the credentials they entered and asks for confirmation to register.
						var proceed = confirm("A mentor account will be registered with the following details:\n\nUsername: " + name + "\nName: " + fname + " " + sname + "\nE-mail address: " + email + "\n\nProceed?");
						if(proceed == false){ //stops the form from posting if the user presses cancel on the prompt.
							e.preventDefault(e);
						}
					}
				
				});
				
			});
		</script>
	
		<?php
			}
		?>
	</body>
</html>