<?php
require '../db.php';
include 'header.php';
include '../footer.html';
include '../noscript.html';

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>EmployabilityUK [Reset password]</title>
	<link type="text/css" rel="stylesheet" href="../style.css" />
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			//when the user clicks "Update"
			$("#submit").click(function(e){
			
				var p = $("#password").val(); //password
				var pc = $("#password-c").val(); //confirm password
				var pl = p.length; //length of the password
				
				//defining the criteria for a valid password.
				//in this case, the password must be between 8 to 127 characters long and must contain at least one number or symbol, and at least one uppercase letter.
				var specialChars = /^(?=.*[A-Z])(?=.*[0-9!@#$%^&*., ])[a-zA-Z0-9!@#$%^&*., ]{8,127}$/;
				
				if ((p == "") || (pc == "")){ //blank password check
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
				else { //shows the user the credentials they entered and asks for confirmation to register.
					var proceed = confirm("Are you sure you want to update your password?");
					if(proceed == false){ //stops the form from posting if the user presses cancel on the prompt.
						e.preventDefault(e);
					}
				}
				
			});
		});
	</script>
</head>

<body>
	<?php
	//kicks the user back to the index if they are already logged in.
	if(isset($_SESSION['name'])){
		header("location: ../index.php");
	}

	else {
		
		$answer = md5($_POST["answer"]); //the entered answer
		$user = $_POST["user"]; //the user
		
		//fetching the user's answer stored on the database, and seeing if it matches the answer that was typed in.
		$sql = "SELECT answer FROM empuk_users WHERE username = '" . $user . "'";
		
		$result = $conn->query($sql);
		$row = $result->fetch_assoc();
		$sa = $row["answer"];
		
		if ($answer == $sa) {
		echo "
		<div id='box-wrapper'>
			<div id='loginform'>
				<h1 align='center'>Reset your password</h1>
					
				<form name='pass' action='update-pass.php' method=post>
					<table align=center>
						<tr>
							<td align=right><label>Username: </label></td>
							<td><input type='text' name='user' id='user' value='" . $user . "' size=40 readonly /></td>
						</tr>
						<tr>
							<td align=right><label>New password: </label></td>
							<td><input type='password' name='password' id='password' maxlength=127 size=40 /></td>
						</tr>
						<tr>
							<td align=right><label for=answer>Confirm new password: </label></td>
							<td><input type='password' name='password-c' id='password-c' maxlength=127 size=40 /></td>
						</tr>
					</table>
					<input type='submit' name='submit' id='submit' value='Update' />
				</form>
				<br>
				<p>Your password must be at least 8 characters long and must contain at least one uppercase letter and one of the following:</label><br>
				0-9 !@#$%^&*., Space</label><br><br>
				
				We recommend using a password manager such as <a href='https://lastpass.com/'>LastPass</a> to generate a secure password.<br><br>
				
				[<a href='../index.php'>Return to the log in screen</a>]</p>	
			</div>
		</div>";
		}
		
		else {
			echo "
			<div id='box-wrapper'>
				<div id='loginform'>
					<p>You have entered the incorrect answer to your security question. Make sure you typed the answer correctly, and try again.<br>
					[<a href='stage1.php'>Return to the Password Reset page</a>]</p>
				</div>
			</div>";
		}
	?>
</body>
	
<?php
}
?>