<?php
require 'db.php';
require 'header.php';
require 'footer.html';
require 'noscript.html';

//displays a simple login page if there is no session data.
function loginForm(){
	echo'
	<div id="box-wrapper">
		<div id="loginform">
			<h1 align="center">Log in</h1>
			<form action="login.php" method="post">
				<table align=center>
					<tr>
						<td align=right><label for="name">Username:</label></td>
						<td><input type="text" name="name" id="name" size=40 /></td>
					</tr>
					<tr>
						<td align=right><label for="password">Password:</label></td>
						<td><input type="password" name="password" id="password" size=40/></td>
					</tr>
				</table>
				<input type="submit" name="enter" id="enter" value="Log in" />
			</form>
			<br>
			<p>[<a href="reset-pass/stage1.php">Forgot your password?</a>] || [<a href="reg.php">Create an account</a>]<p>	
		</div>
	</div>';
}

//when the "Log in" button is pressed, a session is created
//with the 'name' variable being set to the entered username.
//if no username is entered, then a prompt is shown, telling the user to enter a name.
if(isset($_POST['enter'])){
	if(($_POST['name'] != "") && ($_POST['password'] != "")){
		$_SESSION['name'] = stripslashes(htmlspecialchars($_POST['name']));
	}
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>EmployabilityUK [Messaging]</title>
		<link type="text/css" rel="stylesheet" href="style.css" />
	</head>

	<body>
		<?php
			//displays the login form if there is no session data.
			if(!isset($_SESSION['name'])) {loginForm();}

			//displays the inbox if the user is logged in.
			else {							 
				$stmt = $conn->query("SELECT * FROM  empuk_email WHERE receiver = '" . $_SESSION['id'] . "' AND draft = FALSE");
				$row_count = $stmt->num_rows;
		?>
			<div id="box-wrapper">
				<div id="inbox"></div>
				<div id="message"></div>
			</div>
			
			<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
			<script type="text/javascript">
				$(document).ready(function(){
					//loads messages from the database and displays them in the "inbox" div.
					function loadLog(){
						var box = <?php if (isset($_GET['box'])) { echo $_GET['box']; }
						else { echo "1"; } ?>
						
						switch(box) {
							case 1:
								var url = "view-inbox.php";
								break;
							case 2:
								var url = "view-sent.php";
								break;
							case 3:
								var url = "view-drafts.php";
								break;
							default:
								var url = "view-inbox.php";
						}
						
						$.ajax({
							url: url,
							cache: true,
							success: function(html){		
								$("#inbox").html(html); //inserts the messages in to the div.
							}
						});
					}
					loadLog(); //initial loading of message list.
					setInterval(loadLog, 2500) //loads messages ever 2500ms (2.5 secs).
					
					var countOld = <?php echo $row_count; ?>; //message count. used for sound notification.
					//checks for new messages every second.
					//if there is a new message, an alert sound will play and the message list will be updated.
					setInterval(function(){
						$.ajax({
							url: "count-inbox.php",
							cache: true,
							success: function(html){		
								if (html != countOld) {
									if (html > countOld){
										var audio = new Audio('alert/alert.mp3');
										audio.play();
									}
									
									countOld = html;
								};
							}
						});
					},1000);
					
				});
				
			</script>
	
		<?php
			}
		?>
	</body>
</html>