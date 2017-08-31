<?
require '../db.php';
require 'header.php';
require '../footer.html';
require '../noscript.html';

$id = $_GET['id'];

//sends the user back to index.php if they are not an admin (or not logged in).
function returnToIndex(){
	header("Location: ../index.php");
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>EmployabilityUK [User Actions]</title>
		<link type="text/css" rel="stylesheet" href="../style.css" />
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
			
				<?php
				
				//fetching and displaying the user's details.
				$sql = "SELECT id, username, forename, surname, email, role, last_active, time_locked FROM empuk_users WHERE id='" . $id . "'";
				$result = $conn->query($sql);
				
				if ($result->num_rows == 1)
				{
					$row = $result->fetch_assoc();
					
					if ($row['role'] == "Admin") { //sends the admin back to the admin panel if they are trying to do an action on an admin account.
						header("Location: ../admin.php");
					}
					
					echo "<h1 align=center>User actions for: " . $row['username'] . "</h1>";
					echo "<table id='user-table' align=center><tr id='usertable-toprow'><td>ID</td><td>Username</td><td>Full name</td><td>E-mail address</td><td>Role</td><td>Last active</td><td>Unlock time</td></tr>";
					echo "<tr id='usertable-datarow'>
					<td>" . $row['id'] . "</td>
					<td>" . $row['username'] . "</td>
					<td>" . $row['forename'] . " " . $row['surname'] .  "</td>
					<td>" . $row['email'] . "</td>
					<td>" . $row['role'] . "</td>
					<td>" . $row['last_active'] . "</td>
					<td>" . $row['time_locked'] . "</td></tr>";
				}
				else {echo $conn->error;}
				?>
				
				</table>
				
				<p align=center><a href="#" id="view-messages">View this user's messages</a> || <a href="#" id="modify-user">Modify this user's details</a> || <a href="#" id="lock-user">Lock/Unlock this user</a> || <a href="#" id="delete-user">Delete this user</a></p><br>
				<p align=center><a href="../admin.php">Return to the admin panel</a></p><br>
				
				<div id="view-usermsg" style="float:left"></div>
				<div id="view-fullmsg"></div>
				
				<div id='modify'>
					<form action="modify-user.php" method=post>
						<table align=center>
							<tr>
								<td align=right><label for="name">ID:</label></td>
								<td><input type="id" name="id" id="id" value="<?php echo $id ?>" readonly size=40 /></td>
							</tr>
							<tr>
								<td align=right><label for="name">Username:</label></td>
								<td><input type="text" name="name" id="name" value="<?php echo $row['username'] ?>" maxlength=30 size=40 /></td>
							</tr>
							<tr>
								<td align=right><label for="fname">First name:</label></td>
								<td><input type="text" name="fname" id="fname" value="<?php echo $row['forename'] ?>" maxlength=30 size=40 /></td>
							</tr>
							<tr>
								<td align=right><label for="sname">Surname:</label></td>
								<td><input type="text" name="sname" id="sname" value="<?php echo $row['surname'] ?>" maxlength=30 size=40 /></td>
							</tr>
							<tr>
								<td align=right><label for="email">E-mail address:</label></td>
								<td><input type="text" name="email" id="email" value="<?php echo $row['email'] ?>" maxlength=60 size=40 /></td>
							</tr>
						</table>
						<input type="submit" name="submit" id="submit" value="Submit" /><br>
					</form>
				</div>
				
				<div id='lock'>
					<p><a href="#" id="l-mins">Lock for a specified number of minutes</a> || <a href="#" id="l-date">Choose a date to lock until</a> || <a href="#" id="l-perma">Permanently lock</a> || <a href="#" id="unl">Unlock</a></p><br>
					
					<div id='lock-mins'>
						Lock for <input type='number' id='minutes' size=10 /> minutes
						<input type='submit' id='l-mins-submit' value='Submit' />
					</div>
					<div id='lock-date'>
						Lock until:<br>
						Day:<input type='number' id='day' min='1' max='31' size=5 />
						Month:<input type='number' id='month' min='1' max='12' size=5 />
						Year:<input type='number' id='year' min='2016'size=5 />
						<input type='submit' id='l-date-submit' value='Submit' />
					</div>
				</div>
				
			</div>
		</div>

		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
		<script type="text/javascript">
			$(document).ready(function(){
			
				$('#view-usermsg').hide();
				$('#view-fullmsg').hide();
				$('#modify').hide();
				$('#lock').hide();
				$('#lock-mins').hide();
				$('#lock-date').hide();
			
				$('#loginform').click(function(e){
					e.stopPropagation();
				})
				
				$('#view-messages').click(function(e){
					$('#view-usermsg').show();
					$('#view-fullmsg').show();
					$('#modify').hide();
					$('#lock').hide();
					$.ajax({
						url: "view-messages.php?id=<?php echo $id ?>",
						cache: true,
						success: function(html){
							$('#view-usermsg').html(html); //inserts the messages in to the div.				
						},
					});					
				})
				
				$('#modify-user').click(function(e){
					$('#view-usermsg').hide();
					$('#view-fullmsg').hide();
					$('#modify').show();
					$('#lock').hide();
				})
				
				$('#lock-user').click(function(e){
					$('#view-usermsg').hide();
					$('#view-fullmsg').hide();
					$('#modify').hide();
					$('#lock').show();
				})
				
					$('#l-mins').click(function(e) {
						$('#lock-mins').show();
						$('#lock-date').hide();
					})
					
						$('#l-mins-submit').click(function(e) {
							var minutes = $('#minutes').val();
							var proceed = confirm("<?php echo $row['username'] ?> will be locked out for " + minutes + " minutes.\nProceed?");
							if (proceed == true) {
								$.post("lock-user.php", {id: <?php echo $id ?>, type: 'minutes', mins: minutes}, function(){
									alert("<?php echo $row['username'] ?> has been locked out for " + minutes + " minutes.");
								})
							}
						})
					
					$('#l-date').click(function(e) {
						$('#lock-mins').hide();
						$('#lock-date').show();
					})
					
						$('#l-date-submit').click(function(e) {
							var day = $('#day').val();
							var month = $('#month').val();
							var year = $('#year').val();
							var proceed = confirm("<?php echo $row['username'] ?> will be locked out until " + year + "-" + month + "-" + day + ".\nProceed?");
							if (proceed == true) {
								$.post("lock-user.php", {id: <?php echo $id ?>, type: 'date', day: day, month: month, year: year}, function(){
									alert("<?php echo $row['username'] ?> has been locked out until " + year + "-" + month + "-" + day + ".");
								})
							}
						})
					
					$('#l-perma').click(function(e) {
						var proceed = confirm("<?php echo $row['username'] ?> will be permanently locked out.\nThis can be undone by selecting 'Unlock' under User Actions.\nProceed?");
						if (proceed == true) {
							$.post("lock-user.php", {id: <?php echo $id ?>, type: 'permanent'}, function(){
								alert("<?php echo $row['username'] ?> has been permanently locked out.");
							})
						}
					})
						
					$('#unl').click(function(e) {
						var proceed = confirm("<?php echo $row['username'] ?> will be unlocked.\nProceed?");
						if (proceed == true) {
							$.post("lock-user.php", {id: <?php echo $id ?>, type: 'unlock'}, function(){
								alert("<?php echo $row['username'] ?> has been unlocked.");
							})
						}
					})
				
				$('#delete-user').click(function(e){
					var proceed = confirm("Are you sure you want to delete this user?\nThis cannot be undone!");
					if (proceed == true) {
						$.post("delete-user.php", {id: <?php echo $id ?>}, function(){
							window.location = ("../admin.php");
						});
					}
				})
				
				$("#submit").click(function(e){
			
					//grabs the values that were entered by the user
					var name = $("#name").val(); //username
					var fname = $("#fname").val(); //first name
					var sname = $("#sname").val(); //surname
					var email = $("#email").val(); //email address
				
					//defining the criteria for a valid email address -> must contain an @ and a .
					var emailSpecialChars = /^(?=.*[@])(?=.*[.])[a-zA-Z0-9@.\-_]{5,60}$/;
					
					//defining the criteria for a valid username
					var userSpecialChars = /^[a-zA-Z0-9.\-_ ]{1,30}$/;
					
					if (!userSpecialChars.test(name)){ //checks to see if the username field was left blank
						alert("Please enter a valid username.\nUsernames can contain letters, numbers, spaces, and .-_");
						e.preventDefault(e); //stops the form from posting (applies to all other instances of this command)
					}
					else if (fname == ""){ //checks to see if the first name field was left blank
						alert("Please enter the user's first name.");
						e.preventDefault(e);
					}
					else if (sname == ""){ //blank surname check
						alert("Please enter the user's surname.");
						e.preventDefault(e);
					}
					else if (!emailSpecialChars.test(email)){ //valid email check
						alert("Please enter a valid e-mail address.");
						e.preventDefault(e);
					}
					else { //shows the user the credentials they entered and asks for confirmation to register.
						var proceed = confirm("The account will be updated with the following details:\n\nUsername: " + name + "\nName: " + fname + " " + sname + "\nE-mail address: " + email + "\n\nProceed?");
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