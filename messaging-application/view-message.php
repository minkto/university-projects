<link type="text/css" rel="stylesheet" href="style.css" />
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		//opens a compose window when the user clicks "reply".
		$(".reply").click(function() {
			var id = $(this).prop('id'); //the message's id
			
			window.open('compose.php?eid=' + id, 'newwindow', 'width=800, height=600'); //opens a reply window.
			return false;
		});
		
		$(".edit").click(function() {
			var id = $(this).prop('id'); //the message's id
			
			window.open('compose-draft.php?eid=' + id, 'newwindow', 'width=800, height=600'); //opens an edit window.
			return false;
		});
		
		$(".flag").click(function() {
			var id = $(this).prop('id'); //the message's id
			
			$.post(
				"msg/flag.php",
				{ msgid: id },
				function(data){
					alert(data);
				}
			);
			return false;
		});
		
		$(".delete").click(function() {
			var id = $(this).prop('id'); //the message's id
			var del = confirm('Are you sure you want to delete this message? This cannot be undone!');
			
			if(del == true) {
				$.post(
					"msg/delete.php",
					{ msgid: id },
					function(data){
						alert(data);
					}
				);
			}
			return false;
		});
	});			
</script>

<?php
require 'db.php';

session_start();

if(isset($_SESSION['name'])){

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
			//determining whether the message is being viewed by the sender, by the receiver or as a draft.
			//r = received (inbox), s = sent (sent items), d = draft
			if(isset($_GET['type']))
				$type = $_GET['type'];
			
			//fetching the receiver's user ID and seeing if it matches the ID of the currently logged in user.
			$sql = "SELECT receiver, sender, markread FROM empuk_email WHERE emailid = '" . $eid . "'";
			$result = $conn->query($sql);
			$row = $result->fetch_assoc();
			$rid = $row['receiver'];
			$sid = $row['sender'];
			$markread = $row['markread'];
			
			//does not display the message if the receiver's ID is different to the logged in user's ID.
			if ((($rid != $_SESSION['id']) && ($sid != $_SESSION['id'])) || (!isset($_GET['type']))) {
				echo "<i>You cannot display this message.</i>";
			}
			
			else {
				if ($type == 'r') {
					if (!$markread) { //only marks the message as read if the recipient is viewing it
						$sql = "UPDATE empuk_email SET markread = TRUE WHERE emailID = " . $eid;
						$conn->query($sql);
					}
				}
				
				if (($type == 's') || ($type == 'd'))
					$join = "empuk_email.receiver";
				else
					$join = "empuk_email.sender";
					
				//selects the message from the database.
				$sql = "SELECT empuk_email.content, empuk_email.subject, empuk_email.sender, empuk_email.receiver, empuk_email.time, empuk_email.flag,
				empuk_users.forename, empuk_users.surname, empuk_users.username, empuk_users.id, empuk_users.role
				FROM empuk_email
				INNER JOIN empuk_users
				ON " . $join . " = empuk_users.id
				WHERE empuk_email.emailID = '" . $eid . "'";
				$result = $conn->query($sql);
				$row = $result->fetch_assoc();
				
				//if the message has no subject, shows an italic "No Subject" in its place.
				if ($row['subject'] == NULL) {
					$row['subject'] = "<i>(No subject)</i>";
				}
				
				//displays the contents of the message.
				echo "<div style='font-size:120%'>" . $row['subject'] . "</div>";
				
				if (($type == 's') || ($type == 'd'))
					echo "<div>Sent to <b>";
				else
					echo "<div>Sent by <b>";
				
				if ($row["role"] == "Admin")
					echo "[Admin] ";
				else if ($row["role"] == "Mentor")
					echo "[Mentor] ";
					
				echo $row['forename'] . " " . $row['surname'] . "</b> (" . $row['username'] . ") at " . $row['time'] . "</div><br>";
				
				if ($type == 'r') {
					if(!$row['flag'])
						$f = "Flag";
					else
						$f = "Unflag";
					
					echo "<div>[<a class='reply' id='" . $eid . "' href='#'>Reply</a>] 
					[<a class='flag' id='" . $eid . "' href='#'>" . $f . "</a>] 
					[<a class='delete' id='" . $eid . "' href='#'>Delete</a>]</div>";
				}
				else if ($type == 'd') {
					echo "<div>[<a class='edit' id='" . $eid . "' href='#'>Edit</a>]
					[<a class='delete' id='" . $eid . "' href='#'>Delete</a>]</div>";
				}
				
				echo "<br>
				<div>" . nl2br($row['content']) . "</div>";
			}
		}
	}	
}

else {
	header("Location: index.php");
}
		
	$conn->close();
?>