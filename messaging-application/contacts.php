<link type="text/css" rel="stylesheet" href="style.css" />

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$(".send").click(function() {
			var id = $(this).prop('id'); //the user's id
		
			window.open('compose.php?uid=' + id, 'newwindow', 'width=800, height=600'); //opens a message window.
			return false;
		});
		
		$(".add").click(function() {
			var user = prompt("Enter the username to add:");
			
			if ((user != "") && (user != null)) {
				$.post("contact/add.php", {user: user},
				function(data){
					alert(data);
				});
			}
			else
				alert("No username was entered.");
			
		});
		
		$(".remove").click(function() {
			var rem = confirm("Are you sure you want to remove this user from your contacts list?");
			var id = $(this).prop('id'); //user's id
			
			if (rem == true) {
				$.post("contact/remove.php", {id: id},
				function(data){
					alert(data);
				});
			}
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
		$sql = "SELECT u1, u2 FROM empuk_contacts
		WHERE u1 = '" . $_SESSION['id'] . "'";
		$result = $conn->query($sql);
		
		echo "<p><a href='#' class='add'>Add contact</a></p>";
		
		//if the user has no messages, show a prompt informing the user of this.
		if ($result->num_rows == 0) {
			echo "<i>You have no contacts.</i>";
		}
			
		else {
			//getting contacts
			while ($row = $result->fetch_assoc()) {
				$sqlsub = "SELECT username, forename, surname, role FROM empuk_users WHERE id = '" . $row['u2'] . "'";
				$resultsub = $conn->query($sqlsub);
				$rowsub = $resultsub->fetch_assoc();
					
				echo "<div class='contacts'><div>";
					
				if (($rowsub['role'] == "Admin") || ($rowsub['role'] == "Mentor"))
					echo "<b>[" . $rowsub['role'] . "]</b> ";
					
				echo $rowsub['forename'] . " " . $rowsub['surname'] . " (" . $rowsub['username'] . ")</div>
				<div>[<a class='send' href='#' id='" . $row['u2'] . "'>Send message</a>] || [<a class='remove' href='#' id='" . $row['u2'] . "'>Remove</a>]</div></div>";
			}
		}
	}
}

else {
	header("Location: index.php");
}
		
	$conn->close();
?>