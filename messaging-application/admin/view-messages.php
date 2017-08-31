<link type="text/css" rel="stylesheet" href="../style.css" />

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$(".msgln").click(function() {
			var id = $(this).prop('id'); //the message's id
			var type = $(this).attr('name'); //determining whether the admin is viewing flagged messages or a certain user's messages.
			
			if (type == "flag")
				var url = "admin/view-fullmsg.php?eid=" + id + "&flag=1";
			else
				var url = "view-fullmsg.php?eid=" + id;
			
			$.ajax({
				url: url, //loading the view-fullmsg.php page using the message's id.
				cache: true,
				success: function(html){
					$("#view-fullmsg").html(html); //inserts the content in to the div.
				},
			});
		});
	});			
</script>

<?php
require '../db.php';

session_start();

if($_SESSION['role'] == "Admin"){ //only executes the page is the user is logged in as an admin.

	if ($conn->connect_error)
		die("Could not connect to the server." . $conn->connect_error);
	
	if (isset($_GET['id'])) { //if the admin is viewing a user's messages from teh user actions page
		$get = "id";
		$sql = "SELECT emailid, time, subject, sender, receiver
		FROM empuk_email
		WHERE sender = '" . $_GET['id'] . "'
		AND draft = FALSE
		ORDER BY time DESC";
	}
	else if(isset($_GET['flag'])) { //if the admin is viewing flagged messages from the admin panel
		$get = "flag";
		$sql = "SELECT emailid, time, subject, sender, receiver
		FROM empuk_email
		WHERE flag = TRUE
		AND draft = FALSE
		ORDER BY time DESC";
	}
	else { //if the admin is doing neither of those two things (in which case they shouldn't be on this page)
		echo "An error occured.";
		exit;
	}
	
	$result = $conn->query($sql);
		
	//if the user has no messages, show a prompt informing the user of this.
	if ($result->num_rows == 0) {
		if ($get == "id")
			echo "<i>This user has not sent any messages.</i>";
		else
			echo "<i>No flagged messages to display.</i>";
	}
			
	else {
		//displays each message in its own div.
		while($row = $result->fetch_assoc()) {
			//fetching values from the sql query
			if ($get == "id")
				$user = $row['receiver']; //receiver userID
			else
				$user = $row['sender']; //sender userID
			
			$time = $row['time']; //time/date that the message was sent
			$eid = $row['emailid']; //message ID
					
			//if the message has no subject, shows an italic "No Subject" in its place.
			if ($row['subject'] == NULL)
				$subject = "<i>(No subject)</i>";
			else
				$subject = $row['subject'];
					
			//fetching some details of the user that sent the message.
			$sqlsub = "SELECT forename, surname, username, role FROM empuk_users WHERE id = '" . $user . "'";
			$resultsub = $conn->query($sqlsub);
			$rowsub = $resultsub->fetch_assoc();
				
			echo "<div class=msgln name=" . $get . " id=" . $eid . ">";
				
			//displays the receiver and subject of the message. Indicates if the receiver is a mentor/admin.
			if ($get == "flag")
				echo "<div>Sent by <b>";
			else
				echo "<div>To -> <b>";
				
			if ($rowsub["role"] == "Admin")
				echo "[Admin] ";
			else if ($rowsub["role"] == "Mentor")
				echo "[Mentor] ";
				
			echo $rowsub['forename'] . " " . $rowsub['surname'] . "</b> (" . $rowsub['username'] . ") - " . $time . "</div>
			<div style='font-size:120%'>" . $subject . "</div>
			</div>";
		}
	}
		
	$conn->close();
}

else {
	//kicks the user out to the index page if they are not logged in as an admin.
	header("Location: ../index.php");
}
?>