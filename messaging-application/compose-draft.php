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
			var msgid = <?php echo $_GET['eid']; ?>;
			
			$.post("send.php", {
				recipient: recipient,
				subject: subject,
				msg: msg,
				type: type,
				msgid: msgid
			},
			function(data) {
				if (data == "success") {
					alert("Message sent.");
					window.close();
				}
				else
					alert(data);
			});
		});
		
		$(".draft").click(function() {
			var recipient = $("#recipient").val();
			var subject = $("#subject").val();
			var msg = $("#msg").val();
			var type = $("#type").val();
			var msgid = <?php echo $_GET['eid']; ?>;
			
			$.post("save-draft.php", {
				recipient: recipient,
				subject: subject,
				msg: msg,
				type: type,
				msgid: msgid
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
		$sql = "SELECT empuk_email.receiver, empuk_email.subject, empuk_email.content, empuk_users.username
		FROM empuk_email
		INNER JOIN empuk_users
		ON empuk_email.receiver=empuk_users.id
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
					<input id='recipient' type='text' value='" . $row['username'] . "' maxlength=30 size=20 />
				</td>
			</tr>
			<tr>
				<td><label for='subject'>Subject:</label></td>
				<td><input id='subject' type='text' value='" . $row['subject'] . "' maxlength=255 size=40 /></td>
			</tr>
		</table>
		
		[<a class='send' href='#'>Send</a>] || [<a class='draft' href='#'>Save draft</a>]<br>
		<textarea id='msg' style='width:90%; height:420px;'>" . $row['content'] . "</textarea>";
	}		
		
}	

else {
	header("Location: index.php");
}
		
	$conn->close();
?>