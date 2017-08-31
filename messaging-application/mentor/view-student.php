<link type="text/css" rel="stylesheet" href="../style.css" />
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$(".send").click(function() {
			var id = $(this).prop('id'); //the user's id
		
			window.open('compose.php?uid=' + id, 'newwindow', 'width=800, height=600'); //opens a message window.
			return false;
		});
	});
</script>


<?php
require '../db.php';

session_start();

if($_SESSION['role'] == "Mentor"){

	if ($conn->connect_error)
		die("Could not connect to the server." . $conn->connect_error);
	
	//selects all users from the database, displaying the newest users first.
	$sql = "SELECT id, username, forename, surname, email, role,paired,online, last_active
	FROM empuk_users
	WHERE role ='User'
	ORDER BY id DESC";
	$result = $conn->query($sql);

	if ($result->num_rows > 0)
	{
		echo "<table id='user-table'><tr id='usertable-toprow'><td>ID</td><td>Username</td><td>Full name</td><td>E-mail address</td><td>Last active</td><td></td></tr>";
	
		//displays each user on a separate line..
		
		while($row = $result->fetch_assoc()) {
			
			
				echo "<tr id='usertable-datarow'><td>" . $row['id'] . "</td><td>". $row['username']."</td><td>" . $row['forename'] . " " . $row['surname'] .  "</td><td>" . $row['email'] . "</td><td>" . $row['last_active'] . "</td><td><a href='#' class='send' id='" . $row['id'] . "'>Send message</a></td></tr>";
			
		}
		
		echo "</table>";
	}

	else
	{
		echo $conn->error;
	}
		
	$conn->close();
}

else {
	header("Location: ../index.php");
}
?>