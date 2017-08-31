<link type="text/css" rel="stylesheet" href="style.css" />

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		//opens a message in the "message" div when the user clicks on it.
		$(".msgln, .msgln-u").click(function() {
			var id = $(this).prop('id'); //the message's id
			
			$.ajax({
				url: "view-message.php?eid=" + id + "&type=r", //loading the view-message.php page using the message's id.
				cache: true,
				success: function(html){
					$("#message").html(html); //inserts the content in to the div.
				},
			});
		});
		
		//marks the message as read.
		$(".markread").click(function(e) {
			e.stopPropagation();
			var id = $(this).prop('id'); //the message's id
			$.post(
				"msg/markread.php",
				{ msgid: id },
				function(){
					loadLog();
				}
			);
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
		//selects the messages from the database.
		$sql = "SELECT emailid, time, subject, sender, receiver, markread, flag
		FROM empuk_email
		WHERE receiver = '" . $_SESSION['id'] . "'
		AND draft = FALSE
		ORDER BY time DESC";
		$result = $conn->query($sql);
		
		//if the user has no messages, show a prompt informing the user of this.
		if ($result->num_rows == 0) {
			echo "<i>You have no messages.</i>";
		}
			
		else {
			//displays each message in its own div.
			while($row = $result->fetch_assoc()) {
				//fetching values from the sql query
				$user = $row['sender']; //sender userID
				$time = $row['time']; //time/date that the message was sent
				$eid = $row['emailid']; //message ID
				$markread = $row['markread']; //whether or not the message has been read
				$flag = $row['flag']; //whether or not the message has been flagged
					
				//if the message has no subject, shows an italic "No Subject" in its place.
				if ($row['subject'] == NULL) {
					$subject = "<i>(No subject)</i>";
				}
				else {
					$subject = $row['subject'];
				}
					
				//fetching some details of the user that sent the message.
				$sqlsub = "SELECT forename, surname, username, role FROM empuk_users WHERE id = '" . $user . "'";
				$resultsub = $conn->query($sqlsub);
				$rowsub = $resultsub->fetch_assoc();
				
				if(!$markread)
					echo "<div class=msgln-u id=" . $eid . ">"; //unread message
				else
					echo "<div class=msgln id=" . $eid . ">"; //read message
				
				//displays the sender and subject of the message. Indicates if the sender is a mentor/admin.
				echo "<div><b>";
				
				if ($rowsub["role"] == "Admin")
					echo "[Admin] ";
				else if ($rowsub["role"] == "Mentor")
					echo "[Mentor] ";
				
				echo $rowsub['forename'] . " " . $rowsub['surname'] . "</b> (" . $rowsub['username'] . ") - " . $time;
				
				if($flag) //indicates if the message has been flagged.
					echo "<i> (Flagged)</i>";
				
				echo "</div>";
				
				echo "<div style='font-size:120%'>" . $subject . "</div>";
				
				if (!$markread)
					echo "<div>[<a class='markread' id='" . $eid . "' href='#'>Mark as read</a>]</div></div>";
				else
					echo "<div>[<a class='markread' id='" . $eid . "' href='#'>Mark as unread</a>]</div></div>";
					
			}
		}
	}
}

else {
	header("Location: index.php");
}
		
	$conn->close();
?>