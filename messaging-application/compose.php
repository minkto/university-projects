<head>
	<title>EmployabilityUK [Compose message]</title>
	<link type="text/css" rel="stylesheet" href="style.css" />
</head>

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$(".send").click(function() {
			var recipient = $("#recipient").val();
			var subject = $("#subject").val();
			var msg = $("#msg").val();
			var type = $("#type").val();
			
			$.post("send.php", {
				recipient: recipient,
				subject: subject,
				msg: msg,
				type: type
			},
			function(data) {
				if (data == "success") {
					alert("Message sent.");
					window.close();
				}
				else
					alert(data);
			})
		});
		
		$(".draft").click(function() {
			var recipient = $("#recipient").val();
			var subject = $("#subject").val();
			var msg = $("#msg").val();
			var type = $("#type").val();
			
			$.post("save-draft.php", {
				recipient: recipient,
				subject: subject,
				msg: msg,
				type: type
			},
			function(data) {
				if (data == "success") {
					alert("Message saved.");
					window.close();
				}
				else
					alert(data);
			});
		});
	});			
</script>

<?php
require 'db.php';

session_start();

if(isset($_SESSION['name'])){

	echo "<h1>Compose Message</h1>";
		
	//if the user is replying to a message, auto-fill the "recipient" and "subject" fields.
	if (isset($_GET['eid'])) {
		//fetching the message ID.
		$eid = $_GET['eid'];
				
		//fetching the receiver's user ID and seeing if it matches the ID of the currently logged in user.
		$sql = "SELECT empuk_email.sender, empuk_email.subject, empuk_users.username
		FROM empuk_email
		INNER JOIN empuk_users
		ON empuk_email.sender=empuk_users.id
		WHERE emailid = '" . $eid . "'";
		$result = $conn->query($sql);
		$row = $result->fetch_assoc();
		
		if ($row['subject'] == NULL) {
			$row['subject'] = "(No subject)";
		}
			
		echo "
		<table align=center>
			<tr>
				<td><label for='recipient'>To:</label></td>
				<td>
				<select name='type' id='type'>
					<option value='user'>Username</option>
					<option value='email'>E-mail address</option>
				</select>
				<input id='recipient' type='text' value='" . $row['username'] . "' maxlength=30 size=20 /></td>
			</tr>
			<tr>
				<td><label for='subject'>Subject:</label></td>
				<td><input id='subject' type='text' value='Re: " . $row['subject'] . "' maxlength=255 size=40 /></td>
			</tr>
		</table>";
	}
		
	//if the user pressed "compose" from the header
	else {
		echo "
		<table align=center>
			<tr>
				<td><label for='recipient'>To:</label></td>
				<td>
					<select name='type' id='type'>
						<option value='user'>Username</option>
						<option value='email'>E-mail address</option>
					</select>";
					
					//if the user clicked "Send message" via mentor/contacts panel.
					if (isset($_GET['uid'])) {
						$sql = "SELECT username FROM empuk_users WHERE id = '" .  $_GET['uid'] . "'";
						$result = $conn->query($sql);
						$row = $result->fetch_assoc();
						
						echo "<input id='recipient' type='text' maxlength=30 size=20 value='" . $row['username'] . "' />";
					}
					else
						echo "<input id='recipient' type='text' maxlength=30 size=20 />";
					
				echo "</td>
			</tr>
			<tr>
				<td><label for='subject'>Subject:</label></td>
				<td><input id='subject' type='text' maxlength=255 size=40 /></td>
			</tr>
		</table>";
	}
		
	echo "[<a class='send' href='#'>Send</a>] || [<a class='draft' href='#'>Save draft</a>]<br><br>
	<textarea id='msg' style='width:90%; height:420px;'></textarea>";
		
		
}	

else {
	header("Location: index.php");
}
		
	$conn->close();
?>