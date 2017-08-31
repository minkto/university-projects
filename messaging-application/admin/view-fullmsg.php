<link type="text/css" rel="stylesheet" href="style.css" />
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$(".delete").click(function() {
			var id = $(this).prop('id'); //the message's id
			var del = confirm('Are you sure you want to delete this message?');
			var url = "<?php if (!isset($_GET['flag'])) {echo "../";} ?>";
			
			
			if(del == true) {
				$.post(
					url + "msg/delete.php",
					{ msgid: id },
					function(){
						alert('Message deleted.');
					}
				);
			}
			return false;
		});
	});			
</script>

<?php
require '../db.php';

session_start();

if($_SESSION['role'] == "Admin"){

	if ($conn->connect_error)
		die("Could not connect to the server." . $conn->connect_error);
	
	
	else {
		//in case the page is accessed with no parameters.
		//this should only happen if the user manually goes to this page from the address bar.
		if (!isset($_GET['eid'])) {
			echo "<i>No message was selected.</i>";
		}
		else {
			//fetching the message ID.
			$eid = $_GET['eid'];
			
			if(isset($_GET['flag']))
				$user = "empuk_email.sender";
			else
				$user = "empuk_email.receiver";
					
			//selects the message from the database.
			$sql = "SELECT empuk_email.content, empuk_email.subject, empuk_email.sender, empuk_email.receiver, empuk_email.time,
			empuk_users.forename, empuk_users.surname, empuk_users.username, empuk_users.id, empuk_users.role
			FROM empuk_email
			INNER JOIN empuk_users
			ON " . $user . " = empuk_users.id
			WHERE empuk_email.emailID = '" . $eid . "'";
			$result = $conn->query($sql);
			$row = $result->fetch_assoc();
				
			//if the message has no subject, shows an italic "No Subject" in its place.
			if ($row['subject'] == NULL) {
				$row['subject'] = "<i>(No subject)</i>";
			}
				
			//displays the contents of the message.
			echo "<div style='font-size:120%'>" . $row['subject'] . "</div>";
			
			if (isset($_GET['flag']))
				echo "<div>Sent by <b>";
			else
				echo "<div>Sent to <b>";
				
			if ($row["role"] == "Admin")
				echo "[Admin] ";
			else if ($row["role"] == "Mentor")
				echo "[Mentor] ";
					
			echo $row['forename'] . " " . $row['surname'] . "</b> (" . $row['username'] . ") at " . $row['time'] . "</div><br>
			[<a class='delete' id='" . $eid . "' href='#'>Delete</a>]";
			
			if (isset($_GET['flag']))
				echo " [<a href='admin/user-actions.php?id=" . $row['sender'] . "'>Go to user actions</a>]";
			
			echo "</div>";
			echo "<br><br>
			<div>" . nl2br($row['content']) . "</div>";
		}
	}	
}

else {
	//kicks the user out to the index page if they are not logged in as an admin.
	header("Location: ../index.php");
}
		
	$conn->close();
?>