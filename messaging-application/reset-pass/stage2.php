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
</head>

<body>
	<?php
	//kicks the user back to the index if they are already logged in.
	if(isset($_SESSION['name'])){
		header("location: ../index.php");
	}

	else {
		
		$type = $_POST["type"]; //the option between username/email that was selected
		$id = $_POST["id"]; //the entered username/email
		
		//fetching the user's security question.
		if ($type == "email") {
			$sql = "SELECT username, question FROM empuk_users WHERE email = '" . $id . "'";
		}
		else {
			$sql = "SELECT username, question FROM empuk_users WHERE username = '" . $id . "'";
		}
		
		$result = $conn->query($sql);
			
		if ($result->num_rows == 1) {
			$row = $result->fetch_assoc();
			$sq = $row["question"];
			$user = $row["username"];
			
			//the question is stored as an int in the database, so we have to translate that int to the actual question that the user selected.
			//see the form in reg.php to see which numbers correlate to which question.
			switch($sq) {
				case 1:
					$sqstring = "Your mother's maiden name?";
					break;
				case 2:
					$sqstring = "The town/city you were born in?";
					break;
				case 3:
					$sqstring = "The name of your first pet?";
					break;
				case 4:
					$sqstring = "Your first school?";
					break;
				case 5:
					$sqstring = "Your favourite food?";
					break;
				default:
					$sqstring = "???";
					break;
			}
			
			echo "
			<div id='box-wrapper'>
				<div id='loginform'>
					<h1 align='center'>Reset your password</h1>
						
					<form name='pass' action='stage3.php' method=post>
						<table align=center>
							<tr>
								<td align=right><label>Username: </label></td>
								<td><input type='text' name='user' id='user' value='" . $user . "' size=40 readonly /></td>
							</tr>
							<tr>
								<td align=right><label>Security question: </label></td>
								<td>" . $sqstring . "</td>
							</tr>
							<tr>
								<td align=right><label for=answer>Answer: </label></td>
								<td><input type='text' name='answer' id='answer' size=40 /></td>
							</tr>
						</table>
						<input type='submit' name='submit' id='submit' value='Submit' />
					</form>
					<br>
						
					<p>[<a href='../index.php'>Return to the log in screen</a>]</p>	
				</div>
			</div>";
		}
		
		else {
			echo "
			<div id='box-wrapper'>
				<div id='loginform'>
					<p>That user could not be found. Make sure you typed your username or e-mail address correctly, and try again.<br>
					[<a href='stage1.php'>Return to the Password Reset page</a>]</p>
				</div>
			</div>";
		}
	?>
</body>
	
<?php
}
?>